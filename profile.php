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

// Dynamic details
$username = isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : "Gamer";
$email = isset($_SESSION['email']) ? htmlspecialchars($_SESSION['email']) : "gamer@gamestore.com";

// Calculate stats dynamically
$games_owned_count = count($owned_games);
$hours_played = $games_owned_count * 45; // Dynamic estimation
$achievements_count = $games_owned_count * 3; // Dynamic estimation
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Profile - GameStore</title>
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
      <a href="profile.php" class="active">Profile</a>
      <!-- Cart navigation link with dynamic item counter -->
      <a href="cart.php" class="nav-cart-link">
        Cart <span class="cart-badge" id="cartBadge"><?php echo count($_SESSION['cart']); ?></span>
      </a>
      <!-- ✅ Logout button -->
      <a href="login.php?logout=true" style="color:#ff007f; font-weight:bold;">Logout</a>
    </nav>
  </header>

  <!-- Profile Section -->
  <main class="profile-container">
    <!-- Banner -->
    <div class="profile-banner">
      <img src="images/banner.jpg" alt="Profile Banner">
    </div>

    <!-- Profile Header Info -->
    <div class="profile-header-card">
      <img src="images/avatar.png" alt="User Avatar" class="profile-avatar">
      <h2><?php echo $username; ?></h2>
      <p class="tagline">Level 15 | Gamer since 2026</p>
      <p class="email">Email: <?php echo $email; ?></p>
      <button class="edit-btn">Edit Profile</button>
    </div>

    <!-- Stats Section -->
    <section class="profile-stats">
      <div class="stat-card">
        <h3>Games Owned</h3>
        <p><?php echo $games_owned_count; ?></p>
      </div>
      <div class="stat-card">
        <h3>Hours Played</h3>
        <p><?php echo $hours_played; ?>h</p>
      </div>
      <div class="stat-card">
        <h3>Achievements</h3>
        <p><?php echo $achievements_count; ?></p>
      </div>
    </section>

    <!-- Achievements Section -->
    <section class="achievements">
      <h2 class="section-title">Achievements</h2>
      <div class="achievement-grid">
        <div class="achievement">
          <img src="images/trophy.png" alt="Trophy">
          <p>First Win</p>
        </div>
        <div class="achievement">
          <img src="images/collector.png" alt="Trophy">
          <p>Collector</p>
        </div>
        <div class="achievement">
          <img src="images/speedrun.png" alt="Trophy">
          <p>Marathon Gamer</p>
        </div>
      </div>
    </section>

    <!-- Recently Played Section (Dynamic) -->
    <section class="recently-played">
      <h2 class="section-title">Recently Played</h2>
      <?php if ($games_owned_count == 0): ?>
        <p style="color: var(--text-secondary); text-align: center;">No games played recently. Start playing some games from your library!</p>
      <?php else: ?>
        <div class="recent-grid">
          <?php 
          $recent_count = 0;
          foreach ($owned_games as $id => $game): 
            if ($recent_count >= 3) break;
          ?>
            <div class="recent-card" onclick="openGame('game.php?game=<?php echo urlencode($id); ?>')">
              <img src="<?php echo $game['image']; ?>" alt="<?php echo htmlspecialchars($game['title']); ?>">
              <p><?php echo htmlspecialchars($game['title']); ?></p>
            </div>
          <?php 
            $recent_count++;
          endforeach; 
          ?>
        </div>
      <?php endif; ?>
    </section>
  </main>

  <!-- Footer -->
  <footer>
    <p>&copy; 2026 GameStore. All rights reserved.</p>
  </footer>
</body>
</html>
