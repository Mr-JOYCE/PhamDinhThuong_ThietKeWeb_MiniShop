<?php
require __DIR__ . '/includes/init.php';
$pageTitle = 'Đăng ký';
require __DIR__ . '/includes/header.php';
?>
<section>
    <h2>Đăng ký tài khoản</h2>
    <form method="post" class="form" id="register-form">
        <p>
            <label for="fullname">Họ và tên</label><br>
            <input type="text" id="fullname" name="fullname" required minlength="3" maxlength="100"
                   autocomplete="name" placeholder="Nguyễn Văn An">
        </p>
        <p>
            <label for="email">Email</label><br>
            <input type="email" id="email" name="email" required autocomplete="email"
                   placeholder="example@email.com">
        </p>
        <p>
            <label for="password">Mật khẩu</label><br>
            <input type="password" id="password" name="password" required minlength="6"
                   autocomplete="new-password">
        </p>
        <p>
            <label for="confirm-password">Nhập lại mật khẩu</label><br>
            <input type="password" id="confirm-password" name="confirm-password" required minlength="6"
                   autocomplete="new-password">
            <span class="error" id="password-error" aria-live="polite"></span>
        </p>
        <p>
            <label for="phone">Số điện thoại</label><br>
            <input type="tel" id="phone" name="phone" required pattern="0[0-9]{9}"
                   autocomplete="tel" placeholder="0900000000"
                   title="Số điện thoại phải bắt đầu bằng số 0 và có 10 chữ số">
        </p>
        <p>
            <label for="address">Địa chỉ</label><br>
            <textarea id="address" name="address" rows="3" cols="40" autocomplete="street-address"></textarea>
        </p>
        <p>
            <input type="checkbox" id="agree" name="agree" value="1" required>
            <label for="agree">Tôi đồng ý với các điều khoản</label>
        </p>
        <p><button type="submit" class="btn">Đăng ký</button></p>
    </form>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
