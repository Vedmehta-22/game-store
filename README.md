# GameStore - Digital Game Distribution Portal

GameStore is a premium, state-of-the-art gaming web portal reminiscent of Steam and Epic Games. Built using a modern cyberpunk theme, it integrates glassmorphism, responsive grid layouts, hover micro-interactions, and neon glow accents on top of secure database login and checkout flows.

---

## 🚀 Key Features

* **Secure Authentication & Session Management**:
  * Dual signup and login interfaces verifying credentials against a local database.
  * Encrypted user password hashes (`password_hash` with `PASSWORD_DEFAULT`).
  * Automated redirect rules protecting internal pages (`home.php`, `library.php`, `profile.php`, `game.php`) from unauthorized guest views.

* **Interactive Shopping Cart**:
  * Epic/Steam-like Shopping Cart where users can add paid games.
  * Running cost subtotal calculations, dynamic nav item badges (`Cart (X)`), and removal operations.
  * Complete checkout flow transferring cart games into the user's Library.

* **Game Library & Store Database**:
  * Loops over 14 popular games including custom generated high-quality covers.
  * Detail views displaying screenshots and custom detail cards.
  * Instant add-to-library option for free-to-play titles.

* **Dynamic Gamer Profile**:
  * Reflects logged-in session username and email.
  * Calculates owned games count, estimated achievements, and play hours based on active ownership.
  * Displays a "Recently Played" section filtering only games that you actually own.

---

## 🛠️ Tech Stack
* **Frontend**: HTML5, Vanilla CSS3 (Glassmorphism, custom variables, animations), JavaScript (ES6, dynamic grid layouts, overlay modals).
* **Backend**: PHP 8.x (Session variables, database query verification, shared data scripting).
* **Database**: MySQL / MariaDB.

---

## 📂 Project Directory Structure

```text
game-store/
│
├── images/               # Organized folder for game covers, screenshots, banner, and badges
├── cart.php              # Shopping cart and checkout page
├── database.sql          # MySQL database schema setup script
├── game.php              # Individual game descriptions and screenshots detail page
├── games_db.php          # Centralized store PHP inventory database
├── home.php              # Main landing store page
├── library.php           # User library page (filtering owned games)
├── login.php             # Session start / Login & Signup processing page
├── profile.php           # User stats and gamer profile page
├── signup.php            # Standalone registration handler
├── script.js             # Client-side screenshot zoom modal and dynamic library helpers
├── style.css             # Main unified cyberpunk design stylesheet
└── README.md             # Project documentation and guide
```

---

## ⚙️ Installation & Local Setup

To run this project locally, you will need a local environment server like **XAMPP**:

### Step 1: Place Files in htdocs
Copy the entire `game-store` project directory and paste it inside XAMPP's `htdocs` folder:
`C:\xampp\htdocs\game-store`

### Step 2: Start XAMPP Services
1. Open the **XAMPP Control Panel**.
2. Click **Start** for **Apache** (web server).
3. Click **Start** for **MySQL** (database server).

### Step 3: Setup the Database
1. Open your browser and navigate to: `http://localhost/phpmyadmin/`
2. Click **New** in the sidebar to create a new database.
3. Name it exactly **`gamestore`** and click **Create**.
4. Click on the `gamestore` database, click the **Import** tab at the top.
5. Click **Choose File** and select `database.sql` from your project folder.
6. Click **Import** (or **Go**). This will construct the `users` table.

### Step 4: Run the Application
Open your browser and navigate to:
👉 **`http://localhost/game-store/login.php`**
