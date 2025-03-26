<?php
ob_start();
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../database-connection.php';

header("Content-Type: application/json");

// Ensure the request is a POST request
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(["success" => false, "error" => "Invalid request method"]);
    exit();
}

// Validate inputs
if (!isset($_POST['message'], $_POST['studentID'], $_POST['adminID'])) {
    echo json_encode(["success" => false, "error" => "Missing required fields"]);
    exit();
}

$message = trim($_POST['message']);
$studentID = intval($_POST['studentID']);
$adminID = intval($_POST['adminID']);
$role = trim($_POST['userRole']);

if (empty($message)) {
    echo json_encode(["success" => false, "error" => "Message cannot be empty"]);
    exit();
}

// Insert the message into the database
$sql = "INSERT INTO complaint_messages (sender_id, receiver_id, message, created_at) VALUES (?, ?, ?, NOW())";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iis", $adminID, $studentID, $message);

if ($stmt->execute()) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "error" => $stmt->error]);
}


$stmt->close();
$conn->close();
