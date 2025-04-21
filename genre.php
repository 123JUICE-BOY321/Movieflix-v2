<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Discover Movies</title>
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
                            echo '
                                <div class="dropdown me-2">
                                    <button class="btn btn-primary dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                        ' . htmlspecialchars($_SESSION['username']) . '
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="userDropdown">
                                        <li><a class="dropdown-item" href="history.php">View History</a></li>
                                        <li><a class="dropdown-item" href="profile.php">Edit Profile</a></li>
                                    </ul>
                                </div>
                                <a href="logout.php">
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

        <div class="container-fluid px-5 mt-5">
            <div id="genre-title" class="h2 mb-3"></div>
            <div id="genre-movies" class="row row-cols-3 row-cols-md-3 row-cols-lg-4 row-cols-xl-5 row-cols-xxl-6 g-4"></div>
        </div>

        <div id="pagination" class="text-center my-4"></div>

        <footer class="bg-black text-white text-center py-3 mt-5 border-top">
            <div class="container">
                <p>🎬 MovieFlix. No Rights Reserved.</p>
                <p>
                    <img src="https://media4.giphy.com/media/l1Lc1Kn9hImgpx5Re/200w.gif" alt="THE END" width="150">
                </p>
            </div>
        </footer>

        <script src="config.js"></script>
        <script src="script.js"></script>
        <script>
            const urlParams = new URLSearchParams(window.location.search);
            const genreId = urlParams.get("genre");
            const genreTitleMap = {
                28: "Action Movies",
                12: "Adventure Movies",
                35: "Comedy Movies",
                18: "Drama Movies",
                27: "Horror Movies",
                10749: "Romance Movies",
                878: "Science Fiction Movies"
            };

            document.getElementById("genre-title").innerText = genreTitleMap[genreId];
            fetchMovies(null, "genre-movies", genreId);
        </script>
    </body>
</html>