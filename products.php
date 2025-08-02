<?php
include '../includes/header.php';

// Get all categories for filter (using DISTINCT to avoid duplicates)
$cat_sql = "SELECT DISTINCT category_id, name FROM categories ORDER BY name";
$categories = $conn->query($cat_sql);

// Get initial category filter if set
$category_filter = isset($_GET['category']) ? (int)$_GET['category'] : 0;
$sort_by = $_GET['sort'] ?? 'title';
$sort_order = $_GET['order'] ?? 'ASC';

// Build WHERE conditions
$where_conditions = [];
$params = [];
$param_types = '';

// Category filter
if ($category_filter > 0) {
    $where_conditions[] = "p.category_id = ?";
    $params[] = $category_filter;
    $param_types .= 'i';
}

// Build the complete query
$sql = "SELECT p.*, c.name as category_name 
        FROM products p 
        LEFT JOIN categories c ON p.category_id = c.category_id";

if (!empty($where_conditions)) {
    $sql .= " WHERE " . implode(" AND ", $where_conditions);
}

// Add sorting
$allowed_sorts = ['title', 'base_price', 'created_at'];
$allowed_orders = ['ASC', 'DESC'];

if (in_array($sort_by, $allowed_sorts) && in_array($sort_order, $allowed_orders)) {
    $sql .= " ORDER BY p.{$sort_by} {$sort_order}";
} else {
    $sql .= " ORDER BY p.title ASC";
}

