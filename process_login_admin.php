<?php
// process_login.php

// Database connection details
$host = 'localhost:3306';
$dbname = 'foodpantryuofr';
$user = 'root';
$pass = '';

// Connect to the database
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);

    // Set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Retrieve form data
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepare SQL query
    $stmt = $pdo->prepare("SELECT * FROM Admins WHERE Employee_Email_Address = :username AND Password = :password");
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $password);

    // Execute the query
    $stmt->execute();

    // Check if user exists
    if ($stmt->rowCount() > 0) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $adminName = $row['Employee_Name'];
        session_start();
        $_SESSION['adminName'] = $adminName;
        // User found, redirect to admin dashboard or similar page
        header('Location: admin_home.php');
    } else {
        // User not found, redirect back to login with error
        header('Location: admin_login.html?error=invalid_credentials');
    }
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>
