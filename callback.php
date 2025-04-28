<?php
session_start();
require 'config.php'; // Include the Google Client configuration

// Google login authentication
if (isset($_GET['code'])) {
    $access_token = $googleClient->fetchAccessTokenWithAuthCode($_GET['code']);
    $_SESSION['access_token'] = $access_token;

    // Fetch user information from Google
    $google_service = new Google_Service_Oauth2($googleClient);
    $user_info = $google_service->userinfo->get();

    // Check if the user already exists in the database
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$user_info->email]);
    $user = $stmt->fetch();

    if ($user) {
        // If user exists, set session
        $_SESSION['user_info'] = $user;
    } else {
        // If user does not exist, insert into users table
        $stmt = $pdo->prepare("INSERT INTO users (email, name, picture, provider) VALUES (?, ?, ?, ?)");
        $stmt->execute([$user_info->email, $user_info->name, $user_info->picture, 'google']);

        // Get the user ID and set session
        $userId = $pdo->lastInsertId();
        $_SESSION['user_info'] = [
            'id' => $userId,
            'email' => $user_info->email,
            'name' => $user_info->name,
            'picture' => $user_info->picture
        ];
    }

    // Insert session into user_sessions table
    $stmt = $pdo->prepare("INSERT INTO user_sessions (user_id) VALUES (?)");
    $stmt->execute([$_SESSION['user_info']['id']]);

    // Redirect to profile page
    header('Location: profile.php');
    exit();
} else {
    echo 'Google authentication failed.';
}
