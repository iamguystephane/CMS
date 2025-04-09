<?php
// test-privilege.php
session_start();
require_once '../../database-connection.php';

$msg = null;
$error = null;
$usersWithPrivilege = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['table'])) {
    $table = preg_replace('/[^a-zA-Z0-9_]/', '', $_POST['table']);
    $dbName = $conn->query("SELECT DATABASE() AS db")->fetch_assoc()['db'];
    $fullTable = "`$dbName`.`$table`";

    // Query to get all users with SELECT privilege on the selected table
    $user_result = $conn->query("SELECT User, Host FROM mysql.user");
    while ($row = $user_result->fetch_assoc()) {
        $user = $row['User'];
        $host = $row['Host'];

        // Check if the user has the SELECT privilege on the specified table
        $grants_result = $conn->query("SHOW GRANTS FOR '$user'@'$host'");
        while ($grant = $grants_result->fetch_array()) {
            if (strpos($grant[0], "GRANT SELECT ON $fullTable") !== false) {
                $usersWithPrivilege[] = "$user@$host";
            }
        }
    }

    if (!empty($usersWithPrivilege)) {
        $msg = "Users with SELECT privilege on $table:";
    } else {
        $msg = "No users have SELECT privilege on $table.";
    }

    // Query to fetch sample records from the table
    $query = "SELECT * FROM `$table` LIMIT 5";
    try {
        $result = $conn->query($query);
        if ($result && $result->num_rows > 0) {
            $msg .= " Showing first 5 records.";
        } else {
            $msg .= " Query executed but no records found.";
        }
    } catch (mysqli_sql_exception $e) {
        $error = "Access denied or error: " . $e->getMessage();
    }
}

$tables = [];
$table_result = $conn->query("SHOW TABLES");
while ($row = $table_result->fetch_array()) {
    $tables[] = $row[0];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Privilege Test</title>
    <link rel="stylesheet" href="../../../styles/output.css">
</head>

<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="bg-white p-4 rounded-lg shadow-lg w-1/2">
        <h2 class="text-xl font-bold mb-4">Test User Privileges</h2>

        <?php if ($msg): ?>
            <div class="p-4 mb-4 bg-green-100 text-green-800 rounded"> <?= htmlspecialchars($msg) ?> </div>
        <?php elseif ($error): ?>
            <div class="p-4 mb-4 bg-red-100 text-red-800 rounded"> <?= htmlspecialchars($error) ?> </div>
        <?php endif; ?>

        <?php if (!empty($usersWithPrivilege)): ?>
            <div class="p-4 mb-4 bg-blue-100 text-blue-800 rounded">
                <h3 class="font-semibold">Users with SELECT Privilege on <?= htmlspecialchars($table) ?>:</h3>
                <ul>
                    <?php foreach ($usersWithPrivilege as $user): ?>
                        <li><?= htmlspecialchars($user) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="POST" class="flex flex-col gap-4">
            <div>
                <label class="block font-semibold">Select Table to Test SELECT Privilege</label>
                <select name="table" class="w-full p-2 border rounded">
                    <?php foreach ($tables as $table): ?>
                        <option value="<?= $table ?>"><?= $table ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button class="bg-green-500 hover:bg-blue-700 text-white px-4 py-2 rounded">Run SELECT Test</button>
        </form>
    </div>
</body>

</html>