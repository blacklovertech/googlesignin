<?php
session_start();

// If the user is not logged in, redirect to login page
if (!isset($_SESSION['user_info'])) {
    header('Location: index.php');
    exit();
}

$user_info = $_SESSION['user_info'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Profile</title>
</head>
<body>
    <h2>Welcome, <?= htmlspecialchars($user_info['name']) ?>!</h2>
    <p>Email: <?= htmlspecialchars($user_info['email']) ?></p>
    <p>
    Profile Picture: 
    <img src="<?= isset($user_info['picture']) && !empty($user_info['picture']) ? htmlspecialchars($user_info['picture']) : 'default-profile.jpg' ?>" alt="Profile Picture">
</p>

    <a href="logout.php">Logout</a>
</body>
</html>
