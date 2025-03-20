<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/sign-up.css">
    <link rel="stylesheet" href="https://cdn.hugeicons.com/font/hgi-stroke-rounded.css" />
    <link rel="stylesheet" href="../src/output.css">
    <title>Complaint Management System - Sign up </title>
</head>
<body>
    <div id="toast" class="hidden fixed top-5 right-5 bg-green-500 text-white py-2 px-4 rounded shadow-lg transition-opacity duration-500"></div>
    <form class='' action='submit-sign-up.php' method='POST' >
        <h1 class='text-xl font-bold' > Sign up </h1>
        <div class='w-full' >
            <p> Full names </p>
            <input class='names border w-full px-2 py-2 rounded focus:outline-blue-500' name='names' />
        </div>
        <div class='w-full mt-2' >
            <p> Email </p>
            <input class='email border w-full px-2 py-2 rounded focus:outline-blue-500' type='email' name='email' />
        </div>
        <div class='w-full mt-2' >
            <p> Department </p>
            <select class='department w-full border p-2 rounded' name='department' >
                <option> Engineering and Technology </option>
                <option> Law </option>
                <option> Maritime </option>
                <option> Business, Finance, and Management </option>
            </select>
        </div>
        <div class='w-full mt-2' >
            <p> Level </p>
            <select class='level w-full border p-2 rounded' name='level' >
                <option> Level 100 </option>
                <option> Level 200 </option>
                <option> Level 300 </option>
            </select>
        </div>
        <div class='w-full mt-2' >
            <p> Role </p>
            <select class='level w-full border p-2 rounded' name='role' >
                <option> Admin </option>
                <option> Student </option>
            </select>
        </div>
        <div class='w-full' >
            <p> Password </p>
            <div class='flex items-center justify-center !relative' >
                <input class='password border w-full px-2 py-2 rounded focus:outline-blue-500' type='password' name='password' />
                <div class='no-eye !absolute right-5 cursor-pointer p-2 hover:bg-black hover:text-white transition-all duration-300 ease-in-out rounded-full w-[30px] h-[30px] flex items-center justify-center'><i class="hgi hgi-stroke hgi-view-off-slash"></i></div>
                <div class='show-eye !absolute right-5 cursor-pointer p-2 hover:bg-black hover:text-white transition-all duration-300 ease-in-out rounded-full w-[30px] h-[30px] grid place-content-center'><i class="hgi hgi-stroke hgi-eye"></i></div>
            </div>
            <p class='password-error text-red-500 text-sm w-full' >  </p>
        </div>
        <div class='w-full' >
            <p> Confirm password </p>
            <input class='confirmPassword border w-full px-2 py-2 rounded focus:outline-blue-500' type='password' />
        </div>
        <p class="w-full mt-4"> already have an account? <a href="./login.php" style="color: red;"> login </a></p>
        <button type='submit' class='submit-btn w-full bg-purple-500 py-3 rounded text-white mt-5 cursor-pointer hover:bg-purple-800 transition-all duration-500 ease-in-out'> Sign up </button>
    </form>
    <script src='../js/sign-up-validation.js' defer></script>
    <script src='../js/toast.js' defer></script>
</body>
</html>



