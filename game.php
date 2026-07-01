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

// Validate game parameter
if (!isset($_GET['game']) || !isset($games[$_GET['game']])) {
    header("Location: home.php");
    exit;
}

$id = $_GET['game'];
$game = $games[$id];

// Handle actions (Add to Cart / Add to Library)
if (isset($_GET['action'])) {
    $action = $_GET['action'];
    
    if ($action === 'add_to_cart') {
        $is_owned = in_array($id, $_SESSION['library']);
        $is_in_cart = in_array($id, $_SESSION['cart']);
        if (!$is_owned && !$is_in_cart) {
            $_SESSION['cart'][] = $id;
        }
        header("Location: game.php?game=" . urlencode($id));
        exit;
    }
    
    if ($action === 'add_to_library') {
        $is_owned = in_array($id, $_SESSION['library']);
        if (!$is_owned) {
            $_SESSION['library'][] = $id;
            
            // Remove from cart if it was there
            $cart_key = array_search($id, $_SESSION['cart']);
            if ($cart_key !== false) {
                unset($_SESSION['cart'][$cart_key]);
                $_SESSION['cart'] = array_values($_SESSION['cart']); // Reindex array
            }
        }
        header("Location: game.php?game=" . urlencode($id));
        exit;
    }
}

// Check ownership and cart states
$is_owned = in_array($id, $_SESSION['library']);
$is_in_cart = in_array($id, $_SESSION['cart']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title><?php echo htmlspecialchars($game['title']); ?> - GameStore</title>
  <link rel="stylesheet" href="style.css">
  <!-- Share PHP Games Database with JavaScript -->
  <script>
    const games = <?php echo json_encode($games); ?>;
  </script>
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
      <a href="cart.php" class="nav-cart-link">
        Cart <span class="cart-badge" id="cartBadge"><?php echo count($_SESSION['cart']); ?></span>
      </a>
      <!-- ✅ Logout button -->
      <a href="login.php?logout=true" style="color:#ff007f; font-weight:bold;">Logout</a>
    </nav>
  </header>

  <!-- Details Content -->
  <main class="game-detail">
    <div class="game-detail-header">
      <h2><?php echo htmlspecialchars($game['title']); ?></h2>
      <p><?php echo htmlspecialchars($game['bio']); ?></p>

      <div class="game-detail-meta-row">
        <!-- Price Display -->
        <div class="detail-price-tag">
          <?php echo $game['price'] == 0 ? 'Free' : '$' . number_format($game['price'], 2); ?>
        </div>

        <!-- Dynamic Action Buttons -->
        <div class="detail-actions">
          <?php if ($is_owned): ?>
            <a href="library.php" class="detail-btn primary">Play Now</a>
          <?php elseif ($is_in_cart): ?>
            <a href="cart.php" class="detail-btn secondary">In Cart (Go to Cart)</a>
          <?php else: ?>
            <?php if ($game['price'] == 0): ?>
              <!-- Free games can be added directly to the library -->
              <a href="game.php?game=<?php echo urlencode($id); ?>&action=add_to_library" class="detail-btn primary">Add to Library</a>
            <?php else: ?>
              <a href="game.php?game=<?php echo urlencode($id); ?>&action=add_to_library" class="detail-btn secondary">Buy Now</a>
              <a href="game.php?game=<?php echo urlencode($id); ?>&action=add_to_cart" class="detail-btn primary">Add to Cart</a>
            <?php endif; ?>
          <?php endif; ?>
        </div>
      </div>
    </div>

    <!-- Gallery Subtitle -->
    <h3 class="gallery-header">Screenshots</h3>
    
    <!-- Screenshots Gallery (Populated by script.js) -->
    <div class="gallery" id="gameGallery"></div>
  </main>

  <!-- Modal for large image view -->
  <div id="imgModal" class="modal">
    <span class="close">&times;</span>
    <img class="modal-content" id="modalImg">
    <div id="caption"></div>
  </div>

  <!-- Footer -->
  <footer>
    <p>&copy; 2026 GameStore. All rights reserved.</p>
  </footer>
</body>
</html>
