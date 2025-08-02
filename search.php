<?php
require_once '../includes/header.php';

// Get search parameters
$query = trim($_GET['q'] ?? '');
$category_filter = isset($_GET['category']) ? (int)$_GET['category'] : 0;
$price_min = isset($_GET['min_price']) ? (float)$_GET['min_price'] : 0;
$price_max = isset($_GET['max_price']) ? (float)$_GET['max_price'] : 0;
$sort_by = $_GET['sort'] ?? 'title';
$sort_order = $_GET['order'] ?? 'ASC';

// Get all categories for filter dropdown
$categories = $conn->query("SELECT * FROM categories ORDER BY name")->fetch_all(MYSQLI_ASSOC);

// Get price range for slider
$price_range_result = $conn->query("SELECT MIN(base_price) as min_price, MAX(base_price) as max_price FROM products");
$price_range = $price_range_result->fetch_assoc();
$global_min_price = floor($price_range['min_price'] ?? 0);
$global_max_price = ceil($price_range['max_price'] ?? 100);

// If no max price is set, use global max
if ($price_max == 0) $price_max = $global_max_price;

// Build search query
$where_conditions = [];
$params = [];
$param_types = '';

// Text search with improved precision
if (!empty($query)) {
    // For short queries (like "AI"), use word boundary matching to avoid partial matches
    if (strlen($query) <= 3) {
        $where_conditions[] = "(
            p.title = ? OR
            p.title LIKE ? OR
            p.title LIKE ? OR
            p.title LIKE ? OR
            p.description LIKE ? OR
            p.description LIKE ? OR
            p.description LIKE ?
        )";
        // Exact match
        $params[] = $query;
        // Word at start
        $params[] = $query . " %";
        // Word at end
        $params[] = "% " . $query;
        // Word in middle
        $params[] = "% " . $query . " %";
        // Same patterns for description
        $params[] = "% " . $query . " %";
        $params[] = $query . " %";
        $params[] = "% " . $query;
        $param_types .= 'sssssss';
    } else {
        // For longer queries, use the standard LIKE search
        $where_conditions[] = "(p.title LIKE ? OR p.description LIKE ?)";
        $search_term = "%{$query}%";
        $params[] = $search_term;
        $params[] = $search_term;
        $param_types .= 'ss';
    }
}

// Category filter
if ($category_filter > 0) {
    $where_conditions[] = "p.category_id = ?";
    $params[] = $category_filter;
    $param_types .= 'i';
}

// Price range filter
if ($price_min > 0) {
    $where_conditions[] = "p.base_price >= ?";
    $params[] = $price_min;
    $param_types .= 'd';
}

