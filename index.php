<?php
session_start();
$isLoggedIn = isset($_SESSION['user_id']);
$username = $isLoggedIn ? htmlspecialchars($_SESSION['name']) : null;
$userRole = $isLoggedIn && isset($_SESSION['role']) ? $_SESSION['role'] : null;
?>
<!DOCTYPE html>
<html>
<head>
    <title>Golf App Home</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <!-- Navbar -->
        <nav class="navbar navbar-light bg-white shadow-sm px-2">
            <div class="container-fluid d-flex justify-content-between align-items-center">
                <div>
                    <?php if ($isLoggedIn): ?>
                        <span class="navbar-text fw-semibold">Welcome, <?= $username ?>!</span>
                    <?php endif; ?>
                </div>
                <div class="dropdown">
                    <button class="btn p-0 border-0 bg-transparent" type="button" id="userMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end mt-2" aria-labelledby="userMenuButton">
                        <li>
                            <a class="dropdown-item" href="view_rounds.php">View Previous Rounds</a>
                        </li>
                        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                            <li>
                                <a class="dropdown-item" href="admin.php">Admin Panel</a>
                            </li>
                        <?php endif; ?>
                        <li>
                            <a class="dropdown-item" href="logout.php">Logout</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    <!-- Main Content -->
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-7">
                <div class="card shadow-sm">
                    <div class="card-body text-center">
                        <h1 class="card-title mb-4">Welcome to the Golf App</h1>
                        <p class="lead mb-4">
                            <?php if ($isLoggedIn): ?>
                                Hello, <strong><?= $username ?></strong>!
                            <?php else: ?>
                                Please log in or register to continue.
                            <?php endif; ?>
                        </p>
                        <?php if ($isLoggedIn): ?>
                            <a href="play_round.php" class="btn btn-lg btn-success mt-3">Play a Round</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>