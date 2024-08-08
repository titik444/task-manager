# Task Manager

## Description

This is a simple task manager application built using PHP and MySQL. It allows you to add, update, and search for tasks.

## Features

- Add new tasks
- Update task status
- Delete tasks
- Search tasks by title
- Filter tasks by status

## Requirements

- PHP (version 7 or above)
- MySQL (version 5.6 or above)
- Web server (e.g., Apache or Nginx)

## Installation

### 1. Clone the Repository

Clone this repository to your local machine:

```bash
git clone https://github.com/titik444/task-manager.git
cd task-manager
```

### 2. Create Database and Table
Run the following SQL commands in your MySQL database to create the required database and table:
```mysql
CREATE DATABASE task_manager;

USE task_manager;

CREATE TABLE tasks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    status ENUM('Pending', 'In Progress', 'Completed') DEFAULT 'Pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```
### 3. Configure Database Connection
Edit the config.php file to set your database connection details:
```php
<?php
// config.php

$host = 'localhost'; // or your database host
$dbname = 'task_manager';
$username = 'root'; // your database username
$password = ''; // your database password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
```
### 4. Run the Application
Place index.php and config.php files in the root directory of your web server. Access the application through your web browser (e.g., http://localhost/task-manager/index.php).

### 5. Additional Features
- Add Task: Use the "Add Task" button to add new tasks.
- Update Status: Change task status directly from the task list.
- Delete Task: Delete tasks with a confirmation prompt.
- Search Tasks: Use the search form to filter tasks by title.
- Filter Tasks: Select a task status to filter the task list.
## Technologies Used
- PHP
- MySQL
- Bootstrap (for styling)
- Font Awesome (for icons)
- Toastr (for notifications)
## Contributing
If you want to contribute to this project, please fork the repository and submit a pull request with your changes. Be sure to follow the contribution guidelines and test your changes before submitting a pull request.

## License
This project is licensed under the MIT License. See the LICENSE file for more details.