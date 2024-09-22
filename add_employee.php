<?php
require_once "helpers/functions.php";
require_once "core/DBManager.php";
require_once "models/Manager.php";
require_once "core/FileManager.php";

session_start();
$user = auth();
$errors = [];

if (server()->isPostRequest()) {

    $name = request()->get('name');
    $email = request()->get('email');
    $phone = request()->get('phone');
    $picture = $_FILES['picture'];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Invalid email format.';
    }

    if (strlen($phone) < 10 || strlen($phone) > 15) {
        $errors[] = 'Phone number must be at least 10 digits.';
    }

    $picturePath = null;
    if ($picture && $picture['error'] === UPLOAD_ERR_OK) {
        $fileManager = new FileManager();
        $picturePath = $fileManager->store($picture);
        if ($picturePath === false) {
            $errors[] = 'Error in file upload.';
        }
    }

    if (empty($errors)) {
        $dbManager = new DBManager();
        $manager = new Manager($user->id, $dbManager);
        $manager->addEmployee($name, $email, $phone, $picturePath);
        $_SESSION['success'] = 'Employee added successfully!';
        header('Location: employees.php');
        exit;
    } else {
        $_SESSION['errors'] = $errors;
        header('Location: add_employee.php');
        exit;
    }
}
?>






<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <title>Add Employee</title>
</head>

<body class="bg-gray-800 text-gray-100 h-screen flex flex-col">

    <?php include __DIR__ . '/views/partials/navbar.php'; ?>

    <main class="flex-grow">
        <section class="w-full max-w-md mx-auto bg-gray-700 p-6 rounded-lg border border-gray-600 shadow-lg mt-8">
            <h2 class="text-2xl font-bold text-gray-100 mb-6 text-center">Add New Employee</h2>

            <?php include "components/messages/success.php"; ?>
            <?php include "components/messages/error.php"; ?>

            <form action="add_employee.php" method="POST" enctype="multipart/form-data" class="space-y-4">
                <div>
                    <label for="name" class="block text-gray-300">Name</label>
                    <input type="text" id="name" name="name" class="w-full px-4 py-2 rounded-md border border-gray-600 bg-gray-800 text-gray-100 placeholder-gray-400 focus:border-blue-500 focus:ring-1 focus:ring-blue-500" required>
                </div>
                <div>
                    <label for="email" class="block text-gray-300">Email</label>
                    <input type="email" id="email" name="email" class="w-full px-4 py-2 rounded-md border border-gray-600 bg-gray-800 text-gray-100 placeholder-gray-400 focus:border-blue-500 focus:ring-1 focus:ring-blue-500" required>
                </div>
                <div>
                    <label for="phone" class="block text-gray-300">Phone Number</label>
                    <input type="text" id="phone" name="phone" class="w-full px-4 py-2 rounded-md border border-gray-600 bg-gray-800 text-gray-100 placeholder-gray-400 focus:border-blue-500 focus:ring-1 focus:ring-blue-500" required>
                </div>
                <div>
                    <label for="picture" class="block text-gray-300">Picture</label>
                    <input type="file" id="picture" name="picture" class="w-full bg-gray-800 text-white-100 file:bg-gray-700 file:border-gray-600  file:py-2 file:px-4 file:cursor-pointer hover:file:bg-gray-600" accept="image/*" required>
                </div>
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">Add Employee</button>
            </form>
        </section>
    </main>

</body>
</html>