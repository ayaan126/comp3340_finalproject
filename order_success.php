<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../config/db.php';
require_once '../includes/header.php';

if (!isset($_SESSION['user_id']) || !isset($_GET['order_id'])) {
    header('Location: /DigitalNovels/index.php');
    exit;
}

$order_id = (int)$_GET['order_id'];

// Get order details
$stmt = $conn->prepare("SELECT * FROM orders WHERE order_id = ? AND user_id = ?");
$stmt->bind_param("ii", $order_id, $_SESSION['user_id']);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();

if (!$order) {
    header('Location: /DigitalNovels/index.php');
    exit;
}

// Get order items
$stmt = $conn->prepare("
    SELECT oi.*, p.title, p.image, c.name as category_name 
    FROM order_items oi 
    JOIN products p ON oi.product_id = p.product_id 
    LEFT JOIN categories c ON p.category_id = c.category_id 
    WHERE oi.order_id = ?
");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$order_items = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>

<div class="success-container">
    <div class="success-header">
        <div class="success-icon">✅</div>
        <h1>Order Placed Successfully!</h1>
        <p>Thank you for your purchase. Your order has been received and is being processed.</p>
    </div>

    <div class="order-details">
        <div class="detail-card">
            <h2>Order Information</h2>
            <div class="detail-grid">
                <div class="detail-item">
                    <strong>Order Number:</strong>
                    <span>#<?php echo str_pad($order['order_id'], 6, '0', STR_PAD_LEFT); ?></span>
                </div>
                <div class="detail-item">
                    <strong>Order Date:</strong>
                    <span><?php echo date('F j, Y g:i A', strtotime($order['order_date'])); ?></span>
                </div>
                <div class="detail-item">
                    <strong>Status:</strong>
                    <span class="status-badge status-<?php echo $order['order_status']; ?>">
                        <?php echo ucfirst($order['order_status']); ?>
                    </span>
                </div>
                <div class="detail-item">
                    <strong>Total Amount:</strong>
                    <span class="total-amount">$<?php echo number_format($order['total_amount'], 2); ?></span>
                </div>
            </div>
        </div>

        <div class="detail-card">
            <h2>Customer Information</h2>
            <div class="detail-grid">
                <div class="detail-item">
                    <strong>Name:</strong>
                    <span><?php echo htmlspecialchars($order['customer_name']); ?></span>
                </div>
                <div class="detail-item">
                    <strong>Email:</strong>
                    <span><?php echo htmlspecialchars($order['customer_email']); ?></span>
                </div>
                <div class="detail-item">
                    <strong>Phone:</strong>
                    <span><?php echo htmlspecialchars($order['customer_phone']); ?></span>
                </div>
                <div class="detail-item">
                    <strong>Payment Method:</strong>
                    <span><?php echo ucfirst(str_replace('_', ' ', $order['payment_method'])); ?></span>
                </div>
            </div>
        </div>

        <div class="detail-card">
            <h2>Shipping Address</h2>
            <p><?php echo htmlspecialchars($order['shipping_address']); ?></p>
        </div>
    </div>

    <div class="order-items-section">
        <h2>Order Items</h2>
        <div class="order-items">
            <?php foreach ($order_items as $item): ?>
                <div class="order-item">
                    <div class="item-image">
                        <img src="/DigitalNovels/assets/images/<?php echo htmlspecialchars($item['image']); ?>" 
                             alt="<?php echo htmlspecialchars($item['title']); ?>"
                             onerror="this.src='/DigitalNovels/assets/images/book-placeholder.svg'">
                    </div>
                    <div class="item-details">
                        <h3><?php echo htmlspecialchars($item['title']); ?></h3>
                        <span class="category-tag"><?php echo htmlspecialchars($item['category_name']); ?></span>
                        <p class="item-price">$<?php echo number_format($item['price'], 2); ?> × <?php echo $item['quantity']; ?></p>
                    </div>
                    <div class="item-total">
                        $<?php echo number_format($item['price'] * $item['quantity'], 2); ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="success-actions">
        <div class="action-card">
            <h3>What's Next?</h3>
            <ul class="next-steps">
                <li>📧 You'll receive an order confirmation email shortly</li>
                <li>📦 We'll notify you when your order ships</li>
                <li>🚚 Estimated delivery: 3-5 business days</li>
                <li>❓ Questions? Contact our customer support</li>
            </ul>
        </div>
        
        <div class="action-buttons">
            <a href="/DigitalNovels/pages/orders.php" class="btn-primary">View Order History</a>
            <a href="/DigitalNovels/pages/products.php" class="btn-secondary">Continue Shopping</a>
            <a href="/DigitalNovels/index.php" class="btn-outline">Back to Home</a>
        </div>
    </div>
</div>

<style>
.success-container {
    max-width: 1000px;
    margin: 2rem auto;
    padding: 0 1rem;
}

.success-header {
    text-align: center;
    margin-bottom: 3rem;
    padding: 2rem;
    background: linear-gradient(135deg, #28a745, #20c997);
    color: white;
    border-radius: 12px;
}

.success-icon {
    font-size: 4rem;
    margin-bottom: 1rem;
}

.success-header h1 {
    margin: 0 0 1rem 0;
    font-size: 2.5rem;
}

.success-header p {
    margin: 0;
    font-size: 1.2rem;
    opacity: 0.9;
}

.order-details {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 2rem;
    margin-bottom: 3rem;
}

.detail-card {
    background: white;
    padding: 2rem;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.detail-card:last-child {
    grid-column: 1 / -1;
}

.detail-card h2 {
    margin: 0 0 1.5rem 0;
    color: #333;
    padding-bottom: 0.5rem;
    border-bottom: 2px solid #f0f0f0;
}

.detail-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
}

.detail-item {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.detail-item strong {
    color: #666;
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.detail-item span {
    color: #333;
    font-size: 1.1rem;
}

.status-badge {
    display: inline-block;
    padding: 0.25rem 0.75rem;
    border-radius: 12px;
    font-size: 0.875rem;
    font-weight: 500;
}

.status-pending {
    background: #fff3cd;
    color: #856404;
}

.total-amount {
    font-weight: 600;
    font-size: 1.25rem !important;
    color: #28a745 !important;
}

.order-items-section {
    margin-bottom: 3rem;
}

.order-items-section h2 {
    margin-bottom: 1.5rem;
    color: #333;
    padding-bottom: 0.5rem;
    border-bottom: 2px solid #f0f0f0;
}

.order-items {
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    overflow: hidden;
}

.order-item {
    display: grid;
    grid-template-columns: auto 1fr auto;
    gap: 1.5rem;
    align-items: center;
    padding: 1.5rem;
    border-bottom: 1px solid #f0f0f0;
}

.order-item:last-child {
    border-bottom: none;
}

.item-image img {
    width: 80px;
    height: 120px;
    object-fit: cover;
    border-radius: 6px;
}

.item-details h3 {
    margin: 0 0 0.5rem 0;
    color: #333;
    font-size: 1.1rem;
}

.category-tag {
    display: inline-block;
    background: #f0f0f0;
    padding: 0.25rem 0.75rem;
    border-radius: 12px;
    font-size: 0.8rem;
    color: #666;
    margin-bottom: 0.5rem;
}

.item-price {
    margin: 0;
    color: #666;
    font-size: 0.9rem;
}

.item-total {
    font-weight: 600;
    font-size: 1.2rem;
    color: #333;
}

.success-actions {
    display: grid;
    grid-template-columns: 1fr auto;
    gap: 2rem;
    align-items: start;
}

.action-card {
    background: white;
    padding: 2rem;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.action-card h3 {
    margin: 0 0 1rem 0;
    color: #333;
}

.next-steps {
    list-style: none;
    padding: 0;
    margin: 0;
}

.next-steps li {
    padding: 0.5rem 0;
    color: #666;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.action-buttons {
    display: flex;
    flex-direction: column;
    gap: 1rem;
    min-width: 200px;
}

.btn-outline {
    border: 2px solid #6c757d;
    color: #6c757d;
    background: white;
    padding: 0.75rem 1.5rem;
    text-decoration: none;
    border-radius: 6px;
    text-align: center;
    transition: all 0.2s;
}

.btn-outline:hover {
    background: #6c757d;
    color: white;
}

@media (max-width: 768px) {
    .success-header h1 {
        font-size: 2rem;
    }
    
    .order-details {
        grid-template-columns: 1fr;
    }
    
    .detail-grid {
        grid-template-columns: 1fr;
    }
    
    .order-item {
        grid-template-columns: 1fr;
        text-align: center;
        gap: 1rem;
    }
    
    .success-actions {
        grid-template-columns: 1fr;
    }
    
    .action-buttons {
        order: -1;
    }
}
</style>

<?php include '../includes/footer.php'; ?>
