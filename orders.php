<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../config/db.php';
require_once '../includes/header.php';
require_once '../includes/CartManager.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: /DigitalNovels/user/login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$cartManager = new CartManager($conn);

// For now, we'll show current cart items as "order history"
// In a real system, you'd have a proper orders table
$cart = $cartManager->getCart($user_id);

// Create orders table if it doesn't exist (for future use)
$create_orders_table = "CREATE TABLE IF NOT EXISTS orders (
    order_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    total_amount DECIMAL(10,2) NOT NULL,
    order_status ENUM('pending', 'processing', 'shipped', 'delivered', 'cancelled') DEFAULT 'pending',
    order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    shipping_address TEXT,
    FOREIGN KEY (user_id) REFERENCES users(user_id)
)";
$conn->query($create_orders_table);

$create_order_items_table = "CREATE TABLE IF NOT EXISTS order_items (
    order_item_id INT PRIMARY KEY AUTO_INCREMENT,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(order_id),
    FOREIGN KEY (product_id) REFERENCES products(product_id)
)";
$conn->query($create_order_items_table);

// Check for actual orders
$orders_sql = "SELECT * FROM orders WHERE user_id = ? ORDER BY order_date DESC";
$orders_stmt = $conn->prepare($orders_sql);
$orders_stmt->bind_param("i", $user_id);
$orders_stmt->execute();
$orders_result = $orders_stmt->get_result();
?>

