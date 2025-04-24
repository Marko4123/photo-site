<?php include 'admin/config.php'; ?>
<!DOCTYPE html>
<html lang="bg">
  <head>
    <meta charset="UTF-8" />
    <title>Добре дошъл в моя свят</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Exo+2:ital,wght@0,100..900;1,100..900&display=swap"
      rel="stylesheet"
    />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
    />
    <link rel="stylesheet" href="css/style.css" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  </head>
  <body>
    <div class="wrapper">
      <!-- Loading bar -->
      <div id="preloader">
        <img src="images/logo.png" alt="Лого" class="preload-logo" />
        <div class="loader-bar">
          <div class="bar"></div>
        </div>
        <p class="loading-text">Зареждане...</p>
      </div>

      <!-- Навигация -->
      <header class="site-header">
        <div class="nav-container">
          <!-- Лого вляво -->
          <div class="header-left">
            <a href="index.html"
              ><img src="images/logo.png" alt="Лого" class="logo"
            /></a>
          </div>

          <!-- Меню в центъра -->
          <nav class="header-center">
            <ul>
              <li><a href="index.html">Начало</a></li>
              <li><a href="albums.php" class="active">Албуми</a></li>
              <li><a href="#">Блог</a></li>
              <li><a href="#">Контакти</a></li>
            </ul>
          </nav>

          <!-- Иконки вдясно -->
          <div class="header-right">
            <a href="#"><i class="fab fa-instagram"></i></a>
            <a href="#"><i class="fab fa-facebook-f"></i></a>
            <a href="#"><i class="fab fa-pinterest-p"></i></a>
          </div>
          <div class="hamburger" id="hamburger">
            <span></span>
            <span></span>
            <span></span>
          </div>
        </div>
      </header>
      <!-- Мобилно меню -->
      <div class="mobile-menu" id="mobile-menu">
        <img src="images/logo.png" alt="Logo" class="mobile-logo" />
        <div class="mobile-top">
          <div class="close-btn" id="close-btn">&times;</div>
        </div>

        <div class="mobile-social">
          <a href="#"><i class="fab fa-instagram"></i></a>
          <a href="#"><i class="fab fa-facebook-f"></i></a>
          <a href="#"><i class="fab fa-pinterest-p"></i></a>
        </div>

        <ul class="mobile-links">
          <li><a href="index.html">Начало</a></li>
          <li><a href="albums.php" class="active">Албуми</a></li>
          <li><a href="#">Блог</a></li>
          <li><a href="#">Контакти</a></li>
        </ul>
      </div>

      <!-- Основно съдържание -->
      <main class="main-container">
        <section class="page-banner">
          <div class="banner-overlay">
            <h1>МОИТЕ АЛБУМИ</h1>
          </div>
        </section>
        <section class="filter-menu">
          <div class="filter-container">
            <ul class="filter-list" id="filter-list">
            
            </ul>
          </div>
        </section>
        <section class="gallery">
          <div class="gallery-container" id="gallery-container">
            <?php
            $limit = 8;
            $offset = isset($_GET['offset']) ? intval($_GET['offset']) : 0;
            
            $stmt = $conn->prepare("
                SELECT g.id, g.name, g.year, g.created_at, p.image_path
                FROM galleries g
                LEFT JOIN photos p ON g.id = p.gallery_id AND p.is_cover = 1
                ORDER BY g.created_at DESC
                LIMIT ? OFFSET ?
            ");
            $stmt->bind_param("ii", $limit, $offset);
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                echo '<div class="gallery-item">';
                echo '  <a href="album.php?id=' . $row['id'] . '">';
                echo '    <img src="admin/' . htmlspecialchars($row['image_path']) . '" alt="' . htmlspecialchars($row['name']) . '">';
                echo '    <div class="caption">';
                echo '      <span class="category">' . htmlspecialchars($row['year']) . '</span>';
                echo '      <h3>' . htmlspecialchars($row['name']) . '</h3>';
                echo '    </div>';
                echo '  </a>';
                echo '</div>';
            }
            ?>
          </div>
          <!-- Бутон за зареждане на още снимки -->
          <div class="load-more-wrapper">
            <button class="load-more-btn" id="load-more-btn" type="button">Зареди повече</button>
          </div>
        </section>
      </main>
      <!-- Footer -->
      <footer class="site-footer">
        <div class="footer-container">
          <p>&copy; 2025 Моят Свят. Всички права запазени.</p>
        </div>
      </footer>
    </div>
    <button id="scrollToTopBtn" title="Към началото">&#8679;</button>
    <script src="js/load.js"></script>
    <script src="js/script.js"></script>
    <script>
      const hamburger = document.getElementById("hamburger");
      const mobileMenu = document.getElementById("mobile-menu");
      const closeBtn = document.getElementById("close-btn");

      hamburger.addEventListener("click", () => {
        mobileMenu.classList.add("open");
        hamburger.classList.add("active"); // ☰ ➜ ✖
      });

      closeBtn.addEventListener("click", () => {
        mobileMenu.classList.remove("open");
        hamburger.classList.remove("active");
      });
    </script>
    <script>
      const scrollBtn = document.getElementById("scrollToTopBtn");

      window.addEventListener("scroll", () => {
        if (window.scrollY > 300) {
          scrollBtn.style.display = "block";
        } else {
          scrollBtn.style.display = "none";
        }
      });

      scrollBtn.addEventListener("click", () => {
        window.scrollTo({
          top: 0,
          behavior: "smooth",
        });
      });
    </script>
  </body>
</html>
