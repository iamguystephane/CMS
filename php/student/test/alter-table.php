<?php
session_start();
require_once '../../database-connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['table-name'], $_POST['column-name'], $_POST['column-type'])) {
    $tableName = preg_replace('/[^a-zA-Z0-9_]/', '', $_POST['table-name']);
    $columnName = preg_replace('/[^a-zA-Z0-9_]/', '', $_POST['column-name']);
    $columnType = strtoupper(trim($_POST['column-type'])); // e.g. VARCHAR(255), INT(11)

    $alter_query = "ALTER TABLE `$tableName` ADD `$columnName` $columnType";

    if ($conn->query($alter_query) === TRUE) {
        echo "<script>alert('Column `$columnName` added to `$tableName`.');</script>";
    } else {
        echo "<script>alert('Error: " . $conn->error . "');</script>";
    }
}
