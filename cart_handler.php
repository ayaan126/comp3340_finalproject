<?php
// Start session and connect to database without HTML output
session_start();
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/CartManager.php';

// Clear any previous output and set content type for JSON response
ob_clean();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Please login to add items to cart']);
    exit;
}

try {
    $cartManager = new CartManager($conn);
    $action = $_POST['action'] ?? '';
    $productId = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
    $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;

    // Debug logging
    error_log("Cart action: $action, ProductID: $productId, Quantity: $quantity, UserID: " . $_SESSION['user_id']);

    // Validate input based on action
    if ($action === 'clear') {
        // Clear cart doesn't need product validation
    } else if ($productId <= 0 && $action !== 'clear') {
        echo json_encode(['success' => false, 'message' => 'Invalid product ID']);
        exit;
    }

    // Check if product exists (except for clear action)
    if ($action !== 'clear') {
        $stmt = $conn->prepare("SELECT product_id FROM products WHERE product_id = ?");
        $stmt->bind_param("i", $productId);
        $stmt->execute();
        if ($stmt->get_result()->num_rows === 0) {
            echo json_encode(['success' => false, 'message' => 'Product not found']);
            exit;
        }
    }

    switch ($action) {
        case 'add':
            $result = $cartManager->addToCart($_SESSION['user_id'], $productId, $quantity);
            break;
        case 'remove':
            $result = $cartManager->removeFromCart($_SESSION['user_id'], $productId);
            break;
        case 'update':
            $result = $cartManager->updateQuantity($_SESSION['user_id'], $productId, $quantity);
            break;
        case 'clear':
            $result = $cartManager->clearCart($_SESSION['user_id']);
            break;
        default:
            echo json_encode(['success' => false, 'message' => 'Invalid action: ' . $action]);
            exit;
    }

    $cartCount = $cartManager->getCartCount($_SESSION['user_id']);
    
    // Debug the result
    error_log("Cart operation result: " . ($result ? 'success' : 'failed') . ", New cart count: $cartCount");
    
    echo json_encode([
        'success' => $result,
        'cartCount' => $cartCount,
        'message' => $result ? 'Cart updated successfully' : 'Failed to update cart - check server logs for details'
    ]);

} catch (Exception $e) {
    error_log("Cart handler exception: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
}

exit;
