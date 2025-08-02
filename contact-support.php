<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact & Support - Digital Novels Help</title>
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
        .contact-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin: 20px 0;
        }
        .contact-card {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            border-left: 4px solid #007bff;
            text-align: center;
        }
        .contact-card h4 {
            margin-top: 0;
            color: #007bff;
        }
        .faq-item {
            background: #f8f9fa;
            padding: 15px;
            margin: 15px 0;
            border-radius: 8px;
            border-left: 4px solid #28a745;
        }
        .faq-question {
            font-weight: bold;
            color: #333;
            margin-bottom: 10px;
        }
        .faq-answer {
            color: #666;
        }
        .contact-form {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }
        .form-group textarea {
            height: 100px;
            resize: vertical;
        }
        .submit-btn {
            background: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        .submit-btn:hover {
            background: #0056b3;
        }
        .breadcrumb {
            margin-bottom: 20px;
            color: #666;
        }
        .breadcrumb a {
            color: #007bff;
            text-decoration: none;
        }
        .highlight-box {
            background: #e3f2fd;
            border-left: 4px solid #2196f3;
            padding: 15px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <?php include '../includes/header.php'; ?>
    
    <div class="help-content">
        <div class="breadcrumb">
            <a href="../index.php">Home</a> > <a href="index.php">Help Center</a> > Contact & Support
        </div>
        
        <div class="help-nav">
            <h1>Contact & Support</h1>
            <p>Get help, ask questions, or provide feedback. We're here to assist you with any issues or concerns.</p>
        </div>

        <div class="help-section" id="contact">
            <h2>Contact Information</h2>
            <p>Multiple ways to reach our support team:</p>
            
            <div class="contact-grid">
                <div class="contact-card">
                    <h4>📧 Email Support</h4>
                    <p><strong>support@digitalnovels.com</strong></p>
                    <p>Response time: 24-48 hours</p>
                    <p>Best for detailed questions and technical issues</p>
                </div>
                
                <div class="contact-card">
                    <h4>💬 Live Chat</h4>
                    <p><strong>Available on website</strong></p>
                    <p>Mon-Fri: 9 AM - 6 PM EST</p>
                    <p>Instant help for quick questions</p>
                </div>
                
                <div class="contact-card">
                    <h4>📱 Social Media</h4>
                    <p><strong>@DigitalNovels</strong></p>
                    <p>Twitter, Facebook, Instagram</p>
                    <p>Updates and community support</p>
                </div>
                
                <div class="contact-card">
                    <h4>📞 Phone Support</h4>
                    <p><strong>1-800-NOVELS-1</strong></p>
                    <p>Mon-Fri: 10 AM - 5 PM EST</p>
                    <p>For urgent account issues</p>
                </div>
            </div>
            
            <div class="highlight-box">
                <strong>Tip:</strong> Before contacting support, try checking our <a href="#faq">FAQ section</a> or <a href="troubleshooting.php">troubleshooting guide</a> - you might find a quick solution!
            </div>
        </div>

        <div class="help-section" id="faq">
            <h2>Frequently Asked Questions</h2>
            <p>Quick answers to the most common questions:</p>
            
            <div class="faq-item">
                <div class="faq-question">Q: How do I download my purchased books?</div>
                <div class="faq-answer">A: After purchase, go to your Profile page and view your Order History. Each order will have download links for all purchased books in available formats.</div>
            </div>
            
            <div class="faq-item">
                <div class="faq-question">Q: Can I re-download books I've already purchased?</div>
                <div class="faq-answer">A: Yes! You can re-download your books as many times as needed from your Order History. There are no limits on downloads for purchased content.</div>
            </div>
            
            <div class="faq-item">
                <div class="faq-question">Q: What payment methods do you accept?</div>
                <div class="faq-answer">A: We accept major credit cards (Visa, MasterCard, American Express), debit cards, and PayPal. All payments are processed securely.</div>
            </div>
            
            <div class="faq-item">
                <div class="faq-question">Q: Can I read books on multiple devices?</div>
                <div class="faq-answer">A: Absolutely! Once you download a book, you can transfer it to any compatible device. Our books work on phones, tablets, computers, and e-readers.</div>
            </div>
            
            <div class="faq-item">
                <div class="faq-question">Q: How do I reset my password?</div>
                <div class="faq-answer">A: Currently, password resets are handled by our support team. Contact us with your email address and we'll help you regain access to your account.</div>
            </div>
            
            <div class="faq-item">
                <div class="faq-question">Q: Are there any refunds for digital purchases?</div>
                <div class="faq-answer">A: Due to the instant nature of digital downloads, we have a limited refund policy. Contact support within 24 hours of purchase if you experience technical issues.</div>
            </div>
            
            <div class="faq-item">
                <div class="faq-question">Q: How do I change my account email address?</div>
                <div class="faq-answer">A: For security reasons, email changes must be processed by our support team. Contact us with your current email and the new email you'd like to use.</div>
            </div>
        </div>

        <div class="help-section" id="feedback">
            <h2>Send Feedback</h2>
            <p>We value your input! Help us improve Digital Novels by sharing your thoughts:</p>
            
            <div class="contact-form">
                <form action="#" method="POST">
                    <div class="form-group">
                        <label for="feedback-type">Feedback Type:</label>
                        <select id="feedback-type" name="feedback_type" required>
                            <option value="">Select feedback type...</option>
                            <option value="suggestion">Feature Suggestion</option>
                            <option value="compliment">Compliment</option>
                            <option value="complaint">Complaint</option>
                            <option value="bug-report">Bug Report</option>
                            <option value="general">General Feedback</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="feedback-subject">Subject:</label>
                        <input type="text" id="feedback-subject" name="subject" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="feedback-message">Your Feedback:</label>
                        <textarea id="feedback-message" name="message" placeholder="Please share your thoughts, suggestions, or concerns..." required></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="feedback-email">Your Email (optional):</label>
                        <input type="email" id="feedback-email" name="email" placeholder="If you'd like a response">
                    </div>
                    
                    <button type="submit" class="submit-btn">Send Feedback</button>
                </form>
            </div>
        </div>

        <div class="help-section" id="report">
            <h2>Report Issues</h2>
            <p>Encountered a problem? Report technical issues or bugs:</p>
            
            <div class="contact-form">
                <form action="#" method="POST">
                    <div class="form-group">
                        <label for="issue-type">Issue Type:</label>
                        <select id="issue-type" name="issue_type" required>
                            <option value="">Select issue type...</option>
                            <option value="login-problem">Login Problem</option>
                            <option value="payment-issue">Payment Issue</option>
                            <option value="download-problem">Download Problem</option>
                            <option value="website-bug">Website Bug</option>
                            <option value="account-issue">Account Issue</option>
                            <option value="other">Other Technical Issue</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="issue-description">Issue Description:</label>
                        <textarea id="issue-description" name="description" placeholder="Please describe the problem in detail. Include any error messages you see." required></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="browser-info">Browser & Version:</label>
                        <input type="text" id="browser-info" name="browser" placeholder="e.g., Chrome 120, Safari 17, Firefox 121">
                    </div>
                    
                    <div class="form-group">
                        <label for="steps-tried">Steps Already Tried:</label>
                        <textarea id="steps-tried" name="steps_tried" placeholder="What troubleshooting steps have you already attempted?"></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="contact-email">Your Email:</label>
                        <input type="email" id="contact-email" name="email" required placeholder="We'll use this to follow up on your issue">
                    </div>
                    
                    <button type="submit" class="submit-btn">Report Issue</button>
                </form>
            </div>
            
            <div class="highlight-box">
                <strong>Priority Support:</strong> Technical issues are typically resolved within 24-48 hours. Critical problems affecting payments or account access receive priority attention.
            </div>
        </div>

        <div style="text-align: center; margin-top: 40px;">
            <a href="index.php" style="background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px;">← Back to Help Center</a>
        </div>
    </div>

    <script>
        // Simple form handling (you would implement actual form submission)
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                alert('Thank you for your submission! We will get back to you soon.');
                form.reset();
            });
        });
    </script>

    <?php include '../includes/footer.php'; ?>
</body>
</html>
