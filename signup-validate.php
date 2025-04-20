<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    $host = 'localhost';
    $dbname = 'movieflix2';
    $user = 'root';
    $pass = '1234';
    $conn = new mysqli($host, $user, $pass, $dbname);
    if ($conn->connect_error) {
        die("Database connection failed: " . $conn->connect_error);
    }
    $username = mysqli_real_escape_string($conn, trim($_POST['username']));
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $errors = [];
    if ($password !== $confirm_password) {
        $errors[] = "Passwords do not match.";
    }
    $sql = "SELECT id FROM users WHERE username = '$username'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $errors[] = "Username already exists.";
    }
    $sql = "SELECT id FROM users WHERE email = '$email'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $errors[] = "Email already exists.";
    }
    if (empty($errors)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashedPassword')";
        if ($conn->query($sql) === TRUE) {
            unset($_SESSION['old']);
            unset($_SESSION['errors']);
            header("Location: login.php");
            exit();
        }
    }
    $_SESSION['old'] = [
        'username' => $username,
        'email' => $email
    ];
    $_SESSION['errors'] = $errors;
    header("Location: signup.php");
    $conn->close();
?>
