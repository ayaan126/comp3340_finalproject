<?php
class CartManager {
    private $conn;
    
    public function __construct($conn) {
        $this->conn = $conn;
        $this->initializeTable();
    }
    
    private function initializeTable() {
        $sql = "CREATE TABLE IF NOT EXISTS cart_items (
            cart_id INT PRIMARY KEY AUTO_INCREMENT,
            user_id INT,
            product_id INT,
            quantity INT DEFAULT 1,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(user_id),
            FOREIGN KEY (product_id) REFERENCES products(product_id)
        )";
        $this->conn->query($sql);
    }
    
    public function addToCart($userId, $productId, $quantity = 1) {
        try {
            // Check if item already exists in cart
            $stmt = $this->conn->prepare("SELECT cart_id, quantity FROM cart_items WHERE user_id = ? AND product_id = ?");
            $stmt->bind_param("ii", $userId, $productId);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0) {
                // Update quantity if item exists
                $item = $result->fetch_assoc();
                $newQuantity = $item['quantity'] + $quantity;
                $stmt = $this->conn->prepare("UPDATE cart_items SET quantity = ? WHERE cart_id = ?");
                $stmt->bind_param("ii", $newQuantity, $item['cart_id']);
                return $stmt->execute();
            } else {
                // Add new item
                $stmt = $this->conn->prepare("INSERT INTO cart_items (user_id, product_id, quantity) VALUES (?, ?, ?)");
                $stmt->bind_param("iii", $userId, $productId, $quantity);
                return $stmt->execute();
            }
        } catch (Exception $e) {
            error_log("CartManager addToCart error: " . $e->getMessage());
            return false;
        }
    }
    
    public function removeFromCart($userId, $productId) {
        try {
            $stmt = $this->conn->prepare("DELETE FROM cart_items WHERE user_id = ? AND product_id = ?");
            $stmt->bind_param("ii", $userId, $productId);
            $result = $stmt->execute();
            
            if (!$result) {
                error_log("CartManager removeFromCart SQL error: " . $this->conn->error);
            }
            
            return $result;
        } catch (Exception $e) {
            error_log("CartManager removeFromCart error: " . $e->getMessage());
            return false;
        }
    }
    
    public function updateQuantity($userId, $productId, $quantity) {
        try {
            if ($quantity <= 0) {
                return $this->removeFromCart($userId, $productId);
            }
            
            $stmt = $this->conn->prepare("UPDATE cart_items SET quantity = ? WHERE user_id = ? AND product_id = ?");
            $stmt->bind_param("iii", $quantity, $userId, $productId);
            $result = $stmt->execute();
            
            if (!$result) {
                error_log("CartManager updateQuantity SQL error: " . $this->conn->error);
            }
            
            return $result;
        } catch (Exception $e) {
            error_log("CartManager updateQuantity error: " . $e->getMessage());
            return false;
        }
    }
    
    public function getCart($userId) {
        $sql = "SELECT ci.*, p.title, p.description, p.base_price, p.image, c.name as category_name 
                FROM cart_items ci 
                JOIN products p ON ci.product_id = p.product_id 
                LEFT JOIN categories c ON p.category_id = c.category_id 
                WHERE ci.user_id = ?";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        
        $result = $stmt->get_result();
        $cart = [];
        $total = 0;
        
        while ($item = $result->fetch_assoc()) {
            $item['subtotal'] = $item['base_price'] * $item['quantity'];
            $total += $item['subtotal'];
            $cart[] = $item;
        }
        
        return [
            'items' => $cart,
            'total' => $total,
            'count' => count($cart)
        ];
    }
    
    public function clearCart($userId) {
        try {
            $stmt = $this->conn->prepare("DELETE FROM cart_items WHERE user_id = ?");
            $stmt->bind_param("i", $userId);
            $result = $stmt->execute();
            
            if (!$result) {
                error_log("CartManager clearCart SQL error: " . $this->conn->error);
            }
            
            return $result;
        } catch (Exception $e) {
            error_log("CartManager clearCart error: " . $e->getMessage());
            return false;
        }
    }
    
    public function getCartCount($userId) {
        $stmt = $this->conn->prepare("SELECT COUNT(*) as count FROM cart_items WHERE user_id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc()['count'];
    }
}
?>
