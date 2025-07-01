<?php
session_start();
require 'db.php';

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$round_session_id = isset($_GET['round_session_id']) ? intval($_GET['round_session_id']) : null;

if (!$round_session_id) {
    header("Location: play_round.php");
    exit();
}

// Fetch one row to get course info and played_at
$stmt = $pdo->prepare("SELECT course_id, played_at FROM rounds WHERE round_session_id = ? AND user_id = ? LIMIT 1");
$stmt->execute([$round_session_id, $user_id]);
$round_info = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$round_info) {
    echo "<div class='alert alert-danger text-center'>Round not found.</div>";
    exit();
}

// Fetch course details
$stmt = $pdo->prepare("SELECT name, holes, par FROM courses WHERE id = ?");
$stmt->execute([$round_info['course_id']]);
$course = $stmt->fetch(PDO::FETCH_ASSOC);

// Fetch scores for this round
$stmt = $pdo->prepare("SELECT hole_number, score FROM rounds WHERE round_session_id = ? AND user_id = ? ORDER BY hole_number ASC");
$stmt->execute([$round_session_id, $user_id]);
$hole_scores = $stmt->fetchAll(PDO::FETCH_ASSOC);

$total_score = 0;
foreach ($hole_scores as $row) {
    $total_score += (int)$row['score'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Round Summary</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .summary-card { border-radius: 1.5rem; box-shadow: 0 4px 20px rgba(0,0,0,0.09); }
        .course-title { font-size: 2.1rem; font-weight: bold; letter-spacing: 0.03em; }
        .course-description { font-size: 1.15rem; color: #555; }
        .score-table th, .score-table td { font-size: 1.15rem; }
        @media (max-width: 600px) {
            .summary-card {padding: 2rem 0.5rem;}
            .course-title {font-size: 1.3rem;}
            .course-description {font-size: 1rem;}
            .score-table th, .score-table td {font-size: 1rem;}
        }
    </style>
</head>
<body class="bg-light">
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-12 col-md-10 col-lg-7">
            <div class="card summary-card my-4">
                <div class="card-body text-center">
                    <h2 class="course-title mb-2"><?= htmlspecialchars($course['name']) ?></h2>
                    <div class="course-description mb-2">
                        <?php
                        if (!empty($course['description'])) {
                            echo nl2br(htmlspecialchars($course['description']));
                        } else {
                            echo "<span class='text-muted fst-italic'>No description available.</span>";
                        }
                        ?>
                    </div>
                    <div class="mb-3 text-muted">
                        <span><strong>Holes:</strong> <?= htmlspecialchars($course['holes']) ?> &nbsp;|&nbsp; <strong>Par:</strong> <?= htmlspecialchars($course['par']) ?></span>
                    </div>
                    <div class="mb-3 text-muted">
                        <span><strong>Date Played:</strong> <?= htmlspecialchars(date('D, M j, Y, g:i a', strtotime($round_info['played_at']))) ?></span>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered score-table">
                            <thead>
                                <tr>
                                    <th class="text-center">Hole</th>
                                    <th class="text-center">Score</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($hole_scores as $row): ?>
                                    <tr>
                                        <td class="text-center"><?= $row['hole_number'] ?></td>
                                        <td class="text-center"><?= $row['score'] ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr class="table-success">
                                    <th class="text-end">Total</th>
                                    <th class="text-center"><?= $total_score ?></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <a href="play_round.php" class="btn btn-primary mt-3">Play Another Round</a>
                    <a href="index.php" class="btn btn-secondary mt-3">Home</a>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>