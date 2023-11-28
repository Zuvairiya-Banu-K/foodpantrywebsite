<?php
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['student_logged_in']) || $_SESSION['student_logged_in'] !== true) {
    header('Location: login.html');
    exit;
}

// Database connection details
$host = 'localhost:3306';
$dbname = 'foodpantryuofr';
$user = 'root';
$pass = '';

// Initialize PDO
$pdo = null;

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}

// Fetch cart items from the database
$cartItems = [];
if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $productId => $productDetails) {
        $stmt = $pdo->prepare("SELECT * FROM Products WHERE Product_Id = :productId");
        $stmt->execute(['productId' => $productId]);
        $productData = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($productData) {
            $cartItems[$productId] = $productData;
        }
    }
}

// Clear Cart
if (isset($_POST['clear_cart'])) {
    $_SESSION['cart'] = [];
    $_SESSION['cart_count'] = 0;
    $cartItems = [];
}

// Proceed to Checkout
if (isset($_POST['confirm_checkout'])) {
    header('Location: checkout.php');
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Community Food Pantry - Cart</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<header>
        <div class="top-header">
            <img src="icon.png" alt="Food Pantry Icon" class="header-icon">
            <div class="header-buttons">
                <span class="student-name"><?php echo htmlspecialchars($_SESSION['student_name']); ?></span>
                <button class="cart-button" onclick="location.href='cart.php'">
                    Checkout the items 
                </button>
                <?php echo $_SESSION['cart_count']; ?>
                <button onclick="location.href='logout.php'">Logout</button>
            </div>
        </div>
        <div class="main-header">
            <h1>Community Food Pantry</h1>
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

    <div class="container">
        <div class="products-container">
            <?php foreach ($cartItems as $item): ?>
                <div class="product-item">
                    <img src="<?php echo htmlspecialchars($item['image_path']); ?>" alt="Product Image" class="product-image">
                    <div class="product-name"><?php echo htmlspecialchars($item['Product_Name']); ?></div>
                    <!-- Add more product details if needed -->
                </div>
            <?php endforeach; ?>
        </div>

        <div class="cart-actions">
            <form method="post">
                <button type="submit" name="clear_cart">Clear Cart</button>
                <button type="submit" name="confirm_checkout">Confirm Checkout</button>
            </form>
        </div>
    </div>

    <footer>
        <p>&copy; 2023 Community Food Pantry</p>
    </footer>
</body>
</html>
