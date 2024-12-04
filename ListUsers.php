<?php

header('Content-Type: application/json');

$host = 'localhost';
$dbname = 'dolphin_crm';
$user = 'root';
$password = '';

try{
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $conn->prepare("SELECT firstname, lastname, email, role, created_at FROM users");
    $stmt->execute();

    $users=$stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($users);

}catch (PDOException $e){
    echo "Error: " . $e->getMessage();
}
?>