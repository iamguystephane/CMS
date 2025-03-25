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
$sql = "SELECT complaints.*, `sign up`.names AS names FROM complaints JOIN `sign up` ON complaints.studentID = `sign up`.id  ORDER BY complaints.created_at DESC";
$result = $conn->query($sql);
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complaint Management System - View Complaints</title>
    <link rel="stylesheet" href='../../styles/student-dashboard.css'>
    <link rel="stylesheet" href='../../styles/admin-dashboard.css'>
    <link rel="stylesheet" href="https://cdn.hugeicons.com/font/hgi-stroke-rounded.css" />

</head>

<body>
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
                        <p class="username"><?php echo htmlspecialchars($name) ?></p>
                        <p style="font-size: 12px;"> admin </p>
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
                            <a href="view-complaints.php" class="highlight">View Complaints</a>
                        </div>
                    </div>
                    <div class='menu-list'>
                        <div>
                            <i class="icon hgi hgi-stroke hgi-notification-03"></i>
                            <a href="#">My Questions (Answres)</a>
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
            <style>
                .complaint-container {
                    width: 90%;
                    max-width: 1000px;
                    margin: auto;
                    background: white;
                    padding: 20px;
                    border-radius: 10px;
                    box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
                }

                table {
                    width: 100%;
                    border-collapse: collapse;
                    margin-top: 20px;
                }

                th,
                td {
                    border: 1px solid #ddd;
                    padding: 10px;
                    text-align: left;
                }

                th {
                    background-color: #333;
                    color: white;
                }

                .user-avatar {
                    width: 40px;
                    height: 40px;
                    border-radius: 50%;
                }

                .status-btn {
                    display: inline-block;
                    padding: 5px 10px;
                    font-size: 14px;
                    border-radius: 5px;
                    cursor: pointer;
                    text-decoration: none;
                    margin-right: 5px;
                }

                .status-answer {
                    background-color: blue;
                    color: white;
                }

                .status-close {
                    background-color: red;
                    color: white;
                }

                .closed-status {
                    background-color: gray;
                    color: white;
                    padding: 5px 10px;
                    border-radius: 5px;
                }

                .priority-high {
                    background-color: red;
                    color: white;
                    padding: 5px 10px;
                    border-radius: 5px;
                }

                .priority-average {
                    background-color: green;
                    color: white;
                    padding: 5px 10px;
                    border-radius: 5px;
                }

                .delete-btn {
                    color: red;
                    cursor: pointer;
                    text-decoration: none;
                }

                .response-badge {
                    padding: 5px 10px;
                    border-radius: 5px;
                    font-weight: bold;
                }

                .has-response {
                    background-color: green;
                    color: white;
                }

                .no-response {
                    background-color: red;
                    color: white;
                }
            </style>
            <!-- Main Content -->
            <main class="main-content">
                <div class="complaint-container">
                    <table>
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Question</th>
                                <th>Status</th>
                                <th>Priority</th>
                                <th>Submitted on</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><img src="https://via.placeholder.com/40" class="user-avatar" alt="User"><br><br><span><?= htmlspecialchars($row['names']) ?></span></td>
                                    <td><?= htmlspecialchars($row['description']) ?></td>
                                    <td><span class="status-btn status-answer">Answer</span> <span class="status-btn status-close">Close</span></td>
                                    <td><span class="priority-average"><?= htmlspecialchars($row['priority']) ?></span></td>
                                    <td><?= htmlspecialchars($row['created_at']) ?></td>
                                    <td><a href="#" class="delete-btn">🗑 Delete</a></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
                <footer class="footer">
                    <p>Copyright &copy; 2018 <span class="bold">Final Year Project</span>. All rights reserved.</p>
                </footer>
            </main>
        </div>
        <script src='../../js/student-dashboard.js'></script>
        <script src='../../js/registration-toast.js'></script>
</body>

</html>