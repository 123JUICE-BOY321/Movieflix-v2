<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MovieFlix</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    ?>
    <nav class="navbar navbar-expand-lg border-bottom border-body">
        <div class="container-fluid">
            <a class="navbar-brand text-white" href="index.php">🎬 MovieFlix</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto me-3 mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active text-white" aria-current="page" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="movies.php">Movies</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="tvshows.php">TV Shows</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="toprated.php">Top Rated</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-white" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Genre
                        </a>
                        <ul class="dropdown-menu dropdown-menu-dark">
                            <li><a class="dropdown-item" href="genre.php?genre=28">Action</a></li>
                            <li><a class="dropdown-item" href="genre.php?genre=12">Adventure</a></li>
                            <li><a class="dropdown-item" href="genre.php?genre=35">Comedy</a></li>
                            <li><a class="dropdown-item" href="genre.php?genre=18">Drama</a></li>
                            <li><a class="dropdown-item" href="genre.php?genre=27">Horror</a></li>
                            <li><a class="dropdown-item" href="genre.php?genre=10749">Romance</a></li>
                            <li><a class="dropdown-item" href="genre.php?genre=878">Science Fiction</a></li>
                        </ul>
                    </li>
                </ul>
                <form class="d-flex me-auto" role="search" action="search.php" method="GET">
                    <input class="form-control me-2" type="search" name="query" id="searchInput" placeholder="Search" aria-label="Search" required>
                    <button class="btn btn-outline-success me-2" type="submit">Search</button>
                </form>
                <?php 
                    if (isset($_SESSION['username'])) {
                        echo '<a href="history.html">
                                <button class="btn btn-primary me-2">' . htmlspecialchars($_SESSION['username']) . '</button>
                            </a>';
                        echo '<a href="logout.php">
                                <button class="btn btn-danger me-2">Logout</button>
                            </a>';
                    } else {
                        echo '<a href="signup.php">
                                <button class="btn btn-primary me-2">Sign up</button>
                            </a>';
                        echo '<a href="login.php">
                                <button class="btn btn-danger me-2">Login</button>
                            </a>';
                    }
                ?>
            </div>
        </div>
    </nav>

    <div class="container d-flex align-items-center justify-content-center">
        <div class="card p-4 mt-5" style="width: 25rem;">
            <div class="card-body">
                <h3 class="card-title text-center mb-3">Login</h3>
                <form action="login-validate.php" method="POST">
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($_SESSION['old_username'] ?? '') ?>" placeholder="Enter username" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password"  placeholder="Enter password" required minlength="8">
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox"  class="form-check-input" id="remember" name="remember" >
                        <label class="form-check-label" for="remember">Remember me</label>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Login</button>
                </form>
                <?php
                    if (isset($_SESSION['errors'])) {
                        foreach ($_SESSION['errors'] as $error) {
                            echo "<div class='alert alert-danger text-center mt-2'>$error</div>";
                        }
                        unset($_SESSION['errors']);
                    }
                    unset($_SESSION['old_username']);
                ?>
            </div>
        </div>
    </div>

    <footer class="bg-black text-white text-center py-3 mt-5 border-top">
        <div class="container">
            <p>🎬 MovieFlix. No Rights Reserved.</p>
            <p>
                <img src="https://media4.giphy.com/media/l1Lc1Kn9hImgpx5Re/200w.gif" alt="THE END" width="150">
            </p>
        </div>
    </footer>
</body>
</html>