<div class="orders-container">
    <div class="orders-header">
        <h1>Order History</h1>
        <p>View your completed orders and current cart items</p>
    </div>

    <?php if ($orders_result->num_rows > 0): ?>
        <div class="orders-section">
            <h2>Your Orders</h2>
            <div class="orders-list">
                <?php while ($order = $orders_result->fetch_assoc()): ?>
                    <div class="order-card">
                        <div class="order-header">
                            <div class="order-info">
                                <span class="order-id">Order #<?php echo str_pad($order['order_id'], 6, '0', STR_PAD_LEFT); ?></span>
                                <span class="order-date"><?php echo date('M j, Y', strtotime($order['order_date'])); ?></span>
                            </div>
                            <span class="order-status status-<?php echo $order['order_status']; ?>">
                                <?php echo ucfirst($order['order_status']); ?>
                            </span>
                        </div>
                        <div class="order-details">
                            <div class="order-customer">
                                <strong><?php echo htmlspecialchars($order['customer_name']); ?></strong><br>
                                <?php echo htmlspecialchars($order['shipping_address']); ?>
                            </div>
                            <div class="order-payment">
                                <span class="payment-method"><?php echo ucfirst(str_replace('_', ' ', $order['payment_method'])); ?></span>
                            </div>
                        </div>
                        <div class="order-total">
                            <span class="total-label">Total:</span>
                            <span class="total-amount">$<?php echo number_format($order['total_amount'], 2); ?></span>
                        </div>
                        <div class="order-actions">
                            <button onclick="toggleOrderItems(<?php echo $order['order_id']; ?>)" class="btn-secondary btn-small">
                                View Items
                            </button>
                            <a href="/DigitalNovels/pages/order_success.php?order_id=<?php echo $order['order_id']; ?>" class="btn-outline btn-small">
                                View Details
                            </a>
                            <?php if (in_array($order['order_status'], ['pending', 'processing'])): ?>
                                <button onclick="cancelOrder(<?php echo $order['order_id']; ?>)" class="btn-danger btn-small" id="cancel-btn-<?php echo $order['order_id']; ?>">
                                    Cancel Order
                                </button>
                            <?php endif; ?>
                            <?php if ($order['order_status'] === 'delivered'): ?>
                                <button onclick="reorderItems(<?php echo $order['order_id']; ?>)" class="btn-primary btn-small">
                                    Reorder
                                </button>
                            <?php endif; ?>
                        </div>
                        <div id="order-items-<?php echo $order['order_id']; ?>" class="order-items-detail" style="display: none;">
                            <?php
                            // Get order items for this order
                            $items_stmt = $conn->prepare("
                                SELECT oi.*, p.title, p.image, c.name as category_name 
                                FROM order_items oi 
                                JOIN products p ON oi.product_id = p.product_id 
                                LEFT JOIN categories c ON p.category_id = c.category_id 
                                WHERE oi.order_id = ?
                            ");
                            $items_stmt->bind_param("i", $order['order_id']);
                            $items_stmt->execute();
                            $items_result = $items_stmt->get_result();
                            ?>
                            <div class="items-list">
                                <?php while ($item = $items_result->fetch_assoc()): ?>
                                    <div class="item-summary">
                                        <img src="/DigitalNovels/assets/images/<?php echo htmlspecialchars($item['image']); ?>" 
                                             alt="<?php echo htmlspecialchars($item['title']); ?>"
                                             onerror="this.src='/DigitalNovels/assets/images/book-placeholder.svg'">
                                        <div class="item-info">
                                            <span class="item-title"><?php echo htmlspecialchars($item['title']); ?></span>
                                            <span class="item-category"><?php echo htmlspecialchars($item['category_name']); ?></span>
                                            <span class="item-quantity">Qty: <?php echo $item['quantity']; ?> × $<?php echo number_format($item['price'], 2); ?></span>
                                        </div>
                                        <div class="item-subtotal">
                                            $<?php echo number_format($item['price'] * $item['quantity'], 2); ?>
                                        </div>
                                    </div>
                                <?php endwhile; ?>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    <?php endif; ?>

    <div class="orders-section">
        <h2>Current Cart Items</h2>
        <?php if (empty($cart['items'])): ?>
            <div class="empty-state">
                <p>You don't have any items in your cart.</p>
                <a href="/DigitalNovels/pages/products.php" class="btn-primary">Browse Books</a>
            </div>
        <?php else: ?>
            <div class="cart-summary-section">
                <p>You have <strong><?php echo count($cart['items']); ?> item(s)</strong> in your cart with a total of <strong>$<?php echo number_format($cart['total'], 2); ?></strong></p>
                <div class="cart-actions">
                    <a href="/DigitalNovels/cart/" class="btn-primary">View Full Cart</a>
                    <a href="/DigitalNovels/pages/checkout.php" class="btn-secondary">Proceed to Checkout</a>
                </div>
            </div>
            
            <div class="current-cart-items">
                <?php foreach ($cart['items'] as $item): ?>
                    <div class="cart-item-summary">
                        <div class="item-image">
                            <img src="/DigitalNovels/assets/images/<?php echo htmlspecialchars($item['image']); ?>" 
                                 alt="<?php echo htmlspecialchars($item['title']); ?>"
                                 onerror="this.src='/DigitalNovels/assets/images/book-placeholder.svg'">
                        </div>
                        <div class="item-details">
                            <h4><?php echo htmlspecialchars($item['title']); ?></h4>
                            <span class="category-tag"><?php echo htmlspecialchars($item['category_name']); ?></span>
                            <p class="item-price">$<?php echo number_format($item['base_price'], 2); ?> × <?php echo $item['quantity']; ?></p>
                        </div>
                        <div class="item-subtotal">
                            $<?php echo number_format($item['subtotal'], 2); ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <?php if ($orders_result->num_rows == 0 && empty($cart['items'])): ?>
        <div class="empty-state">
            <h3>No Orders Yet</h3>
            <p>You haven't made any purchases yet. Start browsing our collection!</p>
            <a href="/DigitalNovels/pages/products.php" class="btn-primary">Browse Books</a>
        </div>
    <?php endif; ?>
</div>

<script>
function toggleOrderItems(orderId) {
    const itemsDiv = document.getElementById('order-items-' + orderId);
    const button = event.target;
    
    if (itemsDiv.style.display === 'none' || itemsDiv.style.display === '') {
        itemsDiv.style.display = 'block';
        button.textContent = 'Hide Items';
    } else {
        itemsDiv.style.display = 'none';
        button.textContent = 'View Items';
    }
}

function cancelOrder(orderId) {
    if (!confirm('Are you sure you want to cancel this order? This action cannot be undone.')) {
        return;
    }

    const cancelBtn = document.getElementById('cancel-btn-' + orderId);
    const originalText = cancelBtn.textContent;
    cancelBtn.textContent = 'Cancelling...';
    cancelBtn.disabled = true;

    fetch('/DigitalNovels/includes/order_handler.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `action=cancel&order_id=${orderId}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update the status badge
            const statusBadge = cancelBtn.closest('.order-card').querySelector('.order-status');
            statusBadge.textContent = 'Cancelled';
            statusBadge.className = 'order-status status-cancelled';
            
            // Remove the cancel button
            cancelBtn.remove();
            
            // Show success message
            showNotification('Order cancelled successfully', 'success');
        } else {
            cancelBtn.textContent = originalText;
            cancelBtn.disabled = false;
            showNotification(data.message || 'Failed to cancel order', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        cancelBtn.textContent = originalText;
        cancelBtn.disabled = false;
        showNotification('An error occurred while cancelling the order', 'error');
    });
}

function reorderItems(orderId) {
    if (!confirm('Add all items from this order to your cart?')) {
        return;
    }

    const reorderBtn = event.target;
    const originalText = reorderBtn.textContent;
    reorderBtn.textContent = 'Adding to Cart...';
    reorderBtn.disabled = true;

    fetch('/DigitalNovels/includes/order_handler.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `action=reorder&order_id=${orderId}`
    })
    .then(response => response.json())
    .then(data => {
        reorderBtn.textContent = originalText;
        reorderBtn.disabled = false;
        
        if (data.success) {
            // Update cart count in header if it exists
            const cartCount = document.querySelector('.cart-count');
            if (cartCount && data.cart_count) {
                cartCount.textContent = data.cart_count;
            } else if (data.cart_count > 0) {
                // Create cart count if it doesn't exist
                const cartLink = document.querySelector('.cart-link');
                if (cartLink && !cartLink.querySelector('.cart-count')) {
                    const span = document.createElement('span');
                    span.className = 'cart-count';
                    span.textContent = data.cart_count;
                    cartLink.appendChild(span);
                }
            }
            
            showNotification(data.message, 'success');
        } else {
            showNotification(data.message || 'Failed to reorder items', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        reorderBtn.textContent = originalText;
        reorderBtn.disabled = false;
        showNotification('An error occurred while reordering', 'error');
    });
}

function showNotification(message, type) {
    // Remove existing notifications
    const existingNotifications = document.querySelectorAll('.notification');
    existingNotifications.forEach(n => n.remove());

    // Create notification
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.innerHTML = `
        <span>${message}</span>
        <button onclick="this.parentElement.remove()" class="notification-close">&times;</button>
    `;

    // Add to page
    document.body.appendChild(notification);

    // Auto remove after 5 seconds
    setTimeout(() => {
        if (notification.parentElement) {
            notification.remove();
        }
    }, 5000);
}
</script>

<style>
.orders-container {
    max-width: 1200px;
    margin: 2rem auto;
    padding: 0 1rem;
}

.orders-header {
    text-align: center;
    margin-bottom: 2rem;
}

.orders-header h1 {
    margin: 0;
    color: #333;
}

.orders-header p {
    color: #666;
    margin: 0.5rem 0 0 0;
}

.orders-section {
    margin-bottom: 3rem;
}

.orders-section h2 {
    color: #333;
    border-bottom: 2px solid #f0f0f0;
    padding-bottom: 0.5rem;
    margin-bottom: 1.5rem;
}

.order-card {
    background: white;
    border: 1px solid #ddd;
    border-radius: 12px;
    padding: 2rem;
    margin-bottom: 1.5rem;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    transition: transform 0.2s, box-shadow 0.2s;
}

.order-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.15);
}

