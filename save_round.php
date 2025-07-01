<?php
session_start();
require 'db.php';

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$course_id = isset($_POST['course_id']) ? intval($_POST['course_id']) : null;
$scores = isset($_POST['score']) && is_array($_POST['score']) ? $_POST['score'] : [];

if (!$course_id || empty($scores)) {
    header("Location: play_round.php");
    exit();
}

// Fetch course to validate number of holes
$stmt = $pdo->prepare("SELECT holes FROM courses WHERE id = ?");
$stmt->execute([$course_id]);
$course = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$course) {
    header("Location: play_round.php");
    exit();
}

$total_holes = (int)$course['holes'];

if (count($scores) != $total_holes) {
    $_SESSION['error'] = "Please enter a score for each hole.";
    header("Location: play_hole.php?course_id=" . urlencode($course_id));
    exit();
}

// Generate a new round_session_id (max + 1)
$stmt = $pdo->prepare("SELECT IFNULL(MAX(round_session_id), 0) + 1 AS next_id FROM rounds");
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$round_session_id = $row ? (int)$row['next_id'] : 1;

// Insert a row for each hole
$insert = $pdo->prepare(
    "INSERT INTO rounds (user_id, course_id, hole_number, score, played_at, round_session_id)
     VALUES (?, ?, ?, ?, NOW(), ?)"
);

foreach ($scores as $hole => $score) {
    $hole_number = (int)$hole;
    $score = (int)$score;
    if ($hole_number < 1 || $hole_number > $total_holes) continue;
    if ($score < 1 || $score > 15) continue;
    $insert->execute([
        $user_id,
        $course_id,
        $hole_number,
        $score,
        $round_session_id
    ]);
}

// Redirect to summary or home
header("Location: round_summary.php?round_session_id=" . urlencode($round_session_id));
exit();
?>