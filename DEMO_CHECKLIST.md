# CHECKLIST DEMO HỆ THỐNG ĐẶT VÉ XEM PHIM

## 📋 TỔNG QUAN

Tài liệu này liệt kê các phần cần demo và checklist chuẩn bị trước khi demo.

---

## 🎯 CÁC PHẦN CẦN DEMO

### 1. DEMO ĐĂNG NHẬP VÀ PHÂN QUYỀN ⭐⭐⭐

**Mục đích:** Giới thiệu hệ thống authentication và authorization

**Các bước demo:**
1. ✅ Đăng ký tài khoản mới
   - Nhập thông tin: email, mật khẩu, tên, số điện thoại
   - Xác nhận email
   - Đăng nhập lần đầu

2. ✅ Đăng nhập với các role khác nhau
   - User (khách hàng)
   - Staff (nhân viên)
   - Admin (quản trị viên)

3. ✅ Kiểm tra phân quyền
   - User không thể truy cập admin panel
   - Staff có thể check-in vé
   - Admin có đầy đủ quyền

**Thời gian:** 3-5 phút

**Chuẩn bị:**
- [ ] Tạo sẵn 3 tài khoản: user@test.com, staff@test.com, admin@test.com
- [ ] Mật khẩu: password123
- [ ] Test phân quyền trước

---

### 2. DEMO QUẢN LÝ DANH MỤC HÀNG HÓA (PHIM) ⭐⭐⭐

**Mục đích:** Giới thiệu tính năng quản lý phim cho Admin

**Các bước demo:**
1. ✅ Xem danh sách phim (Frontend)
   - Trang chủ hiển thị phim đang chiếu
   - Phim sắp chiếu
   - Tìm kiếm phim

2. ✅ Quản lý phim (Admin)
   - Thêm phim mới: tên, mô tả, poster, trailer, thể loại
   - Sửa thông tin phim
   - Xóa phim (nếu chưa có suất chiếu)
   - Upload poster

3. ✅ Xem chi tiết phim
   - Thông tin phim
   - Trailer
   - Lịch chiếu

**Thời gian:** 5-7 phút

**Chuẩn bị:**
- [ ] Có sẵn ít nhất 5 phim trong database
- [ ] Có poster và trailer cho mỗi phim
- [ ] Test upload poster

---

### 3. DEMO QUY TRÌNH NHẬP KHO (QUẢN LÝ RẠP/PHÒNG/GHẾ) ⭐⭐

**Mục đích:** Giới thiệu quản lý cơ sở hạ tầng rạp chiếu

**Các bước demo:**
1. ✅ Quản lý rạp chiếu
   - Thêm rạp mới: tên, địa chỉ, số điện thoại
   - Sửa thông tin rạp
   - Kích hoạt/vô hiệu hóa rạp

2. ✅ Quản lý phòng chiếu
   - Thêm phòng mới: tên, sức chứa, loại phòng (Standard/VIP/IMAX)
   - Cấu hình layout ghế (JSON)
   - Sửa thông tin phòng

3. ✅ Quản lý ghế
   - Import sơ đồ ghế tự động
   - Thêm/sửa/xóa ghế thủ công
   - Cấu hình loại ghế (Standard/VIP/Couple)
   - Cấu hình giá vé theo loại ghế

**Thời gian:** 5-7 phút

**Chuẩn bị:**
- [ ] Có sẵn ít nhất 2 rạp
- [ ] Mỗi rạp có 2-3 phòng
- [ ] Mỗi phòng có sơ đồ ghế (ví dụ: 10 hàng x 15 ghế)
- [ ] Test import sơ đồ ghế

---

### 4. DEMO QUY TRÌNH XUẤT KHO (QUẢN LÝ LỊCH CHIẾU) ⭐⭐⭐

**Mục đích:** Giới thiệu quản lý lịch chiếu

