<!DOCTYPE html>
<html>
<head>
    <title>Navigation Test</title>
</head>
<body>
    <h2>Navigation Links Test</h2>
    
    <?php
    $links_to_test = [
        'Home' => '/DigitalNovels/index.php',
        'Books' => '/DigitalNovels/pages/products.php',
        'About' => '/DigitalNovels/about.php',
        'Contact' => '/DigitalNovels/pages/contact.php',
        'Login' => '/DigitalNovels/pages/login.php',
        'Profile' => '/DigitalNovels/pages/profile.php',
        'Cart' => '/DigitalNovels/cart/',
        'Logout' => '/DigitalNovels/user/logout.php'
    ];
    
    echo "<table border='1' style='border-collapse: collapse;'>";
    echo "<tr><th>Link</th><th>URL</th><th>Status</th><th>Test</th></tr>";
    
    foreach ($links_to_test as $name => $url) {
        $file_path = $_SERVER['DOCUMENT_ROOT'] . $url;
        
        // Special handling for directories
        if (substr($url, -1) === '/') {
            $file_path .= 'index.php';
        }
        
        $exists = file_exists($file_path);
        $status_color = $exists ? 'green' : 'red';
        $status_text = $exists ? '✅ EXISTS' : '❌ MISSING';
        
        echo "<tr>";
        echo "<td><strong>{$name}</strong></td>";
        echo "<td><code>{$url}</code></td>";
        echo "<td style='color: {$status_color};'>{$status_text}</td>";
        echo "<td><a href='{$url}' target='_blank'>Test Link</a></td>";
        echo "</tr>";
    }
    
    echo "</table>";
    ?>
    
    <h3>Quick Fix Summary</h3>
    <p>✅ Fixed login link: now points to <code>pages/login.php</code></p>
    <p>✅ Fixed profile link: now points to <code>pages/profile.php</code></p>
    <p>✅ All navigation should work correctly now</p>
    
    <p><a href="/DigitalNovels/index.php">← Back to Home</a></p>
    
</body>
</html>
