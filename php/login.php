<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../src/output.css">
    <link rel="stylesheet" href="../styles/login.css">
    <link rel="stylesheet" href="https://cdn.hugeicons.com/font/hgi-stroke-rounded.css" />
    <title>Complaint Management System - Login </title>
</head>
<body class="w-full min-h-screen flex items-center justify-center bg-no-repeat bg-center bg-cover">
    <form action="./submit-login.php" method="POST" >
        <h1 class="text-black text-2xl" > Login </h1>
        <div class="w-full flex form-div-email" >
            <input placeholder="Enter your email" class='border border-gray-500' type='email' name="email"/>
            <div class='icon-container'><i class="icon hgi hgi-stroke hgi-mail-01" ></i></div>
        </div>
        <div class="w-full flex form-div-password" >
            <input placeholder="Enter your password" class='border border-gray-500' type='password' name="password"/>
            <!-- <div class='icon-container'><i class="eye-icon hgi hgi-stroke hgi-view-off-slash"></i></div> -->
            <div class='icon-container'><i class="icon-no-eye hgi hgi-stroke hgi-view-off-slash"></i></div>
        </div>
        <div class='flex items-center justify-between w-full rem-me' >
            <div class='flex items-center justify-center remember-me-container' >
                <input type='checkbox'/>
                <div class='flex items-center justify-center remember-me'>
                    <span> Remember </span>
                    <span> me </span>
                </div>
            </div>
            <a href='' class='forgot-password' > forgot password? </a>
        </div>
        <button type='submit'>Login now </button>
        <p class='sign-up' > Don't have an account? <a href="sign-up.php" > sign up </a> </p>
    </form>
</body>
</html>