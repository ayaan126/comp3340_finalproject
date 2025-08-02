<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../config/db.php';
require_once '../includes/CartManager.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: /DigitalNovels/pages/login.php');
    exit;
}

$cartManager = new CartManager($conn);
$cart = $cartManager->getCart($_SESSION['user_id']);

if (empty($cart['items'])) {
    header('Location: /DigitalNovels/pages/products.php');
    exit;
}

// Get user information for pre-filling form
$stmt = $conn->prepare("SELECT username, email FROM users WHERE user_id = ?");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

$error = '';
$processing = false;

// Process checkout form submission
if ($_POST && isset($_POST['place_order'])) {
    $processing = true;
    
    // Validate form data
    $required_fields = ['first_name', 'last_name', 'email', 'phone', 'address', 'city', 'postal_code', 'payment_method'];
    $form_data = [];
    
    foreach ($required_fields as $field) {
        $form_data[$field] = trim($_POST[$field] ?? '');
        if (empty($form_data[$field])) {
            $error = 'Please fill in all required fields.';
            $processing = false;
            break;
        }
    }
    
    if (!$error && !filter_var($form_data['email'], FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
        $processing = false;
    }
    
    if (!$error) {
        try {
            // Start transaction
            $conn->begin_transaction();
            
            // Create order
            $shipping_address = $form_data['address'] . ', ' . $form_data['city'] . ' ' . $form_data['postal_code'];
            $stmt = $conn->prepare("INSERT INTO orders (user_id, total_amount, order_status, shipping_address, customer_name, customer_email, customer_phone, payment_method) VALUES (?, ?, 'pending', ?, ?, ?, ?, ?)");
            $customer_name = $form_data['first_name'] . ' ' . $form_data['last_name'];
            $stmt->bind_param("idsssss", $_SESSION['user_id'], $cart['total'], $shipping_address, $customer_name, $form_data['email'], $form_data['phone'], $form_data['payment_method']);
            $stmt->execute();
            
            $order_id = $conn->insert_id;
            
            // Add order items
            foreach ($cart['items'] as $item) {
                $stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("iiid", $order_id, $item['product_id'], $item['quantity'], $item['base_price']);
                $stmt->execute();
            }
            
            // Clear cart
            $cartManager->clearCart($_SESSION['user_id']);
            
            // Commit transaction
            $conn->commit();
            
            // Redirect to success page
            header('Location: /DigitalNovels/pages/order_success.php?order_id=' . $order_id);
            exit;
            
        } catch (Exception $e) {
            $conn->rollback();
            $error = 'An error occurred while processing your order. Please try again.';
            error_log("Checkout error: " . $e->getMessage());
            $processing = false;
        }
    }
}

// Include header after all processing is complete
include '../includes/header.php';
?>

<div class="checkout-container">
    <div class="checkout-header">
        <h1>Checkout <a href="javascript:showContextHelp()" style="font-size: 14px; color: #007bff; text-decoration: none; margin-left: 10px;">❓ Need Help?</a></h1>
        <div class="checkout-steps">
            <div class="step active">
                <span class="step-number">1</span>
                <span class="step-title">Shipping & Payment</span>
            </div>
            <div class="step">
                <span class="step-number">2</span>
                <span class="step-title">Confirmation</span>
            </div>
        </div>
    </div>

    <?php if ($error): ?>
        <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <div class="checkout-content">
        <div class="checkout-form-section">
            <form method="POST" class="checkout-form" <?php echo $processing ? 'style="pointer-events: none; opacity: 0.6;"' : ''; ?>>
                <div class="form-section">
                    <h2>Shipping Information</h2>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="first_name">First Name *</label>
                            <input type="text" id="first_name" name="first_name" required 
                                   value="<?php echo htmlspecialchars($_POST['first_name'] ?? ''); ?>">
                        </div>
                        <div class="form-group">
                            <label for="last_name">Last Name *</label>
                            <input type="text" id="last_name" name="last_name" required 
                                   value="<?php echo htmlspecialchars($_POST['last_name'] ?? ''); ?>">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email Address *</label>
                        <input type="email" id="email" name="email" required 
                               value="<?php echo htmlspecialchars($_POST['email'] ?? $user['email']); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="phone">Phone Number *</label>
                        <input type="tel" id="phone" name="phone" required 
                               value="<?php echo htmlspecialchars($_POST['phone'] ?? ''); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="address">Street Address *</label>
                        <input type="text" id="address" name="address" required 
                               value="<?php echo htmlspecialchars($_POST['address'] ?? ''); ?>">
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="city">City *</label>
                            <input type="text" id="city" name="city" required 
                                   value="<?php echo htmlspecialchars($_POST['city'] ?? ''); ?>">
                        </div>
                        <div class="form-group">
                            <label for="state">State/Province</label>
                            <input type="text" id="state" name="state" 
                                   value="<?php echo htmlspecialchars($_POST['state'] ?? ''); ?>">
                        </div>
                        <div class="form-group">
                            <label for="postal_code">Postal Code *</label>
                            <input type="text" id="postal_code" name="postal_code" required 
                                   value="<?php echo htmlspecialchars($_POST['postal_code'] ?? ''); ?>">
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <h2>Payment Information</h2>
                    <div class="payment-methods">
                        <div class="payment-method">
                            <input type="radio" id="credit_card" name="payment_method" value="credit_card" 
                                   <?php echo ($_POST['payment_method'] ?? '') === 'credit_card' ? 'checked' : ''; ?> required>
                            <label for="credit_card">
                                <span class="payment-icon">💳</span>
                                Credit/Debit Card
                            </label>
                        </div>
                        <div class="payment-method">
                            <input type="radio" id="paypal" name="payment_method" value="paypal" 
                                   <?php echo ($_POST['payment_method'] ?? '') === 'paypal' ? 'checked' : ''; ?>>
                            <label for="paypal">
                                <span class="payment-icon">📱</span>
                                PayPal
                            </label>
                        </div>
                        <div class="payment-method">
                            <input type="radio" id="bank_transfer" name="payment_method" value="bank_transfer" 
                                   <?php echo ($_POST['payment_method'] ?? '') === 'bank_transfer' ? 'checked' : ''; ?>>
                            <label for="bank_transfer">
                                <span class="payment-icon">🏦</span>
                                Bank Transfer
                            </label>
                        </div>
                    </div>
                    
                    <div class="payment-note">
                        <p><strong>Note:</strong> This is a demo checkout. No actual payment will be processed.</p>
                    </div>
                </div>

                <div class="form-actions">
                    <a href="/DigitalNovels/cart/" class="btn-secondary">← Back to Cart</a>
                    <button type="submit" name="place_order" class="btn-primary" <?php echo $processing ? 'disabled' : ''; ?>>
                        <?php echo $processing ? 'Processing...' : 'Place Order'; ?>
                    </button>
                </div>
            </form>
        </div>

        <div class="order-summary">
            <div class="summary-card">
                <h2>Order Summary</h2>
                
                <div class="order-items">
                    <?php foreach ($cart['items'] as $item): ?>
                        <div class="order-item">
                            <div class="item-image">
                                <img src="/DigitalNovels/assets/images/<?php echo htmlspecialchars($item['image']); ?>" 
                                     alt="<?php echo htmlspecialchars($item['title']); ?>"
                                     onerror="this.src='/DigitalNovels/assets/images/book-placeholder.svg'">
                            </div>
                            <div class="item-details">
                                <h4><?php echo htmlspecialchars($item['title']); ?></h4>
                                <p class="item-category"><?php echo htmlspecialchars($item['category_name']); ?></p>
                                <p class="item-quantity">Qty: <?php echo $item['quantity']; ?></p>
                            </div>
                            <div class="item-price">
                                $<?php echo number_format($item['subtotal'], 2); ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <div class="order-totals">
                    <div class="total-row">
                        <span>Subtotal:</span>
                        <span>$<?php echo number_format($cart['total'], 2); ?></span>
                    </div>
                    <div class="total-row">
                        <span>Shipping:</span>
                        <span>Free</span>
                    </div>
                    <div class="total-row">
                        <span>Tax:</span>
                        <span>$0.00</span>
                    </div>
                    <div class="total-row final-total">
                        <span><strong>Total:</strong></span>
                        <span><strong>$<?php echo number_format($cart['total'], 2); ?></strong></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.checkout-container {
    max-width: 1200px;
    margin: 2rem auto;
    padding: 0 1rem;
}

.checkout-header {
    text-align: center;
    margin-bottom: 2rem;
}

.checkout-header h1 {
    margin: 0 0 1rem 0;
    color: #333;
}

.checkout-steps {
    display: flex;
    justify-content: center;
    gap: 2rem;
    margin-bottom: 2rem;
}

.step {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: #999;
}

.step.active {
    color: #007bff;
}

.step-number {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 30px;
    height: 30px;
    border-radius: 50%;
    background: #f0f0f0;
    color: #999;
    font-weight: 600;
}

.step.active .step-number {
    background: #007bff;
    color: white;
}

.checkout-content {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 2rem;
}

.checkout-form {
    background: white;
    padding: 2rem;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.form-section {
    margin-bottom: 2rem;
}

.form-section h2 {
    margin: 0 0 1rem 0;
    color: #333;
    padding-bottom: 0.5rem;
    border-bottom: 2px solid #f0f0f0;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
}

.form-row.three-cols {
    grid-template-columns: 1fr 1fr 1fr;
}

.form-group {
    margin-bottom: 1rem;
}

.form-group label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 500;
    color: #333;
}

.form-group input {
    width: 100%;
    padding: 0.75rem;
    border: 1px solid #ddd;
    border-radius: 4px;
    box-sizing: border-box;
    font-size: 1rem;
}

.form-group input:focus {
    outline: none;
    border-color: #007bff;
    box-shadow: 0 0 0 2px rgba(0, 123, 255, 0.25);
}

.payment-methods {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.payment-method {
    border: 2px solid #eee;
    border-radius: 8px;
    padding: 1rem;
    cursor: pointer;
    transition: all 0.2s;
}

.payment-method:hover {
    border-color: #007bff;
}

.payment-method input[type="radio"] {
    display: none;
}

.payment-method input[type="radio"]:checked + label {
    color: #007bff;
}

.payment-method input[type="radio"]:checked {
    + label::before {
        background: #007bff;
        border-color: #007bff;
    }
}

.payment-method label {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    cursor: pointer;
    font-weight: 500;
    margin: 0;
}

.payment-method label::before {
    content: '';
    width: 20px;
    height: 20px;
    border: 2px solid #ddd;
    border-radius: 50%;
    background: white;
    transition: all 0.2s;
}

.payment-method input[type="radio"]:checked ~ label::before {
    background: #007bff;
    border-color: #007bff;
    box-shadow: inset 0 0 0 3px white;
}

.payment-icon {
    font-size: 1.2rem;
}

.payment-note {
    margin-top: 1rem;
    padding: 1rem;
    background: #f8f9fa;
    border-radius: 4px;
    border-left: 4px solid #007bff;
}

.payment-note p {
    margin: 0;
    font-size: 0.9rem;
    color: #666;
}

.form-actions {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 2rem;
    border-top: 1px solid #eee;
}

.summary-card {
    background: white;
    padding: 2rem;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    position: sticky;
    top: 2rem;
}

.summary-card h2 {
    margin: 0 0 1rem 0;
    color: #333;
    padding-bottom: 0.5rem;
    border-bottom: 2px solid #f0f0f0;
}

.order-items {
    margin-bottom: 1.5rem;
}

.order-item {
    display: grid;
    grid-template-columns: auto 1fr auto;
    gap: 1rem;
    align-items: center;
    padding: 1rem 0;
    border-bottom: 1px solid #f0f0f0;
}

.order-item:last-child {
    border-bottom: none;
}

.item-image img {
    width: 50px;
    height: 75px;
    object-fit: cover;
    border-radius: 4px;
}

.item-details h4 {
    margin: 0 0 0.25rem 0;
    font-size: 0.9rem;
    color: #333;
}

.item-category,
.item-quantity {
    margin: 0;
    font-size: 0.8rem;
    color: #666;
}

.item-price {
    font-weight: 600;
    color: #333;
}

.order-totals {
    border-top: 1px solid #eee;
    padding-top: 1rem;
}

.total-row {
    display: flex;
    justify-content: space-between;
    margin-bottom: 0.5rem;
}

.final-total {
    font-size: 1.1rem;
    padding-top: 0.5rem;
    border-top: 1px solid #eee;
    margin-top: 0.5rem;
}

.error-message {
    background: #fee;
    border: 1px solid #fcc;
    color: #c33;
    padding: 1rem;
    border-radius: 4px;
    margin-bottom: 2rem;
}

@media (max-width: 768px) {
    .checkout-content {
        grid-template-columns: 1fr;
    }
    
    .form-row {
        grid-template-columns: 1fr;
    }
    
    .form-actions {
        flex-direction: column;
        gap: 1rem;
    }
    
    .checkout-steps {
        flex-direction: column;
        align-items: center;
    }
}
</style>

<?php include '../includes/footer.php'; ?>
