<?php
session_start();
require_once '../../database-connection.php';

// Fetch all tables in the current database
$tables = [];
$users = [];

$result = $conn->query("SHOW TABLES");
while ($row = $result->fetch_array()) {
    $tables[] = $row[0];
}
$user_result = $conn->query("SELECT User, Host FROM mysql.user");
while ($row = $user_result->fetch_assoc()) {
    $users[] = "{$row['User']}@{$row['Host']}";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['create_user'])) {
        $new_user = $conn->real_escape_string($_POST['new_user']);
        $new_password = $conn->real_escape_string($_POST['new_password']);
        $create_user_query = "CREATE USER '$new_user'@'localhost' IDENTIFIED BY '$new_password'";

        if ($conn->query($create_user_query) === TRUE) {
            $_SESSION['message'] = "User '$new_user' created successfully.";
        } else {
            $_SESSION['message'] = "Error creating user: " . $conn->error;
        }

        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }

    if (isset($_POST['assign_privileges']) && !empty($_POST['user']) && !empty($_POST['table'])) {
        $table = preg_replace('/[^a-zA-Z0-9_]/', '', $_POST['table']);
        $userHost = explode('@', $_POST['user']);
        $user = $conn->real_escape_string($userHost[0]);
        $host = $conn->real_escape_string($userHost[1]);
        $dbName = $conn->query("SELECT DATABASE() AS db")->fetch_assoc()['db'];
        $fullTable = "`$dbName`.`$table`";

        $selectedPrivs = isset($_POST['privileges']) ? $_POST['privileges'] : [];
        $allPrivs = ['SELECT', 'INSERT', 'UPDATE', 'DELETE', 'CREATE', 'DROP', 'INDEX', 'ALTER'];

        foreach ($allPrivs as $priv) {
            $hasPriv = in_array($priv, $selectedPrivs);
            $alreadyHasPriv = userHasPrivilege($conn, $user, $host, $dbName, $table, $priv);

            if ($hasPriv && !$alreadyHasPriv) {
                $conn->query("GRANT $priv ON $fullTable TO '$user'@'$host'");
            } elseif (!$hasPriv && $alreadyHasPriv) {
                $conn->query("REVOKE $priv ON $fullTable FROM '$user'@'$host'");
            }
        }
    }
}

// Function to check if user has a specific privilege
function userHasPrivilege($conn, $user, $host, $db, $table, $priv)
{
    $result = $conn->query("SHOW GRANTS FOR '$user'@'$host'");
    $fullTable = "`$db`.`$table`";

    while ($row = $result->fetch_array()) {
        foreach ($row as $grant) {
            if (
                preg_match("/GRANT\s.+\sON\s`?$db`?\.`?$table`?\sTO/i", $grant) &&
                stripos($grant, $priv) !== false
            ) {
                return true;
            }
        }
    }

    return false;
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>DCL Manager</title>
    <link rel="stylesheet" href="../../../styles/output.css">
</head>

<body class="bg-gray-100 p-4 w-full min-h-screen flex flex-col items-center justify-center">
    <?php if (isset($_SESSION['message'])): ?>
        <div class="mb-4 p-3 rounded bg-blue-100 text-blue-800">
            <?= htmlspecialchars($_SESSION['message']) ?>
            <?php unset($_SESSION['message']); ?>
        </div>
    <?php endif; ?>
    <div class="max-w-3xl mx-auto bg-white rounded-xl shadow-md w-1/2 p-4">
        <h1 class="text-2xl font-bold mb-4">MySQL DCL Manager</h1>
        <a href="privileges.php" class="my-4 font-bold text-lg text-green-500"> Test privileges </a>
        <!-- Create user form -->
        <form method="POST" class="mb-6" name='create-user'>
            <h2 class="font-semibold text-lg mb-2">Create New User</h2>
            <div class="grid grid-cols-2 gap-4">
                <input name="new_user" type="text" placeholder="Username" class="border p-2 rounded" required />
                <input name="new_password" type="password" placeholder="Password" class="border p-2 rounded" required />
            </div>
            <button type="submit" style="color: white; background: green; padding: 8px; border-radius: 8px; cursor: pointer; width: 200px; margin: 5px 0;" name="create_user">Create user</button>
        </form>

        <!-- Privilege Management -->
        <form method="POST">
            <div class="mb-4">
                <label class="block font-medium">Select User</label>
                <select name="user" onchange="this.form.submit()" class="w-full border p-2 rounded" required>
                    <option value="">-- Select User --</option>
                    <?php foreach ($users as $u): ?>
                        <option value="<?= $u ?>" <?= isset($_POST['user']) && $_POST['user'] == $u ? 'selected' : '' ?>><?= $u ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-4">
                <label class="block font-medium">Select Table</label>
                <select name="table" onchange="this.form.submit()" class="w-full border p-2 rounded" required>
                    <option value="">-- Select Table --</option>
                    <?php foreach ($tables as $t): ?>
                        <option value="<?= $t ?>" <?= isset($_POST['table']) && $_POST['table'] == $t ? 'selected' : '' ?>><?= $t ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <?php if (isset($_POST['user']) && isset($_POST['table'])):
                $selectedUser = explode('@', $_POST['user']);
                $selectedTable = $_POST['table'];
                $dbName = $conn->query("SELECT DATABASE() AS db")->fetch_assoc()['db'];
            ?>
                <div>
                    <label class="block font-medium">Privileges</label>
                    <div class="grid grid-cols-2 gap-2 mt-2">
                        <?php
                        $privs = ['SELECT', 'INSERT', 'UPDATE', 'DELETE', 'CREATE', 'DROP', 'INDEX', 'ALTER'];
                        foreach ($privs as $priv):
                            $isChecked = userHasPrivilege($conn, $selectedUser[0], $selectedUser[1], $dbName, $selectedTable, $priv);
                        ?>
                            <label class="flex items-center gap-2">
                                <input type="checkbox" name="privileges[]" value="<?= $priv ?>" <?= $isChecked ? 'checked' : '' ?> />
                                <?= $priv ?>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>
                <button type="submit" style="color: white; background: green; padding: 8px; border-radius: 8px; cursor: pointer; width: 200px; margin: 5px 0;" name="assign_privileges">Apply privileges</button>
            <?php endif; ?>
        </form>

        <!-- Privilege Test -->
        <div class="mt-8">
            <h3 class="font-semibold text-lg mb-2">Privilege Testing (Try a SELECT)</h3>
            <form method="POST" action="privileges.php" class="flex items-center gap-4" style="flex-wrap: wrap">
                <input type="text" name="user" placeholder="Username" class="border p-2 rounded" required />
                <input type="text" name="password" placeholder="Password" class="border p-2 rounded" required />
                <input type="text" name="table" placeholder="Table Name" class="border p-2 rounded" required />
                <button type="submit" style="color: white; background: green; padding: 8px; border-radius: 8px; cursor: pointer; width: 100px;">Test</button>
            </form>
        </div>
    </div>
</body>

</html>