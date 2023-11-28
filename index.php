<?php
session_start(); // Start the session at the very beginning

// Initialize cart count
if (!isset($_SESSION['cart_count'])) {
    $_SESSION['cart_count'] = 0; // Set initial count to 0
}

// Database configuration
$host = 'localhost:3306'; // or your database host
$dbname = 'foodpantryuofr';
$username = 'root';
$password = '';

// ... rest of your PHP code for database connection ...
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Community Food Pantry</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <div class="top-header">
            <img src="icon.png" alt="Food Pantry Icon" class="header-icon">
            <div class="header-buttons">
                
                <button onclick="location.href='login.html'">Student Login</button>
                <button onclick="location.href='admin_login.html'">Admin Login</button>
            </div>
        </div>
        <!-- ... rest of your header ... -->
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
            $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $pdo->query("SELECT Category_Id, Category_Name FROM Categories");
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<li><a href='check_login.php?category=" . $row['Category_Id'] . "'>" . htmlspecialchars($row['Category_Name']) . "</a></li>";
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        ?>
    </ul>
</nav>
        <main>
            <section>
                <h2>Welcome to Our Food Pantry</h2>
                <p>Helping our community with food assistance.</p>
            </section>
            <!-- Other sections can be added here -->
        </main>
    </div>
    <footer>
        <p>&copy; 2023 Community Food Pantry</p>
    </footer>
</body>
</html>
