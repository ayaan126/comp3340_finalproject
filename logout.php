<?php
require_once '../includes/header.php';
require_once '../includes/UserManager.php';

$userManager = new UserManager($conn);
$userManager->logout();

header('Location: /DigitalNovels/index.php');
exit;
?>
