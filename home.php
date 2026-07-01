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
    // New users start with Valorant and Rocket League owned by default
    $_SESSION['library'] = ['Valorant', 'RocketLeague'];
}

// Include games database
require_once 'games_db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Home - GameStore</title>
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
      <a href="home.php" class="active">Home</a>
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

  <!-- Hero Section -->
  <section class="hero-section">
    <div class="hero-content">
      <span class="hero-badge">Featured Game</span>
      <h2 class="hero-title">Cyberpunk 2077</h2>
      <p class="hero-subtitle">Step into Night City, a massive neon-lit futuristic metropolis where choices shape your destiny. Play today.</p>
      <a href="game.php?game=cyberpunk" class="hero-btn">Play Now</a>
    </div>
  </section>

  <!-- Welcome / Section Title -->
  <div class="section-header">
    <h2>Welcome back, <?php echo htmlspecialchars($_SESSION['username']); ?>! 👋</h2>
  </div>

  <!-- Game Grid (Dynamic Loop) -->
  <main class="game-grid">
    <?php foreach ($games as $id => $game): ?>
    <div class="game-card" onclick="openGame('game.php?game=<?php echo $id; ?>')">
      <div class="game-card-img-wrapper">
        <img src="<?php echo $game['image']; ?>" alt="<?php echo htmlspecialchars($game['title']); ?>">
      </div>
      <div class="game-card-info">
        <h3><?php echo htmlspecialchars($game['title']); ?></h3>
        <div class="game-card-meta">
          <span class="game-card-genre"><?php echo htmlspecialchars($game['genre']); ?></span>
          <span class="game-card-price">
            <?php echo $game['price'] == 0 ? 'Free' : '$' . number_format($game['price'], 2); ?>
          </span>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
  </main>

  <!-- Footer -->
  <footer>
    <p>&copy; 2026 GameStore. All rights reserved.</p>
  </footer>
</body>
</html>
