<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    header('Content-Type: application/json');
    $host = 'localhost';
    $dbname = 'movieflix2';
    $user = 'root';
    $pass = '1234';
    $conn = new mysqli($host, $user, $pass, $dbname);
    if ($conn->connect_error) {
        echo json_encode(["error" => "Database connection failed."]);
        exit();
    }
    $user_id = (int)$_SESSION['user_id'];
    $sql = "SELECT movie_id FROM history WHERE user_id = $user_id ORDER BY watched_at DESC";
    $result = $conn->query($sql);
    $movie_ids = [];
    while ($row = $result->fetch_assoc()) {
        $movie_ids[] = $row['movie_id'];
    }
    echo json_encode($movie_ids);
    $conn->close();
?>