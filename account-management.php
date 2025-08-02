<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Management - Digital Novels Help</title>
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
        .warning-box {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
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
    </style>
</head>
<body>
    <?php include '../includes/header.php'; ?>
    
    <div class="help-content">
        <div class="breadcrumb">
            <a href="../index.php">Home</a> > <a href="index.php">Help Center</a> > Account Management
        </div>
        
        <div class="help-nav">
            <h1>Account Management</h1>
            <p>Learn how to manage your Digital Novels account, update your profile, and keep your account secure.</p>
        </div>

        <div class="help-section" id="profile">
            <h2>Managing Your Profile</h2>
            <p>Your profile contains all your personal information and account preferences. Here's how to access and manage it:</p>
            
            <div class="step-list">
                <h3>Accessing Your Profile:</h3>
                <ol>
                    <li>Log in to your account</li>
                    <li>Click on "Profile" in the top navigation menu</li>
                    <li>View your account information and order history</li>
                </ol>
            </div>
            
            <h3>Profile Information Includes:</h3>
            <ul>
                <li><strong>Username:</strong> Your unique identifier on the platform</li>
                <li><strong>Email Address:</strong> Used for login and communications</li>
                <li><strong>Account Creation Date:</strong> When you joined Digital Novels</li>
                <li><strong>Order History:</strong> All your past purchases</li>
            </ul>
        </div>

        <div class="help-section" id="login">
            <h2>Login & Password Management</h2>
            
            <h3>Logging In</h3>
            <p>To access your account:</p>
            <div class="step-list">
                <ol>
                    <li>Click "Login" in the top navigation</li>
                    <li>Enter your email address and password</li>
                    <li>Click "Login" to access your account</li>
                </ol>
            </div>
            
            <h3>Password Security</h3>
            <p>We recommend following these password best practices:</p>
            <ul>
                <li>Use at least 6 characters (longer is better)</li>
                <li>Include a mix of letters, numbers, and symbols</li>
                <li>Don't use the same password on multiple sites</li>
                <li>Don't share your password with anyone</li>
            </ul>
            
            <div class="warning-box">
                <strong>Forgot Your Password?</strong> Currently, password reset must be handled through customer support. Contact us if you need to reset your password.
            </div>
        </div>

        <div class="help-section" id="settings">
            <h2>Account Settings</h2>
            
            <h3>Theme Preferences</h3>
            <p>Customize your browsing experience with our theme options:</p>
            <ul>
                <li><strong>Regular Theme:</strong> Clean, classic design</li>
                <li><strong>Modern Theme:</strong> Contemporary styling with enhanced visuals</li>
                <li><strong>Holiday Theme:</strong> Seasonal decorations and colors</li>
            </ul>
            
            <p>To change your theme, use the theme selector in the header navigation.</p>
            
            <h3>Email Preferences</h3>
            <p>Your email address is used for:</p>
            <ul>
                <li>Account login and authentication</li>
                <li>Order confirmations and receipts</li>
                <li>Important account notifications</li>
                <li>Customer support communications</li>
            </ul>
            
            <div class="highlight-box">
                <strong>Note:</strong> Keep your email address current to ensure you receive important account updates and order information.
            </div>
        </div>

        <div class="help-section" id="security">
            <h2>Security & Privacy</h2>
            
            <h3>Account Security</h3>
            <p>We take your account security seriously. Here's how we protect your information:</p>
            <ul>
                <li><strong>Password Encryption:</strong> Your password is securely hashed and stored</li>
                <li><strong>Secure Sessions:</strong> Your login sessions are protected</li>
                <li><strong>Data Protection:</strong> Your personal information is kept confidential</li>
            </ul>
            
            <h3>What You Can Do</h3>
            <ul>
                <li>Always log out when using shared computers</li>
                <li>Keep your password confidential</li>
                <li>Use a secure internet connection</li>
                <li>Report any suspicious account activity immediately</li>
            </ul>
            
            <h3>Privacy Information</h3>
            <p>We collect and use your information to:</p>
            <ul>
                <li>Process your orders and payments</li>
                <li>Provide customer support</li>
                <li>Improve our services</li>
                <li>Communicate important updates</li>
            </ul>
            
            <div class="warning-box">
                <strong>Suspicious Activity?</strong> If you notice any unauthorized access to your account, contact our support team immediately through the <a href="contact-support.php">Contact Support</a> page.
            </div>
        </div>

        <div style="text-align: center; margin-top: 40px;">
            <a href="index.php" style="background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px;">← Back to Help Center</a>
        </div>
    </div>

    <?php include '../includes/footer.php'; ?>
</body>
</html>
