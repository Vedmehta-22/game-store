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

// Filter games database to only show owned games
$owned_games = [];
foreach ($_SESSION['library'] as $game_id) {
    if (isset($games[$game_id])) {
        $owned_games[$game_id] = $games[$game_id];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Library - GameStore</title>
  <link rel="stylesheet" href="style.css">
  <script src="script.js" defer></script>
</head>
<body>
  <!-- Header -->
  <header>
    <h1>GameStore</h1>
    <nav>
      <a href="home.php">Home</a>
      <a href="library.php" class="active">Library</a>
      <a href="profile.php">Profile</a>
      <!-- Cart navigation link with dynamic item counter -->
      <a href="cart.php" class="nav-cart-link">
        Cart <span class="cart-badge" id="cartBadge"><?php echo count($_SESSION['cart']); ?></span>
      </a>
      <!-- ✅ Logout button -->
      <a href="login.php?logout=true" style="color:#ff007f; font-weight:bold;">Logout</a>
    </nav>
  </header>

  <!-- Library Section -->
  <main class="library-container">
    <!-- Success checkout notification -->
    <?php if (isset($_SESSION['success'])): ?>
      <div style="background: rgba(0, 242, 254, 0.1); border: 1px solid var(--primary-color); color: var(--primary-color); padding: 1rem 1.5rem; border-radius: 12px; margin-bottom: 2rem; text-shadow: 0 0 10px var(--primary-glow); font-weight: 500; text-align: center;">
        <?php 
          echo $_SESSION['success']; 
          unset($_SESSION['success']);
        ?>
      </div>
    <?php endif; ?>

    <div class="section-header" style="padding-left: 0; padding-right: 0;">
      <h2>Your Library</h2>
    </div>
    
    <!-- Search & Filter Bar -->
    <div class="search-filter-container">
      <div class="search-wrapper">
        <input type="text" class="search-bar" placeholder="Search your library...">
      </div>
      <div class="filter-tags">
        <button class="filter-btn active">All</button>
        <button class="filter-btn">Installed</button>
        <button class="filter-btn">Favorites</button>
        <button class="filter-btn">Recent</button>
      </div>
    </div>

    <!-- User Games List -->
    <?php if (count($owned_games) == 0): ?>
      <div class="empty-cart">
        <p>You don't own any games yet.</p>
        <a href="home.php" class="hero-btn">Explore Games Store</a>
      </div>
    <?php else: ?>
      <div class="game-grid" style="padding-left: 0; padding-right: 0;">
        <?php foreach ($owned_games as $id => $game): ?>
        <div class="game-card" onclick="openGame('game.php?game=<?php echo urlencode($id); ?>')">
          <div class="game-card-img-wrapper">
            <img src="<?php echo $game['image']; ?>" alt="<?php echo htmlspecialchars($game['title']); ?>">
          </div>
          <div class="game-card-info">
            <h3><?php echo htmlspecialchars($game['title']); ?></h3>
            <div class="game-card-meta">
              <span class="game-card-genre"><?php echo htmlspecialchars($game['genre']); ?></span>
              <span style="color: var(--primary-color);">Owned</span>
            </div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </main>

  <!-- Footer -->
  <footer>
    <p>&copy; 2026 GameStore. All rights reserved.</p>
  </footer>
</body>
</html>
