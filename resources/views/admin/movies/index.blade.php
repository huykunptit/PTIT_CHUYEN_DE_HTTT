@extends('layouts.admin')

@section('title', 'Quản lý phim - Admin Panel')
@section('page-title', 'Quản lý phim')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="mb-1"><i class="fas fa-film me-2"></i>Quản lý phim</h3>
                    <p class="text-muted mb-0">Quản lý danh sách phim trong hệ thống</p>
                </div>
                <a href="{{ route('admin.movies.create') }}" class="btn btn-primary btn-lg">
                    <i class="fas fa-plus-circle me-2"></i>Thêm phim mới
                </a>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-primary bg-opacity-10 rounded p-3">
                                <i class="fas fa-film fa-2x text-primary"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Tổng số phim</h6>
                            <h3 class="mb-0">{{ $movies->count() }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-success bg-opacity-10 rounded p-3">
                                <i class="fas fa-play-circle fa-2x text-success"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Đang chiếu</h6>
                            <h3 class="mb-0">{{ $movies->where('status', 'NOW_SHOWING')->count() }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-info bg-opacity-10 rounded p-3">
                                <i class="fas fa-clock fa-2x text-info"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Sắp chiếu</h6>
                            <h3 class="mb-0">{{ $movies->where('status', 'COMING_SOON')->count() }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-secondary bg-opacity-10 rounded p-3">
                                <i class="fas fa-stop-circle fa-2x text-secondary"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Kết thúc</h6>
                            <h3 class="mb-0">{{ $movies->where('status', 'ENDED')->count() }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter and Table Card -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-bottom">
            <ul class="nav nav-tabs card-header-tabs" id="moviesTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="all-tab" data-bs-toggle="tab" data-bs-target="#all" type="button" role="tab">
                        <i class="fas fa-list me-2"></i>Tất cả
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="showing-tab" data-bs-toggle="tab" data-bs-target="#showing" type="button" role="tab">
                        <i class="fas fa-play-circle me-2"></i>Đang chiếu
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="coming-tab" data-bs-toggle="tab" data-bs-target="#coming" type="button" role="tab">
                        <i class="fas fa-clock me-2"></i>Sắp chiếu
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="ended-tab" data-bs-toggle="tab" data-bs-target="#ended" type="button" role="tab">
                        <i class="fas fa-stop-circle me-2"></i>Kết thúc
                    </button>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <!-- Search and Filter Bar -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="fas fa-search"></i></span>
                        <input type="text" class="form-control border-start-0" id="searchInput" placeholder="Tìm kiếm theo tên phim, thể loại...">
                    </div>
                </div>
                <div class="col-md-3">
                    <select class="form-select" id="genreFilter">
                        <option value="">Tất cả thể loại</option>
                        <option value="Hành động">Hành động</option>
                        <option value="Tình cảm">Tình cảm</option>
                        <option value="Kinh dị">Kinh dị</option>
                        <option value="Hài">Hài</option>
                        <option value="Khoa học viễn tưởng">Khoa học viễn tưởng</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <div class="btn-group w-100" role="group">
                        <button type="button" class="btn btn-outline-secondary" id="gridViewBtn">
                            <i class="fas fa-th"></i>
                        </button>
                        <button type="button" class="btn btn-outline-secondary active" id="listViewBtn">
                            <i class="fas fa-list"></i>
                        </button>
                        <button type="button" class="btn btn-outline-secondary" id="exportBtn">
                            <i class="fas fa-download"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Tab Content -->
            <div class="tab-content" id="moviesTabContent">
                <div class="tab-pane fade show active" id="all" role="tabpanel">
                    <div id="moviesContainer"></div>
                </div>
                <div class="tab-pane fade" id="showing" role="tabpanel">
                    <div id="showingContainer"></div>
                </div>
                <div class="tab-pane fade" id="coming" role="tabpanel">
                    <div id="comingContainer"></div>
                </div>
                <div class="tab-pane fade" id="ended" role="tabpanel">
                    <div id="endedContainer"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.nav-tabs .nav-link {
    color: #6c757d;
    border: none;
    border-bottom: 3px solid transparent;
    transition: all 0.3s ease;
}

.nav-tabs .nav-link:hover {
    color: #0052cc;
    border-bottom-color: #0052cc;
}

.nav-tabs .nav-link.active {
    color: #0052cc;
    background: transparent;
    border-bottom-color: #0052cc;
    font-weight: 600;
}

.movie-card-admin {
    transition: all 0.3s ease;
    cursor: pointer;
}

.movie-card-admin:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15) !important;
}

.movie-poster-admin {
    width: 100%;
    height: 200px;
    object-fit: cover;
    border-radius: 8px;
}

.view-grid .movie-item {
    margin-bottom: 20px;
}

.view-list .movie-item {
    margin-bottom: 15px;
}

.movie-actions .btn {
    padding: 0.375rem 0.75rem;
}
</style>
@endsection

@section('scripts')
@parent
@php
    $moviesData = $movies->map(function ($movie) {
        return [
            'id' => $movie->id,
            'title' => $movie->title,
            'description' => $movie->description,
            'genre' => $movie->genre,
            'status' => $movie->status,
            'rating' => (float) $movie->rating,
            'duration' => $movie->duration,
            'age_rating' => $movie->age_rating,
            'release_date' => $movie->release_date->format('Y-m-d'),
            'release_date_display' => $movie->release_date->format('d/m/Y'),
            'poster_url' => $movie->poster_url,
            'show_url' => route('admin.movies.show', $movie->id),
            'edit_url' => route('admin.movies.edit', $movie->id),
            'destroy_url' => route('admin.movies.destroy', $movie->id),
        ];
    });
@endphp
<script>
    const csrfToken = '{{ csrf_token() }}';
    const moviesData = @json($moviesData);
    let currentView = 'list';
    let currentFilter = 'all';

    function renderMovies(movies, container, view = 'list') {
        const containerEl = document.getElementById(container);
        if (!containerEl) return;

        if (movies.length === 0) {
            containerEl.innerHTML = `
                <div class="text-center py-5">
                    <i class="fas fa-film text-muted" style="font-size: 4rem;"></i>
                    <h5 class="text-muted mt-3">Không có phim nào</h5>
                </div>
            `;
            return;
        }

        if (view === 'grid') {
            containerEl.className = 'row view-grid';
            containerEl.innerHTML = movies.map(movie => `
                <div class="col-md-3 col-sm-6 movie-item">
                    <div class="card border-0 shadow-sm movie-card-admin h-100">
                        <div class="position-relative" style="height: 200px; overflow: hidden;">
                            ${movie.poster_url 
                                ? `<img src="${movie.poster_url}" alt="${movie.title}" class="movie-poster-admin" onerror="this.src='https://via.placeholder.com/200x300?text=No+Image'">` 
                                : `<div class="d-flex align-items-center justify-content-center h-100 bg-light"><i class="fas fa-film fa-3x text-muted"></i></div>`
                            }
                            ${getStatusBadge(movie.status)}
                        </div>
                        <div class="card-body">
                            <h6 class="card-title mb-2">${movie.title}</h6>
                            <p class="card-text text-muted small mb-2">${movie.description.substring(0, 60)}...</p>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="badge bg-secondary">${movie.genre}</span>
                                <div class="text-warning small">
                                    ${getRatingStars(movie.rating)}
                                </div>
                            </div>
                            <div class="d-flex gap-2 movie-actions">
                                <a href="${movie.show_url}" class="btn btn-sm btn-outline-info flex-fill" title="Xem">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="${movie.edit_url}" class="btn btn-sm btn-outline-warning flex-fill" title="Sửa">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button onclick="deleteMovie(${movie.id})" class="btn btn-sm btn-outline-danger flex-fill" title="Xóa">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            `).join('');
        } else {
            containerEl.className = 'view-list';
            containerEl.innerHTML = movies.map(movie => `
                <div class="card border-0 shadow-sm mb-3 movie-item">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-2">
                                ${movie.poster_url 
                                    ? `<img src="${movie.poster_url}" alt="${movie.title}" class="img-fluid rounded" style="height: 120px; object-fit: cover; width: 100%;" onerror="this.src='https://via.placeholder.com/150x200?text=No+Image'">` 
                                    : `<div class="d-flex align-items-center justify-content-center bg-light rounded" style="height: 120px;"><i class="fas fa-film fa-2x text-muted"></i></div>`
                                }
                            </div>
                            <div class="col-md-6">
                                <h5 class="mb-2">${movie.title}</h5>
                                <p class="text-muted mb-2">${movie.description.substring(0, 150)}...</p>
                                <div class="d-flex gap-2 flex-wrap">
                                    <span class="badge bg-secondary">${movie.genre}</span>
                                    ${getStatusBadge(movie.status)}
                                    <span class="badge bg-light text-dark"><i class="fas fa-clock me-1"></i>${movie.duration} phút</span>
                                    <span class="badge bg-light text-dark"><i class="fas fa-calendar me-1"></i>${movie.release_date_display}</span>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="text-warning text-center">
                                    ${getRatingStars(movie.rating)}
                                    <div class="mt-1"><strong>${movie.rating}</strong>/10</div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="d-flex flex-column gap-2">
                                    <a href="${movie.show_url}" class="btn btn-sm btn-outline-info">
                                        <i class="fas fa-eye me-1"></i>Xem
                                    </a>
                                    <a href="${movie.edit_url}" class="btn btn-sm btn-outline-warning">
                                        <i class="fas fa-edit me-1"></i>Sửa
                                    </a>
                                    <button onclick="deleteMovie(${movie.id})" class="btn btn-sm btn-outline-danger">
                                        <i class="fas fa-trash me-1"></i>Xóa
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `).join('');
        }
    }

    function getStatusBadge(status) {
        const statusMap = {
            'NOW_SHOWING': { text: 'Đang chiếu', class: 'success' },
            'COMING_SOON': { text: 'Sắp chiếu', class: 'info' },
            'ENDED': { text: 'Kết thúc', class: 'secondary' }
        };
        const st = statusMap[status] || { text: status, class: 'secondary' };
        return `<span class="badge bg-${st.class}">${st.text}</span>`;
    }

    function getRatingStars(rating) {
        let stars = '';
        const starCount = Math.round(rating / 2);
        for (let i = 1; i <= 5; i++) {
            stars += `<i class="fas fa-star${i <= starCount ? '' : '-o'}"></i>`;
        }
        return stars;
    }

    function deleteMovie(id) {
        const movie = moviesData.find(m => m.id === id);
        if (movie && confirm(`Bạn có chắc muốn xóa phim "${movie.title}"?`)) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = movie.destroy_url;
            form.innerHTML = `
                <input type="hidden" name="_token" value="${csrfToken}">
                <input type="hidden" name="_method" value="DELETE">
            `;
            document.body.appendChild(form);
            form.submit();
        }
    }

    function filterMovies() {
        const searchTerm = document.getElementById('searchInput').value.toLowerCase();
        const genreFilter = document.getElementById('genreFilter').value;

        let filtered = moviesData;

        if (searchTerm) {
            filtered = filtered.filter(m => 
                m.title.toLowerCase().includes(searchTerm) || 
                m.description.toLowerCase().includes(searchTerm) ||
                m.genre.toLowerCase().includes(searchTerm)
            );
        }

        if (genreFilter) {
            filtered = filtered.filter(m => m.genre === genreFilter);
        }

        renderMovies(filtered, 'moviesContainer', currentView);
        renderMovies(filtered.filter(m => m.status === 'NOW_SHOWING'), 'showingContainer', currentView);
        renderMovies(filtered.filter(m => m.status === 'COMING_SOON'), 'comingContainer', currentView);
        renderMovies(filtered.filter(m => m.status === 'ENDED'), 'endedContainer', currentView);
    }

    // Event Listeners
    document.addEventListener('DOMContentLoaded', function() {
        // Initial render
        filterMovies();

        // Search
        document.getElementById('searchInput').addEventListener('input', filterMovies);
        document.getElementById('genreFilter').addEventListener('change', filterMovies);

        // View toggle
        document.getElementById('gridViewBtn').addEventListener('click', function() {
            currentView = 'grid';
            this.classList.add('active');
            document.getElementById('listViewBtn').classList.remove('active');
            filterMovies();
        });

        document.getElementById('listViewBtn').addEventListener('click', function() {
            currentView = 'list';
            this.classList.add('active');
            document.getElementById('gridViewBtn').classList.remove('active');
            filterMovies();
        });

        // Export
        document.getElementById('exportBtn').addEventListener('click', function() {
            alert('Chức năng export đang được phát triển');
        });
    });
</script>
@endsection