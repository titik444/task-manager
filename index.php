<?php
session_start();

require 'config.php';
require 'functions.php';

// Handle status update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_status'])) {
    $status = $_POST['status'];
    $id = $_POST['id'];
    updateTaskStatus($pdo, $id, $status);
}

// Handle delete request
if (isset($_GET['delete']) && isset($_GET['id'])) {
    $id = $_GET['id'];
    deleteTask($pdo, $id);

    $_SESSION['success'] = 'Task deleted successfully!';
    header('Location: index.php'); // Redirect to the task list page
    exit();
}

// Handle search and filter
$searchQuery = isset($_GET['search']) ? $_GET['search'] : '';
$filterStatus = isset($_GET['status']) ? $_GET['status'] : 'All';

$tasks = searchTasks($pdo, $searchQuery, $filterStatus);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Manager</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <!-- Toastr -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <style>
        .status-pending {
            background-color: #f8d7da;
            color: #721c24;
        }

        .status-in-progress {
            background-color: #fff3cd;
            color: #856404;
        }

        .status-completed {
            background-color: #d4edda;
            color: #155724;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <!-- Header -->
        <h1 class="text-center mb-5">Task Manager</h1>
        <div class="d-flex flex-column flex-md-row justify-content-between mb-3">
            <a href="create.php" class="btn btn-primary mb-2 mb-md-0"><i class="fas fa-plus"></i> Add Task</a>

            <!-- Search and filter -->
            <div class="d-flex flex-column flex-md-row">
                <input type="text" id="searchInput" class="form-control mb-2 mb-md-0 me-md-2" placeholder="Search..." value="<?php echo htmlspecialchars($searchQuery); ?>">
                <select id="filterSelect" class="form-select">
                    <option value="All" <?php if ($filterStatus == 'All') echo 'selected'; ?>>All</option>
                    <option value="Pending" <?php if ($filterStatus == 'Pending') echo 'selected'; ?>>Pending</option>
                    <option value="In Progress" <?php if ($filterStatus == 'In Progress') echo 'selected'; ?>>In Progress</option>
                    <option value="Completed" <?php if ($filterStatus == 'Completed') echo 'selected'; ?>>Completed</option>
                </select>
            </div>
        </div>

        <!-- Table -->
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th class="col-md-6">Title</th>
                        <th class="col-md-2 col-3 text-center">Status</th>
                        <th class="col-md-2 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    <!-- Table rows -->
                    <?php if ($tasks) : ?>
                        <?php foreach ($tasks as $task) : ?>
                            <tr>
                                <td><?php echo htmlspecialchars($task['title']); ?></td>
                                <td>
                                    <form method="POST" action="index.php" style="display: inline;">
                                        <input type="hidden" name="id" value="<?php echo $task['id']; ?>">
                                        <select name="status" onchange="this.form.submit()" class="form-select form-select-sm <?php echo 'status-' . strtolower(str_replace(' ', '-', $task['status'])); ?>">
                                            <option value="Pending" <?php if ($task['status'] == 'Pending') echo 'selected'; ?>>Pending</option>
                                            <option value="In Progress" <?php if ($task['status'] == 'In Progress') echo 'selected'; ?>>In Progress</option>
                                            <option value="Completed" <?php if ($task['status'] == 'Completed') echo 'selected'; ?>>Completed</option>
                                        </select>
                                        <input type="hidden" name="update_status" value="1">
                                    </form>
                                </td>
                                <td class="text-center">
                                    <a href="#" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#taskModal" onclick="showTaskDetails('<?php echo htmlspecialchars($task['id']); ?>')"><i class="fas fa-eye"></i> View</a>
                                    <a href="edit.php?id=<?php echo $task['id']; ?>" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i> Edit</a>
                                    <a href="index.php?delete=1&id=<?php echo $task['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this task?');"><i class="fas fa-trash"></i> Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td class="text-center" colspan="3">No tasks found.</td>
                        </tr>
                    <?php endif; ?>

                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="taskModal" tabindex="-1" aria-labelledby="taskModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="taskModalLabel">Task Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h4 id="modalTitle"></h4>
                    <p><strong>Description:</strong> <span id="modalDescription"></span></p>
                    <p><strong>Status:</strong> <span id="modalStatus"></span></p>
                    <p><strong>Created At:</strong> <span id="modalCreatedAt"></span></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Toastr -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>

    <script>
        // toastr options
        toastr.options = {
            "closeButton": true,
            "debug": false,
            "newestOnTop": false,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "preventDuplicates": false,
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        };

        // toastr messages
        <?php if (isset($_SESSION['success'])) : ?>
            toastr.success("<?php echo htmlspecialchars($_SESSION['success']); ?>");
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        // filter tasks
        document.getElementById('searchInput').addEventListener('blur', function() {
            filterTasks();
        });

        document.getElementById('filterSelect').addEventListener('change', function() {
            filterTasks();
        });

        function filterTasks() {
            const searchInput = document.getElementById('searchInput').value;
            const filterSelect = document.getElementById('filterSelect').value;
            window.location.href = `index.php?search=${searchInput}&status=${filterSelect}`;
        }

        // show task details
        function showTaskDetails(taskId) {
            // AJAX request to fetch task details
            fetch(`get_task_details.php?id=${taskId}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('modalTitle').textContent = data.title;
                    document.getElementById('modalDescription').textContent = data.description;
                    document.getElementById('modalStatus').textContent = data.status;
                    document.getElementById('modalCreatedAt').textContent = data.created_at;
                })
                .catch(error => console.error('Error fetching task details:', error));
        }
    </script>

</body>

</html>