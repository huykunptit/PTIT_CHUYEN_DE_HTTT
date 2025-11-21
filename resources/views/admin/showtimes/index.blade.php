@extends('layouts.admin')

@section('title', 'Quản lý lịch chiếu - Admin Panel')
@section('page-title', 'Quản lý lịch chiếu')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="mb-0">Danh sách lịch chiếu</h5>
    <a href="{{ route('admin.showtimes.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>Tạo lịch chiếu mới
    </a>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table id="showtimes-table" 
                   data-toggle="table"
                   data-search="true"
                   data-pagination="true"
                   data-page-size="10"
                   data-page-list="[10, 25, 50, 100, all]"
                   data-show-export="true"
                   data-export-types="['excel', 'csv']"
                   data-export-options='{"fileName": "danh-sach-lich-chieu"}'
                   data-locale="vi-VN"
                   data-sort-name="date"
                   data-sort-order="desc">
                <thead>
                    <tr>
                        <th data-field="movie_title" data-sortable="true" data-formatter="movieFormatter">Phim</th>
                        <th data-field="cinema_name" data-sortable="true">Rạp</th>
                        <th data-field="room_name" data-sortable="true">Phòng</th>
                        <th data-field="date" data-sortable="true" data-formatter="dateFormatter">Ngày</th>
                        <th data-field="time" data-sortable="true" data-formatter="timeFormatter">Giờ chiếu</th>
                        <th data-field="status" data-sortable="true" data-formatter="statusFormatter">Trạng thái</th>
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
    $showtimesData = $showtimes->map(function ($showtime) {
        return [
            'id' => $showtime->id,
            'movie_title' => $showtime->movie->title,
            'movie_genre' => $showtime->movie->genre,
            'cinema_name' => $showtime->room->cinema->name,
            'room_name' => $showtime->room->name,
            'date' => $showtime->date->format('Y-m-d'),
            'date_display' => $showtime->date->format('d/m/Y'),
            'start_time' => \Carbon\Carbon::parse($showtime->start_time)->format('H:i'),
            'end_time' => \Carbon\Carbon::parse($showtime->end_time)->format('H:i'),
            'status' => $showtime->status,
            'show_url' => route('admin.showtimes.show', $showtime->id),
            'edit_url' => route('admin.showtimes.edit', $showtime->id),
            'destroy_url' => route('admin.showtimes.destroy', $showtime->id),
        ];
    });
@endphp
<script>
    const csrfToken = '{{ csrf_token() }}';
    const showtimesData = @json($showtimesData);

    function movieFormatter(value, row) {
        return `<strong>${value}</strong><br><small class="text-muted">${row.movie_genre}</small>`;
    }

    function dateFormatter(value, row) {
        return row.date_display;
    }

    function timeFormatter(value, row) {
        return `${row.start_time} - ${row.end_time}`;
    }

    function statusFormatter(value) {
        const statusMap = {
            'ACTIVE': { text: 'Hoạt động', class: 'success' },
            'CANCELLED': { text: 'Đã hủy', class: 'danger' },
            'COMPLETED': { text: 'Hoàn thành', class: 'secondary' }
        };
        const status = statusMap[value] || { text: value, class: 'secondary' };
        return `<span class="badge bg-${status.class}">${status.text}</span>`;
    }

    function actionsFormatter(value, row) {
        return `<div class="btn-group" role="group">
            <a href="${row.show_url}" class="btn btn-sm btn-outline-info" title="Xem"><i class="fas fa-eye"></i></a>
            <a href="${row.edit_url}" class="btn btn-sm btn-outline-warning" title="Sửa"><i class="fas fa-edit"></i></a>
            <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteShowtime(${row.id})" title="Xóa"><i class="fas fa-trash"></i></button>
        </div>`;
    }

    function deleteShowtime(id) {
        const row = showtimesData.find(s => s.id === id);
        if (row && confirm('Bạn có chắc muốn xóa lịch chiếu này?')) {
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
        $('#showtimes-table').bootstrapTable('load', showtimesData);
    });
</script>
@endsection
