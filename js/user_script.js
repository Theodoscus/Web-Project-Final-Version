const reviewList = document.getElementById("review-list");
const reviewButton = document.getElementById("review-button");
const reviewModal = document.getElementById("review-modal");
const publishButton = document.getElementById("publish-button");
const cancelButton = document.getElementById("cancel-button");
const closeButton = document.getElementsByClassName("close")[0];
const nameInput = document.getElementById("name-input");
const reviewInput = document.getElementById("review-input");

// Add event listener to review button to show modal
reviewButton.addEventListener("click", () => {
  reviewModal.style.display = "block";
  reviewInput.value = "";
});

function publishReview() {
  const review = reviewInput.value;
  const name = nameInput.value;

  // Check for HTML tags in review input
  const htmlRegex = /<\s*[a-zA-Z]+.*?>/g;
  if (htmlRegex.test(review)) {
    alert("You can't write HTML tags, please remove them.");
    return;
  }

  // Create review elements
  const li = document.createElement("li");
  const slide = document.createElement("div");
  const img = document.createElement("img");
  const p = document.createElement("p");
  const stars = document.createElement("div");
  const h3 = document.createElement("h3");

  // Set values and attributes for review elements
  slide.className = "swiper-slide slide";
  img.src = "images/pic-1.png";
  img.alt = name;
  p.textContent = review;
  stars.className = "stars";
  h3.textContent = name;

  for (let i = 0; i < 5; i++) {
    const star = document.createElement("i");
    star.className = i < 4 ? "fas fa-star" : "fas fa-star-half-alt";
    stars.appendChild(star);
  }

  // Append review elements to list
  slide.appendChild(img);
  slide.appendChild(p);
  stars.appendChild(h3);
  stars.appendChild(h3);
  slide.appendChild(stars);
  slide.appendChild(h3);
  li.appendChild(slide);
  reviewList.appendChild(li);

  // Clear review input
  reviewInput.value = "";

  // Close review modal
  reviewModal.style.display = "none";
}

// Add event listener to publish button
publishButton.addEventListener("click", publishReview);

// Add event listener to cancel button to hide modal
cancelButton.addEventListener("click", () => {
  reviewModal.style.display = "none";
});

// Add event listener to close button to hide modal
closeButton.addEventListener("click", () => {
  reviewModal.style.display = "none";
});

// Add event listener to review input to check for HTML tags
reviewInput.addEventListener("input", () => {
  const review = reviewInput.value;
  const htmlRegex = /<\s*[a-zA-Z]+.*?>/g;
  if (htmlRegex.test(review)) {
    alert("You can't write HTML tags, please remove them.");
    reviewInput.value = review.replace(htmlRegex, "");
  }
});

// Initialize the swiper
const reviewsSlider = new Swiper(".reviews-slider", {
  loop: true,
  spaceBetween: 20,
  pagination: {
    el: ".swiper-pagination",
    clickable: true,
  },
});

// Close review modal
reviewModal.style.display = "none";
