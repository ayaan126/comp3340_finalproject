<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../config/db.php';

// Set content type for JSON response
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Please login to manage orders']);
    exit;
}

try {
    $action = $_POST['action'] ?? '';
    $order_id = isset($_POST['order_id']) ? (int)$_POST['order_id'] : 0;
    $user_id = $_SESSION['user_id'];

    // Validate input
    if ($order_id <= 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid order ID']);
        exit;
    }

    // Verify order belongs to user and get current status
    $stmt = $conn->prepare("SELECT order_status FROM orders WHERE order_id = ? AND user_id = ?");
    $stmt->bind_param("ii", $order_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        echo json_encode(['success' => false, 'message' => 'Order not found']);
        exit;
    }
    
    $order = $result->fetch_assoc();
    $current_status = $order['order_status'];

    switch ($action) {
        case 'cancel':
            // Only allow cancellation for pending and processing orders
            if (!in_array($current_status, ['pending', 'processing'])) {
                echo json_encode([
                    'success' => false, 
                    'message' => 'Order cannot be cancelled. Current status: ' . ucfirst($current_status)
                ]);
                exit;
            }

            // Update order status to cancelled
            $stmt = $conn->prepare("UPDATE orders SET order_status = 'cancelled' WHERE order_id = ? AND user_id = ?");
            $stmt->bind_param("ii", $order_id, $user_id);
            $result = $stmt->execute();

            if ($result) {
                echo json_encode([
                    'success' => true, 
                    'message' => 'Order cancelled successfully',
                    'new_status' => 'cancelled'
                ]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to cancel order']);
            }
            break;

        case 'reorder':
            // Get order items
            $stmt = $conn->prepare("
                SELECT oi.product_id, oi.quantity 
                FROM order_items oi 
                WHERE oi.order_id = ?
            ");
            $stmt->bind_param("i", $order_id);
            $stmt->execute();
            $items_result = $stmt->get_result();

            if ($items_result->num_rows === 0) {
                echo json_encode(['success' => false, 'message' => 'No items found in order']);
                exit;
            }

            // Add items to cart
            require_once __DIR__ . '/CartManager.php';
            $cartManager = new CartManager($conn);
            $added_count = 0;

            while ($item = $items_result->fetch_assoc()) {
                if ($cartManager->addToCart($user_id, $item['product_id'], $item['quantity'])) {
                    $added_count++;
                }
            }

            if ($added_count > 0) {
                $cart_count = $cartManager->getCartCount($user_id);
                echo json_encode([
                    'success' => true, 
                    'message' => "Added {$added_count} item(s) to cart",
                    'cart_count' => $cart_count
                ]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to add items to cart']);
            }
            break;

        default:
            echo json_encode(['success' => false, 'message' => 'Invalid action']);
            break;
    }

} catch (Exception $e) {
    error_log("Order management error: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Server error occurred']);
}
exit;
?>
