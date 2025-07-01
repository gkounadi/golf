<?php
require 'db.php';
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); exit();
}
$user_id = $_SESSION['user_id'];

// Get all unique round sessions for this user
$stmt = $pdo->prepare(
    "SELECT r.round_session_id, MIN(r.created_at) as round_date, c.name as course_name
     FROM rounds r
     JOIN courses c ON r.course_id = c.id
     WHERE r.user_id = ?
     GROUP BY r.round_session_id, c.name
     ORDER BY round_date DESC"
);
$stmt->execute([$user_id]);
$rounds = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Your Previous Rounds</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <h2>Your Previous Rounds</h2>
    <?php if (empty($rounds)): ?>
        <div class="alert alert-info">You have not saved any rounds yet.</div>
    <?php else: ?>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Course</th>
                    <th>View Details</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($rounds as $round): ?>
                <tr>
                    <td><?= htmlspecialchars(date('Y-m-d H:i', strtotime($round['round_date']))) ?></td>
                    <td><?= htmlspecialchars($round['course_name']) ?></td>
                    <td>
                        <a href="view_round_details.php?session=<?= urlencode($round['round_session_id']) ?>" class="btn btn-sm btn-outline-primary">View</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
    <a href="index.php" class="btn btn-secondary mt-3">Back to Home</a>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>