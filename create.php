<?php
session_start();

require 'config.php';
require 'functions.php';

$errors = [];
$pageTitle = 'Add Task';
$submitName = 'add_task';
$submitText = 'Add Task';

// Handle form submission for adding a new task
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST[$submitName])) {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $status = $_POST['status'];

    // Validate input
    $errors = validateTaskData($title, $description, $status);

    if (empty($errors)) {
        if (saveTask($pdo, ['title' => $title, 'description' => $description, 'status' => $status])) {
            $_SESSION['success'] = 'Task added successfully';
            header('Location: index.php');
            exit();
        } else {
            $errors['general'] = 'Failed to add task. Please try again.';
        }
    }
}

include 'form_layout.php';