document.addEventListener("DOMContentLoaded", function () {
    let offset = 0;
    let activeYear = "all";
    const loadMoreBtn = document.getElementById("load-more-btn");
    const galleryContainer = document.getElementById("gallery-container");
    const filterButtons = document.querySelectorAll(".filter-btn");
  
    function loadAlbums() {
      let url = `load-more.php?offset=${offset}`;
      if (activeYear !== "all") {
        url += `&year=${activeYear}`;
      }
  
      fetch(url)
        .then(res => res.text())
        .then(data => {
          if (offset === 0) {
            galleryContainer.innerHTML = ""; // при нов филтър изчистваме старите
          }
  
          if (data.trim() === "") {
            loadMoreBtn.style.display = "none";
          } else {
            galleryContainer.insertAdjacentHTML("beforeend", data);
            offset += 8;
            loadMoreBtn.disabled = false;
            loadMoreBtn.textContent = "Зареди още";
            loadMoreBtn.style.display = "block";
          }
        })
        .catch(err => {
          console.error("Грешка:", err);
          loadMoreBtn.disabled = false;
          loadMoreBtn.textContent = "Грешка. Опитай отново.";
        });
    }
    const filterList = document.getElementById("filter-list");

// Зареждаме годините от базата
fetch("get-years.php")
  .then(res => res.text())
  .then(data => {
    filterList.innerHTML = data;

    // Прикачваме кликове към генерираните бутони
    document.querySelectorAll(".filter-btn").forEach(btn => {
      btn.addEventListener("click", function (e) {
        e.preventDefault();
        document.querySelectorAll(".filter-btn").forEach(b => b.classList.remove("active"));
        btn.classList.add("active");

        activeYear = btn.dataset.year;
        offset = 0;
        loadAlbums();
      });
    });
  });

  
    loadMoreBtn.addEventListener("click", function (e) {
      e.preventDefault();
      loadMoreBtn.disabled = true;
      loadMoreBtn.textContent = "Зареждане...";
      loadAlbums();
    });
  
    filterButtons.forEach(btn => {
      btn.addEventListener("click", function (e) {
        e.preventDefault();
        filterButtons.forEach(b => b.classList.remove("active"));
        btn.classList.add("active");
  
        activeYear = btn.dataset.year;
        offset = 0;
        loadAlbums();
      });
    });
  
    loadAlbums(); // зарежда ALL по подразбиране
  });
  