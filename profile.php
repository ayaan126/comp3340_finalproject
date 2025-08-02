<?php
session_start();
include '../config/db.php';
include '../includes/header.php';

if (!isset($_SESSION['user_id'])) {
    echo "<p>You must <a href='login.php'>login</a> to view your profile.</p>";
    include '../includes/footer.php';
    exit;
}

$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT * FROM users WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

echo "<h1>Welcome, {$user['username']} <a href='javascript:showContextHelp()' style='font-size: 14px; color: #007bff; text-decoration: none; margin-left: 10px;'>❓ Need Help?</a></h1>";
echo "<p>Email: {$user['email']}</p>";
echo "<p><a href='orders.php'>View Order History</a></p>";

include '../includes/footer.php';
?>
