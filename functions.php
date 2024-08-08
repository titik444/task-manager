<?php
/**
 * Validate task data before saving to the database.
 *
 * @param string $title
 * @param string $description
 * @param string $status
 * @return array $errors
 */
function validateTaskData($title, $description, $status) {
    $errors = [];

    // Validate title
    if (empty($title)) {
        $errors['title'] = 'Title is required';
    } elseif (strlen($title) < 3) {
        $errors['title'] = 'Title must be at least 3 characters long';
    }

    // Validate description
    if (empty($description)) {
        $errors['description'] = 'Description is required';
    }

    // Validate status
    $validStatuses = ['Pending', 'In Progress', 'Completed'];
    if (!in_array($status, $validStatuses)) {
        $errors['status'] = 'Invalid status selected';
    }

    return $errors;
}

/**
 * Save a new task or update an existing task in the database.
 *
 * @param PDO $pdo
 * @param array $data
 * @param bool $isUpdate
 * @return bool
 */
function saveTask(PDO $pdo, array $data, bool $isUpdate = false) {
    try {
        if (!$isUpdate) {
            $stmt = $pdo->prepare('INSERT INTO tasks (title, description, status) VALUES (?, ?, ?)');
            return $stmt->execute([$data['title'], $data['description'], $data['status']]);
        } else {
            $stmt = $pdo->prepare('UPDATE tasks SET title = ?, description = ?, status = ? WHERE id = ?');
            return $stmt->execute([$data['title'], $data['description'], $data['status'], $data['id']]);
        }
    } catch (PDOException $e) {
        error_log($e->getMessage());
        return false;
    }
}

/**
 * Fetch a single task by ID.
 *
 * @param PDO $pdo
 * @param int $id
 * @return array|false
 */
function fetchTaskById(PDO $pdo, int $id) {
    $stmt = $pdo->prepare("SELECT id, title, description, status, created_at, updated_at FROM tasks WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

/**
 * Update the status of a task.
 *
 * @param PDO $pdo
 * @param int $id
 * @param string $status
 * @return bool
 */
function updateTaskStatus(PDO $pdo, int $id, string $status) {
    if (isset($status) && isset($id)) {
        $stmt = $pdo->prepare('UPDATE tasks SET status = ? WHERE id = ?');
        return $stmt->execute([$status, $id]);
    }
    return false;
}

/**
 * Delete a task by ID.
 *
 * @param PDO $pdo
 * @param int $id
 * @return bool
 */
function deleteTask(PDO $pdo, int $id) {
    if (isset($id)) {
        $stmt = $pdo->prepare('DELETE FROM tasks WHERE id = ?');
        return $stmt->execute([$id]);
    }
    return false;
}

/**
 * Search tasks by title and status filter.
 *
 * @param PDO $pdo
 * @param string $searchQuery
 * @param string $filterStatus
 * @return array
 */
function searchTasks(PDO $pdo, string $searchQuery = '', string $filterStatus = 'All') {
    $query = 'SELECT * FROM tasks WHERE 1=1';
    $params = [];

    if (!empty($searchQuery) && trim($searchQuery) !== '') {
        $query .= ' AND title LIKE ?';
        $params[] = "%$searchQuery%";
    }

    if ($filterStatus != 'All') {
        $query .= ' AND status = ?';
        $params[] = $filterStatus;
    }

    $stmt = $pdo->prepare($query);
    $stmt->execute($params);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}