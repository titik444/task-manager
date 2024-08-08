<?php
session_start();

require 'config.php';
require 'functions.php';

$errors = [];
$pageTitle = 'Edit Task';
$submitName = 'update_task';
$submitText = 'Update Task';

// Load existing task data for editing
if (isset($_GET['id'])) {
    $task = fetchTaskById($pdo, $_GET['id']);
    if (!$task) {
        die('Task not found');
    }
} else {
    die('Invalid request');
}

// Handle form submission for updating a task
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST[$submitName])) {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $status = $_POST['status'];

    // Validate input
    $errors = validateTaskData($title, $description, $status);

    if (empty($errors)) {
        if (saveTask($pdo, ['id' => $task['id'], 'title' => $title, 'description' => $description, 'status' => $status], true)) {
            $_SESSION['success'] = 'Task updated successfully';
            header('Location: index.php');
            exit();
        } else {
            $errors['general'] = 'Failed to update task. Please try again.';
        }
    }
}

$title = $task['title'];
$description = $task['description'];
$status = $task['status'];

include 'form_layout.php';