<?php
session_start();
require 'db.php';

// Redirect to login if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch all courses
$stmt = $pdo->prepare("SELECT * FROM courses ORDER BY name ASC");
$stmt->execute();
$courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Play a Round</title>
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
        @media (max-width: 600px) {
            .course-card {padding: 2rem 0.5rem;}
            .course-title {font-size: 1.3rem;}
            .course-description {font-size: 1rem;}
        }
    </style>
</head>
<body class="bg-light">
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-12 col-md-10 col-lg-7">
            <div class="mb-4">
                <h2 class="text-center fw-bold mb-3" style="font-size:2rem;">Select a Course to Start</h2>
                <div class="text-center mb-3">
                    <a href="index.php" class="btn btn-secondary">Home</a>
                </div>
                <?php if (empty($courses)): ?>
                    <div class="alert alert-warning text-center">No courses available.</div>
                <?php else: ?>
                    <div class="row gy-4">
                        <?php foreach ($courses as $course): ?>
                            <div class="col-12">
                                <div class="card course-card">
                                    <div class="card-body text-center">
                                        <h3 class="course-title mb-2"><?= htmlspecialchars($course['name']) ?></h3>
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
                                        <form action="play_hole.php" method="get" class="d-inline">
                                            <input type="hidden" name="course_id" value="<?= $course['id'] ?>">
                                            <button type="submit" class="btn btn-success btn-lg px-5 py-2">Start Round</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>