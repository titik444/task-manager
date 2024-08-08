<?php
require 'config.php';
require 'functions.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $task = fetchTaskById($pdo, $id);
    if ($task) {
        echo json_encode([
            'title' => $task['title'],
            'description' => $task['description'],
            'status' => $task['status'],
            'created_at' => $task['created_at']
        ]);
    } else {
        echo json_encode(['error' => 'Task not found']);
    }
}
?>