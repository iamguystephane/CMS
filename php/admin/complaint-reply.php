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
$department = $_SESSION['department'];
$studentID = $_GET['studentID'];    //get the student's ID who submitted the complaint using the GET parameter, passed through the link in view-complaints.php

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
$stmt->bind_param("i", $studentID);
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

$messagesSql = "SELECT * FROM complaint_messages WHERE sender_id = ? OR receiver_id = ? ORDER BY created_at ASC";
$stmt = $conn->prepare($messagesSql);
$stmt->bind_param("ii", $user_id, $user_id);
$stmt->execute();
$messagesResult = $stmt->get_result();
$messages = [];

while ($row = $messagesResult->fetch_assoc()) {
    $messages[] = $row;
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complaint Management System - Reply Complaint</title>
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
                        <p style="font-size: 14px"> <?php echo htmlspecialchars($user['role']) ?> </p>
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
                            <a href="#" class="active">Dashboard</a>
                        </div>
                    </div>
                    <div class='menu-list'>
                        <div>
                            <i class="icon hgi hgi-stroke hgi-user"></i>
                            <a href="#">My Profile</a>
                        </div>
                    </div>
                    <div class='menu-list'>
                        <div>
                            <i class="icon hgi hgi-stroke hgi-megaphone-02"></i>
                            <a href="view-complaints.php" class="highlight">Submit Complaint</a>
                        </div>
                    </div>
                    <div class='menu-list'>
                        <div>
                            <i class="icon hgi hgi-stroke hgi-notification-03"></i>
                            <a href="#">My Questions (Answers)</a>
                        </div>
                    </div>
                    <div class='menu-list'>
                        <div>
                            <i class="icon hgi hgi-stroke hgi-notification-03"></i>
                            <a href="#">My Questions</a>
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
            <style>
                .complaint-container {
                    display: flex;
                    flex-direction: column;
                    gap: 20px;
                    max-width: 900px;
                }

                .complaint-container .card {
                    background: white;
                    padding: 20px;
                    border-radius: 5px;
                    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                    width: 400px;
                    height: 350px;
                    overflow: auto;
                }

                .complaint-container .card h3 {
                    margin-top: 0;
                    padding-bottom: 10px;
                    border-bottom: 2px solid #ccc;
                    color: gray;
                    width: 100%;
                    font-size: 17px;
                    text-align: left;
                }

                .complaint-container .user-details {
                    border-left: 5px solid #0d6efd;
                }

                .complaint-container .complain-details {
                    border-left: 5px solid #f4a100;
                }

                .complaint-container label {
                    display: block;
                    margin: 10px 0 5px;
                    color: gray;
                    font-size: 13px;
                    width: 100%;
                    text-align: left;
                }

                .complaint-container input,
                .complaint-container select,
                .complaint-container textarea {
                    width: 100%;
                    padding: 8px;
                    border: 1px solid #ccc;
                    border-radius: 4px;
                    margin-bottom: 10px;
                }

                .complaint-container button {
                    background-color: #0d6efd;
                    color: white;
                    padding: 10px;
                    border: none;
                    width: 100%;
                    border-radius: 4px;
                    cursor: pointer;
                }

                .complaint-container button:hover {
                    background-color: #0b5ed7;
                }
            </style>
            <main class="main-content complaint-container">
                <div id="toast"></div>
                <form style="display: flex; width: 75vw; align-items: center; justify-content: center; gap: 15px; " method="POST">
                    <div class="questions-section" style="width: 100%; height: 80vh; overflow: auto;">
                        <h3>Complaint Conversation</h3>
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
                                        <?php
                                        $sender_id = $msg['sender_id'];
                                        $query = "SELECT * FROM `sign up` WHERE id = ?";
                                        $stmt = $conn->prepare($query);
                                        $stmt->bind_param("i", $sender_id);
                                        $stmt->execute();
                                        $result = $stmt->get_result();
                                        if ($result->num_rows == 1) {
                                            $user = $result->fetch_assoc();
                                        } else {
                                            echo "User not found!";
                                        }
                                       
                                        ?>
                                        <p> <?= htmlspecialchars($user['Names']) ?> </p>
                                        <p> <?php echo date("d-m-Y H:i", strtotime($msg['created_at'])); ?> </p>
                                    </div>
                                    <div style="display: flex; gap: 5px; align-items: center;">
                                        <p class='question' style="width: 100%; padding: 10px; background: <?php echo ($user['role'] === 'admin') ? '#007bff' : '#ccc'; ?>; color: white; border-radius: 5px;">
                                            <?php echo htmlspecialchars($msg['message']); ?>
                                        </p>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <div class="question-input" style="display: flex; gap: 5px;">
                            <input type="text" id="replyMessage" placeholder="Type a reply..." style="height: 30px; flex-grow: 1;">
                            <button style="width: 100px; height: 45px;" id="sendReply">Send</button>
                        </div>
                    </div>
                    <input type="hidden" name='adminID' value="<?php echo htmlspecialchars($user_id) ?>" />
                    <input type="hidden" name='sender-name' value="<?php echo htmlspecialchars($name) ?>" id="sender_name" />
                    <input type="hidden" name='studentID' value="<?php echo htmlspecialchars($complaint['studentID']) ?>" />
                    <input type="hidden" name='userRole' value="<?php echo htmlspecialchars($role) ?>" />
                </form>
                <footer style="width: 100%; position: fixed; bottom: 0; background-color: gray; color: white; text-align: left; padding-left: 10px;">
                    <p>Copyright &copy; 2018 <span class="bold">Final Year Project</span>. All rights reserved.</p>
                </footer>
            </main>
        </div>
        <script src='../../js/student-dashboard.js' defer></script>
        <script src='../../js/registration-toast.js' defer></script>
        <script src='../../js/send-reply-admin.js' defer></script>
</body>

</html>