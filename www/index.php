<?php
// Database connection parameters
$host = 'localhost';
$dbname = 'default_database';
$username = 'default_user';
$password = 'default_password';

try {
    // Create connection
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    // Set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Query to select all animals
    $stmt = $conn->query("SELECT * FROM animal");
    
    // Fetch all rows
    $animals = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Display results in HTML
    echo "<h1>Animals List</h1>";
    echo "<table border='1'>
            <tr>
                <th>ID</th>
                <th>Species</th>
                <th>Name</th>
                <th>Age</th>
                <th>Description</th>
                <th>Image</th>
            </tr>";
    
    foreach($animals as $animal) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($animal['id']) . "</td>";
        echo "<td>" . htmlspecialchars($animal['specie']) . "</td>";
        echo "<td>" . htmlspecialchars($animal['name']) . "</td>";
        echo "<td>" . htmlspecialchars($animal['age']) . "</td>";
        echo "<td>" . htmlspecialchars($animal['description']) . "</td>";
        echo "<td><img src='" . htmlspecialchars($animal['image']) . "'></td>";
        echo "</tr>";
    }
    echo "</table>";

} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
$conn = null;
?>