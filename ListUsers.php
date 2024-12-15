<?php

$host = "localhost"; 
$username = "root";
$password = "";
$database = "dolphin_crm";

$conn = new mysqli($host,$username,$password,$database);
if($conn->connect_error){
    die("Connection failed: ".$conn->connect_error);
}

$sql="SELECT CONCAT(firstname,' ', lastname) AS name, email, role, created_at FROM users";
$result = $conn -> query($sql);

if($result->num_rows>0){
    $users=array();
    while ($row = $result->fetch_assoc()){
        $users[]=$row;
    }
    echo json_encode($users);
}else{
    echo json_encode([]);
}

$conn->close();
?>