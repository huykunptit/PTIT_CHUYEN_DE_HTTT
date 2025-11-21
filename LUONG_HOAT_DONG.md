# 📋 LUỒNG HOẠT ĐỘNG HỆ THỐNG ĐẶT VÉ XEM PHIM

## 🎬 LUỒNG ĐẶT VÉ VÀ CHECK-IN

### **BƯỚC 1: Duyệt phim (Browse Movies)**

#### 1.1. Vào trang chủ
- **URL:** `http://localhost:8089/` hoặc `http://localhost:8089/home`
- **Trang:** Trang chủ hiển thị phim nổi bật
- **Click vào:** 
  - Nút "Phim đang chiếu" trên menu
  - Hoặc banner/phim nổi bật
  - Hoặc link "Đặt vé ngay"

#### 1.2. Xem danh sách phim
- **URL:** `http://localhost:8089/movies`
- **Trang:** Danh sách tất cả phim
- **Lọc phim:**
  - Theo thể loại (genre)
  - Theo trạng thái (đang chiếu, sắp chiếu)
- **Click vào:** Hình ảnh hoặc tên phim để xem chi tiết

#### 1.3. Xem chi tiết phim
- **URL:** `http://localhost:8089/movies/{id}`
- **Trang:** Chi tiết phim + Lịch chiếu
- **Xem được:**
  - Thông tin phim (mô tả, diễn viên, đạo diễn)
  - Lịch chiếu theo ngày và rạp

---

### **BƯỚC 2: Chọn rạp/ngày (Select Cinema/Date)**

#### 2.1. Trong trang chi tiết phim
- **Form lọc ở phía trên danh sách lịch chiếu:**
  - **Dropdown "Chọn rạp":** Chọn rạp chiếu phim muốn xem
  - **Date picker "Chọn ngày":** Chọn ngày muốn xem
  - **Button "Tìm kiếm":** Click để lọc lịch chiếu

#### 2.2. Sau khi lọc
- Hiển thị danh sách lịch chiếu theo:
  - **Theo ngày:** Nhóm theo từng ngày
  - **Theo rạp:** Nhóm theo từng rạp trong ngày
  - Mỗi suất chiếu hiển thị: Tên rạp, phòng chiếu, giờ chiếu

---

### **BƯỚC 3: Chọn suất (Select Showtime)**

#### 3.1. Trong danh sách lịch chiếu
- **Xem thông tin suất chiếu:**
  - Tên rạp (VD: CGV Landmark)
  - Tên phòng (VD: Phòng 1, Phòng VIP)
  - Giờ chiếu (VD: 14:00 - 16:30)
  - Trạng thái (Đang mở bán / Đã hủy)

#### 3.2. Click vào nút "Chọn suất"
- **URL:** `http://localhost:8089/booking/{showtime_id}`
- **Điều kiện:** Suất chiếu phải có trạng thái "ACTIVE"

---

### **BƯỚC 4: Chọn ghế (Select Seat)**

#### 4.1. Trang chọn ghế
- **URL:** `http://localhost:8089/booking/{showtime_id}`
- **Hiển thị:**
  - Thông tin phim và suất chiếu ở trên
  - Sơ đồ ghế ngồi (layout phòng chiếu)
  - Tóm tắt đặt vé ở bên phải

#### 4.2. Chọn ghế
- **Click vào ghế:** Chọn/bỏ chọn ghế
- **Màu sắc ghế:**
  - 🔵 Xanh dương: Ghế đã chọn
  - ⚪ Trắng/Xám: Ghế thường (available)
  - 🟡 Vàng: Ghế VIP
  - 🩷 Hồng: Ghế đôi
  - 🔴 Đỏ: Ghế đã bán (không chọn được)

#### 4.3. Nhập thông tin
- **Form bên dưới sơ đồ ghế:**
  - Họ và tên *
  - Số điện thoại *
  - Email *

#### 4.4. Xem tóm tắt
- **Bên phải màn hình:**
  - Danh sách ghế đã chọn
  - Giá từng ghế
  - Tổng tiền

#### 4.5. Click "Đặt vé"
- **Điều kiện:** 
  - Phải chọn ít nhất 1 ghế
  - Điền đầy đủ thông tin
- **Kết quả:** 
  - Tạo booking với trạng thái PENDING
  - Giữ chỗ trong 15 phút
  - Chuyển sang trang thanh toán

---

### **BƯỚC 5: Thanh toán (Payment)**

#### 5.1. Trang thanh toán
- **URL:** `http://localhost:8089/payment/{booking_id}`
- **Hiển thị:**
  - Thông tin booking
  - Danh sách ghế đã chọn
  - Tổng tiền cần thanh toán
  - Thời gian còn lại để thanh toán (15 phút)

#### 5.2. Chọn phương thức thanh toán
- **Các phương thức:**
  - VNPay QR Code
  - VNPay ATM
  - VNPay Thẻ quốc tế

#### 5.3. Click "Thanh toán"
- **Kết quả:** 
  - Chuyển đến VNPay gateway
  - Thực hiện thanh toán
  - Quay lại sau khi thanh toán thành công

