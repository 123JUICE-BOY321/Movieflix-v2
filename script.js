const BASE_URL = "https://api.themoviedb.org/3";
const IMG_BASE_URL = "https://image.tmdb.org/t/p/w500";
const moviesContainer = document.getElementById("movies-container");

async function fetchMovies(category, containerId, genreId = null, query = null, page = 1) {
    let url = "";
    if (genreId) {
        url = `${BASE_URL}/discover/movie?api_key=${API_KEY}&with_genres=${genreId}&sort_by=popularity.desc&vote_average.gte=7&vote_count.gte=1000&page=${page}`;
    } else {
        if (category === "trending")
            url = `${BASE_URL}/trending/movie/week?api_key=${API_KEY}&page=${page}`;
        else if (category === "popular")
            url = `${BASE_URL}/movie/popular?api_key=${API_KEY}&page=${page}`;
        else if (category === "discover")
            url = `${BASE_URL}/discover/movie?api_key=${API_KEY}&sort_by=vote_count.desc&with_original_language=en&page=${page}`;
        else if (category === "discover_tv")
            url = `${BASE_URL}/discover/tv?api_key=${API_KEY}&sort_by=vote_count.desc&with_original_language=en&page=${page}`;
        else if (category === "top_rated")
            url = `${BASE_URL}/movie/top_rated?api_key=${API_KEY}&page=${page}`;
        else if (category === "search")
            url = `${BASE_URL}/search/movie?api_key=${API_KEY}&query=${query}&page=${page}`;
    }
    try {
        const res = await fetch(url);
        const data = await res.json();
        displayMovies(data.results, containerId);
        updatePaginationControls(data.page, data.total_pages, category, containerId, genreId, query);
    } catch (error) {
        console.error("Error fetching movies:", error);
    }
}

async function displayMovies(movies, containerId) {
    const container = document.getElementById(containerId);
    container.innerHTML = "";
    if (movies.length === 0) {
        container.innerHTML = "<p>No results found.</p>";
        return;
    }
    for (let i = 0; i < movies.length; i++) {
        if (!movies[i].poster_path)
            continue;
        const movie = movies[i];
        const trailerUrl = await fetchTrailer(movie.id, containerId);
        const movieCard = document.createElement("div");
        movieCard.classList.add("col");
        movieCard.innerHTML = `
            <div class="flip-card">
                <div class="flip-card-inner">
                    <div class="flip-card-front">
                        <img src="${IMG_BASE_URL + movie.poster_path}" alt="${movie.title||movie.name}" class="img-fluid rounded-3">
                    </div>
                    <div class="flip-card-back rounded-3 px-2 py-4" style="background-image: url('${IMG_BASE_URL + movie.poster_path}');">
                        <div class="h5">${movie.title||movie.name}</div>
                        <div class="mb-5">${movie.overview.substring(0, 100)}...</div>
                        <a href="${trailerUrl || 'javascript:void(0);'}" ${trailerUrl ? 'target="_blank"' : 'onclick="alert(\'Trailer not available!\')"'}>
                            <button class="btn btn-primary">Watch Now</button>
                        </a>
                    </div>
                </div>
            </div>`;
        container.appendChild(movieCard);
    }
}

async function fetchTrailer(movieId, type) {
    try {
        const url = type === "discover_tvshows" ? `${BASE_URL}/tv/${movieId}/videos?api_key=${API_KEY}` : `${BASE_URL}/movie/${movieId}/videos?api_key=${API_KEY}`;
        const res = await fetch(url);
        const data = await res.json();
        let trailer = null;
        for (let i = 0; i < data.results.length; i++) {
            if (data.results[i].type === "Trailer") {
                trailer = data.results[i];
                break;
            }
        }
        return trailer ? `https://www.youtube.com/watch?v=${trailer.key}` : null;
    } catch (error) {
        console.error("Error fetching trailer:", error);
        return null;
    }
}

function updatePaginationControls(currentPage, totalPages, category, containerId, genreId, query) {
    const paginationContainer = document.getElementById("pagination");
    paginationContainer.innerHTML = "";
    if (totalPages <= 1) return;
    let prevButton = `<button class="btn btn-secondary me-2" ${currentPage === 1 ? "disabled" : ""} onclick="fetchMovies('${category}', '${containerId}', ${genreId}, '${query}', ${currentPage - 1})">Prev</button>`;
    let nextButton = `<button class="btn btn-secondary ms-2" ${currentPage === totalPages ? "disabled" : ""} onclick="fetchMovies('${category}', '${containerId}', ${genreId}, '${query}', ${currentPage + 1})">Next</button>`;
    paginationContainer.innerHTML = prevButton + `<span class="mx-3">Page ${currentPage} of ${totalPages}</span>` + nextButton;
}

async function renderTrendingCarousel() {
    const url = `${BASE_URL}/trending/movie/day?api_key=${API_KEY}`;
    try {
        const res = await fetch(url);
        const data = await res.json();
        const movies = data.results.filter(movie => movie.backdrop_path && movie.poster_path);

        if (movies.length === 0) return;

        const indicators = movies.map((_, idx) =>
            `<button type="button" data-bs-target="#trendingCarousel" data-bs-slide-to="${idx}" ${idx === 0 ? 'class="active"' : ''} aria-label="Slide ${idx + 1}"></button>`
        ).join("");

        const slides = await Promise.all(movies.map(async (movie, idx) => {
            const backdrop = `https://image.tmdb.org/t/p/w1280${movie.backdrop_path}`;
            const poster = `${IMG_BASE_URL}${movie.poster_path}`;
            const trailerUrl = await fetchTrailer(movie.id, "trending");

            return `
                <div class="carousel-item ${idx === 0 ? 'active' : ''}">
                    <div class="position-relative">
                        <img src="${backdrop}" class="d-block w-100 carousel-image" alt="${movie.title}">

                        <!-- Dark overlay -->
                        <div class="position-absolute top-0 start-0 w-100 h-100" style="background-color: rgba(0,0,0,0.4);"></div>
                        
                        <!-- Flip Card Overlay -->
                        <div class="position-absolute" style="bottom: 100px; left: 120px; width: 200px; height: 300px; z-index: 5;">
                            <div class="flip-card h-100 w-100">
                                <div class="flip-card-inner">
                                    <div class="flip-card-front rounded-3 overflow-hidden">
                                        <img src="${poster}" alt="${movie.title}" class="img-fluid w-100 h-100 object-fit-cover">
                                    </div>
                                    <div class="flip-card-back rounded-3 p-3 d-flex flex-column justify-content-between" style="background-image: url('${poster}');">
                                        <div class="fw-bold">${movie.title}</div>
                                        <div class="small">${movie.overview.substring(0, 80)}...</div>
                                        <a href="${trailerUrl || 'javascript:void(0);'}" ${trailerUrl ? 'target="_blank"' : 'onclick="alert(\'Trailer not available!\')"'}>
                                            <button class="btn btn-primary btn-sm mt-2">Watch Now</button>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>`;
        }));

        const carouselHTML = `
            <div class="container-fluid px-5 mt-4 mb-5">
                <div id="trendingCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel">
                    <div class="carousel-indicators">${indicators}</div>
                    <div class="carousel-inner rounded">${slides.join("")}</div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#trendingCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#trendingCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>
            </div>`;

        const navbar = document.querySelector("nav");
        navbar.insertAdjacentHTML("afterend", carouselHTML);

    } catch (error) {
        console.error("Error loading trending carousel:", error);
    }
}
