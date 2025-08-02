<?php
include 'includes/header.php';
include 'config/db.php';
?>

<div class="hero-section">
    <div class="hero-content">
        <h1>Welcome to Digital Novels</h1>
        <p>Your one-stop online bookstore for eBooks, audiobooks, and printed novels.</p>
        <a class="btn-primary" href="pages/products.php">Browse Our Collection</a>
    </div>
</div>

<section class="featured-products">
    <h2>Featured Books</h2>
    <div class="product-grid">

    <?php
    // Fetch 4 random products for the homepage
    $sql = "SELECT * FROM products ORDER BY RAND() LIMIT 4";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<div class='product-card'>";
            echo "<img src='assets/images/{$row['image']}' alt='{$row['title']}' width='150'>";
            echo "<h3>{$row['title']}</h3>";
            echo "<p>" . substr($row['description'], 0, 60) . "...</p>";
            echo "<strong>$ {$row['base_price']}</strong><br>";
            echo "<a class='btn-secondary' href='pages/product_details.php?id={$row['product_id']}'>View Details</a>";
            echo "</div>";
        }
    } else {
        echo "<p>No featured products found.</p>";
    }
    ?>

    </div>
</section>

<section class="about-preview">
    <h2>About Us</h2>
    <p>
        Digital Novels is your ultimate online bookstore offering a wide selection 
        of books in multiple formats. We focus on accessibility, affordability, 
        and convenience for all readers worldwide.
    </p>
    <a class="btn-primary" href="about.php">Learn More</a>
</section>

<?php
include 'includes/footer.php';
?>
