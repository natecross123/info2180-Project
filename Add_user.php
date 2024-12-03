<?php
$host = 'localhost';
$db = 'dolphin_crm';
$user = 'root';
$pass = '';

try{
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $firstName = filter_input(INPUT_POST, 'firstName', FILTER_SANITIZE_STRING);
        $lastName = filter_input(INPUT_POST, 'lastName', FILTER_SANITIZE_STRING);
        $userEmail = filter_input(INPUT_POST, 'userEmail', FILTER_SANITIZE_EMAIL);
        $userPassword = filter_input(INPUT_POST, 'userPassword', FILTER_SANITIZE_STRING);
        $userRole = filter_input(INPUT_POST, 'role', FILTER_SANITIZE_STRING);

        if(!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[A-Za-z\d]{8,}$/', $userPassword)){
            http_response_code(400);
            echo json_encode([
                'status' => 'error',
                'message' => 'Password must be at least 8 characters long, contain at least one letter, one capital letter and one number.'
            ]);
            exit;
        }

        if($firstName && $lastName && $userEmail && $userPassword && in_array($userRole, ['Admin', 'Member'])){
            $pdo -> beginTransaction();

            try{
                $hashedPwd = password_hash($userPassword, PASSWORD_DEFAULT);

                $stmt = $pdo -> prepare("INSERT INTO users (firstname, lastname, email, password, role, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
                $stmt -> execute([$firstName, $lastName, $userEmail, $hashedPwd, $userRole]);

                $pdo -> commit();

                http_response_code(200);
                echo json_encode([
                    'status' => 'success',
                    'message' => 'New user added successfully!'
                ]);
                exit;
            } catch(PDOException $e){
                $pdo -> rollBack();

                http_response_code(500);
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Database error: ' . $e -> getMessage()
                ]);
                exit;
            }
        } else{
            http_response_code(400);
            echo json_encode([
                'status' => 'error',
                'message' => 'Invalid input data'
            ]);
            exit;
        }
    } else{
        http_response_code(405);
        echo json_encode([
            'status' => 'error',
            'message' => 'Method not allowed'
        ]);
        exit;
    }
} catch(PDOException $e){
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Connection failed: ' . $e -> getMessage()
    ]);
    exit;
}
?>
