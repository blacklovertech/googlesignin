<?php
session_start();

// Database connection (Make sure db.php is included to connect to your database)
require 'db.php';

// Fetch all users from the database
$query_all_users = "SELECT id, name, email, picture FROM users";
$stmt_all_users = $pdo->prepare($query_all_users);
$stmt_all_users->execute();
$all_users = $stmt_all_users->fetchAll();

// Fetch logged-in users and session data
$query_logged_in_users = "SELECT u.id, u.name, u.email, u.picture, us.session_start, us.session_end, us.is_active
                          FROM users u
                          LEFT JOIN user_sessions us ON u.id = us.user_id WHERE us.is_active = 1";
$stmt_logged_in_users = $pdo->prepare($query_logged_in_users);
$stmt_logged_in_users->execute();
$logged_in_users = $stmt_logged_in_users->fetchAll();

// Fetch all historical sessions (including inactive ones)
$query_all_sessions = "SELECT u.id, u.name, u.email, us.session_start, us.session_end, us.is_active
                       FROM users u
                       LEFT JOIN user_sessions us ON u.id = us.user_id";
$stmt_all_sessions = $pdo->prepare($query_all_sessions);
$stmt_all_sessions->execute();
$all_sessions = $stmt_all_sessions->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Analytics</title>
    <!-- Bootstrap CSS for better UI -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }
        table {
            margin-top: 20px;
        }
        th {
            text-align: center;
        }
        img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Admin Analytics - Users</h2>

        <!-- Section: All Users -->
        <h3 class="mt-4">All Registered Users</h3>
        <p>Below is the list of all users registered in the system.</p>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">Email</th>
                    <th scope="col">Profile Picture</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($all_users as $user): ?>
                    <tr>
                        <td><?= htmlspecialchars($user['id']) ?></td>
                        <td><?= htmlspecialchars($user['name']) ?></td>
                        <td><?= htmlspecialchars($user['email']) ?></td>
                        <td><img src="<?= htmlspecialchars($user['picture']) ?>" alt="Profile Picture"></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Section: Logged-In Users -->
        <h3 class="mt-4">Currently Logged-In Users</h3>
        <p>Below is the list of currently logged-in users with session details.</p>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">Email</th>
                    <th scope="col">Profile Picture</th>
                    <th scope="col">Session Start</th>
                    <th scope="col">Session End</th>
                    <th scope="col">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($logged_in_users as $user): ?>
                    <tr>
                        <td><?= htmlspecialchars($user['id']) ?></td>
                        <td><?= htmlspecialchars($user['name']) ?></td>
                        <td><?= htmlspecialchars($user['email']) ?></td>
                        <td><img src="<?= htmlspecialchars($user['picture']) ?>" alt="Profile Picture"></td>
                        <td><?= htmlspecialchars($user['session_start']) ?></td>
                        <td>
                            <?php if ($user['session_end']): ?>
                                <?= htmlspecialchars($user['session_end']) ?>
                            <?php else: ?>
                                <span class="text-success">Still Active</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($user['session_end']): ?>
                                <span class="text-danger">Logged Out</span>
                            <?php else: ?>
                                <span class="text-success">Logged In</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Section: All Historical Sessions -->
        <h3 class="mt-4">All Historical Sessions</h3>
        <p>Below is the list of all sessions (including inactive ones) for each user.</p>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">Email</th>
                    <th scope="col">Session Start</th>
                    <th scope="col">Session End</th>
                    <th scope="col">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($all_sessions as $session): ?>
                    <tr>
                        <td><?= htmlspecialchars($session['id']) ?></td>
                        <td><?= htmlspecialchars($session['name']) ?></td>
                        <td><?= htmlspecialchars($session['email']) ?></td>
                        <td><?= htmlspecialchars($session['session_start']) ?></td>
                        <td>
                            <?php if ($session['session_end']): ?>
                                <?= htmlspecialchars($session['session_end']) ?>
                            <?php else: ?>
                                <span class="text-success">Still Active</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($session['session_end']): ?>
                                <span class="text-danger">Logged Out</span>
                            <?php else: ?>
                                <span class="text-success">Logged In</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Bootstrap JS (optional for dropdowns, modals, etc.) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
