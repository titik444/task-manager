<form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>?id=<?php echo htmlspecialchars($task['id'] ?? ''); ?>">
    <input type="hidden" name="id" value="<?php echo htmlspecialchars($task['id'] ?? ''); ?>">
    <div class="mb-3">
        <label for="title" class="form-label">Title</label>
        <input type="text" class="form-control <?php echo !empty($errors['title']) ? 'is-invalid' : ''; ?>" id="title" name="title" value="<?php echo htmlspecialchars($title ?? $task['title'] ?? ''); ?>">
        <?php if (!empty($errors['title'])) : ?>
            <div class="invalid-feedback">
                <?php echo htmlspecialchars($errors['title']); ?>
            </div>
        <?php endif; ?>
    </div>
    <div class="mb-3">
        <label for="description" class="form-label">Description</label>
        <textarea class="form-control <?php echo !empty($errors['description']) ? 'is-invalid' : ''; ?>" id="description" name="description" rows="6"><?php echo htmlspecialchars($description ?? $task['description'] ?? ''); ?></textarea>
        <?php if (!empty($errors['description'])) : ?>
            <div class="invalid-feedback">
                <?php echo htmlspecialchars($errors['description']); ?>
            </div>
        <?php endif; ?>
    </div>
    <div class="mb-3 col-md-4">
        <label for="status" class="form-label">Status</label>
        <select class="form-select <?php echo !empty($errors['status']) ? 'is-invalid' : ''; ?>" id="status" name="status">
            <option value="Pending" <?php if (($status ?? $task['status'] ?? '') == 'Pending') echo 'selected'; ?>>Pending</option>
            <option value="In Progress" <?php if (($status ?? $task['status'] ?? '') == 'In Progress') echo 'selected'; ?>>In Progress</option>
            <option value="Completed" <?php if (($status ?? $task['status'] ?? '') == 'Completed') echo 'selected'; ?>>Completed</option>
        </select>
        <?php if (!empty($errors['status'])) : ?>
            <div class="invalid-feedback">
                <?php echo htmlspecialchars($errors['status']); ?>
            </div>
        <?php endif; ?>
    </div>
    <button type="submit" name="<?php echo htmlspecialchars($submitName); ?>" class="btn btn-primary"><?php echo htmlspecialchars($submitText); ?></button>
    <a href="index.php" class="btn btn-secondary">Back</a>
</form>