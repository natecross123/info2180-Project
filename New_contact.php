<?php
session_start();
$host = 'localhost';
$dbname = 'dolphin_crm';
$username = 'root';
$password = '';


try {
    // Create PDO connection
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    
    // Set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check if the form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        $title = filter_input(INPUT_POST, 'title', FILTER_UNSAFE_RAW);
        $firstname = filter_input(INPUT_POST, 'firstName', FILTER_UNSAFE_RAW);
        $lastname = filter_input(INPUT_POST, 'lastName', FILTER_UNSAFE_RAW);
        $email = filter_input(INPUT_POST, 'conEmail', FILTER_VALIDATE_EMAIL);
        $telephone = filter_input(INPUT_POST, 'contactphone', FILTER_UNSAFE_RAW);
        $company = filter_input(INPUT_POST, 'company', FILTER_UNSAFE_RAW);
        $label = filter_input(INPUT_POST, 'role', FILTER_UNSAFE_RAW);
        $Ass_to = filter_input(INPUT_POST, 'assign_to', FILTER_UNSAFE_RAW);
        
        

        // Validate inputs
        if ($title && $firstname && $lastname && $email && $telephone && $company && $label && $Ass_to) {

            // Check for existing contact
            $stmt_check = $pdo->prepare("SELECT COUNT(*) FROM contacts WHERE email = ? OR telephone = ?");
            $stmt_check->execute([$email, $telephone]);
            $existing_contact_count = $stmt_check->fetchColumn();

            if ($existing_contact_count > 0) {
                http_response_code(409);
                echo json_encode(['status' => 'error', 'message' => 'Contact with this email or telephone already exists']);
                exit;
            }

            
            // Start a transaction
            $pdo->beginTransaction();

            try {
                // Prepare SQL for students table
                $stmt_contact = $pdo->prepare("INSERT INTO contacts (title, firstname, lastname, email, telephone, company, label, assigned_to, created_by, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");
                $stmt_contact->execute([$title, $firstname, $lastname, $email, $telephone, $company,$label, $Ass_to, $_SESSION['id']]);

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