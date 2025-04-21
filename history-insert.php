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
    $movie_id = (int)$_POST['movie_id'];
    $trailerUrl = $_POST['movie_trailer'];
    if (!isset($_SESSION['username'])) {
        header("Location: $trailerUrl");
        exit();
    }
    $user_id = (int)$_SESSION['user_id'];
    $sql = "INSERT INTO history (user_id, movie_id) VALUES ($user_id, $movie_id) ON DUPLICATE KEY UPDATE watched_at = CURRENT_TIMESTAMP";
    $result = $conn->query($sql);
    header("Location: $trailerUrl");
    $conn->close();
    exit();
?>