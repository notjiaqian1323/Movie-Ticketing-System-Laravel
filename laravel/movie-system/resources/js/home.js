document.addEventListener('DOMContentLoaded', () => {
  const posterCarousel = document.getElementById('posterCarousel');
  if (!posterCarousel) return;

  const posters = Array.from(posterCarousel.querySelectorAll('.poster-card'));
  const bg = document.getElementById('popularBg');
  let currentIndex = 0;
  let autoSlideInterval = null;

  // V-shape positions (center is index 2)
  const positions = [
    "translate(-220px, -100px) rotate(-15deg) scale(0.85)",
    "translate(-120px, 50px) rotate(-8deg) scale(0.95)",
    "translate(0px, 150px) scale(1.1)",   // center (active)
    "translate(120px, 50px) rotate(8deg) scale(0.95)",
    "translate(220px, -100px) rotate(15deg) scale(0.85)"
  ];

  function updatePositions() {
    if (posters.length === 0) return;
    let centerIdx = null;

    posters.forEach((poster, i) => {
      // map poster i to a position slot relative to currentIndex
      const posIndex = (i - currentIndex + posters.length) % posters.length;
      // pick position from `positions`. If posters.length > positions.length, positions repeat by modulo.
      const transform = positions[posIndex % positions.length] || positions[2];
      poster.style.transform = transform;
      poster.style.zIndex = (posIndex === 2) ? 100 : (20 - Math.abs(2 - posIndex));
      poster.classList.toggle('active', posIndex === 2);
      if (posIndex === 2) centerIdx = i;
    });

    // sync background and Alpine index to center poster
    if (centerIdx !== null) {
      const centerPoster = posters[centerIdx];
      const bgUrl = centerPoster.dataset.bg;
      if (bg && bgUrl) bg.style.backgroundImage = `url(${bgUrl})`;

      // tell Alpine to show the same slide (Alpine listens for 'set-index' window event)
      window.dispatchEvent(new CustomEvent('set-index', { detail: centerIdx }));
    }
  }

  function nextPoster() {
    currentIndex = (currentIndex + 1) % posters.length;
    updatePositions();
  }

  function startAutoSlide() {
    clearInterval(autoSlideInterval);
    autoSlideInterval = setInterval(nextPoster, 4000);
  }

  // Clicking a poster centers it (compute currentIndex so that clicked poster becomes center)
  posters.forEach((poster, index) => {
    poster.addEventListener('click', (e) => {
      // Determine new currentIndex that makes clicked poster the center (posIndex === 2)
      currentIndex = (index - 2 + posters.length) % posters.length;
      updatePositions();
      startAutoSlide();
    });
  });

  // init
  updatePositions();
  startAutoSlide();
});
