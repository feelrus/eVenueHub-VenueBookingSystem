document.addEventListener("DOMContentLoaded", () => {
  const collapsibles = document.querySelectorAll(".collapsible");

  collapsibles.forEach((button) => {
    button.addEventListener("click", () => {
      const content = button.nextElementSibling;

      if (content.style.maxHeight) {
        content.style.maxHeight = null;
      } else {
        document.querySelectorAll(".content").forEach((panel) => {
          panel.style.maxHeight = null;
        });
        content.style.maxHeight = content.scrollHeight + "px";
      }
    });
  });

  // File validation
  const imageInput = document.getElementById("image");
  const imageError = document.getElementById("imageError");

  imageInput.addEventListener("change", () => {
    const file = imageInput.files[0];
    if (file) {
      const allowedTypes = ["image/jpeg", "image/png", "image/gif"];
      const maxSize = 5 * 1024 * 1024; // 5 MB

      if (!allowedTypes.includes(file.type)) {
        imageError.textContent =
          "Please upload an image file (JPG, JPEG, or PNG).";
        imageError.style.display = "block";
        imageInput.value = ""; // Clear the input
      } else if (file.size > maxSize) {
        imageError.textContent = "File size must be less than 5 MB.";
        imageError.style.display = "block";
        imageInput.value = ""; // Clear the input
      } else {
        imageError.style.display = "none"; // Hide error if file is valid
      }
    }
  });

  document.getElementById("image").addEventListener("change", function (event) {
    const file = event.target.files[0];
    const imageError = document.getElementById("imageError");
    const imagePreview = document.getElementById("imagePreview");

    if (file) {
      if (!file.type.startsWith("image/")) {
        imageError.textContent = "Please upload a valid image file.";
        imagePreview.style.display = "none";
      } else {
        imageError.textContent = "";
        const reader = new FileReader();
        reader.onload = function (e) {
          imagePreview.src = e.target.result;
          imagePreview.style.display = "block";
        };
        reader.readAsDataURL(file);
      }
    } else {
      imagePreview.style.display = "none";
    }
  });

});
