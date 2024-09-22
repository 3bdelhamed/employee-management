<?php
require_once "helpers/functions.php";
require_once "models/Manager.php";
require_once "core/DBManager.php";
session_start();
$user = auth();
$pdo = new DBManager();
$manager = new Manager($user->id, $pdo);
$employeeCount = $manager->getNumberOfEmployees();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <title>Home</title>
</head>

<body class="bg-gray-800 text-gray-100 h-screen">

    <?php include "components/messages/error.php" ?>
    <?php include "components/messages/success.php" ?>

    <?php include __DIR__ . '/views/partials/navbar.php'; ?>


    <section class="mt-5">
        <div class="max-w-4xl mx-auto bg-gray-700 text-center text-gray-100 p-6 rounded-lg border border-gray-600">
            <h5 class="text-xl font-semibold mb-4">Welcome, <?= $user->name ?></h5>
            <div class="bg-gray-800 p-4 rounded-lg shadow-lg flex items-center justify-center space-x-4">
                <a href="employees.php" class="flex items-center bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded space-x-2">
                    <i class="fas fa-users"></i>
                    <span>Employees</span>
                </a>
                <a href="add_employee.php" class="flex items-center bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded space-x-2">
                    <i class="fas fa-user-plus"></i>
                    <span>Add Employee</span>
                </a>
            </div>
        </div>
    </section>

    <section class="mt-5">
        <div class="max-w-4xl mx-auto p-6 rounded-lg border border-gray-600">
            <div class="bg-blue-500 text-white p-4 rounded-lg flex items-center space-x-4">
                <i class="fas fa-users text-3xl"></i>
                <div>
                    <h5 class="text-xl font-semibold">Employee Management</h5>
                    <p class="text-lg">You manage <span class="font-bold"><?= $employeeCount ?></span> employees.</p>
                </div>
            </div>
        </div>
    </section>

</body>

</html>