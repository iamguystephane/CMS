<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../styles/output.css">
    <title>Testing</title>
</head>

<body class="!py-10">
    <div class="w-full min-h-screen flex items-center justify-center">
        <div class="w-1/2 flex flex-col items-center justify-center px-10">
            <h1 class="w-full font-bold text-xl"> Creating a table </h1>
            <p class="w-full my-3"> Please enter the table's details to create a student table </p>
            <form class="shadow shadow-white rounded-lg p-4 flex flex-col gap-4 w-full border" method="POST" action="test/create-table.php">
                <div class="flex flex-col gap-2">
                    <label> Table name </label>
                    <input type="text" name='table-name' class="border w-full p-1 border-gray-500 focus:outline-blue-500 rounded px-2" />
                </div>
                <div class="flex flex-col gap-2">
                    <label> Student name </label>
                    <input type="text" name='name' class="border w-full p-1 border-gray-500 focus:outline-blue-500 rounded px-2" />
                </div>
                <div class="flex flex-col gap-2">
                    <label> Email </label>
                    <input type="email" name='email' class="border w-full p-1 border-gray-500 focus:outline-blue-500 rounded px-2" />
                </div>
                <div class="flex flex-col gap-2">
                    <label> Matricule </label>
                    <input type="text" name='matricule' class="border w-full p-1 border-gray-500 focus:outline-blue-500 rounded px-2" />
                </div>
                <button class="w-full bg-green-500 text-white hover:bg-green-700 transition-all duration-500 ease-in-out py-2 cursor-pointer"> Create table </button>
            </form>
        </div>
        <div class="w-1/3">
            <h1 class="w-full font-bold text-xl mt-4"> Dropping a table </h1>
            <p class="w-full my-3"> Deleting a table from the database </p>
            <form class="shadow shadow-white rounded-lg p-4 mb-4 flex flex-col gap-4 w-full border" method="POST" action="test/drop-table.php">
                <div class="flex flex-col gap-2">
                    <label> Table name </label>
                    <input type="text" name='table-name' class="border w-full p-1 border-gray-500 focus:outline-blue-500 rounded px-2" placeholder="Enter the table's name that you want to delete" />
                    <button type='submit' class="w-full bg-green-500 text-white hover:bg-green-700 transition-all duration-500 ease-in-out py-2 cursor-pointer"> Delete table </button>
                </div>
            </form>
        </div>
    </div>
    <div class="!w-full min-h-screen flex items-center justify-center gap-4">
        <div class="w-1/3">
            <h1 class="w-full font-bold text-xl mt-4"> Truncating a table </h1>
            <p class="w-full my-3"> Truncating a table means deleting all the records on that table </p>
            <form class="shadow shadow-white rounded-lg p-4 mb-4 flex flex-col gap-4 w-full border" method="POST" action="test/truncate-table.php">
                <div class="flex flex-col gap-2">
                    <label> Table name </label>
                    <input type="text" name='table-name' class="border w-full p-1 border-gray-500 focus:outline-blue-500 rounded px-2" placeholder="Enter the table's name that you want to truncate" />
                    <button type='submit' class="w-full bg-green-500 text-white hover:bg-green-700 transition-all duration-500 ease-in-out py-2 cursor-pointer"> Truncate table </button>
                </div>
            </form>
        </div>
        <div class="w-1/3">
            <h1 class="w-full font-bold text-xl mt-4"> Altering a table </h1>
            <p class="w-full my-3"> Altering a table means deleting modifying the schema of that table </p>
            <form class="shadow shadow-white rounded-lg p-4 mb-4 flex flex-col gap-4 w-full border" method="POST" action="test/alter-table.php">
                <div class="flex flex-col gap-2">
                    <label> Table name </label>
                    <input type="text" name='table-name' class="border w-full p-1 border-gray-500 focus:outline-blue-500 rounded px-2" placeholder="Enter the table's name that you want to alter" />
                </div>
                <div class="flex flex-col gap-2">
                    <label> Column name </label>
                    <input type="text" name='column-name' class="border w-full p-1 border-gray-500 focus:outline-blue-500 rounded px-2" placeholder="Enter the Column name that you want to add" />
                </div>
                <div class="flex flex-col gap-2">
                    <label> Datatype </label>
                    <input type="text" name='column-type' class="border w-full p-1 border-gray-500 focus:outline-blue-500 rounded px-2" placeholder="Enter the datatype of the column" />
                </div>
                <button type='submit' class="w-full bg-green-500 text-white hover:bg-green-700 transition-all duration-500 ease-in-out py-2 cursor-pointer"> Alter table </button>
            </form>
        </div>
    </div>
</body>

</html>