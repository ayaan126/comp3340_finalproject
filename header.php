<?php
// Start session if not already started for user authentication
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include database connection and theme management
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/ThemeManager.php';

// Initialize theme manager and get current user's theme preference
$themeManager = new ThemeManager($conn);
$currentTheme = $themeManager->getUserTheme();
?>
<!DOCTYPE html>
<html>
<head>
    <!-- Basic HTML5 document setup -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Digital Novels</title>
    
    <!-- Main stylesheet -->
    <link rel="stylesheet" href="/DigitalNovels/assets/css/styles.css">
    
    <!-- Preload theme stylesheet for better performance -->
    <link rel="preload" href="/DigitalNovels/<?php echo $currentTheme; ?>" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="/DigitalNovels/<?php echo $currentTheme; ?>"></noscript>
    
    <!-- Custom styles for header components -->
    <style>
        /* Smooth page loading transition */
        body { opacity: 0; transition: opacity 0.2s ease-in; }
        body.loaded { opacity: 1; }
        
        /* User menu layout and styling */
        .user-menu {
            display: inline-flex;
            align-items: center;
            gap: 1rem;
        }
        
        /* User greeting text styling */
        .user-menu span {
            color: #666;
            font-weight: 500;
        }
        
        /* User menu link styling with hover effects */
        .user-menu a {
            color: #007bff;
            text-decoration: none;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            transition: background-color 0.2s;
            position: relative;
        }
        
        /* User menu link hover state */
        .user-menu a:hover {
            background-color: rgba(0, 123, 255, 0.1);
        }

        /* Shopping cart link styling */
        .cart-link {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .cart-count {
            position: absolute;
            top: -8px;
            right: -8px;
            background: #dc3545;
            color: white;
            font-size: 0.75rem;
            min-width: 18px;
            height: 18px;
            border-radius: 9px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0 6px;
        }

        /* Navigation Layout */
        .nav-container {
            display: grid;
            grid-template-columns: auto 1fr auto auto;
            gap: 2rem;
            align-items: center;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1rem;
        }

        /* Search Bar Styles */
        .search-bar {
            flex: 1;
            max-width: 500px;
            margin: 0 1rem;
        }

        .search-form {
            display: flex;
            background: white;
            border: 2px solid #e9ecef;
            border-radius: 25px;
            overflow: hidden;
            transition: border-color 0.3s, box-shadow 0.3s;
        }

        .search-form:focus-within {
            border-color: #007bff;
            box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.1);
        }

        .search-input {
            flex: 1;
            padding: 0.75rem 1rem;
            border: none;
            outline: none;
            font-size: 1rem;
            background: transparent;
        }

        .search-input::placeholder {
            color: #6c757d;
        }

        .search-btn {
            padding: 0.75rem 1rem;
            background: #007bff;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 1rem;
            transition: background-color 0.3s;
        }

        .search-btn:hover {
            background: #0056b3;
        }

        .nav-actions {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .nav-container {
                grid-template-columns: 1fr;
                gap: 1rem;
                text-align: center;
            }

            .search-bar {
                margin: 0;
                max-width: none;
            }

            .user-menu {
                justify-content: center;
                flex-wrap: wrap;
                gap: 0.5rem;
            }

            .theme-switcher {
                order: -1;
            }
        }
    </style>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Georgia:wght@400;700&family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <script src="/DigitalNovels/assets/js/theme-switcher.js"></script>
    <script>
        // Initialize page once DOM is fully loaded
        document.addEventListener('DOMContentLoaded', function() {
            document.body.classList.add('loaded');
        });
        
        /**
         * Context-sensitive help function
         * Opens appropriate help page based on current user location
         * Provides intelligent routing to relevant help sections
         */
        function showContextHelp() {
            const currentPage = window.location.pathname;
            let helpUrl = '/DigitalNovels/wiki/help-center.html'; // Default help page
            
            // Map current pages to specific help sections for context-sensitive help
            if (currentPage.includes('register') || currentPage.includes('login')) {
                // Account creation and login help
                helpUrl = '/DigitalNovels/wiki/account-management.html';
            } else if (currentPage.includes('cart') || currentPage.includes('checkout') || currentPage.includes('orders')) {
                // Shopping and order management help
                helpUrl = '/DigitalNovels/wiki/shopping-orders.html';
            } else if (currentPage.includes('products') || currentPage.includes('product_details')) {
                // Digital novels and product information help
                helpUrl = '/DigitalNovels/wiki/digital-novels.html';
            } else if (currentPage.includes('profile')) {
                // User profile management help
                helpUrl = '/DigitalNovels/wiki/account-management.html#profile';
            } else if (currentPage.includes('search')) {
                // Search and discovery help
                helpUrl = '/DigitalNovels/wiki/digital-novels.html#about';
            }
            
            // Open help page in new tab to preserve user's current location
            window.open(helpUrl, '_blank');
        }
    </script>
    
    <!-- CSS styles for floating help widget and tooltip -->
    <style>
        /* Floating Help Widget - fixed position help button */
        .floating-help {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 1000;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 50%;
            width: 60px;
            height: 60px;
            font-size: 24px;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(0,123,255,0.3);
            transition: all 0.3s ease;
        }
        
        .floating-help:hover {
            background: #0056b3;
            transform: scale(1.1);
            box-shadow: 0 6px 16px rgba(0,123,255,0.4);
        }
        
        /* Button press animation */
        .floating-help:active {
            transform: scale(0.95);
        }
        
        /* Tooltip for floating help button with positioning and styling */
        .floating-help::before {
            content: 'Need Help?';
            position: absolute;
            bottom: 70px;
            right: 50%;
            transform: translateX(50%);
            background: #333;
            color: white;
            padding: 8px 12px;
            border-radius: 4px;
            font-size: 12px;
            white-space: nowrap;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s ease;
        }
        
        /* Show tooltip on hover */
        .floating-help:hover::before {
            opacity: 1;
        }
        
        /* Responsive design - adjust help button size on small screens */
        @media (max-width: 768px) {
            .floating-help {
                width: 50px;
                height: 50px;
                font-size: 20px;
            }
        }
    </style>
