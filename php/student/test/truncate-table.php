<?php
session_start();
require_once '../../database-connection.php';

if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['table-name'])) {
    // Sanitize table name to avoid SQL injection
    $tableName = preg_replace('/[^a-zA-Z0-9_]/', '', $_POST['table-name']);

    // Truncate the table (clear all rows but keep structure)
    $truncate_query = "TRUNCATE TABLE `$tableName`";

    if ($conn->query($truncate_query) === TRUE) {
        echo "<script>alert('Table `$tableName` truncated successfully.');</script>";
        header("Location: ../test.php");
        exit();
    } else {
        echo "<script>alert('Error truncating table: " . $conn->error . "');</script>";
    }
}
