<?php
session_start();
require_once '../../database-connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['table-name'])) {
    $table_name = preg_replace('/[^a-zA-Z0-9_]/', '', $_POST['table-name']); // sanitize table name
    $name = $_POST['name'];
    $email = $_POST['email'];
    $matricule = $_POST['matricule'];

    // Create table
    $create_table_query = "CREATE TABLE IF NOT EXISTS `$table_name` (
        id INT(11) AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        email VARCHAR(255) NOT NULL,
        matricule VARCHAR(255) NOT NULL
    )";

    if ($conn->query($create_table_query) === TRUE) {
        // Insert data
        $insert_query = "INSERT INTO `$table_name` (name, email, matricule) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($insert_query);
        if ($stmt) {
            $stmt->bind_param("sss", $name, $email, $matricule);
            $stmt->execute();

            if ($stmt->error) {
                echo "<script>alert('Error inserting data: " . $stmt->error . "');</script>";
            } else {
                echo "<script>alert('Table $table_name created and data inserted successfully into table `$table_name`.');</script>";
                header("Location: ../test.php");
            }

            $stmt->close();
        } else {
            echo "<script>alert('Failed to prepare insert query');</script>";
        }
    } else {
        echo "<script>alert('Failed to create table');</script>";
    }
}