if ($price_max > 0 && $price_max < $global_max_price) {
    $where_conditions[] = "p.base_price <= ?";
    $params[] = $price_max;
    $param_types .= 'd';
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
$search_results = $stmt->get_result();
$total_results = $search_results->num_rows;
?>

<div class="search-container">
    <div class="search-header">
        <h1>
            <?php if (!empty($query)): ?>
                Search Results for "<?php echo htmlspecialchars($query); ?>"
            <?php else: ?>
                Browse All Products
            <?php endif; ?>
        </h1>
        <p><?php echo $total_results; ?> product(s) found</p>
    </div>

    <div class="search-layout">
        <!-- Advanced Search & Filter Sidebar -->
        <aside class="search-sidebar">
            <div class="search-filters">
                <div class="filter-header">
                    <h3>🔍 Search & Filter</h3>
                    <small>Refine your search results</small>
                </div>
                
                <form method="GET" class="search-form">
                    <!-- Text Search -->
                    <div class="filter-section">
                        <div class="filter-group">
                            <label for="q">🔎 Search Products</label>
                            <input type="text" id="q" name="q" placeholder="Enter title, author, or keywords..." 
                                   value="<?php echo htmlspecialchars($query); ?>" class="search-input">
                        </div>
                    </div>

                    <!-- Filters Section -->
                    <div class="filter-section">
                        <h4>📚 Filters</h4>
                        
                        <!-- Category Filter -->
                        <div class="filter-group">
                            <label for="category">Category</label>
                            <select id="category" name="category" class="filter-select">
                                <option value="0">All Categories</option>
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?php echo $category['category_id']; ?>" 
                                            <?php echo $category_filter == $category['category_id'] ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($category['name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Price Range Filter -->
                        <div class="filter-group">
                            <label>💰 Price Range</label>
                            <div class="price-range">
                                <div class="price-inputs">
                                    <input type="number" name="min_price" placeholder="Min $" step="0.01" min="0" 
                                           value="<?php echo $price_min > 0 ? $price_min : ''; ?>" class="price-input">
                                    <span class="price-separator">to</span>
                                    <input type="number" name="max_price" placeholder="Max $" step="0.01" min="0" 
                                           value="<?php echo $price_max < $global_max_price ? $price_max : ''; ?>" class="price-input">
                                </div>
                                <div class="price-range-display">
                                    Available: $<?php echo $global_min_price; ?> - $<?php echo $global_max_price; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sorting Section -->
                    <div class="filter-section">
                        <h4>📊 Sort Results</h4>
                        
                        <div class="filter-group">
                            <label for="sort">Sort By</label>
                            <div class="sort-controls">
                                <select id="sort" name="sort" class="filter-select">
                                    <option value="title" <?php echo $sort_by == 'title' ? 'selected' : ''; ?>>📖 Title</option>
                                    <option value="base_price" <?php echo $sort_by == 'base_price' ? 'selected' : ''; ?>>💵 Price</option>
                                    <option value="created_at" <?php echo $sort_by == 'created_at' ? 'selected' : ''; ?>>🆕 Date Added</option>
                                </select>
                                <select name="order" class="filter-select">
                                    <option value="ASC" <?php echo $sort_order == 'ASC' ? 'selected' : ''; ?>>
                                        <?php echo $sort_by == 'base_price' ? '↗️ Low to High' : '↗️ A to Z'; ?>
                                    </option>
                                    <option value="DESC" <?php echo $sort_order == 'DESC' ? 'selected' : ''; ?>>
                                        <?php echo $sort_by == 'base_price' ? '↘️ High to Low' : '↘️ Z to A'; ?>
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="filter-actions">
                        <button type="submit" class="btn-apply">✨ Apply Filters</button>
                        <a href="/DigitalNovels/pages/search.php" class="btn-clear">🔄 Clear All</a>
                    </div>
                </form>
            </div>
        </aside>

        <!-- Search Results -->
        <main class="search-results">
            <?php if ($total_results > 0): ?>
                <div class="results-grid">
                    <?php while ($product = $search_results->fetch_assoc()): ?>
                        <div class="product-card">
                            <div class="product-image">
                                <img src="/DigitalNovels/assets/images/<?php echo htmlspecialchars($product['image']); ?>" 
                                     alt="<?php echo htmlspecialchars($product['title']); ?>"
                                     onerror="this.src='/DigitalNovels/assets/images/book-placeholder.svg'">
                            </div>
                            <div class="product-info">
                                <h3><?php echo htmlspecialchars($product['title']); ?></h3>
                                <span class="category-tag"><?php echo htmlspecialchars($product['category_name']); ?></span>
                                <p class="description"><?php echo substr(htmlspecialchars($product['description']), 0, 120); ?>...</p>
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
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <div class="no-results">
                    <h3>No products found</h3>
                    <p>Try adjusting your search criteria or browse all products.</p>
                    <a href="/DigitalNovels/pages/products.php" class="btn-primary">Browse All Products</a>
                </div>
            <?php endif; ?>
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
            // Update cart count in header if it exists
            const cartCount = document.querySelector('.cart-count');
            if (cartCount) {
                cartCount.textContent = data.cartCount;
            } else if (data.cartCount > 0) {
                // Create cart count if it doesn't exist
                const cartLink = document.querySelector('.cart-link');
                if (cartLink) {
                    const span = document.createElement('span');
                    span.className = 'cart-count';
                    span.textContent = data.cartCount;
                    cartLink.appendChild(span);
                }
            }
            
            // Show success message
            alert('Item added to cart successfully!');
        } else {
            if (data.message && data.message.includes('Please login')) {
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

// Auto-submit form when sort options change
document.getElementById('sort').addEventListener('change', function() {
    this.form.submit();
});

document.querySelector('select[name="order"]').addEventListener('change', function() {
    this.form.submit();
});
</script>

<style>
.search-container {
    max-width: 1400px;
    margin: 2rem auto;
    padding: 0 1rem;
}

.search-header {
    text-align: center;
    margin-bottom: 2rem;
}

.search-header h1 {
    margin: 0 0 0.5rem 0;
    color: #333;
    font-size: 2.2rem;
}

.search-header p {
    color: #666;
    margin: 0;
    font-size: 1.1rem;
}

.search-layout {
    display: grid;
    grid-template-columns: 320px 1fr;
    gap: 2rem;
}

/* Enhanced Sidebar Styling */
.search-sidebar {
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    height: fit-content;
    position: sticky;
    top: 2rem;
    overflow: hidden;
}

.search-filters {
    padding: 0;
}

.filter-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 1.5rem;
    text-align: center;
}

.filter-header h3 {
    margin: 0 0 0.5rem 0;
    font-size: 1.3rem;
    font-weight: 600;
}

.filter-header small {
    opacity: 0.9;
    font-size: 0.9rem;
}

.search-form {
    padding: 1.5rem;
}

/* Filter Sections */
.filter-section {
    margin-bottom: 2rem;
    padding-bottom: 1.5rem;
    border-bottom: 1px solid #f0f0f0;
}

.filter-section:last-of-type {
    border-bottom: none;
    margin-bottom: 1rem;
}

.filter-section h4 {
    margin: 0 0 1rem 0;
    color: #333;
    font-size: 1rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.filter-group {
    margin-bottom: 1rem;
}

.filter-group:last-child {
    margin-bottom: 0;
}

.filter-group label {
    display: block;
    margin-bottom: 0.5rem;
    color: #555;
    font-weight: 500;
    font-size: 0.9rem;
}

/* Input Styling */
.search-input,
.filter-select,
.price-input {
    width: 100%;
    padding: 0.75rem;
    border: 2px solid #e9ecef;
    border-radius: 8px;
    font-size: 0.9rem;
    transition: all 0.3s ease;
    background: #fafbfc;
}

.search-input:focus,
.filter-select:focus,
.price-input:focus {
    outline: none;
    border-color: #667eea;
    background: white;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.search-input {
    font-size: 1rem;
    padding: 1rem;
}

/* Price Range Styling */
.price-range {
    background: #f8f9fa;
    padding: 1rem;
    border-radius: 8px;
    border: 1px solid #e9ecef;
}

.price-inputs {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin-bottom: 0.75rem;
}

.price-input {
    flex: 1;
    margin: 0;
}

.price-separator {
    color: #666;
    font-weight: 500;
    font-size: 0.9rem;
}

.price-range-display {
    text-align: center;
    color: #666;
    font-size: 0.8rem;
    padding: 0.5rem;
    background: white;
    border-radius: 4px;
    border: 1px solid #e9ecef;
}

/* Sort Controls */
.sort-controls {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

/* Action Buttons */
.filter-actions {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
    margin-top: 1.5rem;
    padding-top: 1.5rem;
    border-top: 2px solid #f0f0f0;
}

.btn-apply {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
    padding: 1rem;
    border-radius: 8px;
    font-weight: 600;
    font-size: 1rem;
    cursor: pointer;
    transition: all 0.3s ease;
    text-align: center;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
}

.btn-apply:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
}

.btn-clear {
    background: white;
    color: #666;
    border: 2px solid #e9ecef;
    padding: 0.75rem;
    border-radius: 8px;
    text-decoration: none;
    text-align: center;
    font-weight: 500;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
}

.btn-clear:hover {
    border-color: #dc3545;
    color: #dc3545;
    background: #fff5f5;
}

/* Search Results Area */
.search-results {
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 12px rgba(0,0,0,0.05);
    padding: 2rem;
    min-height: 400px;
}

.results-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 1.5rem;
}

.product-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 12px rgba(0,0,0,0.08);
    overflow: hidden;
    transition: all 0.3s ease;
    border: 1px solid #f0f0f0;
}

.product-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 30px rgba(0,0,0,0.12);
    border-color: #667eea;
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
    transition: transform 0.3s ease;
}

.product-card:hover .product-image img {
    transform: scale(1.05);
}

.product-info {
    padding: 1.5rem;
}

.product-info h3 {
    margin: 0 0 0.75rem 0;
    font-size: 1.2rem;
    color: #333;
    line-height: 1.3;
    font-weight: 600;
}

.category-tag {
    display: inline-block;
    padding: 0.4rem 0.8rem;
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 500;
    margin-bottom: 1rem;
}

.description {
    color: #666;
    font-size: 0.9rem;
    line-height: 1.5;
    margin-bottom: 1.5rem;
}

.price-info {
    margin-bottom: 1.5rem;
}

.price {
    font-size: 1.4rem;
    color: #2c3e50;
    font-weight: bold;
}

.product-actions {
    display: flex;
    gap: 0.75rem;
}

.product-actions .btn-secondary,
.product-actions .btn-primary {
    flex: 1;
    text-align: center;
    padding: 0.875rem;
    border-radius: 8px;
    text-decoration: none;
    font-size: 0.9rem;
    font-weight: 600;
    border: none;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
}

.btn-secondary {
    background: #f8f9fa;
    color: #495057;
    border: 2px solid #e9ecef;
}

.btn-secondary:hover {
    background: #e9ecef;
    border-color: #dee2e6;
}

.no-results {
    text-align: center;
    padding: 4rem 2rem;
    color: #666;
}

.no-results h3 {
    margin: 0 0 1rem 0;
    color: #333;
    font-size: 1.5rem;
}

.no-results p {
    margin: 0 0 2rem 0;
    font-size: 1.1rem;
}

.no-results a {
    color: #667eea;
    text-decoration: none;
    font-weight: 600;
}

.no-results a:hover {
    text-decoration: underline;
}

/* Responsive Design */
@media (max-width: 968px) {
    .search-layout {
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }
    
    .search-sidebar {
        position: static;
        order: 2;
    }
    
    .results-grid {
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 1rem;
    }
}

@media (max-width: 576px) {
    .search-container {
        padding: 0 0.5rem;
        margin: 1rem auto;
    }
    
    .search-header h1 {
        font-size: 1.8rem;
    }
    
    .filter-actions {
        flex-direction: column;
    }
    
    .results-grid {
        grid-template-columns: 1fr;
    }
    
    .product-actions {
        flex-direction: column;
    }
}
</style>

<?php include '../includes/footer.php'; ?>
