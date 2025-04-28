<?php
session_start();
require 'config.php'; // Include Google Client configuration

if (isset($_SESSION['user_info'])) {
    header('Location: profile.php'); // If logged in, redirect to profile
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle registration and login logic
    $email = $_POST['email'];
    $password = $_POST['password'];
    $name = $_POST['name'];
    $action = $_POST['action']; // 'login' or 'register'

    if ($action == 'login') {
        // Email login
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_info'] = $user;

            // Insert session into user_sessions table
            $stmt = $pdo->prepare("INSERT INTO user_sessions (user_id) VALUES (?)");
            $stmt->execute([$user['id']]);

            header('Location: profile.php'); // Redirect to profile
            exit();
        } else {
            $error = "Invalid credentials!";
        }
    } else if ($action == 'register') {
        // Email registration
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (email, password,name) VALUES (?, ?,?)");
        $stmt->execute([$email, $hashedPassword,$name]);

        // Get user ID and insert session into user_sessions table
        $userId = $pdo->lastInsertId();
        $_SESSION['user_info'] = [
            'id' => $userId,
            'email' => $email,
            'name' => $name 
        ];

        // Insert session into user_sessions table
        $stmt = $pdo->prepare("INSERT INTO user_sessions (user_id) VALUES (?)");
        $stmt->execute([$userId]);

        header('Location: profile.php'); // Redirect to profile
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login/Register</title>
</head>
<body>
    <h2>Login or Register</h2>

    <h3>Email Login</h3>
    <form method="POST">
        <input type="email" name="email" placeholder="Email" required><br>
        <input type="password" name="password" placeholder="Password" required><br>
        <input type="hidden" name="action" value="login">
        <button type="submit">Login</button>
    </form>

    <h3>Email Registration</h3>
    <form method="POST">
        <input type="email" name="email" placeholder="Email" required><br>
        <input type="password" name="password" placeholder="Password" required><br>
        <input type="name" name="name" placeholder="name" required><br>
        <input type="hidden" name="action" value="register">
        <button type="submit">Register</button>
    </form>

    <h3>Or login with Google</h3>
    <a href="<?= $loginUrl ?>">Login with Google</a>

</body>
</html>
