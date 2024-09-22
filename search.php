<?php
require_once __DIR__ . '/core/DBManager.php';
require_once __DIR__ . '/helpers/functions.php';

session_start();
$user = auth();

$query = $_GET['query'] ?? null;

if (!$query) {
    $_SESSION['errors'] = ['Please enter a search query'];
    header("Location: employees.php");
    exit();
}

$query = trim($query);
$db = new DBManager();

if (filter_var($query, FILTER_VALIDATE_EMAIL)) {
    $employee = $db->query("SELECT * FROM employees WHERE email = ?", $query)->fetch(PDO::FETCH_ASSOC);
} elseif (ctype_digit($query)) {
    $employee = $db->query("SELECT * FROM employees WHERE id = ?", $query)->fetch(PDO::FETCH_ASSOC);
} else {
    $employee = null;
}

if ($employee) {
    header("Location: edit_employee.php?id=" . $employee['id']);
    exit();
} else {
    $_SESSION['errors'] = ['Employee not found'];
    header("Location: employees.php");
    exit();
}
