// Simple game database (dynamically populated by PHP)
if (typeof games === 'undefined') {
  window.games = {};
}

// Open game detail
function openGame(url) {
  window.location.href = url;
}

// Load game details on game.php
document.addEventListener("DOMContentLoaded", () => {
  const params = new URLSearchParams(window.location.search);
  const gameId = params.get("game");

  if (gameId && games[gameId]) {
    const game = games[gameId];
    const gallery = document.getElementById("gameGallery");

    if (gallery) {
      // Clear anything inside first
      gallery.innerHTML = "";

      // Add images dynamically
      game.images.forEach(imgPath => {
        const img = document.createElement("img");
        img.src = imgPath;
        img.alt = game.title;
        img.classList.add("game-img");
        gallery.appendChild(img);

        // Click event to open modal
        img.addEventListener("click", () => {
          const modal = document.getElementById("imgModal");
          const modalImg = document.getElementById("modalImg");
          const caption = document.getElementById("caption");

          if (modal && modalImg && caption) {
            modal.style.display = "block";
            modalImg.src = img.src;
            caption.innerText = img.alt;
          }
        });
      });
    }

    // Close modal setup
    const modal = document.getElementById("imgModal");
    const closeBtn = document.querySelector(".close");
    if (modal && closeBtn) {
      closeBtn.onclick = () => {
        modal.style.display = "none";
      };
    }
  }
});

