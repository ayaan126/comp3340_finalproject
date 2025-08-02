<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping & Orders - Digital Novels Help</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .help-content {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            line-height: 1.6;
        }
        .help-nav {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 30px;
        }
        .help-section {
            margin-bottom: 40px;
            padding-bottom: 20px;
            border-bottom: 1px solid #eee;
        }
        .help-section:last-child {
            border-bottom: none;
        }
        .step-list {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .highlight-box {
            background: #e3f2fd;
            border-left: 4px solid #2196f3;
            padding: 15px;
            margin: 20px 0;
        }
        .success-box {
            background: #d4edda;
            border-left: 4px solid #28a745;
            padding: 15px;
            margin: 20px 0;
        }
        .breadcrumb {
            margin-bottom: 20px;
            color: #666;
        }
        .breadcrumb a {
            color: #007bff;
            text-decoration: none;
        }
        .feature-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin: 20px 0;
        }
        .feature-card {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            border-left: 3px solid #007bff;
        }
    </style>
</head>
<body>
    <?php include '../includes/header.php'; ?>
    
    <div class="help-content">
        <div class="breadcrumb">
            <a href="../index.php">Home</a> > <a href="index.php">Help Center</a> > Shopping & Orders
        </div>
        
        <div class="help-nav">
            <h1>Shopping & Orders</h1>
            <p>Learn how to browse, shop, and manage your orders on Digital Novels.</p>
        </div>

        <div class="help-section" id="shopping">
            <h2>How to Shop for Digital Novels</h2>
            <p>Shopping for digital novels is easy and convenient. Follow these steps to find and purchase your next great read:</p>
            
            <div class="step-list">
                <h3>Shopping Process:</h3>
                <ol>
                    <li><strong>Browse the Catalog:</strong> Visit the Products page to see all available books</li>
                    <li><strong>Use Search & Filters:</strong> Find specific books or browse by genre</li>
                    <li><strong>View Book Details:</strong> Click on any book to see detailed information</li>
                    <li><strong>Add to Cart:</strong> Click "Add to Cart" for books you want to purchase</li>
                    <li><strong>Review Your Cart:</strong> Check your selections before checkout</li>
                    <li><strong>Proceed to Checkout:</strong> Complete your purchase</li>
                </ol>
            </div>
            
            <div class="feature-grid">
                <div class="feature-card">
                    <h4>🔍 Smart Search</h4>
                    <p>Search by title, author, or keywords to find exactly what you're looking for.</p>
                </div>
                <div class="feature-card">
                    <h4>🏷️ Filter Options</h4>
                    <p>Filter by genre, price range, and other criteria to narrow your choices.</p>
                </div>
                <div class="feature-card">
                    <h4>📖 Book Details</h4>
                    <p>View descriptions, pricing, and cover images before purchasing.</p>
                </div>
            </div>
        </div>

        <div class="help-section" id="cart">
            <h2>Using Your Shopping Cart</h2>
            <p>Your shopping cart makes it easy to collect books and purchase them all at once.</p>
            
            <h3>Cart Features:</h3>
            <ul>
                <li><strong>Persistent Storage:</strong> Your cart saves items even if you log out</li>
                <li><strong>Quantity Management:</strong> Adjust quantities or remove items</li>
                <li><strong>Total Calculation:</strong> See your subtotal and total cost</li>
                <li><strong>Quick Access:</strong> Cart icon shows your current item count</li>
            </ul>
            
            <div class="step-list">
                <h3>Managing Your Cart:</h3>
                <ol>
                    <li>Click the cart icon in the header to view your cart</li>
                    <li>Review all items and their prices</li>
                    <li>Update quantities or remove items as needed</li>
                    <li>Click "Proceed to Checkout" when ready to purchase</li>
                </ol>
            </div>
            
            <div class="highlight-box">
                <strong>Pro Tip:</strong> Your cart persists across browser sessions, so you can take your time deciding on your purchases.
            </div>
        </div>

        <div class="help-section" id="checkout">
            <h2>Checkout Process</h2>
            <p>Our streamlined checkout process makes purchasing quick and secure.</p>
            
            <div class="step-list">
                <h3>Checkout Steps:</h3>
                <ol>
                    <li><strong>Review Order:</strong> Confirm all items and quantities</li>
                    <li><strong>Shipping Information:</strong> Enter your billing address</li>
                    <li><strong>Payment Details:</strong> Provide payment information</li>
                    <li><strong>Order Confirmation:</strong> Review and place your order</li>
                    <li><strong>Success!</strong> Receive confirmation and access to your books</li>
                </ol>
            </div>
            
            <h3>Required Information:</h3>
            <ul>
                <li>Full name</li>
                <li>Email address</li>
                <li>Billing address</li>
                <li>Payment method details</li>
            </ul>
            
            <div class="success-box">
                <strong>Instant Access:</strong> Digital novels are available immediately after successful payment!
            </div>
        </div>

        <div class="help-section" id="orders">
            <h2>Order History & Management</h2>
            <p>Keep track of all your purchases through your order history.</p>
            
            <h3>Accessing Order History:</h3>
            <div class="step-list">
                <ol>
                    <li>Log in to your account</li>
                    <li>Go to your Profile page</li>
                    <li>View your complete order history</li>
                    <li>Click on any order to see details</li>
                </ol>
            </div>
            
            <h3>Order Information Includes:</h3>
            <ul>
                <li><strong>Order Number:</strong> Unique identifier for each purchase</li>
                <li><strong>Order Date:</strong> When the purchase was made</li>
                <li><strong>Items Purchased:</strong> List of all books in the order</li>
                <li><strong>Total Amount:</strong> Final cost including any taxes</li>
                <li><strong>Payment Status:</strong> Confirmation of payment processing</li>
            </ul>
            
            <div class="highlight-box">
                <strong>Need Help?</strong> If you have questions about a specific order, contact support with your order number for fast assistance.
            </div>
        </div>

        <div class="help-section" id="payment">
            <h2>Payment Methods & Security</h2>
            <p>We accept various payment methods and ensure your payment information is secure.</p>
            
            <h3>Accepted Payment Methods:</h3>
            <ul>
                <li>Credit Cards (Visa, MasterCard, American Express)</li>
                <li>Debit Cards</li>
                <li>PayPal (where available)</li>
                <li>Other secure payment processors</li>
            </ul>
            
            <h3>Payment Security:</h3>
            <ul>
                <li><strong>Encrypted Transactions:</strong> All payments are securely encrypted</li>
                <li><strong>No Storage:</strong> We don't store your payment details</li>
                <li><strong>Secure Processing:</strong> Industry-standard payment security</li>
                <li><strong>Fraud Protection:</strong> Advanced fraud detection systems</li>
            </ul>
            
            <div class="success-box">
                <strong>Secure Shopping:</strong> Your payment information is protected with bank-level security standards.
            </div>
        </div>

        <div style="text-align: center; margin-top: 40px;">
            <a href="index.php" style="background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px;">← Back to Help Center</a>
        </div>
    </div>

    <?php include '../includes/footer.php'; ?>
</body>
</html>
