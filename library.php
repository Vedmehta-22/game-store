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
    <div class="nav-container">
      <h1>GameStore</h1>
      <nav>
        <a href="home.php">Store</a>
        <a href="library.php" class="active">Library</a>
        <a href="profile.php">Profile</a>
        <!-- Cart navigation link with dynamic item counter -->
        <a href="cart.php" class="nav-cart-link">
          Cart <span class="cart-badge" id="cartBadge"><?php echo count($_SESSION['cart']); ?></span>
        </a>
        <!-- ✅ Logout button -->
        <a href="login.php?logout=true" style="color: var(--coral); font-weight: bold;">Logout</a>
      </nav>
    </div>
  </header>

  <!-- Library Section -->
  <main>
    <div class="library-container">
      <!-- Success checkout notification -->
      <?php if (isset($_SESSION['success'])): ?>
        <div class="library-notification">
          <?php 
            echo $_SESSION['success']; 
            unset($_SESSION['success']);
          ?>
        </div>
      <?php endif; ?>

      <div class="section-header">
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
        <div class="game-grid">
          <?php foreach ($owned_games as $id => $game): ?>
          <div class="game-card" onclick="openGame('game.php?game=<?php echo urlencode($id); ?>')">
            <div class="game-card-img-wrapper">
              <img src="<?php echo $game['image']; ?>" alt="<?php echo htmlspecialchars($game['title']); ?>">
            </div>
            <div class="game-card-info">
              <h3><?php echo htmlspecialchars($game['title']); ?></h3>
              <p class="game-card-desc"><?php echo htmlspecialchars($game['bio']); ?></p>
              <div class="game-card-meta">
                <span class="game-card-genre"><?php echo htmlspecialchars($game['genre']); ?></span>
                <span style="color: var(--purple); font-weight: 700;">Owned</span>
              </div>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>
  </main>

  <!-- Footer -->
  <footer>
    <p>&copy; 2026 GameStore. All rights reserved.</p>
  </footer>
</body>
</html>
