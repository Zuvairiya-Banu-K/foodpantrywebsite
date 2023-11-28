<?php
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['student_logged_in']) || $_SESSION['student_logged_in'] !== true) {
    header('Location: login.html');
    exit;
}

// Initialize cart count if not set
if (!isset($_SESSION['cart_count'])) {
    $_SESSION['cart_count'] = 0;
}

// Handle Add to Cart
if (isset($_POST['add_to_cart'])) {
    $productId = $_POST['product_id'];

    // Check if the cart is already initialized
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Add the product to the cart
    $_SESSION['cart'][$productId] = [
        'id' => $productId,
        // You can add more product details here if needed
    ];

    // Update cart count
    $_SESSION['cart_count'] = count($_SESSION['cart']);
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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Community Food Pantry - Products</title>
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
        <nav class="sidebar">
            <ul>
                <?php
                try {
                    $stmt = $pdo->query("SELECT Category_Id, Category_Name FROM Categories");
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo "<li><a href='products.php?category=" . $row['Category_Id'] . "'>" . htmlspecialchars($row['Category_Name']) . "</a></li>";
                    }
                } catch (PDOException $e) {
                    echo "Error: " . $e->getMessage();
                }
                ?>
            </ul>
        </nav>

        <main>
            <?php
            $category_id = $_GET['category'] ?? 1;
            try {
                $stmt = $pdo->prepare("SELECT * FROM Products WHERE Product_Category_Id = :category_id");
                $stmt->execute(['category_id' => $category_id]);

                $productsFound = false;
                echo "<div class='products-container'>";
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $productsFound = true;
                    $imagePath = htmlspecialchars($row['image_path']);
                    $productName = htmlspecialchars($row['Product_Name']);
                    $productId = $row['Product_Id'];
                    echo "<div class='product-item'>
                            <img src='$imagePath' alt='Product Image' class='product-image'>
                            <div class='product-name'>$productName</div>
                            <form action='products.php' method='post'>
                                <input type='hidden' name='product_id' value='$productId'>
                                <button type='submit' name='add_to_cart' class='add-to-cart-button'>Add to Cart</button>
                            </form>
                          </div>";
                }
                echo "</div>";

                if (!$productsFound) {
                    echo "No products found in this category.";
                }
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
            ?>
        </main>
    </div>

    <footer>
        <p>&copy; 2023 Community Food Pantry</p>
    </footer>
</body>
</html>
