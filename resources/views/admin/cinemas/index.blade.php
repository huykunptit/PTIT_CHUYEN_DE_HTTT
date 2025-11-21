@extends('layouts.admin')

@section('title', 'Quản lý rạp - Admin Panel')
@section('page-title', 'Quản lý rạp')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
    <h5 class="mb-0">Danh sách rạp chiếu</h5>
    <div class="d-flex align-items-center gap-2">
        <label for="city-filter" class="text-muted mb-0">Khu vực:</label>
        <select id="city-filter" class="form-select form-select-sm" style="min-width: 180px;">
            <option value="">Tất cả</option>
            <option value="HCM">TP. Hồ Chí Minh</option>
            <option value="HN">Hà Nội</option>
        </select>
        <a href="{{ route('admin.cinemas.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Thêm rạp mới
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table id="cinemas-table" 
                   data-toggle="table"
                   data-search="true"
                   data-pagination="true"
                   data-page-size="10"
                   data-page-list="[10, 25, 50, 100, all]"
                   data-show-export="true"
                   data-export-types="['excel', 'csv']"
                   data-export-options='{"fileName": "danh-sach-rap"}'
                   data-locale="vi-VN"
                   data-sort-name="created_at"
                   data-sort-order="desc">
                <thead>
                    <tr>
                        <th data-field="name" data-sortable="true" data-formatter="nameFormatter">Tên rạp</th>
                        <th data-field="address" data-sortable="true">Địa chỉ</th>
                        <th data-field="city" data-sortable="true" data-formatter="cityFormatter">Khu vực</th>
                        <th data-field="phone" data-sortable="true">Số điện thoại</th>
                        <th data-field="email" data-sortable="true">Email</th>
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
    $cinemasData = $cinemas->map(function ($cinema) {
        return [
            'id' => $cinema->id,
            'name' => $cinema->name,
            'description' => $cinema->description,
            'address' => $cinema->address,
            'city' => $cinema->city,
            'phone' => $cinema->phone,
            'email' => $cinema->email ?? '',
            'is_active' => $cinema->is_active,
            'show_url' => route('admin.cinemas.show', $cinema->id),
            'edit_url' => route('admin.cinemas.edit', $cinema->id),
            'destroy_url' => route('admin.cinemas.destroy', $cinema->id),
        ];
    });
@endphp
<script>
    const csrfToken = '{{ csrf_token() }}';
    const allCinemasData = @json($cinemasData);
    let filteredCinemas = allCinemasData;

    function nameFormatter(value, row) {
        const desc = row.description ? row.description.substring(0, 50) + '...' : '';
        return `<strong>${value}</strong>${desc ? '<br><small class="text-muted">' + desc + '</small>' : ''}`;
    }

    function cityFormatter(value) {
        if (value === 'HN') {
            return '<span class="badge bg-info text-dark">Hà Nội</span>';
        }
        return '<span class="badge bg-primary">TP. Hồ Chí Minh</span>';
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
            <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteCinema(${row.id})" title="Xóa"><i class="fas fa-trash"></i></button>
        </div>`;
    }

    function deleteCinema(id) {
        const row = cinemasData.find(c => c.id === id);
        if (row && confirm('Bạn có chắc muốn xóa rạp này?')) {
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

    function applyCityFilter(city) {
        if (city) {
            filteredCinemas = allCinemasData.filter(cinema => cinema.city === city);
        } else {
            filteredCinemas = allCinemasData;
        }
        $('#cinemas-table').bootstrapTable('load', filteredCinemas);
    }

    $(document).ready(function () {
        const cityFilter = $('#city-filter');
        const initialCity = '{{ $selectedCity ?? '' }}';
        if (initialCity) {
            cityFilter.val(initialCity);
        }

        applyCityFilter(initialCity);

        cityFilter.on('change', function () {
            const value = this.value;
            const url = new URL(window.location.href);
            if (value) {
                url.searchParams.set('city', value);
            } else {
                url.searchParams.delete('city');
            }
            window.history.replaceState({}, '', url);
            applyCityFilter(value);
        });
    });
</script>
@endsection
