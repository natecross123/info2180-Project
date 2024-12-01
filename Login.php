<?php

session_start();

$host = "localhost";
$user = "root";
$pass = "password123";
$db = "dolphin_crm";

$conn = new mysqli('localhost', 'root', '', 'dolphin_crm');

if($conn -> connect_error){
    die("Connection failed: " . $conn -> connect_error);
}

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $Emailaddress = $conn -> real_escape_string($_POST['Email address)']);
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email = ?";

    $stmt = $conn->prepare($sql);
    $stmt -> bind_param("s", $Emailaddress);
    $stmt -> execute();  
    $result = $stmt -> get_result();

    if($result -> num_rows === 1){
        $user = $result -> fetch_assoc();
    
        if($password === $user['password']){
            $_SESSION['role'] = $user['role'];
            $_SESSION['username'] = $username;

            $redirectUrl = match($user['role']){
                'Admin' => 'Admin_dashboard.html',
                'User' => 'User_dashboard.html',
                default => 'login.php'
            };
            

            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'redirectUrl' => $redirectUrl]);
            exit();
            
        } else {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Invalid username or password']);
            exit();
        }
    } else {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'User not found']);
        
        exit();
    }
    
    $stmt -> close();
    $conn -> close();
}
?>