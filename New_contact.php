<?php
session_start();

// Database connection details
$host = 'localhost';
$dbname = 'dolphin_crm';
$username = 'root';
$password = '';

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
    exit;
}

try {
    // Create PDO connection
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    
    // Set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check if the form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
        $firstname = filter_input(INPUT_POST, 'firstName', FILTER_SANITIZE_STRING);
        $lastname = filter_input(INPUT_POST, 'lastName', FILTER_SANITIZE_STRING);
        $email = filter_input(INPUT_POST, 'conEmail', FILTER_VALIDATE_EMAIL);
        $telephone = filter_input(INPUT_POST, 'contactphone', FILTER_SANITIZE_STRING);
        $company = filter_input(INPUT_POST, 'company', FILTER_SANITIZE_STRING);
        $type = filter_input(INPUT_POST, 'role', FILTER_SANITIZE_STRING);
        $Ass_to = filter_input(INPUT_POST, 'assign_to', FILTER_SANITIZE_STRING);
        
        

        // Validate inputs
        if ($title && $firstname && $lastname && $email && $telephone
            && $company && $type 
            && $Ass_to) {
            
            // Start a transaction
            $pdo->beginTransaction();

            try {
                // Prepare SQL for students table
                $stmt_contact = $pdo->prepare("INSERT INTO contacts (title, firstname, lastname, email, telephone, company, label, assigned_to, created_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt_contact->execute([$title, $firstname, $lastname, $email, $telephone, $company,$type, $Ass_to, $_SESSION['user_id']]);

                // Commit the transaction
                $pdo->commit();

                // Return success response
                http_response_code(200);
                echo json_encode(['status' => 'success', 'message' => 'Student added successfully']);
                exit;

            } catch (PDOException $e) {
                // Rollback the transaction
                $pdo->rollBack();

                // Return error response
                http_response_code(500);
                echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
                exit;
            }
        } else {
            // Invalid input
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Invalid input data']);
            exit;
        }
    } else {
        // Not a POST request
        http_response_code(405);
        echo json_encode(['status' => 'error', 'message' => 'Method Not Allowed']);
        exit;
    }
} catch (PDOException $e) {
    // Connection error
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Connection failed: ' . $e->getMessage()]);
    exit;
}

?>