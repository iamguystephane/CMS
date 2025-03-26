<?php
session_start();
require_once '../database-connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $department = $_POST['department'];
    $level = $_POST['level'];
    $user_id = $_SESSION['user_id'];

    $sql = "UPDATE `sign up` SET Names = ?, Email = ?, Department = ?, Level = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssi", $name, $email, $department, $level, $user_id);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            echo "<script>
                localStorage.setItem('toastMessage', 'Information successfully updated');
                window.location.href = 'student-dashboard.php';
            </script>";
        } else {
            echo "<script>
                localStorage.setItem('toastMessage', 'No changes were made');
                window.location.href = 'student-dashboard.php';
            </script>";
        }
    } else {
        echo "<script>
            localStorage.setItem('toastMessage', 'Could not update information');
            window.location.href = 'student-dashboard.php';
        </script>";
    }

    $stmt->close();
    $conn->close();
}
