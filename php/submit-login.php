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
            $_SESSION['user_id'] = $user['ID'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['email'] = $user['Email'];
            $_SESSION['name'] = $user['Names'];

            if ($user['role'] == 'Admin') {
               echo "<script>
                    localStorage.setItem('toastMessage', 'Login successful');
                    window.location.href = 'admin/admin-dashboard.php?success=loggedin';
                </script>";
                exit();
            } else {
                echo "<script>
                    localStorage.setItem('toastMessage', 'Login successful');
                    window.location.href = 'student/student-dashboard.php?success=loggedin';
                </script>";
                exit();
            }
        } else {
            echo "<script>
                localStorage.setItem('toastMessage', 'Incorrect password!');
                window.location.href = 'login.php';
            </script>";
            exit();
        }
    } else {
        echo "<script>
            localStorage.setItem('toastMessage', 'No user found with that email address!');
            window.location.href = 'login.php';
        </script>";
        exit();
    }

    $stmt->close();
    $conn->close();
}
?>
