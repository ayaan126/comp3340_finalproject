<?php
class ThemeManager {
    private $conn;
    
    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function getCurrentTheme() {
        // Check if there's a seasonal theme active
        $sql = "SELECT css_file FROM themes 
                WHERE CURDATE() BETWEEN active_from AND active_to 
                LIMIT 1";
        $result = $this->conn->query($sql);
        
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['css_file'];
        }
        
        // Return default theme if no seasonal theme is active
        return 'assets/css/themes/regular.css';
    }

    public function setTheme($themeName) {
        $_SESSION['user_theme'] = $themeName;
    }

    public function getUserTheme() {
        return isset($_SESSION['user_theme']) ? $_SESSION['user_theme'] : $this->getCurrentTheme();
    }
}
?>
