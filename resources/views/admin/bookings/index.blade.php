@extends('layouts.admin')

@section('title', 'Quản lý đặt vé - Admin Panel')
@section('page-title', 'Quản lý đặt vé')

@section('content')
<div class="card">
    <div class="card-body">
        <div id="booking-status-alert" class="alert alert-dismissible fade show py-2 px-3 d-none mb-3" role="alert">
            <span class="alert-message"></span>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
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
                   data-sort-order="desc"
                   data-toolbar="#toolbar">
                <thead>
                    <tr>
                        <th data-field="booking_code" data-sortable="true" data-formatter="bookingCodeFormatter">Mã đặt vé</th>
                        <th data-field="customer_name" data-sortable="true" data-formatter="customerFormatter">Khách hàng</th>
                        <th data-field="movie_title" data-sortable="true" data-formatter="movieFormatter">Phim</th>
                        <th data-field="cinema_name" data-sortable="true" data-formatter="cinemaFormatter">Rạp</th>
                        <th data-field="showtime_date" data-sortable="true" data-formatter="showtimeFormatter">Ngày chiếu</th>
                        <th data-field="final_amount" data-sortable="true" data-formatter="amountFormatter" data-sorter="amountSorter">Tổng tiền</th>
                        <th data-field="status" data-sortable="true" data-formatter="statusFormatter">Trạng thái</th>
                        <th data-field="actions" data-formatter="actionsFormatter" data-events="actionEvents">Thao tác</th>
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
    $bookingsData = $bookings->map(function ($booking) {
        return [
            'booking_code' => $booking->booking_code,
            'created_at' => $booking->created_at->format('Y-m-d H:i:s'),
            'created_at_display' => $booking->created_at->format('d/m/Y H:i'),
            'customer_name' => $booking->user ? $booking->user->name : 'Khách vãng lai',
            'customer_email' => $booking->user ? $booking->user->email : '',
            'movie_title' => $booking->showtime->movie->title,
            'movie_genre' => $booking->showtime->movie->genre,
            'cinema_name' => $booking->showtime->room->cinema->name,
            'room_name' => $booking->showtime->room->name,
            'showtime_date' => $booking->showtime->date->format('d/m/Y'),
            'showtime_time' => \Carbon\Carbon::parse($booking->showtime->start_time)->format('H:i') . ' - ' . \Carbon\Carbon::parse($booking->showtime->end_time)->format('H:i'),
            'final_amount' => (float) $booking->final_amount,
            'discount_amount' => (float) $booking->discount_amount,
            'status' => $booking->status,
            'payment_status' => $booking->payment_status,
            'update_url' => route('admin.bookings.update-status', $booking->id),
            'show_url' => route('admin.bookings.show', $booking->id),
            'edit_url' => route('admin.bookings.edit', $booking->id),
            'destroy_url' => route('admin.bookings.destroy', $booking->id),
            'ticket_url' => route('tickets.show', $booking->id),
        ];
    });
