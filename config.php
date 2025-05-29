<?php
// config.php - Datenbankverbindung
class Database {
    private $host = 'mlr-shop.de';
    private $dbname = 'onlineshop';
    private $username = 'shopuser';
    private $password = '12345678';
    private $pdo = null;
    
    public function __construct() {
        try {
            $this->pdo = new PDO(
                "mysql:host={$this->host};dbname={$this->dbname};charset=utf8mb4",
                $this->username,
                $this->password,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false
                ]
            );
        } catch (PDOException $e) {
            die("Datenbankverbindung fehlgeschlagen: " . $e->getMessage());
        }
    }
    
    public function getConnection() {
        return $this->pdo;
    }
}

// Produkt-Klasse für Datenbankoperationen
class ProductLoader {
    private $pdo;
    
    public function __construct($database) {
        $this->pdo = $database->getConnection();
    }
    
    public function getProductsByCategory($category) {
        $sql = "
            SELECT 
                p.product_id,
                p.name,
                p.short_description,
                p.price,
                p.sale,
                p.alt_text,
                p.description,
                c.name as category_name,
                sc.name as subcategory_name,
                -- Processor
                proc.model as processor_model,
                proc.brand as processor_brand,
                proc.cores as processor_cores,
                proc.base_clock_ghz as processor_clock,
                -- GPU
                gpu.model as gpu_model,
                gpu.brand as gpu_brand,
                gpu.vram_gb as gpu_vram,
                gpu.integrated as gpu_integrated,
                -- RAM
                ram.capacity_gb as ram_capacity,
                ram.ram_type,
                -- Storage
                storage.capacity_gb as storage_capacity,
                storage.storage_type,
                -- Display
                display.size_inch as display_size,
                display.resolution,
                display.refresh_rate_hz,
                display.panel_type,
                -- OS
                os.name as os_name,
                -- Network
                network.spec as network_spec,
                -- Connectors
                connectors.spec as connectors_spec,
                -- Features
                feature.spec as feature_spec
            FROM product p
            LEFT JOIN subcategory sc ON p.subcategory_id = sc.subcategory_id
            LEFT JOIN category c ON sc.category_id = c.category_id
            LEFT JOIN processor proc ON p.cpu_id = proc.cpu_id
            LEFT JOIN gpu ON p.gpu_id = gpu.gpu_id
            LEFT JOIN ram ON p.ram_id = ram.ram_id
            LEFT JOIN storage ON p.storage_id = storage.storage_id
            LEFT JOIN display ON p.display_id = display.display_id
            LEFT JOIN operating_system os ON p.os_id = os.os_id
            LEFT JOIN network ON p.network_id = network.network_id
            LEFT JOIN connectors ON p.connectors_id = connectors.connectors_id
            LEFT JOIN feature ON p.feature_id = feature.feature_id
        ";
        
        $params = [];
        
        // Filter basierend auf Kategorie
        switch(strtolower($category)) {
            case 'angebote':
            case 'deals':
                $sql .= " WHERE p.sale = 1";
                break;
            case 'pc':
            case 'desktop-pc':
                $sql .= " WHERE c.name = 'PC'";
                break;
            case 'laptop':
            case 'laptops':
                $sql .= " WHERE c.name = 'Laptop'";
                break;
            case 'zubehör':
            case 'zubehoer':
                $sql .= " WHERE c.name = 'Zubehör'";
                break;
            case 'gaming-pc':
            case 'gamingpc':
                $sql .= " WHERE sc.name = 'Gaming-PC'";
                break;
            case 'office-pc':
            case 'officepc':
                $sql .= " WHERE sc.name = 'Office-PC'";
                break;
            case 'gaming-laptop':
            case 'gaminglaptop':
                $sql .= " WHERE sc.name = 'Gaming-Laptop'";
                break;
            case 'office-laptop':
            case 'officelaptop':
                $sql .= " WHERE sc.name = 'Office-Laptop'";
                break;
            case 'monitor':
            case 'monitore':
                $sql .= " WHERE sc.name = 'Monitor'";
                break;
            case 'maus':
            case 'mäuse':
                $sql .= " WHERE sc.name = 'Maus'";
                break;
            case 'tastatur':
            case 'tastaturen':
                $sql .= " WHERE sc.name = 'Tastatur'";
                break;
            default:
                // Fallback: alle Produkte
                break;
        }
        
        $sql .= " ORDER BY p.name";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
    
    public function getProductImages($productId) {
        $sql = "SELECT file_path, sequence_no FROM image WHERE product_id = ? ORDER BY sequence_no";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$productId]);
        return $stmt->fetchAll();
    }
    
    public function getCategoryInfo($category) {
        // Kategorie-Informationen für Breadcrumb und Titel
        $categoryData = [
            'pc' => [
                'breadcrumb' => 'Desktop-PCs',
                'title' => 'DESKTOP',
                'subtitle' => 'PCs',
                'description' => 'Leistungsstarke Desktop-Computer für jeden Anwendungsbereich'
            ],
            'laptop' => [
                'breadcrumb' => 'Laptops',
                'title' => 'LAPTOP',
                'subtitle' => 'Computer',
                'description' => 'Mobile Notebooks für Arbeit und Gaming'
            ],
            'zubehör' => [
                'breadcrumb' => 'Zubehör',
                'title' => 'COMPUTER',
                'subtitle' => 'Zubehör',
                'description' => 'Peripheriegeräte und Erweiterungen'
            ],
            'gaming-pc' => [
                'breadcrumb' => 'Gaming-PCs',
                'title' => 'GAMING',
                'subtitle' => 'PCs',
                'description' => 'High-End Gaming-Computer für maximale Performance'
            ],
            'office-pc' => [
                'breadcrumb' => 'Office-PCs',
                'title' => 'OFFICE',
                'subtitle' => 'PCs',
                'description' => 'Zuverlässige Arbeitsplatz-Computer'
            ],
            'angebote' => [
                'breadcrumb' => 'Angebote',
                'title' => 'SONDER',
                'subtitle' => 'Angebote',
                'description' => 'Unsere besten Deals und Rabatte'
            ]
        ];
        
        return $categoryData[$category] ?? $categoryData['pc'];
    }
}