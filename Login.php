<?php
function logging($event_type, $details){
    date_default_timezone_set('US/Eastern');
    $timestamp = date('D,Y-m-d h:i:s,a');
    $log_msg= "[$timestamp] $event_type: $details\n";
    file_put_contents('Logged_Access.txt', $log_msg, FILE_APPEND);
}

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

    $sql = "SELECT * FROM users WHERE username = ?";

    $stmt = $conn->prepare($sql);
    $stmt -> bind_param("s", $Emailaddress);
    $stmt -> execute();  
    $result = $stmt -> get_result();

    logging("Access Attempt", "User {$Emailaddress} attempting to access dashboard");

    if($result -> num_rows === 1){
        $user = $result -> fetch_assoc();
    
        if($password === $user['password']){
            $_SESSION['role'] = $user['role'];
            $_SESSION['Email address'] = $Emailaddress;


            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'redirectUrl' => $redirectUrl]);
            exit();
            
        } else {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Invalid Email address or password']);
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