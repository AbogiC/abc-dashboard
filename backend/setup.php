<?php
require_once 'config/constants.php';

echo "Setting up Personal Dashboard Database...\n\n";

try {
    // Connect to MySQL without selecting database
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";charset=utf8mb4",
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );

    // Drop database if exists
    $pdo->exec("DROP DATABASE IF EXISTS " . DB_NAME);

    // Read SQL file
    $sql = file_get_contents(__DIR__ . '/db_schema.sql');

    // Execute SQL
    $pdo->exec($sql);

    echo "✅ Database setup completed successfully!\n";
    echo "📊 Database: " . DB_NAME . "\n";
    echo "👤 Default admin: admin / admin123\n";
    echo "🔗 API Base URL: http://localhost/backend/api/\n";

} catch (PDOException $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?>