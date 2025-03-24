<?php
session_start();
require_once "../database-connection.php";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $studentID = $_SESSION['user_id'] ?? null;
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $department = trim($_POST['department']);
    $priority = trim($_POST['priority']);
    $complaint = trim($_POST['complaint']);

    $sql = "INSERT INTO complaints(studentId, names, email, department, priority, description) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssss", $studentID, $name, $email, $department, $priority, $complaint);

    if (!$studentID) {
        die("Error: Student ID is missing.");
    }


    if ($stmt->execute()) {
        echo "<script>
        localStorage.setItem('toastMessage', 'Complaint submitted successfully');
        window.location.href = 'submit-complaint.php';
        </script>";
    } else {
        echo "<script>
        localStorage.setItem('toastMessage', 'Failed to submit complaint. Please try again later');
        window.location.href = 'submit-complaint.php';
        </script>";
    }
    $stmt->close();
    $conn->close();
}
