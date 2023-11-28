<?php
session_start(); // Start the session at the beginning

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Database connection details
$host = 'localhost:3306';
$dbname = 'foodpantryuofr';
$user = 'root';
$pass = '';

// Connect to the database
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Retrieve form data
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepare SQL query
    $stmt = $pdo->prepare("SELECT Student_Name FROM Students WHERE Student_Email_Address = :username AND Password = :password");
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $password);

    // Execute the query
    $stmt->execute();

    // Check if user exists
    if ($stmt->rowCount() > 0) {
        // User found, set session variables
        $_SESSION['student_logged_in'] = true;
        $_SESSION['username'] = $username;

        // Fetch the student's name
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $_SESSION['student_name'] = $row['Student_Name'];

        // Redirect to products page or user dashboard
        header('Location: products.php');
        exit;
    } else {
        // User not found, redirect back to login with error
        header('Location: login.html?error=invalid_credentials');
        exit;
    }
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>
