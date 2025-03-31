const API_KEY = "c81b62667c7fc974b1d6c61dc88c3699";
const BASE_URL = "https://api.themoviedb.org/3";
const IMG_BASE_URL = "https://image.tmdb.org/t/p/w500";
const moviesContainer = document.getElementById("movies-container");
const CURRENT_YEAR = new Date().getFullYear();

async function fetchMovies(category, containerId, genreId = null, query = null) {
    let url = "";
    if (genreId) {
        url = `${BASE_URL}/discover/movie?api_key=${API_KEY}&with_genres=${genreId}&sort_by=popularity.desc&vote_average.gte=7&vote_count.gte=1000`;
    }
    else {
        if (category === "trending")
            url = `${BASE_URL}/trending/movie/week?api_key=${API_KEY}`;
        else if (category === "popular")
            url = `${BASE_URL}/movie/popular?api_key=${API_KEY}`;
        else if (category === "discover")
            url = `${BASE_URL}/discover/movie?api_key=${API_KEY}&sort_by=vote_count.desc&with_original_language=en`;
        else if (category === "discover_tv")
            url = `${BASE_URL}/discover/tv?api_key=${API_KEY}&sort_by=vote_count.desc&with_original_language=en`;
        else if (category === "top_rated")
            url = `${BASE_URL}/movie/top_rated?api_key=${API_KEY}`;
        else if (category === "search")
            url = `${BASE_URL}/search/movie?api_key=${API_KEY}&query=${query}`
    }
    try {
        const res = await fetch(url);
        const data = await res.json();
        displayMovies(data.results, containerId);
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