<?php
// Database Connection Diagnostic Script
// Save this as diagnose_db.php in your application root

require_once __DIR__ . '/vendor/autoload.php';

try {
    // Create Laravel application instance
    $app = require_once __DIR__ . '/bootstrap/app.php';
    
    // Boot the application
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();
    
    echo "<h2>Database Connection Diagnostic</h2>\n";
    
    // Get database configuration
    $dbConfig = config('database.connections.pgsql');
    
    echo "<h3>Current Database Configuration:</h3>\n";
    echo "<pre>";
    print_r([
        'host' => $dbConfig['host'] ?? 'NOT SET',
        'port' => $dbConfig['port'] ?? 'NOT SET',
        'database' => $dbConfig['database'] ?? 'NOT SET',
        'username' => $dbConfig['username'] ?? 'NOT SET',
        'driver' => $dbConfig['driver'] ?? 'NOT SET'
    ]);
    echo "</pre>\n";
    
    // Attempt direct PDO connection
    echo "<h3>Attempting Direct PDO Connection...</h3>\n";
    
    $dsn = "pgsql:host={$dbConfig['host']};port={$dbConfig['port']};dbname={$dbConfig['database']}";
    
    try {
        $pdo = new PDO($dsn, $dbConfig['username'], $dbConfig['password'], [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
        
        echo "<p style='color: green;'>✓ Direct PDO connection successful!</p>\n";
        
        // Test a simple query
        $stmt = $pdo->query("SELECT version();");
        $version = $stmt->fetchColumn();
        echo "<p>PostgreSQL Version: $version</p>\n";
        
        // Test if tables exist
        $stmt = $pdo->query("SELECT to_regclass('settings');");
        $tableExists = $stmt->fetchColumn();
        echo "<p>Settings table exists: " . ($tableExists ? 'YES' : 'NO') . "</p>\n";
        
    } catch (PDOException $e) {
        echo "<p style='color: red;'>✗ Direct PDO connection failed: " . htmlspecialchars($e->getMessage()) . "</p>\n";
    }
    
    // Test Laravel database connection
    echo "<h3>Testing Laravel Database Connection...</h3>\n";
    
    try {
        $db = $app->make('db');
        $connection = $db->connection();
        $pdo = $connection->getPdo();
        
        echo "<p style='color: green;'>✓ Laravel database connection successful!</p>\n";
        
        // Test if settings table exists using Laravel
        $tableExists = $connection->getSchemaBuilder()->hasTable('settings');
        echo "<p>Laravel: Settings table exists: " . ($tableExists ? 'YES' : 'NO') . "</p>\n";
        
    } catch (Exception $e) {
        echo "<p style='color: red;'>✗ Laravel database connection failed: " . htmlspecialchars($e->getMessage()) . "</p>\n";
    }
    
    // Check environment file
    echo "<h3>Environment File Check:</h3>\n";
    $envPath = __DIR__ . '/../.env'; // Assuming .env is in parent directory
    if (file_exists($envPath)) {
        echo "<p style='color: green;'>✓ .env file exists</p>\n";
        
        // Read and check for DB settings
        $envContent = file_get_contents($envPath);
        $hasDbSettings = strpos($envContent, 'DB_') !== false;
        echo "<p>Contains DB settings: " . ($hasDbSettings ? 'YES' : 'NO') . "</p>\n";
    } else {
        echo "<p style='color: red;'>✗ .env file does not exist at: $envPath</p>\n";
        echo "<p>Looking for .env in current directory...</p>\n";
        if (file_exists(__DIR__ . '/.env')) {
            echo "<p style='color: orange;'>.env found in current directory</p>\n";
        } else {
            echo "<p style='color: red;'>.env not found in current directory either</p>\n";
        }
    }
    
    // Check storage permissions
    echo "<h3>Storage Permissions Check:</h3>\n";
    $storagePath = __DIR__ . '/storage';
    if (is_dir($storagePath)) {
        $writable = is_writable($storagePath);
        echo "<p>Storage directory writable: " . ($writable ? 'YES' : 'NO') . "</p>\n";
        
        if (!$writable) {
            echo "<p style='color: red;'>⚠ Storage directory is not writable - this can cause 500 errors!</p>\n";
        }
    } else {
        echo "<p style='color: red;'>✗ Storage directory does not exist</p>\n";
    }
    
    echo "<h3>PHP Information:</h3>\n";
    echo "<p>PHP Version: " . PHP_VERSION . "</p>\n";
    echo "<p>PostgreSQL Extension Loaded: " . (extension_loaded('pgsql') ? 'YES' : 'NO') . "</p>\n";
    echo "<p>PDO PostgreSQL Extension Loaded: " . (extension_loaded('pdo_pgsql') ? 'YES' : 'NO') . "</p>\n";

} catch (Exception $e) {
    echo "<h3>Fatal Error:</h3>\n";
    echo "<p style='color: red;'>" . htmlspecialchars($e->getMessage()) . "</p>\n";
    echo "<p>File: " . $e->getFile() . " Line: " . $e->getLine() . "</p>\n";
}