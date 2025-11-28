@extends('layouts.staff')

@section('title', 'Quản lý đặt vé - Staff Panel')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-ticket-alt me-2"></i>Quản lý đặt vé</h2>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table id="bookings-table" 
                   data-toggle="table"
                   data-search="true"
                   data-pagination="true"
                   data-page-size="10"
                   data-page-list="[10, 25, 50, 100, all]"
                   data-show-export="true"
                   data-export-types="['excel', 'csv']"
                   data-export-options='{"fileName": "danh-sach-dat-ve"}'
                   data-locale="vi-VN"
                   data-sort-name="created_at"
                   data-sort-order="desc">
                <thead>
                    <tr>
                        <th data-field="booking_code" data-sortable="true">Mã đặt vé</th>
                        <th data-field="customer_name" data-sortable="true">Khách hàng</th>
                        <th data-field="movie_title" data-sortable="true">Phim</th>
                        <th data-field="cinema_name" data-sortable="true">Rạp</th>
                        <th data-field="showtime_date" data-sortable="true">Ngày chiếu</th>
                        <th data-field="final_amount" data-sortable="true">Tổng tiền</th>
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
<script>
    const bookingsData = {!! json_encode($bookings->map(function($booking) {
        return [
            'booking_code' => $booking->booking_code,
            'customer_name' => (string) ($booking->user->name ?? 'N/A'),
            'movie_title' => $booking->showtime->movie->title,
            'cinema_name' => $booking->showtime->room->cinema->name,
            'showtime_date' => $booking->showtime->date->format('d/m/Y') . ' ' . \Carbon\Carbon::parse($booking->showtime->start_time)->format('H:i'),
            'final_amount' => $booking->final_amount,
            'status' => $booking->status,
            'id' => $booking->id,
        ];
    })) !!};

    $('#bookings-table').bootstrapTable({
        data: bookingsData
    });

    function statusFormatter(value, row) {
        const statusMap = {
            'PENDING': { class: 'warning', text: 'Chờ xử lý' },
            'CONFIRMED': { class: 'success', text: 'Đã xác nhận' },
            'CANCELLED': { class: 'danger', text: 'Đã hủy' },
            'EXPIRED': { class: 'secondary', text: 'Hết hạn' },
            'COMPLETED': { class: 'info', text: 'Hoàn thành' },
        };
        const status = statusMap[value] || { class: 'secondary', text: value };
        return `<span class="badge bg-${status.class}">${status.text}</span>`;
    }

    function actionsFormatter(value, row) {
        return `
            <a href="/staff/bookings/${row.id}" class="btn btn-sm btn-primary" title="Xem chi tiết">
                <i class="fas fa-eye"></i>
            </a>
        `;
    }
</script>
@endsection