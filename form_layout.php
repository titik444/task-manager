<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8'); ?></title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .centered-grid {
            display: grid;
            place-items: center;
            height: 100vh;
        }

        .container {
            max-width: 800px;
        }
    </style>
</head>

<body>
    <section class="centered-grid">
        <div class="container">
            <h2><?php echo htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8'); ?></h2>
            <?php if (!empty($errors) && isset($errors['general'])) : ?>
                <div class="alert alert-danger">
                    <?php foreach ($errors as $error) : ?>
                        <p><?php echo htmlspecialchars($error); ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            <?php include 'task_form.php'; ?>
        </div>
    </section>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>