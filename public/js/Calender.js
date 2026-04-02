document.addEventListener('DOMContentLoaded', function () {
    var calendarEl = document.getElementById('calendar');
    var allEvents = [];
    var currentFilter = 'all';

    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,listWeek'
        },
        themeSystem: 'standard',
        height: 'auto',
        contentHeight: 700,
        aspectRatio: 1.8,
        handleWindowResize: true,
        stickyHeaderDates: true,

        // Fetch Events
        events: function (fetchInfo, successCallback, failureCallback) {
            fetch('/calendar/api/events')
                .then(response => response.json())
                .then(data => {
                    allEvents = data;
                    successCallback(filterEvents(data, currentFilter));
                })
                .catch(error => failureCallback(error));
        },

        // Interaction
        editable: false,
        droppable: false,

        eventClick: function (info) {
            info.jsEvent.preventDefault();
            showEventModal(info.event);
        }
    });
    calendar.render();

    // 1. Upcoming Events Logic
    function updateUpcomingEvents(events) {
        const container = document.getElementById('upcomingEventsList');
        const today = new Date();

        // Filter future events and sort by date
        const futureEvents = events
            .filter(e => new Date(e.start) >= today)
            .sort((a, b) => new Date(a.start) - new Date(b.start))
            .slice(0, 3); // Take top 3

        if (futureEvents.length === 0) {
            container.innerHTML = '<div class="text-center py-2 text-muted small">No upcoming events</div>';
            return;
        }

        let html = '';
        futureEvents.forEach(e => {
            const date = new Date(e.start);
            const month = date.toLocaleString('default', { month: 'short' });
            const day = date.getDate();

            html += `
                    <div class="upcoming-event-item">
                        <div class="upcoming-date-box">
                            <span class="upcoming-month">${month}</span>
                            <span class="upcoming-day">${day}</span>
                        </div>
                        <div style="flex:1; min-width:0;">
                            <h6 class="mb-0 fw-bold text-truncate" style="font-size:14px;">${e.title}</h6>
                            <small class="text-muted" style="font-size:12px;">${date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })}</small>
                        </div>
                    </div>
                `;
        });
        container.innerHTML = html;
    }

    // Hook into fetch success
    const originalFetch = calendar.getOption('events');
    calendar.setOption('events', function (fetchInfo, successCallback, failureCallback) {
        fetch('/calendar/api/events')
            .then(res => res.json())
            .then(data => {
                allEvents = data;
                updateUpcomingEvents(data); // Update sidebar
                successCallback(filterEvents(data, currentFilter));
            })
            .catch(err => failureCallback(err));
    });

    // 2. Date Jumper
    const dateJumper = document.getElementById('dateJumper');
    dateJumper.addEventListener('change', function () {
        if (this.value) {
            calendar.gotoDate(this.value);
        }
    });

    // 3. Sync Button Mock
    document.getElementById('syncBtn').addEventListener('click', function () {
        const btn = this;
        const originalText = btn.innerHTML;
        btn.innerHTML = '<i class="bi bi-check2"></i> Synced!';
        setTimeout(() => {
            btn.innerHTML = originalText;
        }, 2000);
    });

    // 4. Modal Functions
    window.showEventModal = function (event) {
        const modal = document.getElementById('eventGlassModal');
        const date = event.start.toLocaleString([], { weekday: 'long', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit' });

        document.getElementById('modalTitle').innerText = event.title;
        document.getElementById('modalDate').innerText = date;
        document.getElementById('modalDesc').innerText = event.extendedProps.description || 'No description available.';
        document.getElementById('modalLink').href = event.url || '#';

        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    window.closeEventModal = function () {
        document.getElementById('eventGlassModal').classList.remove('active');
        document.body.style.overflow = '';
    }

    // Close on outside click
    document.getElementById('eventGlassModal').addEventListener('click', function (e) {
        if (e.target === this) closeEventModal();
    });

    // Escape key close
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') closeEventModal();
    });

    // Filter function
    function filterEvents(events, categoryId) {
        if (categoryId === 'all') return events;
        return events.filter(e => e.categoryId == categoryId);
    }

    // Category filter click handlers
    document.querySelectorAll('.cat-pill').forEach(pill => {
        pill.addEventListener('click', function () {
            // Update active state
            document.querySelectorAll('.cat-pill').forEach(p => p.classList.remove('active'));
            this.classList.add('active');

            // Update filter and refetch
            currentFilter = this.dataset.category;
            calendar.refetchEvents();
        });
    });
});