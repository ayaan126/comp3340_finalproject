<?php
include '../includes/header.php';
?>

<h1>Contact Us</h1>
<form method="POST" action="">
    Name: <input type="text" name="name" required><br><br>
    Email: <input type="email" name="email" required><br><br>
    Message:<br>
    <textarea name="message" rows="5" required></textarea><br><br>
    <button type="submit">Send</button>
</form>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "<p>Thank you for contacting us, we will reply soon!</p>";
}

include '../includes/footer.php';
?>
