<?php
require "core/DBManager.php";
require "helpers/functions.php";

session_start();
guest();

if (server()->isPostRequest()) {
    $name = request()->get('name');
    $email = request()->get('email');
    $password = request()->get('password');
    try {

        $pdo = new DBManager();
        $sql = "INSERT INTO `managers` (`name`, `email`, `password`) VALUES (:name, :email, :password)";
        $password = password_hash($password, PASSWORD_DEFAULT);
        $res = $pdo->query($sql, ...compact('name', 'email', 'password'));

        $sql = "SELECT * FROM managers WHERE email=:email";
        $res = $pdo->query($sql, ...compact('email'));
        $user = $res->fetch(PDO::FETCH_ASSOC);

        $_SESSION['user'] = serialize($user);
        $_SESSION['done'] = ['Account created successfully!'];
        header('Location: index.php');
        exit;

    } catch (\PDOException $e) {
        $_SESSION['errors'] = [$e->getMessage()];
        header("Location: register.php");
        die;
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>register</title>
</head>
<body class="bg-gray-900 text-gray-100 flex items-center justify-center min-h-screen">

    <div class="w-full max-w-md p-8 bg-gray-800 rounded-lg shadow-md">
        <h2 class="text-2xl font-bold text-center mb-6">Manager Registration</h2>

        <form action="register.php" method="POST">
   
            <div class="mb-4">
                <label for="name" class="block mb-2 text-sm font-bold">Name</label>
                <input type="text" id="name" name="name" class="w-full px-3 py-2 bg-gray-900 border border-gray-600 rounded-lg text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>

            <div class="mb-4">
                <label for="email" class="block mb-2 text-sm font-bold">Email</label>
                <input type="email" id="email" name="email" class="w-full px-3 py-2 bg-gray-900 border border-gray-600 rounded-lg text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>

            <div class="mb-4">
                <label for="password" class="block mb-2 text-sm font-bold">Password</label>
                <input type="password" id="password" name="password" class="w-full px-3 py-2 bg-gray-900 border border-gray-600 rounded-lg text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>

            <div class="flex items-center justify-between">
                <button type="submit" class="w-fit mx-auto py-2 px-4 bg-blue-500 hover:bg-blue-600 text-white font-bold rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                    Register
                </button>
            </div>
        </form>

        <p class="mt-4 text-center text-sm">
            Already have an account? 
            <a href="login.php" class="text-blue-400 hover:text-blue-600">Login here</a>
        </p>
    </div>

</body>
</html>