<?php
// Database connection details
$servername = "localhost"; // Use "localhost" or "127.0.0.1"
$username = "root";
$password = "";
$dbname = "dolphin_crm";

try {
    // Connect to the database
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch user data
    $stmt = $conn->prepare("SELECT firstname, lastname, email, role, created_at FROM users"); // Adjust table and column names
    $stmt->execute();

    // Fetch all rows as an associative array
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return data as JSON
    echo json_encode($users);

} catch (PDOException $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
?>