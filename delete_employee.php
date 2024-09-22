<?php
require_once __DIR__ . '/core/DBManager.php';
require_once 'helpers/functions.php';
require_once __DIR__ . '/core/FileManager.php';
session_start();

if (server()->isPostRequest()) {
    $id = $_POST['id'] ?? null;
    if ($id) {
        $db = new DBManager();
        $employee = $db->query("SELECT picture FROM employees WHERE id = ?", $id)->fetch(PDO::FETCH_ASSOC);
        if ($employee) {
            $db->query("DELETE FROM employees WHERE id = ?", $id);
            $fileManager = new FileManager();
            if ($employee['picture']) {
                $fileManager->delete($employee['picture']);
            }
            header('Location: employees.php');
            exit();
        } else {
            $_SESSION['errors'] = ['Employee not found'];
            header('Location: employees.php');
        }
    } else {
        $_SESSION['errors'] = ['Invalid ID'];
        header('Location: employees.php');
    }
}
