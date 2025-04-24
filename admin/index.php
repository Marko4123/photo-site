<?php include 'config.php'; ?>
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
    <link rel="stylesheet" href="../css/style.css" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  </head>
  <body>
    <div class="wrapper">
      

      <!-- Навигация -->
      <header class="site-header">
        <div class="nav-container">
          <!-- Лого вляво -->
          <div class="header-left">
            <a href="index.html"
              ><img src="../images/logo.png" alt="Лого" class="logo"
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
        <div class="form-card">
            <form method="POST" enctype="multipart/form-data">
                <input type="text" name="name" placeholder="Име на галерията" required><br><br>
                <input type="number" name="year" placeholder="Година (напр. 2024)" required><br><br>
                <div id="drop-zone" class="drop-zone">
                  <p>Провлачи снимки тук или <label for="fileInput" class="file-label">избери файлове</label></p>
                  <input type="file" id="fileInput" name="photos[]" multiple hidden>
                  <div id="preview" class="preview-gallery"></div>
                </div>
                <button type="submit" name="submit" class="load-more-btn">Създай галерия и качи снимки</button>
            </form>
            <?php
              function transliterate($text) {
                $text = mb_strtolower($text, 'UTF-8');
                $cyr = ['а','б','в','г','д','е','ж','з','и','й','к','л','м','н','о','п','р','с','т','у','ф','х','ц','ч','ш','щ','ъ','ь','ю','я','ё','э'];
                $lat = ['a','b','v','g','d','e','zh','z','i','y','k','l','m','n','o','p','r','s','t','u','f','h','ts','ch','sh','sht','a','y','yu','ya','yo','e'];
                return preg_replace('/[^a-z0-9_-]/', '-', str_replace($cyr, $lat, $text));
            }
            
            if (isset($_POST['submit'])) {
                $name = $conn->real_escape_string($_POST['name']);
                $year = intval($_POST['year']);
                $cover_file_name = $_POST['cover_file_name'] ?? null;
            
                // Създаване на директория
                $safe_folder = transliterate($name);
                $folder = "uploads/" . $safe_folder;
                if (!is_dir($folder)) {
                    mkdir($folder, 0755, true);
                }
            
                // Създаване на галерия
                $sql = "INSERT INTO galleries (name, year) VALUES ('$name', $year)";
                if ($conn->query($sql)) {
                    $gallery_id = $conn->insert_id;
            
                    foreach ($_FILES['photos']['tmp_name'] as $key => $tmp_name) {
                        $fileName = basename($_FILES['photos']['name'][$key]);
                        $targetPath = $folder . "/" . $fileName;
                        $error_code = $_FILES['photos']['error'][$key];
            
                        if ($error_code !== UPLOAD_ERR_OK) {
                            echo "<p style='color:red;'>Грешка при качване на $fileName – код: $error_code</p>";
                            continue;
                        }
                        $is_cover = ($_FILES['photos']['name'][$key] === $cover_file_name) ? 1 : 0;
            
                        if (move_uploaded_file($tmp_name, $targetPath)) {
                            $insert_sql = "INSERT INTO photos (gallery_id, title, image_path, is_cover) 
                                           VALUES ($gallery_id, '$fileName', '$targetPath', $is_cover)";
                            if (!$conn->query($insert_sql)) {
                                echo "<p style='color:red;'>Грешка при запис в DB за $fileName: " . $conn->error . "</p>";
                            }
                        } else {
                            echo "<p style='color:red;'>Неуспешно качване на $fileName</p>";
                        }
                    }
            
                    echo "<p style='color:green;'>Галерията и снимките са създадени успешно!</p>";
                } else {
                    echo "<p style='color:red;'>Грешка при създаване на галерия: " . $conn->error . "</p>";
                }
            }
            ?>
        </div>
      </main>
      <!-- Footer -->
      <footer class="site-footer">
        <div class="footer-container">
          <p>&copy; 2025 Моят Свят. Всички права запазени.</p>
        </div>
      </footer>
    </div>
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
<script src="../js/preview.js"></script>
  </body>
</html>