</head>
<body>
    <!-- Main navigation container -->
    <nav>
        <!-- Navigation grid layout container -->
        <!-- Navigation grid layout container -->
        <div class="nav-container">
            <!-- Main navigation links -->
            <div class="nav-links">
                <a href="/DigitalNovels/index.php">Home</a>
                <a href="/DigitalNovels/pages/products.php">Books</a>
                <a href="/DigitalNovels/about.php">About</a>
                <a href="/DigitalNovels/pages/contact.php">Contact</a>
                <!-- Help center link pointing to HTML help system -->
                <a href="/DigitalNovels/wiki/help-center.html">Help</a>
            </div>
            
            <!-- Search Bar for finding books -->
            <div class="search-bar">
                <form action="/DigitalNovels/pages/search.php" method="GET" class="search-form">
                    <input type="text" name="q" placeholder="Search books..." class="search-input">
                    <button type="submit" class="search-btn">🔍</button>
                </form>
            </div>
            
            <!-- User actions and authentication -->
            <div class="nav-actions">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <!-- Logged in user menu -->
                    <div class="user-menu">
                        <span>Welcome, <?php echo htmlspecialchars($_SESSION['user_name'] ?? 'User'); ?></span>
                        <a href="/DigitalNovels/pages/profile.php">Profile</a>
                        <!-- Shopping cart with dynamic item count -->
                        <a href="/DigitalNovels/cart/" class="cart-link">
                            Cart
                            <?php
                            // Include cart manager to display current cart count
                            require_once __DIR__ . '/CartManager.php';
                            $cartManager = new CartManager($conn);
                            $cartCount = $cartManager->getCartCount($_SESSION['user_id']);
                            if ($cartCount > 0): ?>
                                <!-- Display cart item count badge -->
                                <span class="cart-count"><?php echo $cartCount; ?></span>
                            <?php endif; ?>
                        </a>
                        <!-- Logout link for authenticated users -->
                        <a href="/DigitalNovels/user/logout.php">Logout</a>
                    </div>
                <?php else: ?>
                    <!-- Guest user authentication links -->
                    <div class="user-menu">
                        <a href="/DigitalNovels/pages/login.php">Login</a>
                        <a href="/DigitalNovels/pages/register.php">Register</a>
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- Theme switcher dropdown for personalization -->
            <div class="theme-switcher">
                Theme: 
                <select onchange="changeTheme(this.value)">
                    <!-- Theme options with current selection -->
                    <option value="assets/css/themes/regular.css" <?php echo $currentTheme == 'assets/css/themes/regular.css' ? 'selected' : ''; ?>>Regular</option>
                    <option value="assets/css/themes/holiday.css" <?php echo $currentTheme == 'assets/css/themes/holiday.css' ? 'selected' : ''; ?>>Holiday</option>
                    <option value="assets/css/themes/modern.css" <?php echo $currentTheme == 'assets/css/themes/modern.css' ? 'selected' : ''; ?>>Modern</option>
                </select>
            </div>
        </div>
    </nav>
    
    <!-- Floating Help Button - context-sensitive help widget -->
    <button class="floating-help" onclick="showContextHelp()" title="Need Help?">❓</button>