<?php
session_start();
include '../config/db.php';
include '../includes/header.php';

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Remove from cart
if (isset($_GET['remove'])) {
    $id = $_GET['remove'];
    unset($_SESSION['cart'][$id]);
}

// Display cart
echo "<h1>Your Shopping Cart</h1>";

if (empty($_SESSION['cart'])) {
    echo "<p>Your cart is empty. <a href='products.php'>Browse Books</a></p>";
} else {
    echo "<table border='1' cellpadding='10'>
            <tr>
                <th>Title</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Action</th>
            </tr>";
    $total = 0;

    foreach ($_SESSION['cart'] as $id => $item) {
        $subtotal = $item['price'] * $item['quantity'];
        $total += $subtotal;
        echo "<tr>
                <td>{$item['title']}</td>
                <td>{$item['quantity']}</td>
                <td>$" . number_format($subtotal, 2) . "</td>
                <td><a href='cart.php?remove=$id'>Remove</a></td>
              </tr>";
    }

    echo "<tr>
            <td colspan='2'><strong>Total</strong></td>
            <td colspan='2'><strong>$" . number_format($total, 2) . "</strong></td>
          </tr>
          </table>";

    echo "<p><a class='btn-primary' href='checkout.php'>Proceed to Checkout</a></p>";
}

include '../includes/footer.php';
?>
