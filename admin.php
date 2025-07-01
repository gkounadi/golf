<?php
require 'db.php';
session_start();

// Only allow access if user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Fetch all users
$stmt = $pdo->query("SELECT id, name, email, role FROM users ORDER BY id ASC");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel - Golf App</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-9">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h2 class="card-title mb-4">Admin Panel</h2>
                        <div class="mb-3">
                            <span class="fw-bold">Logged in as:</span>
                            <?= htmlspecialchars($_SESSION['name']) ?> (<?= htmlspecialchars($_SESSION['email']) ?>)
                            <a href="logout.php" class="btn btn-sm btn-outline-danger float-end">Logout</a>
                        </div>
                        <hr>
                        <h4 class="mb-3">Registered Users</h4>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($users as $user): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($user['id']) ?></td>
                                        <td><?= htmlspecialchars($user['name']) ?></td>
                                        <td><?= htmlspecialchars($user['email']) ?></td>
                                        <td>
                                            <span class="badge bg-<?= $user['role'] === 'admin' ? 'primary' : 'secondary' ?>">
                                                <?= htmlspecialchars(ucfirst($user['role'])) ?>
                                            </span>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                    <?php if (empty($users)): ?>
                                    <tr>
                                        <td colspan="4" class="text-center">No users found.</td>
                                    </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4">
                            <a href="index.php" class="btn btn-outline-secondary">Back to Home</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>