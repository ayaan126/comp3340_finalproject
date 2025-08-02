<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Troubleshooting - Digital Novels Help</title>
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
        .problem-card {
            background: #f8f9fa;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin: 15px 0;
            border-radius: 0 8px 8px 0;
        }
        .solution-box {
            background: #d4edda;
            border-left: 4px solid #28a745;
            padding: 15px;
            margin: 15px 0;
            border-radius: 0 8px 8px 0;
        }
        .warning-box {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin: 20px 0;
        }
        .error-box {
            background: #f8d7da;
            border-left: 4px solid #dc3545;
            padding: 15px;
            margin: 15px 0;
            border-radius: 0 8px 8px 0;
        }
        .breadcrumb {
            margin-bottom: 20px;
            color: #666;
        }
        .breadcrumb a {
            color: #007bff;
            text-decoration: none;
        }
        .step-list {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <?php include '../includes/header.php'; ?>
    
    <div class="help-content">
        <div class="breadcrumb">
            <a href="../index.php">Home</a> > <a href="index.php">Help Center</a> > Troubleshooting
        </div>
        
        <div class="help-nav">
            <h1>Troubleshooting Guide</h1>
            <p>Common issues and their solutions. If you don't find your answer here, contact our support team.</p>
        </div>

        <div class="help-section" id="common">
            <h2>Common Issues & Quick Fixes</h2>
            <p>Here are the most frequently encountered issues and their solutions:</p>
            
            <div class="problem-card">
                <h4>🔄 Page Not Loading</h4>
                <p><strong>Problem:</strong> Website pages are slow to load or not loading at all.</p>
            </div>
            <div class="solution-box">
                <strong>Solutions:</strong>
                <ul>
                    <li>Refresh the page (Ctrl+F5 or Cmd+Shift+R)</li>
                    <li>Clear your browser cache and cookies</li>
                    <li>Try a different browser</li>
                    <li>Check your internet connection</li>
                    <li>Disable browser extensions temporarily</li>
                </ul>
            </div>

            <div class="problem-card">
                <h4>🎨 Display Issues</h4>
                <p><strong>Problem:</strong> Website looks broken, images missing, or formatting problems.</p>
            </div>
            <div class="solution-box">
                <strong>Solutions:</strong>
                <ul>
                    <li>Hard refresh the page (Ctrl+Shift+F5)</li>
                    <li>Clear browser cache</li>
                    <li>Enable JavaScript in your browser</li>
                    <li>Try switching themes using the theme selector</li>
                    <li>Update your browser to the latest version</li>
                </ul>
            </div>

            <div class="problem-card">
                <h4>🛒 Cart Problems</h4>
                <p><strong>Problem:</strong> Items not adding to cart or cart showing incorrect quantities.</p>
            </div>
            <div class="solution-box">
                <strong>Solutions:</strong>
                <ul>
                    <li>Make sure you're logged in</li>
                    <li>Enable cookies in your browser</li>
                    <li>Clear browser cache and cookies</li>
                    <li>Try adding items again</li>
                    <li>Contact support if issues persist</li>
                </ul>
            </div>
        </div>

        <div class="help-section" id="login-issues">
            <h2>Login Problems</h2>
            <p>Having trouble accessing your account? Here are common login issues and solutions:</p>
            
            <div class="error-box">
                <h4>❌ "Invalid email or password"</h4>
                <strong>Possible Causes:</strong>
                <ul>
                    <li>Typing error in email or password</li>
                    <li>Caps Lock is enabled</li>
                    <li>Using wrong email address</li>
                    <li>Password was changed recently</li>
                </ul>
            </div>
            
            <div class="step-list">
                <h3>Troubleshooting Steps:</h3>
                <ol>
                    <li><strong>Double-check your email:</strong> Make sure it's the same one used for registration</li>
                    <li><strong>Verify password:</strong> Check for typos, spaces, or incorrect capitalization</li>
                    <li><strong>Check Caps Lock:</strong> Ensure it's not accidentally enabled</li>
                    <li><strong>Try copy/paste:</strong> Copy your credentials from a secure location</li>
                    <li><strong>Clear browser data:</strong> Remove stored passwords that might be incorrect</li>
                </ol>
            </div>

            <div class="error-box">
                <h4>🔒 Account Locked or Suspended</h4>
                <p>If you see messages about locked accounts, contact support immediately.</p>
            </div>
            
            <div class="warning-box">
                <strong>Forgot Your Password?</strong> Currently, password resets must be handled through customer support. Contact us with your email address and we'll help you regain access.
            </div>
        </div>

        <div class="help-section" id="payment-issues">
            <h2>Payment Issues</h2>
            <p>Payment problems can be frustrating. Here's how to resolve common payment issues:</p>
            
            <div class="error-box">
                <h4>💳 Payment Declined</h4>
                <strong>Common Reasons:</strong>
                <ul>
                    <li>Insufficient funds</li>
                    <li>Incorrect card details</li>
                    <li>Expired card</li>
                    <li>Bank security measures</li>
                    <li>International transaction restrictions</li>
                </ul>
            </div>
            
            <div class="solution-box">
                <strong>Solutions:</strong>
                <ul>
                    <li><strong>Verify card details:</strong> Check card number, expiry date, and CVV</li>
                    <li><strong>Contact your bank:</strong> Ensure the card is active and has sufficient funds</li>
                    <li><strong>Try a different card:</strong> Use an alternative payment method</li>
                    <li><strong>Check billing address:</strong> Ensure it matches your card's registered address</li>
                    <li><strong>Wait and retry:</strong> Sometimes temporary issues resolve themselves</li>
                </ul>
            </div>

            <div class="error-box">
                <h4>⏳ Payment Processing Delays</h4>
                <p>Payment appears to be stuck or taking too long to process.</p>
            </div>
            
            <div class="solution-box">
                <strong>What to do:</strong>
                <ul>
                    <li>Wait 5-10 minutes before trying again</li>
                    <li>Check your email for confirmation</li>
                    <li>Don't refresh the payment page repeatedly</li>
                    <li>Contact support if no confirmation after 30 minutes</li>
                </ul>
            </div>
        </div>

        <div class="help-section" id="download-issues">
            <h2>Download Problems</h2>
            <p>Issues downloading your purchased books? Here's how to fix them:</p>
            
            <div class="problem-card">
                <h4>📥 Download Won't Start</h4>
                <p>Download links not working or files not downloading.</p>
            </div>
            
            <div class="solution-box">
                <strong>Troubleshooting:</strong>
                <ul>
                    <li><strong>Right-click and "Save As":</strong> Instead of left-clicking the download link</li>
                    <li><strong>Try different browser:</strong> Some browsers handle downloads differently</li>
                    <li><strong>Check pop-up blocker:</strong> Disable if it's blocking downloads</li>
                    <li><strong>Disable ad blocker:</strong> Temporarily turn off ad blocking extensions</li>
                    <li><strong>Clear download history:</strong> Remove old downloads that might be interfering</li>
                </ul>
            </div>

            <div class="problem-card">
                <h4>📱 File Won't Open</h4>
                <p>Downloaded file appears corrupted or won't open in reading apps.</p>
            </div>
            
            <div class="solution-box">
                <strong>Solutions:</strong>
                <ul>
                    <li><strong>Re-download:</strong> The file may have been corrupted during download</li>
                    <li><strong>Try different format:</strong> Download the same book in a different format</li>
                    <li><strong>Install reading app:</strong> Make sure you have compatible software</li>
                    <li><strong>Check file extension:</strong> Ensure the file has the correct extension (.epub, .pdf, etc.)</li>
                </ul>
            </div>

            <div class="warning-box">
                <strong>Missing Download Links?</strong> If you don't see download links in your order history, the payment may not have completed successfully. Check your email for payment confirmation or contact support.
            </div>
        </div>

        <div class="help-section" id="technical">
            <h2>Technical Support</h2>
            <p>For technical issues not covered above, try these general troubleshooting steps:</p>
            
            <div class="step-list">
                <h3>General Troubleshooting:</h3>
                <ol>
                    <li><strong>Clear Browser Data:</strong>
                        <ul>
                            <li>Clear cache, cookies, and browsing history</li>
                            <li>Close and restart your browser</li>
                        </ul>
                    </li>
                    <li><strong>Disable Extensions:</strong>
                        <ul>
                            <li>Turn off ad blockers, privacy extensions</li>
                            <li>Test with extensions disabled</li>
                        </ul>
                    </li>
                    <li><strong>Try Incognito/Private Mode:</strong>
                        <ul>
                            <li>This disables most extensions and uses fresh settings</li>
                        </ul>
                    </li>
                    <li><strong>Update Your Browser:</strong>
                        <ul>
                            <li>Use the latest version for best compatibility</li>
                        </ul>
                    </li>
                    <li><strong>Check System Requirements:</strong>
                        <ul>
                            <li>Ensure JavaScript is enabled</li>
                            <li>Cookies must be allowed</li>
                        </ul>
                    </li>
                </ol>
            </div>

            <div class="solution-box">
                <strong>Still Having Problems?</strong> Contact our technical support team with:
                <ul>
                    <li>Description of the problem</li>
                    <li>Your browser and version</li>
                    <li>Steps you've already tried</li>
                    <li>Any error messages you see</li>
                    <li>Screenshots if helpful</li>
                </ul>
            </div>
        </div>

        <div style="text-align: center; margin-top: 40px;">
            <a href="contact-support.php" style="background: #28a745; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px; margin-right: 10px;">Contact Support</a>
            <a href="index.php" style="background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px;">← Back to Help Center</a>
        </div>
    </div>

    <?php include '../includes/footer.php'; ?>
</body>
</html>
