<?php

$host = 'localhost';
$user = 'root';
$pass = '';
$db = 'dolphin_crm';

try{
    $conn = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    $conn -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $filter = isset($_GET['filter']) ? $_GET['filter'] : '';

    if($filter){
        $stmt = $conn -> prepare("SELECT title, firstname, lastname, email, company, type FROM contacts WHERE type = :filter");
        $stmt -> bindParam(':filter', $filter);    
    } else{
        $stmt = $conn -> prepare("SELECT title, firstname, lastname, email, company, type FROM contacts");
    }
    
    $stmt -> execute();
    $results = $stmt -> fetchAll(PDO::FETCH_ASSOC);

    if(count($results) > 0){
        echo '<table>';
        echo '<tr>
                <th>Name</th>
                <th>Email</th>
                <th>Company</th>
                <th>Type</th>
                <th></th>
            </tr>';

        foreach($results as $row){
            echo '<tr>';
            echo '<td>' . htmlspecialchars($row['title'] . ' ' . $row['firstname'] . ' ' . $row['lastname']) . '</td>';
            echo '<td>' . htmlspecialchars($row['email']) . '</td>';
            echo '<td>' . htmlspecialchars($row['company']) . '</td>';
            echo '<td>' . htmlspecialchars($row['type']) . '</td>';
            //echo '<td>' . htmlspecialchars() . '</td>';
            echo '</tr>';
        }
        echo '</table>';
    } else{
        echo 'No contact found.';
    }
} catch(PDOException $e){
    echo "Connection failed: " . $e -> getMessage();
}
?>