<?php
require_once __DIR__ . '/core/DBManager.php';
require_once __DIR__ . '/core/FileManager.php';
require_once __DIR__ . '/helpers/functions.php';

session_start();
$user = auth();

$id = $_GET['id'] ?? null;
if (!$id) {
    $_SESSION['errors'] = ['Employee ID is required'];
    header("Location: employees.php");
    exit();
}

$db = new DBManager();
$fileManager = new FileManager();

$employee = $db->query("SELECT * FROM employees WHERE id = ?", $id)->fetch(PDO::FETCH_ASSOC);

if (!$employee) {
    $_SESSION['errors'] = ['Employee not found'];
    header("Location: employees.php");
    exit();
}

if (server()->isPostRequest()) {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';


    $picture = $_FILES['picture'] ?? null;
    $picturePath = null;


    $employee = $db->query("SELECT picture FROM employees WHERE id = ?", $id)->fetch(PDO::FETCH_ASSOC);

    if ($picture && $picture['error'] === UPLOAD_ERR_OK) {
        if ($employee['picture']) {
            $fileManager->delete($employee['picture']);
        }
        $picturePath = $fileManager->store($picture);
    } else {
        $picturePath = $employee['picture'];
    }

    if ($name && $email && $phone) {
        $db->query(
            "UPDATE employees SET name = ?, email = ?, phone = ?, picture = ? WHERE id = ?",
            $name,
            $email,
            $phone,
            $picturePath,
            $id
        );

        header('Location: employees.php');
        exit();
    } else {
        $_SESSION['errors'] = ['All fields are required'];
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
    <title>Edit Employee</title>
    <style>
        .image-wrapper {
            position: relative;
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .image-wrapper:hover .overlay {
            opacity: 1;
        }

        .overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.6);
            opacity: 0;
            transition: opacity 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            font-weight: bold;
            text-align: center;
        }

        #picture {
            display: none;
        }
    </style>
</head>

<body class="bg-gray-800 text-gray-100 h-screen flex flex-col">
    <?php include __DIR__ . '/views/partials/navbar.php'; ?>
    <div class="container mx-auto mt-8 p-4 flex flex-col md:flex-row items-center gap-8">
        <div class="flex-1 max-w-lg bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700 p-5 mx-auto">
            <a href="employees.php" class="flex items-center text-blue-500 hover:text-blue-700 mb-4">
                <i class="fas fa-arrow-left mr-2"></i>
                <span class="text-lg font-medium">Go Back</span>
            </a>

            <form action="edit_employee.php?id=<?= htmlspecialchars($id) ?>" method="POST" enctype="multipart/form-data">
                <?php include "components/messages/error.php" ?>
                <div class="space-y-4">
                    <div>
                        <label for="name" class="block text-lg font-medium">Name:</label>
                        <input type="text" id="name" name="name" value="<?= $employee['name'] ?>" required class="mt-1 block w-full px-3 py-2 rounded border border-gray-600 bg-gray-900 text-gray-100">
                    </div>

                    <div>
                        <label for="email" class="block text-lg font-medium">Email:</label>
                        <input type="email" id="email" name="email" value="<?= $employee['email'] ?>" required class="mt-1 block w-full px-3 py-2 rounded border border-gray-600 bg-gray-900 text-gray-100">
                    </div>

                    <div>
                        <label for="phone" class="block text-lg font-medium">Phone:</label>
                        <input type="text" id="phone" name="phone" value="<?= $employee['phone'] ?>" required class="mt-1 block w-full px-3 py-2 rounded border border-gray-600 bg-gray-900 text-gray-100">
                    </div>

                    <div>
                        <label for="picture" class="block text-lg font-medium">Upload New Picture:</label>
                        <input type="file" id="picture" name="picture" class="mt-1 block w-full px-3 py-2 rounded border border-gray-600 bg-gray-900 text-gray-100">
                    </div>

                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">Update</button>
                </div>
            </form>
        </div>

        <div class="flex-shrink-0 w-64 h-64 flex items-center justify-center bg-gray-700 rounded-full overflow-hidden border-4 border-gray-300 mx-auto image-wrapper">
            <div class="overlay">Change Photo</div>
            <?php if ($employee['picture']): ?>
                <img src="uploads/<?= $employee['picture'] ?>" alt="Picture" class="w-full h-full object-cover rounded-full" id="image-preview">
            <?php else: ?>
                <i class="fa fa-user-circle text-gray-500 text-8xl"></i>
            <?php endif; ?>
        </div>
    </div>

    <script>
        const imageWrapper = document.querySelector('.image-wrapper');
        const fileInput = document.getElementById('picture');

        imageWrapper.addEventListener('click', () => {
            fileInput.click();
        });

        fileInput.addEventListener('change', (event) => {
            const [file] = event.target.files;
            if (file) {
                const imagePreview = document.getElementById('image-preview');
                imagePreview.src = URL.createObjectURL(file);
            }
        });
    </script>
</body>
</html>