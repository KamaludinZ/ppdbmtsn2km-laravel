<?php
// Simple test to check if the application can connect to the database
require_once __DIR__ . '/vendor/autoload.php';

try {
    // Create Laravel application instance
    $app = require_once __DIR__ . '/bootstrap/app.php';
    
    // Get the container
    $container = $app->make(\Illuminate\Contracts\Console\Kernel::class);
    $container->bootstrap();
    
    // Test database connection
    $db = $app->make('db');
    $connection = $db->connection();
    
    // Try to get PDO instance
    $pdo = $connection->getPdo();
    
    echo "Database connection successful!\n";
    echo "Connected to: " . $connection->getDriverName() . "\n";
    
} catch (Exception $e) {
    echo "Database connection failed: " . $e->getMessage() . "\n";
}