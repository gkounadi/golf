<?php
require 'db.php';
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); exit();
}
$user_id = $_SESSION['user_id'];
$session_id = $_GET['session'] ?? null;

if (!$session_id) {
    echo "No round selected."; exit();
}

// Fetch round info
$stmt = $pdo->prepare(
    "SELECT r.*, c.name as course_name, c.holes, c.par FROM rounds r
     JOIN courses c ON r.course_id = c.id
     WHERE r.user_id = ? AND r.round_session_id = ?
     ORDER BY r.hole_number ASC"
);
$stmt->execute([$user_id, $session_id]);
$round = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!$round) {
    echo "Round not found."; exit();
}

$course_name = $round[0]['course_name'];
$total_holes = $round[0]['holes'];
$course_par = $round[0]['par'];
$total_score = array_sum(array_column($round, 'score'));
?>
<!DOCTYPE html>
<html>
<head>
    <title>Round Details - <?= htmlspecialchars($course_name) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <h2>Round Details</h2>
    <div class="mb-3">
        <strong>Course:</strong> <?= htmlspecialchars($course_name) ?><br>
        <strong>Total Holes:</strong> <?= htmlspecialchars($total_holes) ?><br>
        <strong>Par:</strong> <?= htmlspecialchars($course_par) ?><br>
        <strong>Total Score:</strong> <?= htmlspecialchars($total_score) ?>
    </div>
    <table class="table table-bordered w-auto">
        <thead>
            <tr>
                <th>Hole</th>
                <th>Score</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($round as $hole): ?>
            <tr>
                <td><?= htmlspecialchars($hole['hole_number']) ?></td>
                <td><?= htmlspecialchars($hole['score']) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <a href="view_rounds.php" class="btn btn-secondary mt-3">Back to Rounds</a>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>