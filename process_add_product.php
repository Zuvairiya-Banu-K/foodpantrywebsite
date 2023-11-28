<!-- ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL); -->

<!-- $host = 'localhost:3306';
$dbname = 'foodpantryuofr';
$username = 'root';
$password = ''; -->

<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Database configuration
$host = 'localhost:3306'; // or your database host
$dbname = 'foodpantryuofr';
$username = 'root';
$password = '';

// Create database connection
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input
    $product_name = trim($_POST['product_name']);
    $product_category_id = filter_input(INPUT_POST, 'product_category_id', FILTER_VALIDATE_INT);
    $product_quantity = filter_input(INPUT_POST, 'product_quantity', FILTER_VALIDATE_INT);

    // Initialize image_path variable
    $image_path = '';

    // Handle file upload
    if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] == UPLOAD_ERR_OK) {
        // Define the path to store images
        $target_dir = "uploads/"; // Ensure this directory exists and is writable
        $target_file = $target_dir . basename($_FILES["product_image"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Validate file (size, type, etc.)
        // Add your validation code here (size check, type check, etc.)

        // Attempt to upload file
        if (move_uploaded_file($_FILES["product_image"]["tmp_name"], $target_file)) {
            // File uploaded successfully
            $image_path = $target_file;
        } else {
            echo "Sorry, there was an error uploading your file.";
            exit;
        }
    }

    // Insert data into database
    try {
        $stmt = $pdo->prepare("INSERT INTO Products (Product_Name, Product_Category_Id, Product_Quantity, image_path) VALUES (:product_name, :product_category_id, :product_quantity, :image_path)");
        $stmt->bindParam(':product_name', $product_name);
        $stmt->bindParam(':product_category_id', $product_category_id);
        $stmt->bindParam(':product_quantity', $product_quantity);
        $stmt->bindParam(':image_path', $image_path);
        $stmt->execute();

        echo "New product added successfully";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "Invalid request";
}
?>
