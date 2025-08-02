<?php
session_start();
include '../config/db.php';
include '../includes/header.php';

$error_message = '';
$success_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    
    // Basic validation
    if (empty($username) || empty($email) || empty($password)) {
        $error_message = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Invalid email format.";
    } elseif (strlen($password) < 6) {
        $error_message = "Password must be at least 6 characters long.";
    } else {
        // Check if email already exists
        $check_stmt = $conn->prepare("SELECT user_id FROM users WHERE email = ?");
        $check_stmt->bind_param("s", $email);
        $check_stmt->execute();
        $result = $check_stmt->get_result();
        
        if ($result->num_rows > 0) {
            $error_message = "An account with this email already exists. <a href='login.php'>Login instead?</a>";
        } else {
            // Check if username already exists
            $check_username = $conn->prepare("SELECT user_id FROM users WHERE username = ?");
            $check_username->bind_param("s", $username);
            $check_username->execute();
            $username_result = $check_username->get_result();
            
            if ($username_result->num_rows > 0) {
                $error_message = "This username is already taken. Please choose a different one.";
            } else {
                // Insert new user
                $hashed_password = md5($password);
                $insert_stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
                $insert_stmt->bind_param("sss", $username, $email, $hashed_password);
                
                if ($insert_stmt->execute()) {
                    $success_message = "Registration successful! <a href='login.php'>Login now</a>";
                } else {
                    $error_message = "Registration failed. Please try again later.";
                }
            }
        }
    }
}
?>

<h1>Register <a href="javascript:showContextHelp()" style="font-size: 14px; color: #007bff; text-decoration: none; margin-left: 10px;">❓ Need Help?</a></h1>

<?php if (!empty($error_message)): ?>
    <div style="color: red; background-color: #ffebee; padding: 10px; border: 1px solid #red; border-radius: 4px; margin: 10px 0;">
        <?php echo $error_message; ?>
    </div>
<?php endif; ?>

<?php if (!empty($success_message)): ?>
    <div style="color: green; background-color: #e8f5e8; padding: 10px; border: 1px solid #green; border-radius: 4px; margin: 10px 0;">
        <?php echo $success_message; ?>
    </div>
<?php endif; ?>

<form method="POST">
    Username: <input type="text" name="username" value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>" required><br><br>
    Email: <input type="email" name="email" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <button type="submit">Register</button>
</form>

<p>Already have an account? <a href="login.php">Login here</a></p>

<?php include '../includes/footer.php'; ?>
