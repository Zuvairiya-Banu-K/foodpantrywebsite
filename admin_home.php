<?php
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['adminName'])) {
    header('Location: admin_login.html'); // Redirect to login page if not logged in
    exit();
}

$adminName = $_SESSION['adminName'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Home - Community Food Pantry</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <div class="top-header">
            <!-- Add your logo and other elements as in your main page -->
            <img src="icon.png" alt="Food Pantry Icon" class="header-icon">
            <div class="header-buttons">
                Welcome, <?php echo htmlspecialchars($adminName); ?>
                <button onclick="location.href='logout.php'">Logout</button>
            </div>
        </div>
        <div class="main-header">
            <h1>Community Food Pantry - Admin Panel</h1>
            <nav>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="about.html">About Us</a></li>
                    <li><a href="services.html">How to Support the Food Pantry</a></li>
                    <li><a href="contact.html">Contact</a></li>
                    <li><a href="recipes.html">Food Pantry Recipes</a></li>
                </ul>
            </nav>
        </div>
    </header>
    <div class="admin-buttons-container">
        <button class="admin-button" onclick="location.href='add_product.html'">Add Product</button>
        <button class="admin-button" onclick="location.href='update_delivery.html'">Update Delivery Information</button>
    </div>
    <footer>
        <p>&copy; 2023 Community Food Pantry</p>
    </footer>
</body>
</html>
