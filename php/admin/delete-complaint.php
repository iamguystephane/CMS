<?php
session_start();
require_once "../database-connection.php";

if (isset($_GET['studentID'])) {
    $studentID = intval($_GET['studentID']); // Ensure it's an integer

    $sql = "DELETE FROM complaints WHERE studentID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $studentID); // Use "i" for integer
    $stmt->execute();

    if ($stmt->affected_rows > 0) { // Check if any rows were deleted
        echo "<script>
                localStorage.setItem('toastMessage', 'Complaint deleted!');
                window.location.href = 'view-complaints.php?success=deleted';
            </script>";
    } else {
        echo "<script>
                localStorage.setItem('toastMessage', 'No complaints found for this student!');
                window.location.href = 'view-complaints.php?error=notfound';
            </script>";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "<script>
            localStorage.setItem('toastMessage', 'Invalid request!');
            window.location.href = 'view-complaints.php?error=invalid';
        </script>";
    exit();
}
