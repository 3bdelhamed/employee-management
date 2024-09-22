<?php

require "core/DBManager.php";
require "helpers/functions.php";

session_start();

guest();

if (server()->isPostRequest()) {

    $email = request()->get('email');
    $password = request()->get('password');

    $message = "Success";
    try {

        $dbManager = new DBManager();
        $sql = "SELECT * FROM managers WHERE email=?";
        $stmt = $dbManager->query($sql, $email);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user) {
            if (password_verify($password, $user['password'])) {
                $_SESSION['user'] = serialize($user);
                $_SESSION['done'] = ['logged in successfully'];
                header("Location: index.php");
                exit;
            } else {
                $_SESSION['errors'] = ['wrong password'];
                header("Location: login.php");
                exit;
            }
        } else {
            $_SESSION['errors'] = ['user not found'];
            header("Location: login.php");
            exit;
        }
    } catch (\PDOException $e) {
        $_SESSION['errors'] = [$e->getMessage()];
        header("Location: login.php");
        die;
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
        integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<<body class="bg-gray-900 h-screen flex items-center justify-center">

    <section class="w-full max-w-md bg-gray-800 p-6 rounded-lg shadow-lg">
        <h1 class="text-2xl font-bold text-white mb-6 text-center">Login Page</h1>

        
        <?php include "components/messages/success.php"; ?>
        <?php include "components/messages/error.php"; ?>

        <form action="login.php" method="POST" class="space-y-4">
            <div>
                <label for="email" class="block text-gray-300">Email address</label>
                <input type="text" id="email" name="email" class="w-full px-4 py-2 mt-1 rounded-md border border-gray-700 bg-gray-900 text-gray-100 placeholder-gray-500">
                <p class="text-gray-500 text-sm">We'll never share your email with anyone else.</p>
            </div>
            <div>
                <label for="password" class="block text-gray-300">Password</label>
                <input type="password" id="password" name="password" class="w-full px-4 py-2 mt-1 rounded-md border border-gray-700 bg-gray-900 text-gray-100">
            </div>
            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">Login</button>
        </form>
    </section>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/js/all.min.js"
        integrity="sha512-6sSYJqDreZRZGkJ3b+YfdhB3MzmuP9R7X1QZ6g5aIXhRvR1Y/N/P47jmnkENm7YL3oqsmI6AK+V6AD99uWDnIw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    </body>

</html>