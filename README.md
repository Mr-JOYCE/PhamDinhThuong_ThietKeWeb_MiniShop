# MiniShop

Website bán phụ kiện công nghệ dùng PHP thuần, PDO và MySQL/MariaDB. Các trang PHP cần chạy qua Apache/PHP; không mở trực tiếp bằng trình duyệt hoặc Live Server.

## Chạy bằng XAMPP

1. Sao chép thư mục dự án vào `C:\xampp\htdocs\minishop`.
2. Khởi động **Apache** và **MySQL** trong XAMPP Control Panel.
3. Mở phpMyAdmin, tạo cơ sở dữ liệu `minishop` dùng `utf8mb4`, rồi import [`database/minishop.sql`](./database/minishop.sql).
4. Kiểm tra tài khoản CSDL trong [`config/config.php`](./config/config.php). Mặc định dùng `localhost:3306`, cơ sở dữ liệu `minishop`, tài khoản `root` và mật khẩu rỗng theo cấu hình XAMPP phổ biến.
5. Mở `http://localhost/minishop/`.

**Lưu ý:** Tệp SQL xóa và tạo lại các bảng trước khi thêm dữ liệu mẫu; chỉ import vào cơ sở dữ liệu thử nghiệm chưa có dữ liệu cần giữ.

Nếu thư mục chạy có đường dẫn khác, ứng dụng tự nhận đường dẫn gốc từ request. Có thể đặt `MINISHOP_BASE_URL` để ghi đè; thông tin CSDL cũng có thể cấu hình bằng các biến môi trường `MINISHOP_DB_HOST`, `MINISHOP_DB_PORT`, `MINISHOP_DB_NAME`, `MINISHOP_DB_USER`, `MINISHOP_DB_PASS`. Đặt `MINISHOP_DEBUG=0` để tắt hiển thị lỗi chi tiết.

## Chức năng

- Trang chủ đọc sản phẩm/danh mục từ MySQL, lọc danh mục, tìm kiếm tên và sắp xếp giá.
- Mỗi sản phẩm có trang chi tiết `product.php?id=...`; sản phẩm không tồn tại trả về HTTP 404.
- PDO dùng prepared statements cho giá trị đầu vào; dữ liệu hiển thị được escape bằng `e()`.
- Đăng ký, đăng nhập và đăng xuất dùng PHP Session; mật khẩu được băm bằng `password_hash()`.
- Giỏ hàng được lưu trong Session, giá và tồn kho luôn được đọc lại từ cơ sở dữ liệu.
- Người dùng đã đăng nhập có thể đặt hàng, theo dõi tồn kho theo transaction và xem lịch sử đơn hàng.

Biểu mẫu liên hệ hiện vẫn chỉ là giao diện; lưu và gửi tin nhắn liên hệ chưa thuộc phạm vi Buổi 4.
