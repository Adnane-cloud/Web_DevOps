<?php

class HomeController extends Controller {
    public function index() {
        require '../app/config/database.php';
        
        // Fetch Categories (for grid)
        $catStmt = $pdo->query("SELECT * FROM categories LIMIT 6");
        $categories = $catStmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Fetch Featured Events (for carousel)
        $eventStmt = $pdo->query("
            SELECT e.*, c.nom as category_name 
            FROM evenements e 
            LEFT JOIN categories c ON e.categorie_id = c.id 
            WHERE e.est_cloture = 0 
            ORDER BY e.date_evenement ASC 
            LIMIT 8
        ");
        $events = $eventStmt->fetchAll(PDO::FETCH_ASSOC);
        
        $data = [
            'title' => 'Eventium',
            'categories' => $categories,
            'events' => $events
        ];
        $this->view('home/index', $data);
    }
}