**Các bước demo:**
1. ✅ Tạo suất chiếu mới
   - Chọn phim
   - Chọn rạp và phòng
   - Chọn ngày và giờ chiếu
   - Hệ thống tự động tính thời gian kết thúc
   - Kiểm tra xung đột thời gian

2. ✅ Xem lịch chiếu
   - Lịch chiếu theo ngày
   - Lịch chiếu theo phim
   - Lịch chiếu theo rạp

3. ✅ Sửa/Xóa suất chiếu
   - Sửa thời gian (nếu chưa có đơn hàng)
   - Xóa suất chiếu (nếu chưa có đơn hàng)

**Thời gian:** 5-7 phút

**Chuẩn bị:**
- [ ] Có sẵn ít nhất 10 suất chiếu trong tuần
- [ ] Test xung đột thời gian
- [ ] Test không thể xóa suất chiếu đã có đơn hàng

---

### 5. DEMO QUY TRÌNH ĐẶT VÉ ⭐⭐⭐⭐⭐

**Mục đích:** Demo tính năng chính - đặt vé online

**Các bước demo:**
1. ✅ Xem danh sách phim và chọn phim
   - Tìm kiếm phim
   - Xem chi tiết phim
   - Xem lịch chiếu

2. ✅ Chọn suất chiếu
   - Chọn rạp
   - Chọn ngày
   - Chọn suất chiếu

3. ✅ Chọn ghế (REALTIME) ⭐⭐⭐
   - Xem sơ đồ ghế
   - Ghế trống (màu xanh)
   - Ghế đã bán (màu đỏ)
   - Ghế đang được giữ (màu cam)
   - Chọn ghế → Ghế chuyển màu xanh lá
   - **Mở 2 tab khác nhau để demo realtime:**
     - Tab 1: User A chọn ghế A5
     - Tab 2: User B thấy ghế A5 chuyển màu cam
   - Countdown timer 5 phút
   - Nếu hết thời gian, ghế tự động nhả

4. ✅ Checkout
   - Xem tóm tắt đơn hàng
   - Áp dụng mã giảm giá (nếu có)
   - Xem tổng tiền

5. ✅ Thanh toán
   - Chọn phương thức thanh toán
   - Chuyển đến cổng thanh toán
   - Thanh toán thành công

6. ✅ Nhận vé
   - Email vé PDF
   - Xem vé trong tài khoản
   - QR code để check-in

**Thời gian:** 10-15 phút (PHẦN QUAN TRỌNG NHẤT)

**Chuẩn bị:**
- [ ] Có sẵn phim, suất chiếu, ghế
- [ ] Test realtime với 2 browser
- [ ] Test countdown timer
- [ ] Test thanh toán VNPay sandbox
- [ ] Test email vé

---

### 6. DEMO THANH TOÁN VNPAY ⭐⭐⭐⭐

**Mục đích:** Giới thiệu tích hợp thanh toán

**Các bước demo:**
1. ✅ Tạo đơn hàng
2. ✅ Chọn thanh toán VNPay
3. ✅ Redirect đến VNPay sandbox
4. ✅ Chọn phương thức thanh toán (QR/ATM/Card)
5. ✅ Thanh toán thành công
6. ✅ Callback về hệ thống
7. ✅ Xác nhận đơn hàng
8. ✅ Gửi email vé

**Thời gian:** 5-7 phút

**Chuẩn bị:**
- [ ] Cấu hình VNPay sandbox
- [ ] Test callback
- [ ] Test IPN
- [ ] Test thanh toán thất bại

---

### 7. DEMO THANH TOÁN SEPAY ⭐⭐

**Mục đích:** Giới thiệu thanh toán chuyển khoản

**Các bước demo:**
1. ✅ Chọn thanh toán SePay
2. ✅ Hiển thị thông tin chuyển khoản
3. ✅ User chuyển khoản với nội dung đúng
4. ✅ Webhook từ SePay
5. ✅ Tự động cập nhật trạng thái đơn hàng

**Thời gian:** 3-5 phút