// Execute query
$stmt = $conn->prepare($sql);
if (!empty($params)) {
    $stmt->bind_param($param_types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
$products = $result->fetch_all(MYSQLI_ASSOC);
?>

<div class="products-container">
    <div class="products-header">
        <h1>Our Book Collection <a href="javascript:showContextHelp()" style="font-size: 14px; color: #007bff; text-decoration: none; margin-left: 10px;">❓ Need Help?</a></h1>
        <p>Discover your next great read from our curated selection</p>
    </div>

    <div class="products-layout">
        <aside class="filters-sidebar">
            <div class="filters">
                <h3>Filter & Sort</h3>
                
                <form method="GET" class="filter-form">
                    <!-- Category Filter -->
                    <div class="filter-section">
                        <h4>Categories</h4>
                        <div class="category-list">
                            <a href="?category=0&sort=<?php echo $sort_by; ?>&order=<?php echo $sort_order; ?>" 
                               class="category-link <?php echo $category_filter == 0 ? 'active' : ''; ?>">
                                All Categories
                            </a>
                            <?php 
                            // Reset categories result to loop through them again
                            $categories_result = $conn->query($cat_sql);
                            while($cat = $categories_result->fetch_assoc()) { ?>
                                <a href="?category=<?php echo $cat['category_id']; ?>&sort=<?php echo $sort_by; ?>&order=<?php echo $sort_order; ?>" 
                                   class="category-link <?php echo $category_filter == $cat['category_id'] ? 'active' : ''; ?>">
                                   <?php echo htmlspecialchars($cat['name']); ?>
                                </a>
                            <?php } ?>
                        </div>
                    </div>

                    <!-- Sort Options -->
                    <div class="filter-section">
                        <h4>Sort By</h4>
                        <select name="sort" onchange="this.form.submit()">
                            <option value="title" <?php echo $sort_by == 'title' ? 'selected' : ''; ?>>Title</option>
                            <option value="base_price" <?php echo $sort_by == 'base_price' ? 'selected' : ''; ?>>Price</option>
                            <option value="created_at" <?php echo $sort_by == 'created_at' ? 'selected' : ''; ?>>Newest</option>
                        </select>
                        <select name="order" onchange="this.form.submit()">
                            <option value="ASC" <?php echo $sort_order == 'ASC' ? 'selected' : ''; ?>>
                                <?php echo $sort_by == 'base_price' ? 'Low to High' : 'A to Z'; ?>
                            </option>
                            <option value="DESC" <?php echo $sort_order == 'DESC' ? 'selected' : ''; ?>>
                                <?php echo $sort_by == 'base_price' ? 'High to Low' : 'Z to A'; ?>
                            </option>
                        </select>
                        <input type="hidden" name="category" value="<?php echo $category_filter; ?>">
                    </div>

                    <!-- Quick Actions -->
                    <div class="filter-section">
                        <h4>Quick Actions</h4>
                        <div class="quick-actions">
                            <a href="/DigitalNovels/pages/search.php" class="btn-secondary">Advanced Search</a>
                            <a href="/DigitalNovels/pages/products.php" class="btn-outline">Clear Filters</a>
                        </div>
                    </div>
                </form>
            </div>
        </aside>

        <main class="products-main">
            <div class="products-controls">
                <div class="results-info">
                    <span><?php echo count($products); ?> product<?php echo count($products) === 1 ? '' : 's'; ?> found</span>
                </div>
            </div>

            <div class="products-grid">
                <?php if (empty($products)): ?>
                    <div class="no-products">
                        <h3>No products found</h3>
                        <p>Try selecting a different category or <a href="/DigitalNovels/pages/search.php">use advanced search</a>.</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($products as $product): ?>
                        <div class="product-card">
                            <div class="product-image">
                                <img src="/DigitalNovels/assets/images/<?php echo htmlspecialchars($product['image']); ?>" 
                                     alt="<?php echo htmlspecialchars($product['title']); ?>"
                                     onerror="this.src='/DigitalNovels/assets/images/book-placeholder.svg'">
                            </div>
                            <div class="product-info">
                                <h3><?php echo htmlspecialchars($product['title']); ?></h3>
                                <span class="category-tag"><?php echo htmlspecialchars($product['category_name'] ?? 'Uncategorized'); ?></span>
                                <p class="description"><?php echo $product['description'] ? substr(htmlspecialchars($product['description']), 0, 120) . '...' : 'No description available.'; ?></p>
                                <div class="price-info">
                                    <strong class="price">$<?php echo number_format($product['base_price'], 2); ?></strong>
                                </div>
                                <div class="product-actions">
                                    <a href="/DigitalNovels/pages/product_details.php?id=<?php echo $product['product_id']; ?>" class="btn-secondary">View Details</a>
                                    <?php if (isset($_SESSION['user_id'])): ?>
                                        <button onclick="addToCart(<?php echo $product['product_id']; ?>)" class="btn-primary">Add to Cart</button>
                                    <?php else: ?>
                                        <a href="/DigitalNovels/user/login.php" class="btn-primary">Login to Buy</a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </main>
    </div>
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
            // Update cart count in header
            const cartCount = document.querySelector('.cart-count');
            if (cartCount) {
                cartCount.textContent = data.cartCount;
            } else if (data.cartCount > 0) {
                const cartLink = document.querySelector('.cart-link');
                if (cartLink) {
                    const span = document.createElement('span');
                    span.className = 'cart-count';
                    span.textContent = data.cartCount;
                    cartLink.appendChild(span);
                }
            }
            
            // Show success message
            showNotification('Item added to cart successfully!', 'success');
        } else {
            if (data.message && data.message.includes('Please login')) {
                window.location.href = '/DigitalNovels/user/login.php';
            } else {
                showNotification(data.message || 'Failed to add item to cart', 'error');
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('An error occurred while adding the item to cart', 'error');
    });
}

function showNotification(message, type) {
    // Remove existing notifications
    const existingNotifications = document.querySelectorAll('.notification');
    existingNotifications.forEach(n => n.remove());

    // Create notification
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.innerHTML = `
        <span>${message}</span>
        <button onclick="this.parentElement.remove()" class="notification-close">&times;</button>
    `;

    // Add to page
    document.body.appendChild(notification);

    // Auto remove after 3 seconds
    setTimeout(() => {
        if (notification.parentElement) {
            notification.remove();
        }
    }, 3000);
}
</script>

<style>
.products-container {
    max-width: 1400px;
    margin: 2rem auto;
    padding: 0 1rem;
}

.products-header {
    text-align: center;
    margin-bottom: 2rem;
}

.products-header h1 {
    margin: 0 0 0.5rem 0;
    color: #333;
    font-size: 2.5rem;
}

.products-header p {
    color: #666;
    font-size: 1.1rem;
    margin: 0;
}

.products-layout {
    display: grid;
    grid-template-columns: 280px 1fr;
    gap: 2rem;
}

.filters-sidebar {
    background: white;
    padding: 1.5rem;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    height: fit-content;
    position: sticky;
    top: 2rem;
}

.filters h3 {
    margin: 0 0 1.5rem 0;
    color: #333;
    padding-bottom: 0.5rem;
    border-bottom: 2px solid #f0f0f0;
}

