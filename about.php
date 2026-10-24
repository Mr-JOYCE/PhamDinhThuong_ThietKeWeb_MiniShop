<?php
require __DIR__ . '/includes/init.php';
$pageTitle = 'Giới thiệu';
require __DIR__ . '/includes/header.php';
?>
<section>
    <h2>Giới thiệu về MiniShop</h2>
    <img src="<?= e(url('images/sp01.jpg')) ?>" alt="Chuột không dây M1" width="300">

    <h2>Chúng tôi là ai?</h2>
    <p>
        MiniShop là cửa hàng trực tuyến chuyên phụ kiện công nghệ dành cho sinh viên.
        Chúng tôi chọn sản phẩm theo tiêu chí <em>bền, dễ dùng và đúng giá</em>.
    </p>
    <p>
        Địa chỉ: 123 Đường số 1, TP. Biên Hòa, Đồng Nai<br>
        Giờ mở cửa: 8:00 - 21:00, tất cả các ngày trong tuần.
    </p>

    <h2>Vì sao chọn MiniShop</h2>
    <ul>
        <li>Sản phẩm chính hãng, bảo hành 12 tháng</li>
        <li>Giao hàng toàn quốc</li>
        <li>Đổi trả trong 7 ngày</li>
    </ul>

    <h2>Quy trình mua hàng</h2>
    <ol>
        <li>Chọn sản phẩm và thêm vào giỏ hàng</li>
        <li>Đăng nhập và xác nhận đơn hàng</li>
        <li>Nhận hàng và thanh toán</li>
    </ol>

    <hr>
    <h2>Liên hệ</h2>
    <p>
        Email: <a href="mailto:hotro@minishop.example">hotro@minishop.example</a><br>
        Điện thoại: <a href="tel:0900000000">0900 000 000</a>
    </p>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