@endphp
<script>
    const csrfToken = '{{ csrf_token() }}';
    const bookingsData = @json($bookingsData);

    function amountSorter(a, b) {
        return parseFloat(a) - parseFloat(b);
    }

    function bookingCodeFormatter(value, row) {
        return `<strong>${value}</strong><br><small class="text-muted">${row.created_at_display}</small>`;
    }

    function customerFormatter(value, row) {
        if (row.customer_email) {
            return `<strong>${value}</strong><br><small class="text-muted">${row.customer_email}</small>`;
        }
        return `<span class="text-muted">${value}</span>`;
    }

    function movieFormatter(value, row) {
        return `<strong>${value}</strong><br><small class="text-muted">${row.movie_genre}</small>`;
    }

    function cinemaFormatter(value, row) {
        return `${value}<br><small class="text-muted">${row.room_name}</small>`;
    }

    function showtimeFormatter(value, row) {
        return `${value}<br><small class="text-muted">${row.showtime_time}</small>`;
    }

    function amountFormatter(value, row) {
        let html = `<strong>${new Intl.NumberFormat('vi-VN').format(value)}₫</strong>`;
        if (row.discount_amount > 0) {
            html += `<br><small class="text-success">-${new Intl.NumberFormat('vi-VN').format(row.discount_amount)}₫</small>`;
        }
        return html;
    }

    function statusFormatter(value, row) {
        return `<select class="form-select form-select-sm booking-status" data-update-url="${row.update_url}" data-booking-code="${row.booking_code}">
            <option value="PENDING" ${value === 'PENDING' ? 'selected' : ''}>Chờ thanh toán</option>
            <option value="CONFIRMED" ${value === 'CONFIRMED' ? 'selected' : ''}>Đã xác nhận</option>
            <option value="CANCELLED" ${value === 'CANCELLED' ? 'selected' : ''}>Đã hủy</option>
            <option value="EXPIRED" ${value === 'EXPIRED' ? 'selected' : ''}>Hết hạn</option>
        </select>`;
    }

    function actionsFormatter(value, row, index) {
        let html = '<div class="btn-group" role="group">';
        html += `<a href="${row.show_url}" class="btn btn-sm btn-outline-info" title="Xem"><i class="fas fa-eye"></i></a>`;
        html += `<a href="${row.edit_url}" class="btn btn-sm btn-outline-warning" title="Sửa"><i class="fas fa-edit"></i></a>`;
        if (row.status === 'CONFIRMED' || row.payment_status === 'PAID' || row.payment_status === 'SUCCESS') {
            html += `<a href="${row.ticket_url}" class="btn btn-sm btn-outline-success" title="In vé" target="_blank"><i class="fas fa-print"></i></a>`;
        }
        html += `<button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteBooking('${row.booking_code}')" title="Xóa"><i class="fas fa-trash"></i></button>`;
        html += '</div>';
        return html;
    }

    window.actionEvents = {
        'click .btn-outline-info': function (e, value, row, index) {
            window.location.href = row.show_url;
        },
        'click .btn-outline-warning': function (e, value, row, index) {
            window.location.href = row.edit_url;
        },
        'click .btn-outline-success': function (e, value, row, index) {
            window.open(row.ticket_url, '_blank');
        }
    };

    function deleteBooking(bookingCode) {
        const row = bookingsData.find(b => b.booking_code === bookingCode);
        if (row && confirm('Bạn có chắc muốn xóa đặt vé này?')) {
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
        // Khởi tạo Bootstrap Table với dữ liệu
        $('#bookings-table').bootstrapTable('load', bookingsData);

        // Xử lý thay đổi trạng thái
        $(document).on('change', '.booking-status', function() {
            const selectEl = $(this);
            const url = selectEl.data('update-url');
            const status = selectEl.val();
            selectEl.prop('disabled', true);

            fetch(url, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ status })
            })
            .then(res => {
                if (!res.ok) {
                    throw new Error('Có lỗi xảy ra, vui lòng thử lại!');
                }
                return res.json();
            })
            .then(data => {
                showStatusAlert('success', data.message || 'Cập nhật thành công!');
            })
            .catch(() => {
                showStatusAlert('danger', 'Không thể cập nhật trạng thái!');
                selectEl.val(selectEl.data('old-value'));
            })
            .finally(() => selectEl.prop('disabled', false));
        });

        // Lưu giá trị cũ khi focus vào select
        $(document).on('focus', '.booking-status', function() {
            $(this).data('old-value', $(this).val());
        });
    });

    function showStatusAlert(type, message) {
        const alertBox = $('#booking-status-alert');
        alertBox.removeClass('d-none alert-success alert-danger').addClass(`alert-${type}`);
        alertBox.find('.alert-message').text(message);
        setTimeout(() => {
            alertBox.addClass('d-none');
        }, 3000);
    }
</script>
@endsection
