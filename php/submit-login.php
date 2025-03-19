<?php
session_start();
require_once 'database-connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    $sql = "SELECT * FROM `sign up` WHERE Email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['Password'])) {
            // Start session and set user role
            $_SESSION['user_id'] = $user['ID'];   // Store user ID
            $_SESSION['role'] = $user['role'];     // Store role for access control
            $_SESSION['email'] = $user['Email'];
            $_SESSION['name'] = $user['Names'];  // Store email (optional)

            // Redirect based on role
            if ($user['role'] == 'Admin') {
                header("Location: admin/admin-dashboard.php");  // Admin dashboard
            } else {
                header("Location: student/student-dashboard.php"); // Student dashboard
            }
        } else {
            echo "Incorrect password!";
        }
    } else {
        echo "No user found with that email address!";
    }

    $stmt->close();
    $conn->close();
}
?>
