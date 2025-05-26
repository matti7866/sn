<?php
// Database connection parameters
$host = 'localhost';
$dbname = 'snjst';
$username = 'root';
$password = ''; // Change if your MySQL has a password
$socket = '/Applications/XAMPP/xamppfiles/var/mysql/mysql.sock'; // XAMPP socket path for Mac

try {
    // Connect to the database using socket path
    $pdo = new PDO("mysql:unix_socket=$socket;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<h2>Database Structure for '$dbname'</h2>";
    
    // Get list of tables
    $tableQuery = $pdo->query("SHOW TABLES");
    $tables = $tableQuery->fetchAll(PDO::FETCH_COLUMN);
    
    echo "<h3>Tables in database:</h3>";
    echo "<ul>";
    foreach ($tables as $table) {
        echo "<li>$table</li>";
    }
    echo "</ul>";
    
    // Get structure of residence table
    echo "<h3>Structure of 'residence' table:</h3>";
    if (in_array('residence', $tables)) {
        $columnQuery = $pdo->query("DESCRIBE residence");
        $columns = $columnQuery->fetchAll(PDO::FETCH_ASSOC);
        
        echo "<table border='1' cellpadding='5'>";
        echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
        foreach ($columns as $column) {
            echo "<tr>";
            echo "<td>" . $column['Field'] . "</td>";
            echo "<td>" . $column['Type'] . "</td>";
            echo "<td>" . $column['Null'] . "</td>";
            echo "<td>" . $column['Key'] . "</td>";
            echo "<td>" . ($column['Default'] === null ? 'NULL' : $column['Default']) . "</td>";
            echo "<td>" . $column['Extra'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>The 'residence' table does not exist in this database.</p>";
    }
    
    // Check for residence_replace_log table
    echo "<h3>Check if 'residence_replace_log' table exists:</h3>";
    $logTableQuery = $pdo->query("SHOW TABLES LIKE 'residence_replace_log'");
    if ($logTableQuery->rowCount() > 0) {
        echo "<p>The 'residence_replace_log' table exists.</p>";
    } else {
        echo "<p>The 'residence_replace_log' table does not exist.</p>";
    }
    
} catch (PDOException $e) {
    echo "<h2>Database Error</h2>";
    echo "<p>Error: " . $e->getMessage() . "</p>";
}
?> 