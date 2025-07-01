<?php
require 'db.php';
session_start();
if (!isset($_SESSION['user_id'], $_SESSION['selected_course_id'], $_SESSION['round_scores'])) {
    header("Location: play_round.php"); exit();
}

$course_id = $_SESSION['selected_course_id'];
$scores = $_SESSION['round_scores'];
$user_id = $_SESSION['user_id'];
$total_holes = count($scores);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['save'])) {
        // Generate a round session id (could be auto-increment or timestamp)
        $round_session_id = time() . $user_id;
        $stmt = $pdo->prepare("INSERT INTO rounds (user_id, course_id, hole_number, score, round_session_id) VALUES (?, ?, ?, ?, ?)");
        foreach ($scores as $hole => $score) {
            $stmt->execute([$user_id, $course_id, $hole, $score, $round_session_id]);
        }
        // Clear session data for round
        unset($_SESSION['selected_course_id'], $_SESSION['round_scores']);
        $message = "Your round has been saved!";
    } else {
        // Discard round data
        unset($_SESSION['selected_course_id'], $_SESSION['round_scores']);
        $message = "Round ended without saving.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>End Round</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <h2>End Round</h2>
    <?php if (isset($message)): ?>
        <div class="alert alert-info"><?= htmlspecialchars($message) ?></div>
        <a href="index.php" class="btn btn-primary">Return Home</a>
    <?php else: ?>
        <p>Would you like to save your round or discard it?</p>
        <form method="post" class="d-flex gap-2">
            <button type="submit" name="save" class="btn btn-success">End and Save</button>
            <button type="submit" name="discard" class="btn btn-danger">End without Saving</button>
        </form>
    <?php endif; ?>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>