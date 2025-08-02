<?php
require_once __DIR__ . '/../config/db.php';

// Get parameters
$category_filter = isset($_GET['category']) ? (int)$_GET['category'] : 0;
$sort_by = $_GET['sort'] ?? 'title';
$sort_order = $_GET['order'] ?? 'ASC';
$search_query = trim($_GET['q'] ?? '');

// Build WHERE conditions
$where_conditions = [];
$params = [];
$param_types = '';

// Search functionality with improved precision
if (!empty($search_query)) {
    if (strlen($search_query) <= 3) {
        // For short queries, use word boundary matching
        $where_conditions[] = "(
            p.title = ? OR
            p.title LIKE ? OR
            p.title LIKE ? OR
            p.title LIKE ? OR
            p.description LIKE ? OR
            p.description LIKE ? OR
            p.description LIKE ?
        )";
        $params[] = $search_query;                    // Exact match
        $params[] = $search_query . " %";             // Word at start
        $params[] = "% " . $search_query;             // Word at end  
        $params[] = "% " . $search_query . " %";      // Word in middle
        $params[] = "% " . $search_query . " %";      // Description patterns
        $params[] = $search_query . " %";
        $params[] = "% " . $search_query;
        $param_types .= 'sssssss';
    } else {
        // For longer queries, use standard LIKE search
        $where_conditions[] = "(p.title LIKE ? OR p.description LIKE ?)";
        $search_term = "%{$search_query}%";
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

$products = [];
while ($row = $result->fetch_assoc()) {
    $products[] = $row;
}

header('Content-Type: application/json');
echo json_encode($products);
?>
