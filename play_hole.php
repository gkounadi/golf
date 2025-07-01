<?php
session_start();
require 'db.php';

// Redirect to login if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$course_id = isset($_GET['course_id']) ? intval($_GET['course_id']) : null;

// Fetch course info
$selected_course = null;
if ($course_id) {
    $stmt = $pdo->prepare("SELECT * FROM courses WHERE id = ?");
    $stmt->execute([$course_id]);
    $selected_course = $stmt->fetch(PDO::FETCH_ASSOC);
}

if (!$selected_course) {
    // No valid course selected
    header("Location: play_round.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Play a Round - <?= htmlspecialchars($selected_course['name']) ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .course-card {
            border-radius: 1.5rem;
            box-shadow: 0 4px 20px rgba(0,0,0,0.09);
        }
        .course-title {
            font-size: 2.1rem;
            font-weight: bold;
            letter-spacing: 0.03em;
        }
        .course-description {
            font-size: 1.15rem;
            color: #555;
        }
        .hole-label {
            font-size: 1.15rem;
        }
        .hole-input {
            font-size: 1.15rem;
            width: 4.5rem;
            text-align: center;
        }
        @media (max-width: 600px) {
            .course-card {padding: 2rem 0.5rem;}
            .course-title {font-size: 1.3rem;}
            .course-description {font-size: 1rem;}
            .hole-label, .hole-input {font-size: 1rem;}
        }
    </style>
</head>
<body class="bg-light">
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-12 col-md-10 col-lg-7">
            <div class="card course-card my-4">
                <div class="card-body text-center">
                    <h2 class="course-title mb-2"><?= htmlspecialchars($selected_course['name']) ?></h2>
                    <div class="course-description mb-3">
                        <?php
                            if (!empty($selected_course['description'])) {
                                echo nl2br(htmlspecialchars($selected_course['description']));
                            } else {
                                echo "<span class='text-muted fst-italic'>No description available.</span>";
                            }
                        ?>
                    </div>
                    <div class="mb-4 text-muted">
                        <span><strong>Holes:</strong> <?= htmlspecialchars($selected_course['holes']) ?> &nbsp;|&nbsp; <strong>Par:</strong> <?= htmlspecialchars($selected_course['par']) ?></span>
                    </div>
                    <form action="save_round.php" method="post" autocomplete="off">
                        <input type="hidden" name="course_id" value="<?= htmlspecialchars($selected_course['id']) ?>">
                        <div class="table-responsive">
                            <table class="table table-sm table-borderless align-middle mb-4">
                                <thead>
                                    <tr>
                                        <th class="text-center">Hole</th>
                                        <th class="text-center">Score</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php for ($i = 1; $i <= $selected_course['holes']; $i++): ?>
                                    <tr>
                                        <td class="hole-label text-center"><?= $i ?></td>
                                        <td class="text-center">
                                            <input type="number" min="1" max="15" class="form-control hole-input d-inline-block" name="score[<?= $i ?>]" required>
                                        </td>
                                    </tr>
                                <?php endfor; ?>
                                </tbody>
                            </table>
                        </div>
                        <button type="submit" class="btn btn-success btn-lg w-100 mt-2">Submit Scores</button>
                    </form>
                    <a href="play_round.php" class="btn btn-link mt-3">Back to Course List</a>
                    <a href="index.php" class="btn btn-link mt-3">Home</a>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>