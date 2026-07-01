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
<style>
body { background:#0e0e0e; color:#fff; font-family:Arial; display:flex; justify-content:center; align-items:center; height:100vh;}
.login-box { background:#1c1c1c; padding:2rem; border-radius:12px; width:320px; box-shadow:0 0 15px rgba(0,0,0,0.7); text-align:center;}
h2 { margin-bottom:1rem; }
input { width:90%; padding:10px; margin:10px 0; border-radius:6px; border:none; }
button { width:100%; padding:10px; border:none; border-radius:6px; background:#00d4ff; color:#000; font-weight:bold; cursor:pointer; }
button:hover { background:#00aacc; }
.form-box { display:none; }
.form-box.active { display:block; }
.switch-link { margin-top:1rem; color:#00d4ff; cursor:pointer; display:block; }
.switch-link:hover { text-decoration:underline; }
.error { color:red; margin-bottom:10px; }
</style>
</head>
<body>
<div class="login-box">
<h2 id="form-title">Login</h2>

<?php
// Show success message
if (isset($_SESSION['success'])) {
    echo "<div style='color:green; margin-bottom:10px; font-weight:bold;'>".$_SESSION['success']."</div>";
    unset($_SESSION['success']);
}
// Show error message
if (isset($_SESSION['error'])) {
    echo "<div class='error'>".$_SESSION['error']."</div>";
    unset($_SESSION['error']);
}
?>

<form id="login-form" class="form-box active" action="login.php" method="POST">
<input type="text" name="username" placeholder="Username or Email" required><br>
<input type="password" name="password" placeholder="Password" required><br>
<button type="submit">Login</button>
</form>

<!-- Sign Up Form -->
<form id="signup-form" class="form-box" action="signup.php" method="POST">
<input type="text" name="new_username" placeholder="Choose Username" required><br>
<input type="email" name="email" placeholder="Email" required><br>
<input type="password" name="new_password" placeholder="Password" required><br>
<input type="password" name="confirm_password" placeholder="Confirm Password" required><br>
<button type="submit">Sign Up</button>
</form>

<span id="switch-to-signup" class="switch-link">Don’t have an account? Sign up</span>
<span id="switch-to-login" class="switch-link" style="display:none;">Already have an account? Login</span>
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
