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
    <link rel="stylesheet" href='../../styles/student-dashboard.css' >
    <link rel="stylesheet" href='../../styles/admin-dashboard.css' >
    <link rel="stylesheet" href="https://cdn.hugeicons.com/font/hgi-stroke-rounded.css" />
</head>
<body>
    <div class="container">
        <div class='nav-bar'>
            <p class='logo'> Complaint mgt </p>
            <i class="menu-icon hgi hgi-stroke hgi-menu-01"></i>
            <div class='profile'>
                <img src="../../assets/images/profile-image.png" class="" alt="User">
                <p class='profile-name'> <?php echo htmlspecialchars($name)?> </p>
            </div>
        </div>
        <div class='main-content-container'>
            <!-- Sidebar -->
            <aside class="sidebar">
                <div class="user-info">
                    <img src="../../assets/images/profile-image.png" class="avatar" alt="User">
                    <div>
                        <p class="username"><?php echo htmlspecialchars($name)?></p>
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
                    <div class='menu-list' >
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
            
            <!-- Main Content -->
            <main class="main-content">
                <div id="toast"></div>
                <style>
                    .dashboard {
                        display: flex;
                        gap: 15px;
                        margin-bottom: 1em;
                    }
                    .d-card {
                        width: 200px;
                        padding: 20px;
                        border-radius: 8px;
                        color: white;
                        text-align: left;
                        position: relative;
                        cursor: pointer;
                    }
                    .d-card:hover {
                        background-color: grey;
                    }
                    .d-card h1 {
                        margin: 0;
                        font-size: 2rem;
                    }
                    .d-card p {
                        margin: 5px 0;
                    }
                    .d-card a {
                        text-decoration: none;
                        color: white;
                        font-size: 14px;
                        position: absolute;
                        bottom: 10px;
                    }
                    .d-card:nth-child(1) { background-color: #f39c12; }
                    .d-card:nth-child(2) { background-color: #00aaff; }
                    .d-card:nth-child(3) { background-color: #e74c3c; }
                    .d-card:nth-child(4) { background-color: #27ae60; }
                </style>
                <h2 class="title">Dashboard <span class="subtitle">Control Panel</span></h2>
                <div class="dashboard">
                    <div class="d-card">
                        <h1>2</h1>
                        <p>Users Registrations</p>
                        <a href="#">More info →</a>
                    </div>
                    <div class="d-card">
                        <h1>4</h1>
                        <p>Questions Asked</p>
                        <a href="#">More info →</a>
                    </div>
                    <div class="d-card">
                        <h1>4</h1>
                        <p>Total Answers</p>
                        <a href="#">More info →</a>
                    </div>
                    <div class="d-card">
                        <h1>1000</h1>
                        <p>System Stability Ratio</p>
                        <a href="#">More info →</a>
                    </div>
                </div>
                <!-- Questions Section -->
                <div class="questions-section">
                    <h3>Direct Questions</h3>
                    <div class="questions-list">
                        <div style="display: flex; flex-direction: column; gap: 0px;">
                            <div style="display: flex; justify-content: space-between; align-items: center; padding: 0;"> 
                                <p> admin </p>  
                                <p> 15-03-2025 14:30 </p>
                            </div>
                            <div style="display: flex; gap: 5px; align-items: center; padding: 0;">
                                
                                    <i class="icon hgi hgi-stroke hgi-user"></i>
                               
                                <p class='question' style="width: 100%; padding: 40px 5px">This is a really needed system</p>
                            </div>
                        </div>
                        
                    </div>
                    <div class="questions-list">
                        <div style="display: flex; flex-direction: column; gap: 0px;">
                            <div style="display: flex; justify-content: space-between; align-items: center; padding: 0;"> 
                                <p> <?php echo htmlspecialchars($name)?> </p>  
                                <p> 15-03-2025 14:30 </p>
                            </div>
                            <div style="display: flex; gap: 5px; align-items: center; padding: 0;">
                                
                            <img src="../../assets/images/profile-image.png" style="width: 40px; height: 40px; border-radius: 50%;"/> 
                               
                                <p class='question' style="width: 100%; padding: 40px 5px">This is a really needed system</p>
                            </div>
                        </div>
                        
                    </div>
                    <div class="question-input">
                        <input type="text" placeholder="Type Question...">
                        <button>Send</button>
                    </div>
                </div>
            
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
</body>
</html>