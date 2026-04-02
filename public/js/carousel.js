document.addEventListener('DOMContentLoaded', () => {
    // Select all carousel wrappers
    const carousels = document.querySelectorAll('.js-carousel');

    carousels.forEach(wrapper => {
        const container = wrapper.querySelector('.carousel-container');
        const prevBtn = wrapper.querySelector('.prev-btn');
        const nextBtn = wrapper.querySelector('.next-btn');

        const scrollAmount = 500; // Adjusted for larger card width (480px + gap)

        if (nextBtn && container) {
            nextBtn.addEventListener('click', () => {
                container.scrollBy({
                    left: scrollAmount,
                    behavior: 'smooth'
                });

                // Optional Looping Logic Check
                setTimeout(() => {
                    if (Math.ceil(container.scrollLeft + container.clientWidth) >= container.scrollWidth) {
                        // container.scrollTo({left: 0, behavior: 'smooth'}); 
                        // User asked for "loop all the card and return to the first" for the *first* carousel.
                        // We can apply it generally or selectively.
                        container.scrollTo({ left: 0, behavior: 'smooth' });
                    }
                }, 500);
            });
        }

        if (prevBtn && container) {
            prevBtn.addEventListener('click', () => {
                container.scrollBy({
                    left: -scrollAmount,
                    behavior: 'smooth'
                });
            });
        }
    });
});
