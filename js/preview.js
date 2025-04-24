const dropZone = document.getElementById("drop-zone");
const fileInput = document.getElementById("fileInput");
const preview = document.getElementById("preview");
let selectedFiles = [];

// Обработка на избрани файлове
function handleFiles(files) {
  for (const file of files) {
    selectedFiles.push(file);
    const reader = new FileReader();
    reader.onload = e => {
      const thumb = document.createElement("div");
      thumb.classList.add("thumb");
      thumb.setAttribute("data-filename", file.name); // <<< НОВО: оригинално име на файла

      thumb.innerHTML = `
        <img src="${e.target.result}" alt="">
        <button type="button" class="remove">&times;</button>
        <button type="button" class="set-cover" title="Направи корица">&#9733;</button>
      `;

      // Премахване на снимка
      thumb.querySelector(".remove").addEventListener("click", (ev) => {
        ev.stopPropagation();
        preview.removeChild(thumb);
        selectedFiles = selectedFiles.filter(f => f !== file);
        updateFileInput();
        updateCoverInput(); // Обновяваме и корицата
      });

      // Маркиране като корица
      thumb.querySelector(".set-cover").addEventListener("click", () => {
        document.querySelectorAll(".set-cover").forEach(btn => btn.classList.remove("active"));
        thumb.querySelector(".set-cover").classList.add("active");
        thumb.setAttribute("data-cover", "true");
        document.querySelectorAll(".thumb").forEach(other => {
          if (other !== thumb) other.removeAttribute("data-cover");
        });
        updateCoverInput();
      });

      preview.appendChild(thumb);
      updateFileInput();
    };
    reader.readAsDataURL(file);
  }
}

// Обновява real input с файловете от selectedFiles
function updateFileInput() {
  const dataTransfer = new DataTransfer();
  selectedFiles.forEach(f => dataTransfer.items.add(f));
  fileInput.files = dataTransfer.files;
}

// Скрит input с името на избраната за корица снимка
function updateCoverInput() {
  let existing = document.getElementById("cover_file_name");
  if (!existing) {
    existing = document.createElement("input");
    existing.type = "hidden";
    existing.name = "cover_file_name";
    existing.id = "cover_file_name";
    document.querySelector("form").appendChild(existing);
  }
  const selected = document.querySelector(".thumb[data-cover='true']");
  existing.value = selected ? selected.getAttribute("data-filename") : "";
}

// Drag & Drop събития
dropZone.addEventListener("dragover", e => {
  e.preventDefault();
  dropZone.classList.add("hover");
});
dropZone.addEventListener("dragleave", () => {
  dropZone.classList.remove("hover");
});
dropZone.addEventListener("drop", e => {
  e.preventDefault();
  dropZone.classList.remove("hover");
  handleFiles(e.dataTransfer.files);
});

// Избор чрез стандартен бутон
fileInput.addEventListener("change", () => {
  handleFiles(fileInput.files);
});

// Само при клик върху текстов линк
document.querySelector(".file-label").addEventListener("click", e => {
  e.preventDefault();
  fileInput.click();
});