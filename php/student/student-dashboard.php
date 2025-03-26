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

$messagesSql = "SELECT * FROM complaint_messages WHERE sender_id = ? OR receiver_id = ? ORDER BY created_at ASC";
$stmt = $conn->prepare($messagesSql);
$stmt->bind_param("ii", $user_id, $user_id);
$stmt->execute();
$messagesResult = $stmt->get_result();
$messages = [];

while ($row = $messagesResult->fetch_assoc()) {
    $messages[] = $row;
}

$msgReceiver = "SELECT * FROM complaint_messages WHERE receiver_id = ? ORDER BY created_at ASC";
$stmt = $conn->prepare($msgReceiver);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$msgResult = $stmt->get_result();

//getting the admin ID from the last submitted message
$lastMsg = "SELECT admin_id FROM complaint_messages ORDER BY created_at DESC LIMIT 1";
$stmt = $conn->prepare($lastMsg);
$stmt->execute();
$lastMsgResult = $stmt->get_result();
if ($lastMsgResult->num_rows > 0) {
    $lastAdminID = $lastMsgResult->fetch_row()[0];
}
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
            <div class='profile' style="flex-direction: column;">
                <div class="nav-profile" style="display: flex; align-items: center; justify-content: end; gap: 5px; width: 100%;">
                    <img src="../../assets/images/profile-image.png" class="" alt="User">
                    <p class='profile-name'> <?php echo htmlspecialchars($name) ?> </p>
                </div>
                <style>
                    #update-info-form {
                        position: absolute;
                        display: block;
                        top: 75px;
                        right: 15px;
                        width: 300px;
                        height: 300px;
                        border-radius: 8px;
                        background-color: black;
                        padding: 15px;
                        color: white;
                        overflow: auto;
                    }

                    #update-info-form div {
                        display: flex;
                        flex-direction: column;
                    }

                    #update-info-form input,
                    #update-info-form select {
                        padding: 8px 5px;
                        border-radius: 2px;
                        color: black;
                        border: 1px solid white;
                    }

                    #update-info-form input::placeholder {
                        color: black;
                    }

                    #update-info-form label {
                        font-size: 13px;
                        margin-bottom: 5px;
                        margin-top: 8px;
                    }

                    #update-info-form button {
                        width: 100%;
                        padding: 13px;
                        background-color: green;
                        color: white;
                        border: none;
                        border-radius: 5px;
                        margin-top: 15px;
                        cursor: pointer;
                    }

                    #update-info-form.hidden {
                        display: none;
                    }
                </style>
                <script>
                    document.addEventListener('DOMContentLoaded', () => {
                        const form = document.getElementById('update-info-form');
                        const name = document.querySelector('.names');
                        const email = document.querySelector('.email');
                        const department = document.querySelector('.department');
                        const level = document.querySelector('.level');
                        form.classList.add('hidden');

                        const btn = document.querySelector('.nav-profile');
                        btn.addEventListener("click", () => {
                            form.classList.toggle('hidden');
                        });

                        form.addEventListener("submit", (e) => {
                            e.preventDefault();
                            if (name.value.trim() === "" || email.value.trim() === "" || department.value === "" || level.value === "") {
                                alert("All fields must be filled!");
                                return;
                            }
                            if (!/^[a-zA-Z\s]+$/.test(name.value)) {
                                alert("Names must contain only letters and spaces.");
                                return;
                            }
                            if (!/\S+@\S+\.\S+/.test(email.value)) {
                                alert("Please enter a valid email address.");
                                return;
                            }
                            form.submit();
                        });
                    });
                </script>
                <form id="update-info-form" id="update-info-form" action="update-profile.php" method="post">
                    <div>
                        <label> Names </label>
                        <input type="text" class="names" name='name' value="<?= htmlspecialchars($name) ?>" />
                    </div>
                    <div>
                        <label> Email </label>
                        <input type="email" name="email" class="email" value="<?= htmlspecialchars($email) ?>" />
                    </div>
                    <div>
                        <label> Department </label>
                        <select class="department" name="department">
                            <option> <?php echo htmlspecialchars($user['Department']) ?> </option>
                            <option> Maritime </option>
                            <option> Engineering </option>
                            <option> Law </option>
                        </select>
                    </div>
                    <div>
                        <label> Level </label>
                        <select class="level" name="level">
                            <option><?php echo htmlspecialchars($user['Level']) ?> </option>
                            <option> Level 100 </option>
                            <option> Level 200 </option>
                            <option> Level 300 </option>
                        </select>
                    </div>
                    <button> Update Information </button>
                </form>
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
                    <?php if ($msgResult->num_rows > 0): ?>
                        <div class="question-input">
                            <input type="text" placeholder="Reply to admin" id="replyMessage">
                            <button id="sendReply" style="cursor:pointer">Send</button>
                        </div>
                    <?php endif ?>
                    <input type="hidden" name='adminID' value="<?php echo htmlspecialchars($lastAdminID) ?>" />
                    <input type="hidden" name='sender-name' value="<?php echo htmlspecialchars($name) ?>" id="sender_name" />
                    <input type="hidden" name='studentID' value="<?php echo htmlspecialchars($complaint['studentID']) ?>" />
                    <input type="hidden" name='userRole' value="<?php echo htmlspecialchars($role) ?>" id='userRole' />
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