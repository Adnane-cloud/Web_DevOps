document.addEventListener('DOMContentLoaded', function () {
    const filterButtons = document.querySelectorAll('.js-category-filter');
    const categorySections = document.querySelectorAll('.js-category-section');
    const noEventsMsg = document.getElementById('no-events-msg');

    filterButtons.forEach(btn => {
        btn.addEventListener('click', function () {
            const categoryId = this.dataset.categoryId.trim();
            const isActive = this.classList.contains('active');

            // Reset all buttons
            filterButtons.forEach(b => {
                b.classList.remove('active');
                b.style.transform = 'scale(1)';
                b.style.opacity = '0.6';
            });

            // Always hide no-events message when switching
            if (noEventsMsg) noEventsMsg.classList.add('d-none');

            if (isActive) {
                // Clicking active one = show all
                categorySections.forEach(section => {
                    section.classList.remove('d-none');
                    section.classList.add('d-block');
                });

                // Reset buttons opacity
                filterButtons.forEach(b => {
                    b.style.opacity = '1';
                });
            } else {
                // Activate this button
                this.classList.add('active');
                this.style.transform = 'scale(1.2)';
                this.style.opacity = '1';

                const targetSection = document.getElementById('category-section-' + categoryId);

                // Hide all sections first
                categorySections.forEach(section => {
                    section.classList.remove('d-block');
                    section.classList.add('d-none');
                });

                if (targetSection) {
                    // Category has events
                    targetSection.classList.remove('d-none');
                    targetSection.classList.add('d-block');
                    targetSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
                } else {
                    // Category is empty - show message
                    if (noEventsMsg) {
                        noEventsMsg.classList.remove('d-none');
                        noEventsMsg.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                }
            }
        });
    });
});
