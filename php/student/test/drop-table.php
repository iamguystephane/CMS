<?php
session_start();
require_once '../../database-connection.php';

if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['table-name'])) {
    // Sanitize table name to avoid SQL injection
    $tableName = preg_replace('/[^a-zA-Z0-9_]/', '', $_POST['table-name']);

    // Drop the table if it exists
    $drop_query = "DROP TABLE IF EXISTS `$tableName`";

    if ($conn->query($drop_query) === TRUE) {
        echo "<script>alert('Table `$tableName` deleted successfully.');</script>";
        header("Location: ../test.php");
    } else {
        echo "<script>alert('Error deleting table: " . $conn->error . "');</script>";
    }
}
