<?php
// Cleanup Script for DigitalNovels Duplicates
echo "<h2>Cleaning Up Duplicate Files</h2>";

$files_to_remove = [
    // Duplicate login files (keep pages/login.php)
    'user/login.php',
    'login.php',
    
    // Duplicate register files (keep pages/register.php)  
    'user/register.php',
    
    // Duplicate profile files (keep pages/profile.php)
    'user/profile.php',
    
    // Duplicate CSS files (keep assets/css/style.css)
    'style.css',
    
    // Backup files
    'pages/products_backup.php',
    'pages/products_clean.php',
    
    // Debug and temporary files
    'debug_ai_image.php',
    'debug_cart.php',
    'debug_categories.php',
    'debug_products.php',
    'debug_search.php',
    'fix_ai_image.php',
    'fix_ai_step_by_step.php',
    'image_updater.php',
    'current_state.php',
    'reset_to_working.php',
    'update_ai_image.php',
    'update_ancient_image.php',
    'update_checkout_db.php',
    'test_products.php',
    'create_test_user.php',
    'check_products.php',
    'check_admin_users.php',
    'check_users_table.php',
    'cleanup_categories.php',
    'pages/debug_complete.php',
    'pages/test_image.php',
    
    // This cleanup script itself
    'scan_duplicates.php'
];

$removed_count = 0;
$failed_count = 0;

foreach ($files_to_remove as $file) {
    $full_path = __DIR__ . DIRECTORY_SEPARATOR . $file;
    
    if (file_exists($full_path)) {
        if (unlink($full_path)) {
            echo "<p style='color: green;'>✅ Removed: {$file}</p>";
            $removed_count++;
        } else {
            echo "<p style='color: red;'>❌ Failed to remove: {$file}</p>";
            $failed_count++;
        }
    } else {
        echo "<p style='color: gray;'>⚪ Already gone: {$file}</p>";
    }
}

echo "<hr>";
echo "<h3>Cleanup Summary</h3>";
echo "<p><strong>Files removed:</strong> {$removed_count}</p>";
echo "<p><strong>Failed removals:</strong> {$failed_count}</p>";

if ($removed_count > 0) {
    echo "<p style='color: green;'>✅ Cleanup completed successfully!</p>";
    echo "<p>Your DigitalNovels project is now cleaner with no duplicate files.</p>";
}

// Check if user folder is now empty and can be removed
$user_folder = __DIR__ . DIRECTORY_SEPARATOR . 'user';
if (is_dir($user_folder)) {
    $user_files = array_diff(scandir($user_folder), ['.', '..']);
    if (empty($user_files)) {
        if (rmdir($user_folder)) {
            echo "<p style='color: green;'>✅ Removed empty user/ folder</p>";
        }
    } else {
        echo "<p style='color: orange;'>⚠️ user/ folder still contains files:</p>";
        foreach ($user_files as $file) {
            echo "<p>• user/{$file}</p>";
        }
    }
}

echo "<h3>Remaining File Structure</h3>";
echo "<p>Your clean project now has:</p>";
echo "<ul>";
echo "<li><strong>pages/</strong> - All user-facing pages (login, register, products, etc.)</li>";
echo "<li><strong>includes/</strong> - Backend PHP classes and handlers</li>";
echo "<li><strong>assets/</strong> - CSS, JS, and images</li>";
echo "<li><strong>config/</strong> - Database configuration</li>";
echo "<li><strong>admin/</strong> - Admin panel (if needed)</li>";
echo "<li><strong>cart/</strong> - Shopping cart functionality</li>";
echo "</ul>";

?>

<style>
body { font-family: Arial, sans-serif; margin: 20px; }
</style>
