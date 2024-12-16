<?php 
$host = 'localhost';
$user = 'root';
$pass = '';
$db = 'dolphin_crm';

$conn = new mysqli('localhost', 'root', '', 'dolphin_crm');

$data= json_decode(file_get_contents("php://input"), true);
$contact_id= intval($data['contact_id']);
$comment = htmlspecialchars($data['comment']);
$created_by = $_SESSION['user_id'];

if (empty($contact_id)|| empty($comment)){
    echo json_encode(["sucess"=> false, "error"=> "Invalid input"]);
    exit();
}

$stmt = $conn->prepare("INSERT INTO notes (contact_id, comment, created_by, created_at) VALUES (?,?,?,NOW())");
$stmt->bind_param("isi", $contact_id, $comment, $created_by);

if ($stmt-> execute()){
    $user_stmt= $conn->prepare("SELECT CONCAT(firstname, ' ', lastname) AS fullname FROM users WHERE id=?");
    $user_stmt->bind_param("i", $created_by);
    $user_stmt->execute();
    $result= $user_stmt->get_result();
    $user= $result->fetch_assoc();

    echo json_encode(["success"=> true, "user"=> $user['firstname']]);
}else{
    echo json_encode(["success"=> false, "error"=> "Failed to insert note"]);
}

$stmt->close();
$conn->close();
?>
