
<header class="d-flex justify-content-center align-items-center mb-5 pt-4" style="margin-top: 120px;">
    <div class="glass-greeting-pill px-5 py-4 text-center" style="min-width: 600px;">
        <div class="d-flex flex-column align-items-center">
            <h1 class="display-5 fw-bold mb-1 text-dark" id="greetingText">Dashboard</h1>
            <div class="d-flex align-items-center gap-3">
                <span class="fs-4 text-secondary" id="clockTime">00:00:00</span>
                <span class="fs-4 text-secondary">|</span>
                <span class="fs-5 text-secondary" id="currentDate"><?= date('l, F j') ?></span>
            </div>
        </div>
    </div>
    
    <style>
        .glass-greeting-pill {
            background: rgba(255, 255, 255, 0.4);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.5);
            border-radius: 50px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }
        .glass-greeting-pill:hover {
            transform: scale(1.02);
            background: rgba(255, 255, 255, 0.5);
        }
    </style>
    
    <script>
        function updateClock() {
            const now = new Date();
            const timeElement = document.getElementById('clockTime');
            const greetingElement = document.getElementById('greetingText');
            
            if(timeElement) {
                timeElement.textContent = now.toLocaleTimeString('en-US', { hour12: false });
            }
            
            if(greetingElement) {
                const hour = now.getHours();
                let greeting = 'Good Evening';
                if (hour < 12) greeting = 'Good Morning';
                else if (hour < 18) greeting = 'Good Afternoon';
                
                // Only act if not already set or simple update needed
                // Preserving "Admin" or specific user name could be dynamic
                greetingElement.textContent = greeting + ", Admin";
            }
        }
        setInterval(updateClock, 1000);
        // Run immediately
        document.addEventListener('DOMContentLoaded', updateClock);
    </script>
</header>
