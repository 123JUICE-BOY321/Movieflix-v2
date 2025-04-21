<?php
    session_start();
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit();
    }
    $host = 'localhost';
    $dbname = 'movieflix2';
    $user = 'root';
    $pass = '1234';
    $conn = new mysqli($host, $user, $pass, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $errors = [];
    $user_id = $_SESSION['user_id'];
    $username = $conn->real_escape_string($_POST['username']);
    $email = $conn->real_escape_string($_POST['email']);
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];
    if (!empty($newPassword)) {
        if (strlen($newPassword) < 8) {
            $errors[] = "Password must be at least 8 characters.";
        } elseif ($newPassword !== $confirmPassword) {
            $errors[] = "Passwords do not match.";
        }
    }
    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        header("Location: profile.php");
        exit();
    }
    if (!empty($newPassword)) {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $query = "UPDATE users SET username='$username', email='$email', password='$hashedPassword' WHERE id=$user_id";
    } else {
        $query = "UPDATE users SET username='$username', email='$email' WHERE id=$user_id";
    }
    if ($conn->query($query)) {
        $_SESSION['success'] = "Profile updated successfully!";
        $_SESSION['username'] = $username;
    } else {
        $_SESSION['errors'] = ["Database error: " . $conn->error];
    }
    $conn->close();
    header("Location: profile.php");
    exit();
?>