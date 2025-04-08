<?php
session_start();
require_once '../database-connection.php';

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
                echo "<script>alert('Data inserted successfully into table `$table_name`.');</script>";
            }

            $stmt->close();
        } else {
            echo "<script>alert('Failed to prepare insert query');</script>";
        }
    } else {
        echo "<script>alert('Failed to create table');</script>";
    }
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../styles/output.css">
    <title>Testing</title>
</head>

<body>
    <div class="w-full min-h-screen flex flex-col items-center justify-center">
        <h1 class="w-1/3 font-bold text-xl"> Creating a table </h1>
        <p class="w-1/3 my-3"> Please enter the table's details to create a student table </p>
        <form class="shadow shadow-white rounded-lg p-4 flex flex-col gap-4 w-1/3 border" method="POST" action="test.php">
            <div class="flex flex-col gap-2">
                <label> Table name </label>
                <input type="text" name='table-name' class="border w-full p-1 border-gray-500 focus:outline-blue-500 rounded px-2" />
            </div>
            <div class="flex flex-col gap-2">
                <label> Student name </label>
                <input type="text" name='name' class="border w-full p-1 border-gray-500 focus:outline-blue-500 rounded px-2" />
            </div>
            <div class="flex flex-col gap-2">
                <label> Email </label>
                <input type="email" name='email' class="border w-full p-1 border-gray-500 focus:outline-blue-500 rounded px-2" />
            </div>
            <div class="flex flex-col gap-2">
                <label> Matricule </label>
                <input type="text" name='matricule' class="border w-full p-1 border-gray-500 focus:outline-blue-500 rounded px-2" />
            </div>
            <button class="w-full bg-green-500 text-white hover:bg-green-700 transition-all duration-500 ease-in-out py-2 cursor-pointer"> Create table </button>
        </form>
    </div>
</body>

</html>