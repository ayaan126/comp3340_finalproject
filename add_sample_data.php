<?php
require_once '../config/db.php';

// Insert categories if they don't exist
$categories = [
    ['name' => 'Fiction'],
    ['name' => 'Non-Fiction'],
    ['name' => 'Science Fiction'],
    ['name' => 'Mystery']
];

foreach ($categories as $category) {
    $stmt = $conn->prepare("INSERT IGNORE INTO categories (name) VALUES (?)");
    $stmt->bind_param("s", $category['name']);
    $stmt->execute();
}

// Sample products
$products = [
    [
        'title' => 'The Lost World',
        'description' => 'A thrilling adventure novel about a hidden plateau in South America where prehistoric animals still survive.',
        'base_price' => 19.99,
        'category_id' => 1,
        'image' => 'book-placeholder.svg'
    ],
    [
        'title' => 'Mind and Universe',
        'description' => 'An exploration of consciousness and our place in the cosmos.',
        'base_price' => 24.99,
        'category_id' => 2,
        'image' => 'book-placeholder.svg'
    ],
    [
        'title' => 'Stellar Dreams',
        'description' => 'A science fiction epic about humanity\'s first interstellar colony.',
        'base_price' => 29.99,
        'category_id' => 3,
        'image' => 'book-placeholder.svg'
    ],
    [
        'title' => 'The Silent Witness',
        'description' => 'A gripping mystery thriller set in a small coastal town.',
        'base_price' => 22.99,
        'category_id' => 4,
        'image' => 'book-placeholder.svg'
    ]
];

// Insert products
foreach ($products as $product) {
    $stmt = $conn->prepare("INSERT IGNORE INTO products (title, description, base_price, category_id, image) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssdis", 
        $product['title'],
        $product['description'],
        $product['base_price'],
        $product['category_id'],
        $product['image']
    );
    $stmt->execute();
}

echo "Sample data added successfully!";
?>
