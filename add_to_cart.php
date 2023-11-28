<?php
session_start();

// Check if the product ID is set
if (isset($_POST['product_id'])) {
    // Increment the cart count
    if (!isset($_SESSION['cart_count'])) {
        $_SESSION['cart_count'] = 0;
    }
    $_SESSION['cart_count'] += 1;

    // Redirect back to the products page
    header('Location: products.php');
    exit;
}

// Redirect to products page if accessed directly
header('Location: products.php');
?>
