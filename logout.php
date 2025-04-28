<?php
session_start();
require 'db.php';

// Mark the user's session as inactive in the database
if (isset($_SESSION['user_info'])) {
    $userId = $_SESSION['user_info']['id'];
    $stmt = $pdo->prepare("UPDATE user_sessions SET is_active = 0, session_end = NOW() WHERE user_id = ? AND is_active = 1");
    $stmt->execute([$userId]);
}

// Destroy the session
session_unset();
session_destroy();

header('Location: index.php'); // Redirect to login page
exit();
