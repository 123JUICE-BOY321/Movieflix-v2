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
    $password = $_POST['password'];
    $errors = [];
    $sql = "SELECT id, username, password FROM users WHERE username = '$username'";
    $result = $conn->query($sql);
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['username'] = $user['username'];
            unset($_SESSION['old_username']);
            unset($_SESSION['errors']);
            header("Location: index.php");
            exit();
        } else {
            $errors[] = "Invalid password.";
        }
    } else {
        $errors[] = "No account found with that username.";
    }
    $_SESSION['errors'] = $errors;
    $_SESSION['old_username'] = $username;
    header("Location: login.php");
    $conn->close();
    exit();
?>