#### 5.4. Sau khi thanh toán thành công
- **URL:** `http://localhost:8089/tickets/booking/{booking_id}`
- **Trang hiển thị:** 
  - Danh sách vé đã mua
  - Mã vé cho từng ghế
  - Thông tin phim, suất chiếu
  - **Lưu ý:** Lưu mã vé để check-in

---

### **BƯỚC 6: Phát vé (Issue Ticket)**

#### 6.1. Tự động sau thanh toán
- Khi thanh toán thành công:
  - Booking chuyển sang trạng thái CONFIRMED
  - Tất cả vé chuyển sang trạng thái SOLD
  - Mã vé được tạo sẵn (VD: TK12345678)

#### 6.2. Xem vé
- **Trang danh sách vé:** `http://localhost:8089/tickets/booking/{booking_id}`
  - Xem tất cả vé trong booking
  - Mã vé, ghế, giá
- **Trang chi tiết vé:** `http://localhost:8089/tickets/{ticket_id}`
  - Xem thông tin chi tiết 1 vé
  - In vé nếu cần

---

### **BƯỚC 7: Check-in**

#### 7.1. Vào trang check-in
- **URL:** `http://localhost:8089/check-in`
- **Menu:** Có thể thêm link "Check-in" trên header/navbar

#### 7.2. Nhập mã vé
- **Form check-in:**
  - Nhập mã vé (10 ký tự, VD: TK12345678)
  - Mã vé không phân biệt hoa thường
  - Click "Check-in"

#### 7.3. Validation
- **Kiểm tra:**
  - Mã vé có tồn tại
  - Vé đã được thanh toán (CONFIRMED)
  - Vé chưa được check-in
  - Thời gian check-in hợp lệ (30 phút trước - 3 giờ sau giờ chiếu)

#### 7.4. Kết quả
- **Thành công:**
  - Trang success hiển thị thông tin vé đã check-in
  - Vé chuyển sang trạng thái USED
  - Lưu thời gian check-in
- **Thất bại:**
  - Hiển thị lỗi (vé không tồn tại, đã sử dụng, sai thời gian, etc.)

---

## 🔄 LUỒNG TỔNG QUAN

```
1. Trang chủ (/)
   ↓
2. Danh sách phim (/movies)
   ↓
3. Chi tiết phim (/movies/{id})
   ├─ Chọn rạp (dropdown)
   ├─ Chọn ngày (date picker)
   └─ Click "Tìm kiếm" → Lọc lịch chiếu
   ↓
4. Chọn suất → Click "Chọn suất"
   ↓
5. Trang đặt vé (/booking/{showtime_id})
   ├─ Chọn ghế (click vào ghế)
   ├─ Nhập thông tin (tên, SĐT, email)
   └─ Click "Đặt vé"
   ↓
6. Trang thanh toán (/payment/{booking_id})
   ├─ Chọn phương thức thanh toán
   └─ Click "Thanh toán" → VNPay
   ↓
7. VNPay Gateway
   ├─ Thanh toán thành công
   └─ Quay về hệ thống
   ↓
8. Trang vé (/tickets/booking/{booking_id})
   ├─ Xem danh sách vé
   ├─ Lưu mã vé
   └─ Có thể check-in ngay
   ↓
9. Check-in (/check-in)
   ├─ Nhập mã vé
   └─ Click "Check-in"
   ↓
10. Check-in thành công
    └─ Vé chuyển sang USED
```

---

## 👤 LUỒNG CHO ADMIN (Quản lý)

### Quản lý phim
- **URL:** `/admin/movies`
- CRUD phim

### Quản lý rạp
- **URL:** `/admin/cinemas`
- CRUD rạp chiếu

### Quản lý lịch chiếu
- **URL:** `/admin/showtimes`
- CRUD lịch chiếu

### Quản lý đặt vé
- **URL:** `/admin/bookings`
- Xem danh sách booking, vé

---

## 📱 ĐIỂM QUAN TRỌNG

1. **Giữ chỗ:** Booking có thời gian hết hạn 15 phút
2. **Check-in:** Chỉ có thể check-in 30 phút trước - 3 giờ sau giờ chiếu
3. **Mã vé:** 10 ký tự, bắt đầu bằng "TK"
4. **Mã booking:** 10 ký tự, bắt đầu bằng "BK"
5. **Trạng thái vé:**
   - BOOKED: Đã đặt (chưa thanh toán)
   - SOLD: Đã bán (đã thanh toán)
   - USED: Đã sử dụng (đã check-in)
   - CANCELLED: Đã hủy

---

## 🎯 TÍNH NĂNG BỔ SUNG CẦN LÀM

1. Thêm link "Check-in" vào navbar
2. Thêm link "Vé của tôi" cho user đã đăng nhập
3. Thêm tính năng tìm kiếm phim
4. Thêm filter theo thể loại trong danh sách phim
5. Email/SMS gửi vé sau khi thanh toán
6. QR Code cho vé

