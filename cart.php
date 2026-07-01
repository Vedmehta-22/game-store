<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

// Initialize Cart and Library if they do not exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}
if (!isset($_SESSION['library'])) {
    $_SESSION['library'] = ['Valorant', 'RocketLeague'];
}

// Include games database
require_once 'games_db.php';

// Handle removal of an item from cart
if (isset($_GET['remove'])) {
    $remove_id = $_GET['remove'];
    $key = array_search($remove_id, $_SESSION['cart']);
    if ($key !== false) {
        unset($_SESSION['cart'][$key]);
        $_SESSION['cart'] = array_values($_SESSION['cart']); // Reindex array
    }
    header("Location: cart.php");
    exit;
}

// Handle checkout (Buying all items in cart)
if (isset($_POST['checkout']) || (isset($_GET['action']) && $_GET['action'] === 'checkout')) {
    if (count($_SESSION['cart']) > 0) {
        foreach ($_SESSION['cart'] as $game_id) {
            if (!in_array($game_id, $_SESSION['library'])) {
                $_SESSION['library'][] = $game_id;
            }
        }
        // Clear cart
        $_SESSION['cart'] = [];
        $_SESSION['success'] = "Checkout successful! The games have been added to your Library.";
        header("Location: library.php");
        exit;
    }
}

// Calculate cart total
$total_price = 0;
$cart_items = [];
foreach ($_SESSION['cart'] as $game_id) {
    if (isset($games[$game_id])) {
        $cart_items[$game_id] = $games[$game_id];
        $total_price += $games[$game_id]['price'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Shopping Cart - GameStore</title>
  <link rel="stylesheet" href="style.css">
  <script src="script.js" defer></script>
</head>
<body>
  <!-- Header -->
  <header>
    <h1>GameStore</h1>
    <nav>
      <a href="home.php">Home</a>
      <a href="library.php">Library</a>
      <a href="profile.php">Profile</a>
      <!-- Cart navigation link with dynamic item counter -->
      <a href="cart.php" class="active nav-cart-link">
        Cart <span class="cart-badge" id="cartBadge"><?php echo count($_SESSION['cart']); ?></span>
      </a>
      <!-- ✅ Logout button -->
      <a href="login.php?logout=true" style="color:#ff007f; font-weight:bold;">Logout</a>
    </nav>
  </header>

  <!-- Cart Section -->
  <main class="cart-container">
    <div class="section-header" style="padding-left: 0; padding-right: 0;">
      <h2>Your Shopping Cart</h2>
    </div>

    <?php if (count($cart_items) == 0): ?>
      <!-- Empty Cart Message -->
      <div class="empty-cart">
        <p>Your shopping cart is empty.</p>
        <a href="home.php" class="hero-btn">Browse Games Store</a>
      </div>
    <?php else: ?>
      <!-- Cart List Table -->
      <table class="cart-table">
        <thead>
          <tr>
            <th>Game</th>
            <th>Price</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($cart_items as $id => $game): ?>
            <tr>
              <td>
                <div class="cart-item-info">
                  <img src="<?php echo $game['image']; ?>" alt="<?php echo htmlspecialchars($game['title']); ?>" class="cart-item-img">
                  <div>
                    <div class="cart-item-title"><?php echo htmlspecialchars($game['title']); ?></div>
                    <div class="cart-item-genre"><?php echo htmlspecialchars($game['genre']); ?></div>
                  </div>
                </div>
              </td>
              <td>
                <div class="cart-item-price">$<?php echo number_format($game['price'], 2); ?></div>
              </td>
              <td>
                <a href="cart.php?remove=<?php echo urlencode($id); ?>" class="cart-remove-btn">Remove</a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>

      <!-- Cart Total and Checkout Action -->
      <div class="cart-summary">
        <div class="cart-total">
          Total: <span>$<?php echo number_format($total_price, 2); ?></span>
        </div>
        <form action="cart.php" method="POST">
          <button type="submit" name="checkout" class="checkout-btn">Purchase for myself</button>
        </form>
      </div>
    <?php endif; ?>
  </main>

  <!-- Footer -->
  <footer>
    <p>&copy; 2026 GameStore. All rights reserved.</p>
  </footer>
</body>
</html>
