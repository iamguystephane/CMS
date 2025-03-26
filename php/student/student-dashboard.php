<?php
session_start(); // Start session to access session variables
require_once '../database-connection.php';

// Check if the user is logged in (session exists)
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");  // Redirect to login if user is not logged in
    exit();
}

// Retrieve user details from the session
$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];
$email = $_SESSION['email'];
$name = $_SESSION['name'];

// Optionally, fetch user details from the database if needed
$sql = "SELECT * FROM `sign up` WHERE ID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 1) {
    $user = $result->fetch_assoc();
    // You can now use the data from $user for other operations if needed
} else {
    echo "User not found!";
}

$complaintSql = "SELECT * FROM complaints WHERE studentID = ? ORDER BY created_at DESC LIMIT 1";
$stmt = $conn->prepare($complaintSql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$complaintResult = $stmt->get_result();

if ($complaintResult->num_rows == 1) {
    $complaint = $complaintResult->fetch_assoc();
} else {
    echo "<script>
        localStorage.setItem('toastMessage', 'No complaints found for this student');
        window.location.href = 'view-complaints.php?success=not-found';
    </script>";
    exit();
}

$messagesSql = "SELECT * FROM messages WHERE studentID = ? ORDER BY created_at ASC";
$stmt = $conn->prepare($messagesSql);
$stmt->bind_param("i", $studentID);
$stmt->execute();
$messagesResult = $stmt->get_result();
$messages = [];

while ($row = $messagesResult->fetch_assoc()) {
    $messages[] = $row;
}

$stmt->close();
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complaint Management System - Student dashboard</title>
    <link rel="stylesheet" href='../../styles/student-dashboard.css'>
    <link rel="stylesheet" href="https://cdn.hugeicons.com/font/hgi-stroke-rounded.css" />
</head>

<body>
    <div id="toast" style="display: none; position: fixed; top: 10px; right: 20px; background-color: green; color: white; padding: 8px 40px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); transition: opacity 0.5s;"></div>

    <div class="container">
        <div class='nav-bar'>
            <p class='logo'> Complaint mgt </p>
            <i class="menu-icon hgi hgi-stroke hgi-menu-01"></i>
            <div class='profile'>
                <img src="../../assets/images/profile-image.png" class="" alt="User">
                <p class='profile-name'> <?php echo htmlspecialchars($name) ?> </p>
            </div>
        </div>
        <div class='main-content-container'>
            <!-- Sidebar -->
            <aside class="sidebar">
                <div class="user-info">
                    <img src="../../assets/images/profile-image.png" class="avatar" alt="User">
                    <div>
                        <p class="username" style="font-size: 15px;"><?php echo htmlspecialchars($name) ?></p>
                        <p> student </p>
                    </div>
                </div>
                <div class='search-box'>
                    <input type="text" placeholder="Search...">
                    <i class="search-icon hgi hgi-stroke hgi-search-01"></i>
                </div>
                <div class='main-navigation'>
                    <h1>Main navigation</h1>
                </div>
                <nav class="nav-menu">
                    <div class='menu-list'>
                        <div>
                            <i class="icon hgi hgi-stroke hgi-dashboard-speed-02"></i>
                            <a href="student-dashboard.php" class="active">Dashboard</a>
                        </div>
                    </div>
                    <div class='menu-list'>
                        <div>
                            <i class="icon hgi hgi-stroke hgi-user"></i>
                            <a href="profile.php">My Profile</a>
                        </div>
                    </div>
                    <div class='menu-list'>
                        <div>
                            <i class="icon hgi hgi-stroke hgi-megaphone-02"></i>
                            <a href="submit-complaint.php" class="highlight">Submit Complaint</a>
                        </div>
                    </div>
                    <div class='menu-list'>
                        <div>
                            <i class="icon hgi hgi-stroke hgi-notification-03"></i>
                            <a href="answers.php">My Questions (Answres)</a>
                        </div>
                    </div>
                    <div class='menu-list'>
                        <div>
                            <i class="icon hgi hgi-stroke hgi-notification-03"></i>
                            <a href="questions.php">My Questions</a>
                        </div>
                    </div>
                    <a href="../logout.php" style="display: flex; align-items: center; width: 100%; text-decoration: none; color: white;" class="menu-list">
                        <div>
                            <i class="logout-icon icon hgi hgi-stroke hgi-logout-02" style="color: red"></i>
                            <button style="background-color: transparent; border: none; color: white; font-size: 19px;"> Logout </button>
                        </div>
                    </a>
                </nav>
            </aside>

            <!-- Main Content -->
            <main class="main-content">
                <div id="toast"></div>
                <h2 class="title">Dashboard <span class="subtitle">Control Panel</span></h2>

                <!-- Questions Section -->
                <form class="questions-section" method="post">
                    <h3>Direct Questions</h3>
                    <div class="questions-list">
                        <div style="display: flex; flex-direction: column;">
                            <div style="width: 100%; display: flex; justify-content: space-between;">
                                <p> <?php echo htmlspecialchars($complaint['names']) ?> </p>
                                <p> <?php echo htmlspecialchars($complaint['created_at']) ?> </p>
                            </div>
                            <p class='question' style="width: 98%; padding: 15px; color: white; border-radius: 5px; background-color: #0b5ed7">
                                <?php echo htmlspecialchars($complaint['description']); ?>
                            </p>
                        </div>
                        <?php foreach ($messages as $msg): ?>
                            <div style="display: flex; flex-direction: column; gap: 5px;">
                                <div style="display: flex; justify-content: space-between; align-items: center;">
                                    <p> <?php echo ($msg['sender'] === 'admin') ? $name : 'Student'; ?> </p>
                                    <p> <?php echo date("d-m-Y H:i", strtotime($msg['created_at'])); ?> </p>
                                </div>
                                <div style="display: flex; gap: 5px; align-items: center;">
                                    <p class='question' style="width: 100%; padding: 10px; background: <?php echo ($msg['sender'] === 'admin') ? '#007bff' : '#ccc'; ?>; color: white; border-radius: 5px;">
                                        <?php echo htmlspecialchars($msg['message']); ?>
                                    </p>
                                </div>
                            </div>
                        <?php endforeach; ?>

                    </div>
                    <div class="question-input">
                        <input type="text" placeholder="Reply to admin" id="replyMessage">
                        <button id="sendReply" style="cursor:pointer">Send</button>
                    </div>
                    <input type="hidden" name='adminID' value="<?php echo htmlspecialchars($user_id) ?>" />
                    <input type="hidden" name='studentID' value="<?php echo htmlspecialchars($complaint['studentID']) ?>" />
                    <input type="hidden" name='userRole' value="<?php echo htmlspecialchars($role) ?>" id='userRole'/>
                </form>

                <!-- Stats Cards -->
                <div class="stats-grid">
                    <div class="card orange">
                        <p>MY QUESTIONS</p>
                        <p class="number">3</p>
                        <p>50% Increase in 30 Days</p>
                    </div>
                    <div class="card green">
                        <p>QUESTIONS BANK</p>
                        <p class="number">4</p>
                        <p>20% Increase in 2 Days</p>
                    </div>
                    <div class="card red">
                        <p>ANSWERS</p>
                        <p class="number">4</p>
                        <p>70% Increase in 4 Days</p>
                    </div>
                    <div class="card blue">
                        <p>SYSTEM RELIABILITY</p>
                        <p class="number">163,921</p>
                        <p>40% Increase in 14 Days</p>
                    </div>
                </div>
        </div>
        <footer class="footer">
            <p>Copyright &copy; 2018 <span class="bold">Final Year Project</span>. All rights reserved.</p>
        </footer>
        </main>
    </div>
    <script src='../../js/student-dashboard.js'></script>
    <script src='../../js/registration-toast.js'></script>
    <script src='../../js/send-reply-student.js' defer></script>
</body>

</html>