<?php
require_once '../config/db.php';

// Update all existing products to use the placeholder image
$update_sql = "UPDATE products SET image = 'book-placeholder.svg'";
$result = $conn->query($update_sql);

if ($result) {
    echo "All product images updated to use placeholder successfully!<br>";
    
    // Show current products
    $select_sql = "SELECT product_id, title, image FROM products";
    $products = $conn->query($select_sql);
    
    echo "<h3>Current Products:</h3>";
    echo "<ul>";
    while ($product = $products->fetch_assoc()) {
        echo "<li>ID: {$product['product_id']} - {$product['title']} - Image: {$product['image']}</li>";
    }
    echo "</ul>";
} else {
    echo "Error updating products: " . $conn->error;
}
?>