**Chuẩn bị:**
- [ ] Cấu hình SePay webhook
- [ ] Test webhook
- [ ] Test chuyển khoản với nội dung sai

---

### 8. DEMO CHECK-IN VÉ ⭐⭐⭐

**Mục đích:** Giới thiệu tính năng check-in cho Staff

**Các bước demo:**
1. ✅ Staff đăng nhập
2. ✅ Vào màn hình check-in
3. ✅ Quét QR code trên vé
4. ✅ Hệ thống kiểm tra:
   - Vé có hợp lệ không
   - Vé đã check-in chưa
   - Suất chiếu đã bắt đầu chưa
5. ✅ Check-in thành công
6. ✅ Hiển thị thông tin vé

**Thời gian:** 3-5 phút

**Chuẩn bị:**
- [ ] Có sẵn vé đã thanh toán
- [ ] Test QR code scanner
- [ ] Test check-in vé đã check-in
- [ ] Test check-in vé không hợp lệ

---

### 9. DEMO HỆ THỐNG BÁO CÁO DOANH THU ⭐⭐⭐

**Mục đích:** Giới thiệu tính năng báo cáo cho Admin

**Các bước demo:**
1. ✅ Dashboard tổng quan
   - Tổng doanh thu hôm nay
   - Số vé bán được
   - Số đơn hàng
   - Biểu đồ doanh thu

2. ✅ Báo cáo doanh thu
   - Theo ngày
   - Theo tháng
   - Theo phim
   - Theo rạp

3. ✅ Báo cáo đơn hàng
   - Danh sách đơn hàng
   - Lọc theo trạng thái
   - Lọc theo ngày
   - Export Excel

**Thời gian:** 5-7 phút

**Chuẩn bị:**
- [ ] Có sẵn dữ liệu đơn hàng (ít nhất 20 đơn)
- [ ] Test export Excel
- [ ] Test các filter

---

### 10. DEMO QUẢN LÝ MÃ GIẢM GIÁ ⭐⭐

**Mục đích:** Giới thiệu tính năng khuyến mãi

**Các bước demo:**
1. ✅ Tạo mã giảm giá mới
   - Mã code
   - Loại giảm giá (% hoặc số tiền)
   - Giá trị giảm
   - Đơn tối thiểu
   - Ngày hiệu lực
   - Số lần sử dụng

2. ✅ Áp dụng mã giảm giá khi đặt vé
   - Nhập mã code
   - Hệ thống kiểm tra hợp lệ
   - Tính toán giảm giá
   - Hiển thị tổng tiền sau giảm

3. ✅ Quản lý mã giảm giá
   - Xem danh sách mã
   - Sửa/Xóa mã
   - Xem lịch sử sử dụng

**Thời gian:** 3-5 phút

**Chuẩn bị:**
- [ ] Tạo sẵn 2-3 mã giảm giá
- [ ] Test mã hết hạn
- [ ] Test mã đã hết lượt sử dụng

---

### 11. DEMO API DOCUMENTATION (SWAGGER) ⭐⭐

**Mục đích:** Giới thiệu tài liệu API

**Các bước demo:**
1. ✅ Truy cập Swagger UI: `/api/documentation`
2. ✅ Xem danh sách API endpoints
3. ✅ Test API trực tiếp trên Swagger
4. ✅ Xem request/response examples
5. ✅ Export Postman collection

**Thời gian:** 2-3 phút

**Chuẩn bị:**
- [ ] Swagger đã được generate
- [ ] Test một vài API trên Swagger
- [ ] Export Postman collection

---

## 📝 CHECKLIST CHUẨN BỊ TRƯỚC DEMO

### Dữ liệu test

- [ ] **Users:**
  - user@test.com / password123 (User)
  - staff@test.com / password123 (Staff)
  - admin@test.com / password123 (Admin)

- [ ] **Phim:**
  - Ít nhất 5 phim đang chiếu
  - Ít nhất 2 phim sắp chiếu
  - Có poster và trailer

