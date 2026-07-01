<?php
// signup.php
session_start();

$servername = "localhost";
$username = "root";      // default XAMPP user
$password = "";          // default XAMPP password (empty)
$dbname = "gamestore";   // make sure this database exists

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  $_SESSION['error'] = "Connection failed: " . $conn->connect_error;
  header("Location: login.php");
  exit;
}

// Handle signup form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $new_username = $conn->real_escape_string($_POST['new_username']);
  $email = $conn->real_escape_string($_POST['email']);
  $new_password = $_POST['new_password'];
  $confirm_password = $_POST['confirm_password'];

  // Check if passwords match
  if ($new_password !== $confirm_password) {
    $_SESSION['error'] = "Passwords do not match!";
    header("Location: login.php");
    exit;
  }

  // Check if username already exists
  $checkUser = "SELECT * FROM users WHERE username='$new_username'";
  $result = $conn->query($checkUser);

  if ($result && $result->num_rows > 0) {
    $_SESSION['error'] = "Username already taken!";
    header("Location: login.php");
    exit;
  }

  // Hash password for security
  $hashedPassword = password_hash($new_password, PASSWORD_DEFAULT);

  // Insert new user into database
  $sql = "INSERT INTO users (username, email, password) 
          VALUES ('$new_username', '$email', '$hashedPassword')";

  if ($conn->query($sql) === TRUE) {
    $_SESSION['success'] = "Account created successfully! Please log in.";
  } else {
    $_SESSION['error'] = "Error: " . $conn->error;
  }
}

$conn->close();
header("Location: login.php");
exit;
?>
