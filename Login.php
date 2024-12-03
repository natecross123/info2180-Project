<?php
header('Content-Type: application/json');

session_start();

$host = "localhost";
$user = "root";
$pass = "";
$db = "dolphin_crm";

$conn = new mysqli('localhost', 'root', '', 'dolphin_crm');

if($conn -> connect_error){
    die("Connection failed: " . $conn -> connect_error);
}

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $Emailaddress = $conn -> real_escape_string($_POST['email']);
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email = ?";

    $stmt = $conn->prepare($sql);
    $stmt -> bind_param("s", $Emailaddress);
    $stmt -> execute();  
    $result = $stmt -> get_result();

    if($result -> num_rows === 1){
        $user = $result -> fetch_assoc();
    
        if(password_verify($password, $user['password'])){
            $_SESSION['role'] = $user['role'];
            $_SESSION['username'] = $Emailaddress;

            $redirectUrl = 'login.php';
            if($user['role'] === 'Admin'){
                $redirectUrl = 'Admin_dashboard.html';
            };
            if($user['role'] === 'Member'){
                $redirectUrl = 'User_dashboard.html';
            }
            

            echo json_encode(['success' => true, 'redirectUrl' => $redirectUrl]);
            exit();
            
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid username or password']);
            exit();
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'User not found']);
        exit();
    }
    
    $stmt -> close();
    $conn -> close();
}
?>