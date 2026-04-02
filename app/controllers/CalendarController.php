<?php

class CalendarController extends Controller {
    public function index() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        require '../app/config/database.php';
        
        // Always use standard layout (no admin sidebar) for calendar
        $layout = 'user';

        // Fetch categories for filter
        $catStmt = $pdo->query("SELECT * FROM categories");
        $categories = $catStmt->fetchAll(PDO::FETCH_ASSOC);

        $data = [
            'title' => 'Event Calendar',
            'current_page' => 'calendar',
            'layout' => $layout,
            'categories' => $categories
        ];
        
        $this->view('calendar/index', $data);
    }

    public function get_events() {
        require '../app/config/database.php';
        
        // Fetch all active events
        // FullCalendar expects: id, title, start, end, url (optional)
        $stmt = $pdo->query("SELECT id, titre as title, date_evenement as start, categorie_id FROM evenements");
        $events = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Color mapping for categories
        $colors = [
            1 => '#0071e3', // Blue
            2 => '#34c759', // Green
            3 => '#ff9f0a', // Orange
            4 => '#ff3b30', // Red
            5 => '#af52de', // Purple
            6 => '#5856d6'  // Indigo
        ];

        $formattedEvents = [];
        foreach ($events as $event) {
            $catId = $event['categorie_id'];
            $color = $colors[$catId] ?? '#8e8e93'; // Gray default

            $formattedEvents[] = [
                'id' => $event['id'],
                'title' => $event['title'],
                'start' => $event['start'],
                'backgroundColor' => $color,
                'borderColor' => $color,
                'url' => '/python/public/store/event/' . $event['id'],
                'categoryId' => $catId
            ];
        }

        header('Content-Type: application/json');
        echo json_encode($formattedEvents);
    }
}