.filter-section {
    margin-bottom: 2rem;
}

.filter-section:last-child {
    margin-bottom: 0;
}

.filter-section h4 {
    margin: 0 0 1rem 0;
    color: #333;
    font-size: 1rem;
}

.category-list {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.category-link {
    padding: 0.75rem 1rem;
    text-decoration: none;
    color: #666;
    border-radius: 6px;
    transition: all 0.3s;
    border: 1px solid transparent;
}

.category-link:hover,
.category-link.active {
    background: #007bff;
    color: white;
    transform: translateX(5px);
}

.filter-section select {
    width: 100%;
    padding: 0.75rem;
    border: 1px solid #ddd;
    border-radius: 4px;
    background: white;
    color: #333;
    margin-bottom: 0.5rem;
}

.quick-actions {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.products-main {
    min-height: 400px;
}

.products-controls {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
    padding: 1rem;
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.results-info {
    font-weight: 500;
    color: #333;
}

.products-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 1.5rem;
}

.product-card {
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    overflow: hidden;
    transition: transform 0.3s, box-shadow 0.3s;
}

.product-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

.product-image {
    height: 200px;
    overflow: hidden;
    position: relative;
}

.product-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s;
}

.product-card:hover .product-image img {
    transform: scale(1.05);
}

.product-info {
    padding: 1.5rem;
}

.product-info h3 {
    margin: 0 0 0.5rem 0;
    font-size: 1.1rem;
    color: #333;
    line-height: 1.3;
}

.category-tag {
    display: inline-block;
    padding: 0.25rem 0.75rem;
    background: #f0f0f0;
    border-radius: 12px;
    font-size: 0.8rem;
    color: #666;
    margin-bottom: 0.75rem;
}

.description {
    color: #666;
    font-size: 0.9rem;
    line-height: 1.4;
    margin-bottom: 1rem;
}

.price-info {
    margin-bottom: 1rem;
}

.price {
    font-size: 1.2rem;
    color: #2c3e50;
    font-weight: bold;
}

.product-actions {
    display: flex;
    gap: 0.5rem;
}

.product-actions .btn-secondary,
.product-actions .btn-primary {
    flex: 1;
    text-align: center;
    padding: 0.75rem;
    border-radius: 4px;
    text-decoration: none;
    font-size: 0.9rem;
    font-weight: 500;
    border: none;
    cursor: pointer;
    transition: all 0.3s;
}

.btn-primary {
    background: #007bff;
    color: white;
}

.btn-primary:hover {
    background: #0056b3;
}

.btn-secondary {
    background: #6c757d;
    color: white;
}

.btn-secondary:hover {
    background: #545b62;
}

.btn-outline {
    background: white;
    color: #666;
    border: 1px solid #ddd;
    padding: 0.75rem 1rem;
    border-radius: 4px;
    text-decoration: none;
    cursor: pointer;
    transition: all 0.3s;
    width: 100%;
}

.btn-outline:hover {
    border-color: #007bff;
    color: #007bff;
}

.no-products {
    text-align: center;
    padding: 3rem;
    grid-column: 1 / -1;
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.no-products h3 {
    margin: 0 0 1rem 0;
    color: #333;
}

.no-products p {
    color: #666;
    margin: 0;
}

/* Notification Styles */
.notification {
    position: fixed;
    top: 20px;
    right: 20px;
    padding: 15px 20px;
    border-radius: 4px;
    color: white;
    font-weight: 500;
    z-index: 1000;
    display: flex;
    align-items: center;
    gap: 15px;
    min-width: 300px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    animation: slideIn 0.3s ease;
}

.notification-success {
    background: #28a745;
}

.notification-error {
    background: #dc3545;
}

.notification-close {
    background: none;
    border: none;
    color: white;
    font-size: 20px;
    cursor: pointer;
    padding: 0;
    line-height: 1;
}

@keyframes slideIn {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

/* Responsive Design */
@media (max-width: 768px) {
    .products-layout {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
    
    .filters-sidebar {
        position: static;
        order: 2;
    }
    
    .products-grid {
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 1rem;
    }
    
    .products-controls {
        flex-direction: column;
        gap: 1rem;
        text-align: center;
    }
    
    .notification {
        right: 10px;
        left: 10px;
        min-width: auto;
    }
}
</style>

<?php include '../includes/footer.php'; ?>
