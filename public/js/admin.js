document.addEventListener('DOMContentLoaded', () => {
    const notificationBtn = document.getElementById('notificationBtn');
    const notificationPanel = document.getElementById('notificationPanel');
    const profileBtn = document.getElementById('profileBtn');
    const settingsPanel = document.getElementById('settingsPanel');

    function togglePanel(panel, btn) {
        // Close others
        [notificationPanel, settingsPanel].forEach(p => {
            if (p !== panel && p && !p.classList.contains('d-none')) {
                p.classList.add('d-none');
            }
        });

        // Toggle current
        if (panel.classList.contains('d-none')) {
            panel.classList.remove('d-none');
        } else {
            panel.classList.add('d-none');
        }
    }

    if (notificationBtn && notificationPanel) {
        notificationBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            togglePanel(notificationPanel, notificationBtn);
        });
    }

    if (profileBtn && settingsPanel) {
        profileBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            togglePanel(settingsPanel, profileBtn);
        });
    }

    // Close on click outside
    document.addEventListener('click', (e) => {
        if (notificationPanel && !notificationPanel.classList.contains('d-none') && !notificationPanel.contains(e.target)) {
            notificationPanel.classList.add('d-none');
        }
        if (settingsPanel && !settingsPanel.classList.contains('d-none') && !settingsPanel.contains(e.target)) {
            settingsPanel.classList.add('d-none');
        }
    });

    // Prevent closing when clicking inside panels
    if (notificationPanel) notificationPanel.addEventListener('click', e => e.stopPropagation());
    if (settingsPanel) settingsPanel.addEventListener('click', e => e.stopPropagation());
});
