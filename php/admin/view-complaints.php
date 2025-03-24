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

$stmt->close();
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complaint Management System - Admin Dashboard</title>
    <link rel="stylesheet" href='../../styles/student-dashboard.css'>
    <link rel="stylesheet" href='../../styles/admin-dashboard.css'>
    <link rel="stylesheet" href="https://cdn.hugeicons.com/font/hgi-stroke-rounded.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
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
                            <a href="#" class="highlight">View Complaints</a>
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

            <!-- Main Content -->
            <style>
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

                .status-btn {
                    padding: 5px 10px;
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

                .delete-btn {
                    color: red;
                    cursor: pointer;
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

                .user-avatar {
                    width: 40px;
                    height: 40px;
                    border-radius: 50%;
                }
            </style>
            <div class="main-content mt-4">
                <table class="table table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>User</th>
                            <th>Question</th>
                            <th>Status</th>
                            <th>Priority</th>
                            <th>Created On</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><img src="https://via.placeholder.com/40" class="user-avatar" alt="User"></td>
                            <td>This is a really needed system ... <span class="response-badge has-response">Has a response</span></td>
                            <td><span class="status-btn status-answer">Answer</span> <span class="status-btn status-close">Close</span></td>
                            <td><span class="priority-high">Very High</span></td>
                            <td>2018-03-16 17:15:32</td>
                            <td><span class="delete-btn">🗑 Delete</span></td>
                        </tr>
                        <tr>
                            <td><img src="https://via.placeholder.com/40" class="user-avatar" alt="User"></td>
                            <td>this is a test and I hope it works ... <span class="response-badge no-response">No Answer</span></td>
                            <td><span class="status-btn status-answer">Answer</span> <span class="status-btn status-close">Close</span></td>
                            <td><span class="priority-high">Very High</span></td>
                            <td>2018-03-16 17:30:31</td>
                            <td><span class="delete-btn">🗑 Delete</span></td>
                        </tr>
                        <tr>
                            <td><img src="https://via.placeholder.com/40" class="user-avatar" alt="User"></td>
                            <td>This is my question from a question table ... <span class="response-badge has-response">Has a response</span></td>
                            <td><span class="status-btn status-close">Closed</span></td>
                            <td><span class="priority-average">Average</span></td>
                            <td>2018-03-16 15:48:00</td>
                            <td><span class="delete-btn">🗑 Delete</span></td>
                        </tr>
                        <tr>
                            <td><img src="https://via.placeholder.com/40" class="user-avatar" alt="User"></td>
                            <td>When calling the paginate method, you will receive an instance of Illuminate\Pagination\L... <span class="response-badge no-response">No Answer</span></td>
                            <td><span class="status-btn status-answer">Answer</span> <span class="status-btn status-close">Close</span></td>
                            <td><span class="priority-average">Average</span></td>
                            <td>2018-03-16 17:56:24</td>
                            <td><span class="delete-btn">🗑 Delete</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>
        <script src='../../js/student-dashboard.js'></script>
        <script src='../../js/registration-toast.js'></script>
</body>

</html>