- [ ] **Rạp/Phòng:**
  - 2 rạp
  - Mỗi rạp có 2-3 phòng
  - Mỗi phòng có sơ đồ ghế (10 hàng x 15 ghế)

- [ ] **Suất chiếu:**
  - Ít nhất 10 suất chiếu trong tuần
  - Các khung giờ khác nhau

- [ ] **Đơn hàng:**
  - Ít nhất 20 đơn hàng (để demo báo cáo)
  - Một số đơn đã thanh toán
  - Một số đơn chưa thanh toán

- [ ] **Mã giảm giá:**
  - 2-3 mã giảm giá đang hoạt động
  - 1 mã đã hết hạn

### Cấu hình

- [ ] VNPay sandbox đã cấu hình
- [ ] SePay webhook đã cấu hình
- [ ] Email SMTP đã cấu hình
- [ ] Redis đang chạy
- [ ] Queue worker đang chạy
- [ ] WebSocket đang chạy

### Test trước

- [ ] Test đăng nhập với các role
- [ ] Test đặt vé hoàn chỉnh
- [ ] Test realtime seat map (2 browser)
- [ ] Test thanh toán VNPay
- [ ] Test thanh toán SePay
- [ ] Test check-in vé
- [ ] Test email vé
- [ ] Test báo cáo

### Chuẩn bị trình bày

- [ ] Slide presentation (nếu cần)
- [ ] Script demo (thứ tự các bước)
- [ ] Backup database (để restore nếu cần)
- [ ] Test internet connection
- [ ] Test trên trình duyệt khác nhau

---

## ⏱️ THỜI GIAN DEMO TỔNG THỂ

**Tổng thời gian:** 45-60 phút

**Phân bổ:**
- Giới thiệu tổng quan: 5 phút
- Demo đăng nhập và phân quyền: 3-5 phút
- Demo quản lý phim: 5-7 phút
- Demo quản lý rạp/phòng/ghế: 5-7 phút
- Demo quản lý lịch chiếu: 5-7 phút
- **Demo đặt vé (QUAN TRỌNG): 10-15 phút**
- Demo thanh toán VNPay: 5-7 phút
- Demo thanh toán SePay: 3-5 phút
- Demo check-in vé: 3-5 phút
- Demo báo cáo: 5-7 phút
- Demo mã giảm giá: 3-5 phút
- Demo API documentation: 2-3 phút
- Q&A: 5-10 phút

---

## 🎬 KỊCH BẢN DEMO CHI TIẾT

### Phần 1: Giới thiệu (5 phút)

1. Giới thiệu nhóm và đề tài
2. Giới thiệu tổng quan hệ thống
3. Công nghệ sử dụng
4. Kiến trúc hệ thống

### Phần 2: Demo tính năng (40-50 phút)

**Theo thứ tự trong checklist trên**

### Phần 3: Q&A (5-10 phút)

- Trả lời câu hỏi
- Giải thích kỹ thuật
- Hướng phát triển

---

## 📌 LƯU Ý KHI DEMO

1. **Chuẩn bị kỹ:** Test tất cả tính năng trước khi demo
2. **Backup:** Có backup database để restore nếu cần
3. **Internet:** Đảm bảo kết nối internet ổn định
4. **Browser:** Test trên nhiều browser
5. **Realtime:** Demo realtime với 2 browser/tab
6. **Giải thích:** Giải thích rõ ràng từng bước
7. **Xử lý lỗi:** Nếu có lỗi, bình tĩnh xử lý hoặc bỏ qua

---

## ✅ CHECKLIST SAU DEMO

- [ ] Thu thập feedback
- [ ] Ghi lại các câu hỏi
- [ ] Cập nhật tài liệu nếu cần
- [ ] Fix bugs nếu phát hiện
- [ ] Cải thiện dựa trên feedback

---

**Chúc demo thành công! 🎉**

