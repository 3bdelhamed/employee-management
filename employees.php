<?php
require_once "helpers/functions.php";
require_once "core/DBManager.php";
require_once "models/Manager.php";

session_start();
$user = auth();
$pdo = new DBManager();

$manager = new Manager($user->id,$pdo);
$employees = $manager->getAllEmployees();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <title>Employees</title>
</head>

<body class="bg-gray-800 text-gray-100 h-screen flex flex-col">

    <?php include "components/messages/error.php"; ?>
    <?php include "components/messages/success.php"; ?>

    <?php include __DIR__ . '/views/partials/navbar.php'; ?>

    <main class="flex-grow">
        <section class="w-full max-w-6xl mx-auto bg-gray-700 p-6 rounded-lg border border-gray-600 shadow-lg mt-8">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-100">Employees List</h2>
                <a href="add_employee.php" class="flex items-center bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded space-x-2">
                    <i class="fas fa-user-plus"></i>
                    <span>Add Employee</span>
                </a>
            </div>

            <table class="w-full bg-gray-800 text-gray-100 border border-gray-600 rounded-md shadow-md">
                <thead>
                    <tr>
                        <th class="p-4 text-center">🗝️</th>
                        <th class="p-4 text-center">Name</th>
                        <th class="p-4 text-center">Email</th>
                        <th class="p-4 text-center">Phone</th>
                        <th class="p-4 text-center">Picture</th>
                        <th class="p-4 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($employees) > 0): ?>
                        <?php foreach ($employees as $employee): ?>
                            <tr>
                                <td class="p-4 text-center"><?= $employee['id']; ?></td>
                                <td class="p-4 text-center"><?= $employee['name']; ?></td>
                                <td class="p-4 text-center"><?= $employee['email']; ?></td>
                                <td class="p-4 text-center"><?= $employee['phone']; ?></td>
                                <td class="p-4 text-center">
                                    <?php if ($employee['picture']): ?>
                                        <img src="uploads/<?= $employee['picture']; ?>" alt="Picture"
                                            class="w-16 h-16 object-cover rounded-full mx-auto block">
                                    <?php else: ?>
                                        No Picture
                                    <?php endif; ?>
                                </td>
                                <td class="p-4 text-center">
                                    <a href="edit_employee.php?id=<?= $employee['id']; ?>" class="text-blue-500 hover:text-blue-700">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <form action="delete_employee.php" method="POST" onsubmit="return confirm('Are you sure you want to delete this employee?');" class="inline-block">
                                        <input type="hidden" name="id" value="<?= $employee['id']; ?>">
                                        <button type="submit" class="text-red-500 hover:text-red-700 ml-2">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="p-4 text-center">No employees found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </section>
    </main>
</body>

</html>