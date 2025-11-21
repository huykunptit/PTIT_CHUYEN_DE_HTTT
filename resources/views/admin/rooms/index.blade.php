@extends('layouts.admin')

@section('title', 'Quản lý phòng chiếu - Admin Panel')
@section('page-title', 'Quản lý phòng chiếu')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="mb-0">Danh sách phòng chiếu</h5>
    <a href="{{ route('admin.rooms.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>Thêm phòng mới
    </a>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table id="rooms-table" 
                   data-toggle="table"
                   data-search="true"
                   data-pagination="true"
                   data-page-size="10"
                   data-page-list="[10, 25, 50, 100, all]"
                   data-show-export="true"
                   data-export-types="['excel', 'csv']"
                   data-export-options='{"fileName": "danh-sach-phong"}'
                   data-locale="vi-VN"
                   data-sort-name="created_at"
                   data-sort-order="desc">
                <thead>
                    <tr>
                        <th data-field="cinema_name" data-sortable="true">Rạp</th>
                        <th data-field="name" data-sortable="true">Tên phòng</th>
                        <th data-field="type" data-sortable="true" data-formatter="typeFormatter">Loại</th>
                        <th data-field="capacity" data-sortable="true">Sức chứa</th>
                        <th data-field="is_active" data-sortable="true" data-formatter="statusFormatter">Trạng thái</th>
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
    $roomsData = $rooms->map(function ($room) {
        return [
            'id' => $room->id,
            'cinema_name' => $room->cinema->name,
            'name' => $room->name,
            'type' => $room->type,
            'capacity' => $room->capacity,
            'is_active' => $room->is_active,
            'show_url' => route('admin.rooms.show', $room->id),
            'edit_url' => route('admin.rooms.edit', $room->id),
            'destroy_url' => route('admin.rooms.destroy', $room->id),
        ];
    });
@endphp
<script>
    const csrfToken = '{{ csrf_token() }}';
    const roomsData = @json($roomsData);

    function typeFormatter(value) {
        const typeMap = {
            'IMAX': { text: 'IMAX', class: 'danger' },
            '4DX': { text: '4DX', class: 'warning' },
            'VIP': { text: 'VIP', class: 'primary' },
            'STANDARD': { text: 'STANDARD', class: 'secondary' }
        };
        const type = typeMap[value] || { text: value, class: 'secondary' };
        return `<span class="badge bg-${type.class}">${type.text}</span>`;
    }

    function statusFormatter(value) {
        return value 
            ? '<span class="badge bg-success">Hoạt động</span>'
            : '<span class="badge bg-secondary">Tạm dừng</span>';
    }

    function actionsFormatter(value, row) {
        return `<div class="btn-group" role="group">
            <a href="${row.show_url}" class="btn btn-sm btn-outline-info" title="Xem"><i class="fas fa-eye"></i></a>
            <a href="${row.edit_url}" class="btn btn-sm btn-outline-warning" title="Sửa"><i class="fas fa-edit"></i></a>
            <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteRoom(${row.id})" title="Xóa"><i class="fas fa-trash"></i></button>
        </div>`;
    }

    function deleteRoom(id) {
        const row = roomsData.find(r => r.id === id);
        if (row && confirm('Bạn có chắc muốn xóa phòng này?')) {
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
        $('#rooms-table').bootstrapTable('load', roomsData);
    });
</script>
@endsection
