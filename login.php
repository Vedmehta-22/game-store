<?php
session_start();

// Handle Logout
if (isset($_GET['logout'])) {
    session_destroy();
    unset($_SESSION['username']);
    header("Location: login.php");
    exit;
}

// Redirect if already logged in
if (isset($_SESSION['username'])) {
    header("Location: home.php");
    exit;
}

// Database configuration
$servername = "localhost";
$db_username = "root";      // default XAMPP user
$db_password = "";          // default XAMPP password (empty)
$dbname = "gamestore";

// Handle Login POST
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['username']) && isset($_POST['password'])) {
    $conn = new mysqli($servername, $db_username, $db_password, $dbname);
    if ($conn->connect_error) {
        $_SESSION['error'] = "Database connection failed: " . $conn->connect_error;
    } else {
        $username = $conn->real_escape_string($_POST['username']);
        $password = $_POST['password'];

        $sql = "SELECT * FROM users WHERE username='$username' OR email='$username'";
        $result = $conn->query($sql);

        if ($result && $result->num_rows > 0) {
            $user = $result->fetch_assoc();
            // Verify password using password_verify (matches the password_hash in signup.php)
            if (password_verify($password, $user['password'])) {
                $_SESSION['username'] = $user['username'];
                $_SESSION['email'] = $user['email'];
                header("Location: home.php");
                exit;
            } else {
                $_SESSION['error'] = "Invalid username or password.";
            }
        } else {
            $_SESSION['error'] = "Invalid username or password.";
        }
        $conn->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>GameStore - Login</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="auth-container">
    <div class="auth-left">
      <div class="auth-brand">GameStore</div>
      <div class="auth-hero-text">
        <h2>YOUR NEXT ADVENTURE STARTS HERE.</h2>
        <p>Explore over 14 premium and free-to-play titles. Manage your library, track achievements, and build your digital archive.</p>
      </div>
      <div class="auth-stats">
        <div class="auth-stat-item">
          <h4>14+</h4>
          <p>Curated Games</p>
        </div>
        <div class="auth-stat-item">
          <h4>100%</h4>
          <p>Digital Shelf</p>
        </div>
      </div>
    </div>
    <div class="auth-right">
      <div class="auth-card">
        <h2 id="form-title">Login</h2>

        <?php
        // Show success message
        if (isset($_SESSION['success'])) {
            echo "<div class='success'>".$_SESSION['success']."</div>";
            unset($_SESSION['success']);
        }
        // Show error message
        if (isset($_SESSION['error'])) {
            echo "<div class='error'>".$_SESSION['error']."</div>";
            unset($_SESSION['error']);
        }
        ?>

        <!-- Login Form -->
        <form id="login-form" class="form-box active" action="login.php" method="POST">
          <label for="username">Username or Email</label>
          <input type="text" id="username" name="username" placeholder="Username or Email" required>
          
          <label for="password">Password</label>
          <input type="password" id="password" name="password" placeholder="Password" required>
          
          <button type="submit">Login</button>
        </form>

        <!-- Sign Up Form -->
        <form id="signup-form" class="form-box" action="signup.php" method="POST">
          <label for="new_username">Choose Username</label>
          <input type="text" id="new_username" name="new_username" placeholder="Choose Username" required>
          
          <label for="email">Email</label>
          <input type="email" id="email" name="email" placeholder="Email" required>
          
          <label for="new_password">Password</label>
          <input type="password" id="new_password" name="new_password" placeholder="Password" required>
          
          <label for="confirm_password">Confirm Password</label>
          <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm Password" required>
          
          <button type="submit">Sign Up</button>
        </form>

        <span id="switch-to-signup" class="switch-link">Don’t have an account? Sign up</span>
        <span id="switch-to-login" class="switch-link" style="display:none;">Already have an account? Login</span>
      </div>
    </div>
  </div>

  <script>
  const loginForm = document.getElementById("login-form");
  const signupForm = document.getElementById("signup-form");
  const formTitle = document.getElementById("form-title");
  const switchToSignup = document.getElementById("switch-to-signup");
  const switchToLogin = document.getElementById("switch-to-login");

  switchToSignup.addEventListener("click",()=>{
      loginForm.classList.remove("active");
      signupForm.classList.add("active");
      formTitle.textContent="Sign Up";
      switchToSignup.style.display="none";
      switchToLogin.style.display="block";
  });
  switchToLogin.addEventListener("click",()=>{
      signupForm.classList.remove("active");
      loginForm.classList.add("active");
      formTitle.textContent="Login";
      switchToLogin.style.display="none";
      switchToSignup.style.display="block";
  });
  </script>
</body>
</html>
