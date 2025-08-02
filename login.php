<?php
session_start();
include '../config/db.php';

$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    // Use prepared statement for security
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? AND password = MD5(?)");
    $stmt->bind_param("ss", $email, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['username'] = $user['username'];
        
        // Redirect after successful login
        header("Location: profile.php");
        exit;
    } else {
        $error_message = "Invalid email or password.";
    }
}

// Include header only after processing login
include '../includes/header.php';
?>

<h1>Login <a href="javascript:showContextHelp()" style="font-size: 14px; color: #007bff; text-decoration: none; margin-left: 10px;">❓ Need Help?</a></h1>

<?php if (!empty($error_message)): ?>
    <div style="color: red; background-color: #ffebee; padding: 10px; border: 1px solid #red; border-radius: 4px; margin: 10px 0;">
        <?php echo htmlspecialchars($error_message); ?>
    </div>
<?php endif; ?>

<form method="POST">
    Email: <input type="email" name="email" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <button type="submit">Login</button>
</form>

<p>Don't have an account? <a href="register.php">Create one here</a></p>

<?php include '../includes/footer.php'; ?>
