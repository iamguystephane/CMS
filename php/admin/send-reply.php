<?php
ob_start();
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../database-connection.php';

ob_clean();
header("Content-Type: application/json");

// Ensure the request is a POST request
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(["success" => false, "error" => "Invalid request method"]);
    exit();
}

// Validate inputs
if (!isset($_POST['message'], $_POST['studentID'], $_POST['adminID'])) {
    ob_clean();
    echo json_encode(["success" => false, "error" => "Missing required fields"]);
    exit();
}

$message = trim($_POST['message']);
$studentID = intval($_POST['studentID']);
$adminID = intval($_POST['adminID']);

if (empty($message)) {
    ob_clean();
    echo json_encode(["success" => false, "error" => "Message cannot be empty"]);
    exit();
}

// Insert the message into the database
$sql = "INSERT INTO complaint_messages (sender_id, receiver_id, admin_id, message, created_at) VALUES (?, ?, ?, ?, NOW())";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iiis", $adminID, $studentID, $adminID, $message);

if ($stmt->execute()) {
    ob_clean();
    echo json_encode(["success" => true]);
} else {
    ob_clean();
    echo json_encode(["success" => false, "error" => $stmt->error]);
}


$stmt->close();
$conn->close();
