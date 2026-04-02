let atvCurrentIndex = 0;
const atvSlides = document.querySelectorAll('.atv-slide');
const atvDots = document.querySelectorAll('.atv-dot');
const atvTrack = document.getElementById('atvTrack');
const atvTotal = atvSlides.length;

function atvGoTo(index) {
    if (index < 0) index = atvTotal - 1;
    if (index >= atvTotal) index = 0;
    atvCurrentIndex = index;

    // Update slides
    atvSlides.forEach((slide, i) => {
        slide.classList.toggle('active', i === index);
    });

    // Update dots
    atvDots.forEach((dot, i) => {
        dot.classList.toggle('active', i === index);
    });

    // Move track
    const slideWidth = 820; // 800 + 20 gap
    const offset = -index * slideWidth;
    atvTrack.style.transform = `translateX(${offset}px)`;
}

function atvNext() { atvGoTo(atvCurrentIndex + 1); }
function atvPrev() { atvGoTo(atvCurrentIndex - 1); }

// Auto-advance every 5 seconds
setInterval(() => atvNext(), 5000);