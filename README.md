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
- Quản trị viên có thể thêm/sửa/xóa sản phẩm, tải ảnh an toàn và cập nhật trạng thái đơn hàng.
- Biểu mẫu POST được bảo vệ bằng CSRF; các thư mục cấu hình, mã dùng chung và CSDL bị chặn truy cập trực tiếp.

## Tạo tài khoản quản trị

1. Đăng ký tài khoản trong MiniShop như người dùng bình thường.
2. Trong phpMyAdmin, chọn database `minishop` và chạy câu lệnh sau, thay email bằng tài khoản vừa đăng ký:

   ```sql
   UPDATE users SET role = 'admin' WHERE email = 'email-cua-ban@example.com';
   ```

3. Đăng xuất rồi đăng nhập lại để nạp quyền mới. Liên kết **Quản trị** sẽ xuất hiện trên thanh điều hướng.

Biểu mẫu liên hệ hiện chỉ kiểm tra dữ liệu phía trình duyệt và CSRF; chức năng lưu/gửi tin nhắn chưa được triển khai.

## Lưu ý XAMPP

- Dự án được thiết kế để đặt trong một thư mục con của `htdocs`, ví dụ `C:\xampp\htdocs\minishop` hoặc `D:\xampp\htdocs\Thietketweb`.
- Nếu đổi tên thư mục dự án, hãy cập nhật đường dẫn `ErrorDocument 404` trong `.htaccess` theo URL mới.
- Cổng MySQL mặc định hiện là `3307` để khớp cấu hình XAMPP cục bộ đang dùng. Đổi `MINISHOP_DB_PORT` hoặc `DB_PORT` trong `config/config.php` nếu MySQL của bạn chạy cổng khác.
- Chỉ import `database/minishop.sql` vào database thử nghiệm: script sẽ xóa và tạo lại các bảng.
