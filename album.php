<?php
include 'admin/config.php';

$gallery_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Взимаме данни за галерията
$gallery_stmt = $conn->prepare("SELECT * FROM galleries WHERE id = ?");
$gallery_stmt->bind_param("i", $gallery_id);
$gallery_stmt->execute();
$gallery = $gallery_stmt->get_result()->fetch_assoc();

if (!$gallery) {
  echo "<h2>Галерията не е намерена.</h2>";
  exit;
}

// Взимаме коричната снимка
$cover_stmt = $conn->prepare("SELECT image_path FROM photos WHERE gallery_id = ? AND is_cover = 1 LIMIT 1");
$cover_stmt->bind_param("i", $gallery_id);
$cover_stmt->execute();
$cover = $cover_stmt->get_result()->fetch_assoc();
$cover_image = $cover ? 'admin/' . $cover['image_path'] : 'images/default-cover.jpg';

// Взимаме всички снимки от албума
$photos_stmt = $conn->prepare("SELECT * FROM photos WHERE gallery_id = ?");
$photos_stmt->bind_param("i", $gallery_id);
$photos_stmt->execute();
$photos = $photos_stmt->get_result();
?>
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
    <link rel="stylesheet" href="lightbox2/dist/css/lightbox.min.css">
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
              <li><a href="albums.php">Албуми</a></li>
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
          <li><a href="albums.php">Албуми</a></li>
          <li><a href="#">Блог</a></li>
          <li><a href="#">Контакти</a></li>
        </ul>
      </div>

      <!-- Основно съдържание -->
      <main class="main-container">
         <!-- Заглавие и фонова снимка от корицата -->
    <section class="page-banner" style="background-image: url('<?php echo $cover_image; ?>'); background-size: cover;">
      <div class="banner-overlay">
        <h1><?php echo htmlspecialchars($gallery['name']); ?></h1>
      </div>
    </section>
        
    <section class="gallery masonry-gallery">
      <?php while ($photo = $photos->fetch_assoc()): ?>
        <div class="masonry-item">
        <a href="admin/<?php echo $photo['image_path']; ?>" data-lightbox="example-set" data-title="<?php echo htmlspecialchars($photo['title']); ?>">
            <img src="admin/<?php echo $photo['image_path']; ?>" alt="">
        </a>
        </div>
      <?php endwhile; ?>
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
    <script src="lightbox2/dist/js/lightbox-plus-jquery.min.js"></script>
  </body>
</html>
