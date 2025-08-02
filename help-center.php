<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Help Center - Digital Novels</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .help-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        .help-nav {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
        }
        .help-nav h2 {
            margin-top: 0;
            color: #333;
        }
        .help-categories {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        .help-category {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            border-left: 4px solid #007bff;
        }
        .help-category h3 {
            margin-top: 0;
            color: #007bff;
        }
        .help-category ul {
            list-style: none;
            padding: 0;
        }
        .help-category li {
            margin: 10px 0;
        }
        .help-category a {
            color: #333;
            text-decoration: none;
            padding: 5px 0;
            display: block;
        }
        .help-category a:hover {
            color: #007bff;
            text-decoration: underline;
        }
        .search-box {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
            margin-bottom: 20px;
        }
        .breadcrumb {
            margin-bottom: 20px;
            color: #666;
        }
        .breadcrumb a {
            color: #007bff;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <?php include '../includes/header.php'; ?>
    
    <div class="help-container">
        <div class="breadcrumb">
            <a href="../index.php">Home</a> > Help Center
        </div>
        
        <div class="help-nav">
            <h1>Digital Novels Help Center</h1>
            <p>Welcome to our comprehensive help center. Find answers to your questions and learn how to make the most of Digital Novels.</p>
            
            <input type="text" class="search-box" placeholder="Search help articles..." id="helpSearch">
        </div>

        <div class="help-categories">
            <div class="help-category">
                <h3>Getting Started</h3>
                <ul>
                    <li><a href="getting-started.php">How to Get Started</a></li>
                    <li><a href="getting-started.php#account">Creating Your Account</a></li>
                    <li><a href="getting-started.php#navigation">Navigating the Site</a></li>
                    <li><a href="getting-started.php#features">Key Features Overview</a></li>
                </ul>
            </div>

            <div class="help-category">
                <h3>Account Management</h3>
                <ul>
                    <li><a href="account-management.php">Managing Your Profile</a></li>
                    <li><a href="account-management.php#login">Login & Password</a></li>
                    <li><a href="account-management.php#settings">Account Settings</a></li>
                    <li><a href="account-management.php#security">Security & Privacy</a></li>
                </ul>
            </div>

            <div class="help-category">
                <h3>Shopping & Orders</h3>
                <ul>
                    <li><a href="shopping-orders.php">How to Shop</a></li>
                    <li><a href="shopping-orders.php#cart">Using Your Cart</a></li>
                    <li><a href="shopping-orders.php#checkout">Checkout Process</a></li>
                    <li><a href="shopping-orders.php#orders">Order History</a></li>
                    <li><a href="shopping-orders.php#payment">Payment Methods</a></li>
                </ul>
            </div>

            <div class="help-category">
                <h3>Digital Novels</h3>
                <ul>
                    <li><a href="digital-novels.php">About Digital Novels</a></li>
                    <li><a href="digital-novels.php#formats">Supported Formats</a></li>
                    <li><a href="digital-novels.php#reading">How to Read</a></li>
                    <li><a href="digital-novels.php#download">Downloading Books</a></li>
                    <li><a href="digital-novels.php#devices">Compatible Devices</a></li>
                </ul>
            </div>

            <div class="help-category">
                <h3>Troubleshooting</h3>
                <ul>
                    <li><a href="troubleshooting.php">Common Issues</a></li>
                    <li><a href="troubleshooting.php#login-issues">Login Problems</a></li>
                    <li><a href="troubleshooting.php#payment-issues">Payment Issues</a></li>
                    <li><a href="troubleshooting.php#download-issues">Download Problems</a></li>
                    <li><a href="troubleshooting.php#technical">Technical Support</a></li>
                </ul>
            </div>

            <div class="help-category">
                <h3>Contact & Support</h3>
                <ul>
                    <li><a href="contact-support.php">Contact Us</a></li>
                    <li><a href="contact-support.php#faq">Frequently Asked Questions</a></li>
                    <li><a href="contact-support.php#feedback">Send Feedback</a></li>
                    <li><a href="contact-support.php#report">Report Issues</a></li>
                </ul>
            </div>
        </div>
    </div>

    <script>
        // Simple search functionality
        document.getElementById('helpSearch').addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const categories = document.querySelectorAll('.help-category');
            
            categories.forEach(category => {
                const links = category.querySelectorAll('a');
                let hasVisibleLinks = false;
                
                links.forEach(link => {
                    const linkText = link.textContent.toLowerCase();
                    if (linkText.includes(searchTerm)) {
                        link.parentElement.style.display = 'block';
                        hasVisibleLinks = true;
                    } else {
                        link.parentElement.style.display = searchTerm === '' ? 'block' : 'none';
                    }
                });
                
                category.style.display = (hasVisibleLinks || searchTerm === '') ? 'block' : 'none';
            });
        });
    </script>

    <?php include '../includes/footer.php'; ?>
</body>
</html>