.order-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 1.5rem;
    flex-wrap: wrap;
    gap: 1rem;
}

.order-info {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.order-id {
    font-weight: 700;
    color: #333;
    font-size: 1.1rem;
}

.order-date {
    color: #666;
    font-size: 0.9rem;
}

.order-status {
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.875rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.status-pending { background: #fff3cd; color: #856404; }
.status-processing { background: #cce5ff; color: #004085; }
.status-shipped { background: #d4edda; color: #155724; }
.status-delivered { background: #d1ecf1; color: #0c5460; }
.status-cancelled { background: #f8d7da; color: #721c24; }

.order-details {
    display: grid;
    grid-template-columns: 1fr auto;
    gap: 2rem;
    margin-bottom: 1.5rem;
    padding: 1rem;
    background: #f8f9fa;
    border-radius: 8px;
}

.order-customer {
    color: #333;
    line-height: 1.4;
}

.payment-method {
    padding: 0.25rem 0.75rem;
    background: #e9ecef;
    border-radius: 12px;
    font-size: 0.8rem;
    color: #495057;
    text-transform: capitalize;
}

.order-total {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem;
    background: linear-gradient(135deg, #28a745, #20c997);
    color: white;
    border-radius: 8px;
    margin-bottom: 1rem;
}

.total-label {
    font-size: 1rem;
    font-weight: 500;
}

.total-amount {
    font-size: 1.5rem;
    font-weight: 700;
}

.order-actions {
    display: flex;
    gap: 1rem;
    justify-content: flex-end;
}

.btn-small {
    padding: 0.5rem 1rem;
    font-size: 0.875rem;
}

.btn-outline {
    border: 2px solid #6c757d;
    color: #6c757d;
    background: white;
    text-decoration: none;
    border-radius: 6px;
    text-align: center;
    transition: all 0.2s;
    display: inline-block;
}

.btn-outline:hover {
    background: #6c757d;
    color: white;
}

.order-items-detail {
    margin-top: 1.5rem;
    border-top: 2px solid #f0f0f0;
    padding-top: 1.5rem;
}

.items-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.item-summary {
    display: grid;
    grid-template-columns: auto 1fr auto;
    gap: 1rem;
    align-items: center;
    padding: 1rem;
    background: #f8f9fa;
    border-radius: 8px;
}

.item-summary img {
    width: 50px;
    height: 75px;
    object-fit: cover;
    border-radius: 4px;
}

.item-info {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.item-title {
    font-weight: 600;
    color: #333;
}

.item-category {
    font-size: 0.8rem;
    color: #666;
    background: #e9ecef;
    padding: 0.125rem 0.5rem;
    border-radius: 8px;
    display: inline-block;
    width: fit-content;
}

.item-quantity {
    font-size: 0.875rem;
    color: #666;
}

.item-subtotal {
    font-weight: 600;
    color: #333;
    font-size: 1.1rem;
}

.cart-summary-section {
    background: linear-gradient(135deg, #007bff, #0056b3);
    color: white;
    padding: 2rem;
    border-radius: 12px;
    margin-bottom: 2rem;
}

.cart-summary-section p {
    margin: 0 0 1rem 0;
    font-size: 1.1rem;
}

.cart-actions {
    display: flex;
    gap: 1rem;
    margin-top: 1.5rem;
}

.current-cart-items {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.cart-item-summary {
    display: grid;
    grid-template-columns: auto 1fr auto;
    gap: 1.5rem;
    align-items: center;
    padding: 1.5rem;
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.cart-item-summary .item-image img {
    width: 80px;
    height: 120px;
    object-fit: cover;
    border-radius: 6px;
}

.cart-item-summary .item-details h4 {
    margin: 0 0 0.5rem 0;
    color: #333;
    font-size: 1.1rem;
}

.cart-item-summary .category-tag {
    display: inline-block;
    background: #f0f0f0;
    padding: 0.25rem 0.75rem;
    border-radius: 12px;
    font-size: 0.8rem;
    color: #666;
    margin-bottom: 0.5rem;
}

.cart-item-summary .item-price {
    margin: 0;
    color: #666;
}

.cart-item-summary .item-subtotal {
    font-weight: 600;
    color: #333;
    font-size: 1.3rem;
}

.empty-state {
    text-align: center;
    padding: 4rem 2rem;
    background: linear-gradient(135deg, #f8f9fa, #e9ecef);
    border-radius: 12px;
}

.empty-state h3 {
    color: #333;
    margin-bottom: 1rem;
    font-size: 1.5rem;
}

.empty-state p {
    color: #666;
    margin-bottom: 2rem;
    font-size: 1.1rem;
}

@media (max-width: 768px) {
    .order-header {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .order-details {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
    
    .order-actions {
        justify-content: center;
        flex-wrap: wrap;
    }
    
    .cart-actions {
        flex-direction: column;
    }
    
    .cart-item-summary {
        grid-template-columns: 1fr;
        text-align: center;
    }
    
    .item-summary {
        grid-template-columns: 1fr;
        text-align: center;
    }
}

/* Button styles for order actions */
.btn {
    display: inline-block;
    padding: 0.5rem 1rem;
    margin: 0.25rem;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 0.875rem;
    text-decoration: none;
    transition: all 0.3s ease;
    font-weight: 500;
    text-align: center;
}

.btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0,0,0,0.2);
}

.btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    transform: none;
    box-shadow: none;
}

.btn-primary {
    background: #007bff;
    color: white;
}

.btn-primary:hover:not(:disabled) {
    background: #0056b3;
}

.btn-secondary {
    background: #6c757d;
    color: white;
}

.btn-secondary:hover:not(:disabled) {
    background: #545b62;
}

.btn-danger {
    background: #dc3545;
    color: white;
}

.btn-danger:hover:not(:disabled) {
    background: #c82333;
}

.btn-success {
    background: #28a745;
    color: white;
}

.btn-success:hover:not(:disabled) {
    background: #218838;
}

/* Notification styles */
.notification {
    position: fixed;
    top: 20px;
    right: 20px;
    padding: 15px 20px;
    border-radius: 4px;
    color: white;
    font-weight: 500;
    z-index: 1000;
    display: flex;
    align-items: center;
    gap: 15px;
    min-width: 300px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    animation: slideIn 0.3s ease;
}

.notification-success {
    background: #28a745;
}

.notification-error {
    background: #dc3545;
}

.notification-close {
    background: none;
    border: none;
    color: white;
    font-size: 20px;
    cursor: pointer;
    padding: 0;
    line-height: 1;
}

@keyframes slideIn {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

/* Additional responsive styles */
@media (max-width: 768px) {
    .notification {
        right: 10px;
        left: 10px;
        min-width: auto;
    }
    
    .btn {
        font-size: 0.75rem;
        padding: 0.375rem 0.75rem;
    }
}
</style>

<?php include '../includes/footer.php'; ?>
        echo "<li>Order #{$order['order_id']} - {$order['order_date']} - Status: {$order['status']}</li>";
    }
    echo "</ul>";
} else {
    echo "<p>You have no orders yet.</p>";
}

include '../includes/footer.php';
?>
