<?php 
require_once('database-connection.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['names']);
    $email = trim($_POST['email']);
    $department = trim($_POST['department']);
    $level = trim($_POST['level']);
    $password = password_hash(trim($_POST['password']), PASSWORD_DEFAULT);
    $role = trim($_POST['role']);

    $check_email_sql = "SELECT ID FROM `sign up` WHERE Email = ?";
    $check_stmt = $conn->prepare($check_email_sql);
    $check_stmt->bind_param("s", $email);
    $check_stmt->execute();
    $check_stmt->store_result();

    if ($check_stmt->num_rows > 0) {
        echo "<script>localStorage.setItem('toastMessage', 'This email is already registered.'); window.location.href='sign-up.php';</script>";
        exit();
    } else {
        // Insert new user if email does not exist
        $sql = "INSERT INTO `sign up` (Names, Email, Department, Level, Password, role) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssss", $name, $email, $department, $level, $password, $role);

        if ($stmt->execute()) {
            header("Location: login.php?success=registered");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    }

    $check_stmt->close();
    $conn->close();
}
?>
