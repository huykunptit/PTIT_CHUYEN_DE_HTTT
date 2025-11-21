@extends('layouts.admin')

@section('title', 'Quản lý phim - Admin Panel')
@section('page-title', 'Quản lý phim')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="mb-0">Danh sách phim</h5>
    <a href="{{ route('admin.movies.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>Thêm phim mới
    </a>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table id="movies-table" 
                   data-toggle="table"
                   data-search="true"
                   data-pagination="true"
                   data-page-size="10"
                   data-page-list="[10, 25, 50, 100, all]"
                   data-show-export="true"
                   data-export-types="['excel', 'csv']"
                   data-export-options='{"fileName": "danh-sach-phim"}'
                   data-locale="vi-VN"
                   data-sort-name="created_at"
                   data-sort-order="desc">
                <thead>
                    <tr>
                        <th data-field="poster" data-formatter="posterFormatter">Poster</th>
                        <th data-field="title" data-sortable="true" data-formatter="titleFormatter">Tên phim</th>
                        <th data-field="genre" data-sortable="true" data-formatter="genreFormatter">Thể loại</th>
                        <th data-field="status" data-sortable="true" data-formatter="statusFormatter">Trạng thái</th>
                        <th data-field="rating" data-sortable="true" data-formatter="ratingFormatter">Đánh giá</th>
                        <th data-field="release_date" data-sortable="true" data-formatter="dateFormatter">Ngày phát hành</th>
                        <th data-field="actions" data-formatter="actionsFormatter">Thao tác</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
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
            'release_date' => $movie->release_date->format('Y-m-d'),
            'release_date_display' => $movie->release_date->format('d/m/Y'),
            'show_url' => route('admin.movies.show', $movie->id),
            'edit_url' => route('admin.movies.edit', $movie->id),
            'destroy_url' => route('admin.movies.destroy', $movie->id),
        ];
    });
@endphp
<script>
    const csrfToken = '{{ csrf_token() }}';
    const moviesData = @json($moviesData);

    function posterFormatter(value, row) {
        return '<div class="bg-dark d-flex align-items-center justify-content-center" style="width: 60px; height: 80px; border-radius: 4px;"><i class="fas fa-film text-light"></i></div>';
    }

    function titleFormatter(value, row) {
        const desc = row.description ? row.description.substring(0, 50) + '...' : '';
        return `<strong>${value}</strong><br><small class="text-muted">${desc}</small>`;
    }

    function genreFormatter(value) {
        return `<span class="badge bg-secondary">${value}</span>`;
    }

    function statusFormatter(value) {
        const statusMap = {
            'NOW_SHOWING': { text: 'Đang chiếu', class: 'success' },
            'COMING_SOON': { text: 'Sắp chiếu', class: 'info' },
            'ENDED': { text: 'Kết thúc', class: 'secondary' }
        };
        const status = statusMap[value] || { text: value, class: 'secondary' };
        return `<span class="badge bg-${status.class}">${status.text}</span>`;
    }

    function ratingFormatter(value) {
        let stars = '';
        const rating = Math.round(value / 2);
        for (let i = 1; i <= 5; i++) {
            stars += `<i class="fas fa-star${i <= rating ? '' : '-o'}"></i>`;
        }
        return `<div class="text-warning">${stars} <span class="ms-1">${value}</span></div>`;
    }

    function dateFormatter(value, row) {
        return row.release_date_display;
    }

    function actionsFormatter(value, row) {
        return `<div class="btn-group" role="group">
            <a href="${row.show_url}" class="btn btn-sm btn-outline-info" title="Xem"><i class="fas fa-eye"></i></a>
            <a href="${row.edit_url}" class="btn btn-sm btn-outline-warning" title="Sửa"><i class="fas fa-edit"></i></a>
            <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteMovie(${row.id})" title="Xóa"><i class="fas fa-trash"></i></button>
        </div>`;
    }

    function deleteMovie(id) {
        const row = moviesData.find(m => m.id === id);
        if (row && confirm('Bạn có chắc muốn xóa phim này?')) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = row.destroy_url;
            form.innerHTML = `
                <input type="hidden" name="_token" value="${csrfToken}">
                <input type="hidden" name="_method" value="DELETE">
            `;
            document.body.appendChild(form);
            form.submit();
        }
    }

    $(document).ready(function () {
        $('#movies-table').bootstrapTable('load', moviesData);
    });
</script>
@endsection
