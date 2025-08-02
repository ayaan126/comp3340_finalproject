<?php
session_start();
if (isset($_POST['theme'])) {
    $_SESSION['user_theme'] = $_POST['theme'];
    echo 'Theme saved successfully';
}
?>
