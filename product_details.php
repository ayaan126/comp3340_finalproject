<?php
include '../includes/header.php';

// Get product ID from URL
$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!$product_id) {
    header('Location: /DigitalNovels/pages/products.php');
    exit;
}

// Get product details with category
$sql = "SELECT p.*, c.name as category_name 
        FROM products p 
        LEFT JOIN categories c ON p.category_id = c.category_id 
        WHERE p.product_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();

if (!$product) {
    header('Location: /DigitalNovels/pages/products.php');
    exit;
}

// Get related products from the same category
$related_sql = "SELECT * FROM products 
                WHERE category_id = ? AND product_id != ? 
                LIMIT 4";
$stmt = $conn->prepare($related_sql);
$stmt->bind_param("ii", $product['category_id'], $product_id);
$stmt->execute();
$related_products = $stmt->get_result();
?>

<div class="product-details-container">
    <nav class="breadcrumb">
        <a href="/DigitalNovels/pages/products.php">Books</a> &gt;
        <a href="/DigitalNovels/pages/products.php?category=<?php echo $product['category_id']; ?>"><?php echo htmlspecialchars($product['category_name']); ?></a> &gt;
        <span><?php echo htmlspecialchars($product['title']); ?></span>
    </nav>

    <div class="product-details">
        <div class="product-image">
            <img src="/DigitalNovels/assets/images/<?php echo htmlspecialchars($product['image']); ?>" 
                 alt="<?php echo htmlspecialchars($product['title']); ?>"
                 onerror="this.src='/DigitalNovels/assets/images/book-placeholder.svg'">
        </div>

        <div class="product-info">
            <h1><?php echo htmlspecialchars($product['title']); ?></h1>
            <div class="category-tag"><?php echo htmlspecialchars($product['category_name']); ?></div>
            
            <div class="price-section">
                <div class="price">$<?php echo number_format($product['base_price'], 2); ?></div>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <button onclick="addToCart(<?php echo $product['product_id']; ?>)" class="btn-primary">Add to Cart</button>
                <?php else: ?>
                    <a href="/DigitalNovels/user/login.php" class="btn-primary">Login to Purchase</a>
                <?php endif; ?>
            </div>

            <div class="description">
                <h2>Description</h2>
                <p><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
            </div>
        </div>
    </div>

    <?php if ($related_products->num_rows > 0): ?>
    <div class="related-products">
        <h2>Related Books</h2>
        <div class="related-grid">
            <?php while($related = $related_products->fetch_assoc()): ?>
                <div class="related-card">
                    <img src="/DigitalNovels/assets/images/<?php echo htmlspecialchars($related['image']); ?>" 
                         alt="<?php echo htmlspecialchars($related['title']); ?>"
                         onerror="this.src='/DigitalNovels/assets/images/book-placeholder.svg'">
                    <h3><?php echo htmlspecialchars($related['title']); ?></h3>
                    <div class="price">$<?php echo number_format($related['base_price'], 2); ?></div>
                    <a href="product_details.php?id=<?php echo $related['product_id']; ?>" class="btn-secondary">View Details</a>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
    <?php endif; ?>
</div>

<script>
function addToCart(productId) {
    fetch('/DigitalNovels/includes/cart_handler.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `action=add&product_id=${productId}&quantity=1`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const cartCount = document.querySelector('.cart-count');
            if (cartCount) {
                cartCount.textContent = data.cartCount;
            } else {
                const cartLink = document.querySelector('.cart-link');
                if (cartLink) {
                    const span = document.createElement('span');
                    span.className = 'cart-count';
                    span.textContent = data.cartCount;
                    cartLink.appendChild(span);
                }
            }
            alert('Item added to cart successfully');
        } else {
            if (data.message.includes('Please login')) {
                window.location.href = '/DigitalNovels/user/login.php';
            } else {
                alert(data.message || 'Failed to add item to cart');
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while adding the item to cart');
    });
}
</script>

<style>
.product-details-container {
    max-width: 1200px;
    margin: 2rem auto;
    padding: 0 1rem;
}

.breadcrumb {
    margin-bottom: 2rem;
    color: #666;
}

.breadcrumb a {
    color: #007bff;
    text-decoration: none;
}

.breadcrumb a:hover {
    text-decoration: underline;
}

.product-details {
    display: grid;
    grid-template-columns: minmax(300px, 2fr) 3fr;
    gap: 2rem;
    margin-bottom: 3rem;
}

.product-image img {
    width: 100%;
    max-width: 400px;
    height: auto;
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.product-info {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.product-info h1 {
    font-size: 2rem;
    margin: 0;
}

.category-tag {
    display: inline-block;
    padding: 0.5rem 1rem;
    background: #f0f0f0;
    border-radius: 4px;
    font-size: 0.875rem;
    color: #666;
    max-width: fit-content;
}

.price-section {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin: 1rem 0;
}

.price {
    font-size: 1.5rem;
    font-weight: bold;
    color: #2c3e50;
}

.description {
    line-height: 1.6;
}

.description h2 {
    font-size: 1.25rem;
    margin-bottom: 1rem;
}

.related-products {
    margin-top: 3rem;
    padding-top: 2rem;
    border-top: 1px solid #eee;
}

.related-products h2 {
    margin-bottom: 1.5rem;
}

.related-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 1.5rem;
}

.related-card {
    background: white;
    padding: 1rem;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    text-align: center;
    transition: transform 0.2s;
}

.related-card:hover {
    transform: translateY(-5px);
}

.related-card img {
    width: 100%;
    height: 250px;
    object-fit: cover;
    border-radius: 4px;
    margin-bottom: 1rem;
}

.related-card h3 {
    font-size: 1rem;
    margin: 0.5rem 0;
}

.btn-primary {
    background: #007bff;
    color: white;
    border: none;
    padding: 0.75rem 1.5rem;
    border-radius: 4px;
    cursor: pointer;
    font-size: 1rem;
    text-decoration: none;
    display: inline-block;
    transition: background-color 0.2s;
}

.btn-primary:hover {
    background: #0056b3;
}

.btn-secondary {
    background: #f8f9fa;
    color: #333;
    border: 1px solid #ddd;
    padding: 0.75rem 1.5rem;
    border-radius: 4px;
    cursor: pointer;
    font-size: 1rem;
    text-decoration: none;
    display: inline-block;
    transition: background-color 0.2s;
}

.btn-secondary:hover {
    background: #e9ecef;
}

@media (max-width: 768px) {
    .product-details {
        grid-template-columns: 1fr;
    }

    .product-image {
        text-align: center;
    }

    .related-grid {
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
    }
}
</style>

<?php include '../includes/footer.php'; ?>
