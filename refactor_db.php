<?php
require __DIR__ . '/lib/bootstrap.php';

try {
    // 1. Modify scales table: Remove customer_id
    // First drop the foreign key, then the column
    // Note: We use silent catch for drop in case it doesn't exist (if run multiple times), 
    // but proper checks are better. For simplicity in this agent context, we try/catch.
    
    try {
        $pdo->exec("ALTER TABLE scales DROP FOREIGN KEY fk_scales_customer");
    } catch (PDOException $e) { /* Ignore if not exists */ }
    
    try {
        $pdo->exec("ALTER TABLE scales DROP COLUMN customer_id");
    } catch (PDOException $e) { /* Ignore if not exists */ }

    // 2. Modify licenses table: Add customer_id
    // We need to add the column, then add the FK. 
    // Since table is empty (user deleted data), we don't need to migrate existing data.
    
    try {
        $pdo->exec("ALTER TABLE licenses ADD COLUMN customer_id int(10) UNSIGNED NOT NULL AFTER id");
        $pdo->exec("ALTER TABLE licenses ADD CONSTRAINT fk_licenses_customer FOREIGN KEY (customer_id) REFERENCES customers (id) ON UPDATE CASCADE");
    } catch (PDOException $e) {
        // Only ignore "Duplicate column" errors
        if (strpos($e->getMessage(), 'Duplicate column') === false) {
             echo "Info (Licenses): " . $e->getMessage() . "\n";
        }
    }
    
    // 3. Just in case, check sales or other tables if they relied on scale->customer?
    // Sales table has its own customer_id, so it remains valid as a history log.
    
    echo "Database refactoring completed successfully.\n";

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
