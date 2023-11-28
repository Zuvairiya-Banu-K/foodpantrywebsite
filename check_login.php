<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['student_logged_in']) || $_SESSION['student_logged_in'] !== true) {
    header('Location: login.html'); // Redirect to login page
    exit;
}

// If logged in, redirect to the product listing page for the selected category
$category_id = $_GET['category'] ?? 0; // Get the category ID from the URL
header('Location: products.php?category=' . $category_id);
exit;
?>
