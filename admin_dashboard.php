<?php
require 'config.php'; // Include centralized configuration


$user_id = $_SESSION['user_id'];
$error_message = ''; // Variable to store error messages
$success_message = ''; // Variable to store success messages

// Handle form submissions for adding/editing records
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST['action']; // add, edit, or delete
    $table = $_POST['table'];

    try {
        if ($action === 'add' || $action === 'edit') {
            // Prepare data for insertion/update
            $data = [];
            foreach ($_POST as $key => $value) {
                if ($key !== 'action' && $key !== 'table' && $key !== 'id') {
                    $data[$key] = trim($value);
                }
            }

            if ($action === 'add') {
                // Insert new record
                $columns = implode(", ", array_keys($data));
                $placeholders = ":" . implode(", :", array_keys($data));
                $sql = "INSERT INTO $table ($columns) VALUES ($placeholders)";
                $stmt = $pdo->prepare($sql);

                foreach ($data as $key => $value) {
                    $stmt->bindValue(":$key", $value);
                }
                $stmt->execute();
                $success_message = "Record added successfully!";
            } elseif ($action === 'edit') {
                // Update existing record
                $id = $_POST['id'];
                $setClause = "";
                foreach (array_keys($data) as $column) {
                    $setClause .= "$column = :$column, ";
                }
                $setClause = rtrim($setClause, ", ");

                $sql = "UPDATE $table SET $setClause WHERE id = :id";
                $stmt = $pdo->prepare($sql);

                foreach ($data as $key => $value) {
                    $stmt->bindValue(":$key", $value);
                }
                $stmt->bindValue(":id", $id);
                $stmt->execute();
                $success_message = "Record updated successfully!";
            }
        } elseif ($action === 'delete') {
            // Delete record
            $id = $_POST['id'];
            $sql = "DELETE FROM $table WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(":id", $id);
            $stmt->execute();
            $success_message = "Record deleted successfully!";
        }
    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        $error_message = "An error occurred. Please try again.";
    }
}

// Fetch all data from the database tables
$tables = [
    'users',
    'forum_posts',
    'forum_comments',
    'perfumes',
    'reviews',
    'messages',
    'votes',
    'reports',
    'activity_log',
];

$data = [];
foreach ($tables as $table) {
    $stmt = $pdo->query("SELECT * FROM $table");
    $data[$table] = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Fragrance Haven</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .dashboard-header {
            margin-bottom: 2rem;
        }
        .table-container {
            margin-bottom: 2rem;
        }
        .form-container {
            margin-top: 1rem;
        }
        .dropdown-menu {
            max-height: 300px;
            overflow-y: auto;
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>

    <div class="container">
        <div class="dashboard-header text-center">
            <h2>Admin Dashboard</h2>
            <p>Manage all data in the database.</p>
        </div>

        <!-- Error/Success Messages -->
        <?php if (!empty($error_message)): ?>
            <div class="alert alert-danger text-center"><?= htmlspecialchars($error_message) ?></div>
        <?php endif; ?>
        <?php if (!empty($success_message)): ?>
            <div class="alert alert-success text-center"><?= htmlspecialchars($success_message) ?></div>
        <?php endif; ?>

        <!-- Dropdown for Table Selection -->
        <div class="text-center mb-4">
            <div class="btn-group">
                <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                    Select Table
                </button>
                <ul class="dropdown-menu">
                    <?php foreach ($tables as $table): ?>
                        <li><a class="dropdown-item" href="#<?= $table ?>"><?= ucfirst(str_replace('_', ' ', $table)) ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>

        <!-- Display Data Tables -->
        <?php foreach ($data as $table => $rows): ?>
            <div class="table-container" id="<?= $table ?>">
                <h3><?= ucfirst(str_replace('_', ' ', $table)) ?></h3>
                <?php if (!empty($rows)): ?>
                    <table class="table table-bordered table-striped">
                        <thead class="table-dark">
                            <tr>
                                <?php foreach (array_keys($rows[0]) as $column): ?>
                                    <th><?= ucfirst(str_replace('_', ' ', $column)) ?></th>
                                <?php endforeach; ?>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($rows as $row): ?>
                                <tr>
                                    <?php foreach ($row as $value): ?>
                                        <td><?= htmlspecialchars($value) ?></td>
                                    <?php endforeach; ?>
                                    <td>
                                        <div class="btn-group">
                                            <button class="btn btn-sm btn-warning" onclick="toggleEditForm('<?= $table ?>', <?= $row['id'] ?>)">Edit</button>
                                            <form method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this record?');">
                                                <input type="hidden" name="action" value="delete">
                                                <input type="hidden" name="table" value="<?= $table ?>">
                                                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                    <!-- Add New Record Form -->
                    <div class="form-container">
                        <h4>Add New Record to <?= ucfirst(str_replace('_', ' ', $table)) ?></h4>
                        <form method="POST" class="row g-3">
                            <input type="hidden" name="action" value="add">
                            <input type="hidden" name="table" value="<?= $table ?>">
                            <?php foreach (array_keys($rows[0]) as $column): ?>
                                <?php if ($column !== 'id'): ?>
                                    <div class="col-md-6">
                                        <label for="<?= $column ?>" class="form-label"><?= ucfirst(str_replace('_', ' ', $column)) ?></label>
                                        <input type="text" name="<?= $column ?>" class="form-control" required>
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">Add</button>
                            </div>
                        </form>
                    </div>

                    <!-- Edit Form -->
                    <?php foreach ($rows as $row): ?>
                        <div id="<?= $table ?>-edit-form-<?= $row['id'] ?>" class="form-container" style="display: none;">
                            <h4>Edit Record in <?= ucfirst(str_replace('_', ' ', $table)) ?></h4>
                            <form method="POST" class="row g-3">
                                <input type="hidden" name="action" value="edit">
                                <input type="hidden" name="table" value="<?= $table ?>">
                                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                <?php foreach ($row as $column => $value): ?>
                                    <?php if ($column !== 'id'): ?>
                                        <div class="col-md-6">
                                            <label for="<?= $column ?>" class="form-label"><?= ucfirst(str_replace('_', ' ', $column)) ?></label>
                                            <input type="text" name="<?= $column ?>" class="form-control" value="<?= htmlspecialchars($value) ?>" required>
                                        </div>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                                <div class="col-12">
                                    <button type="submit" class="btn btn-success">Update</button>
                                </div>
                            </form>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No data available for <?= ucfirst(str_replace('_', ' ', $table)) ?>.</p>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Bootstrap JS and Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleEditForm(table, id) {
            const formId = `${table}-edit-form-${id}`;
            const form = document.getElementById(formId);
            form.style.display = form.style.display === 'none' ? 'block' : 'none';
        }
    </script>
</body>
</html>