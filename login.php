<?php
require __DIR__ . '/includes/init.php';
$pageTitle = 'Đăng nhập';
require __DIR__ . '/includes/header.php';
?>
<section>
    <h2>Đăng nhập</h2>
    <form class="form" method="post">
        <p>
            <label for="email">Email</label><br>
            <input type="email" id="email" name="email" required autocomplete="email">
        </p>
        <p>
            <label for="password">Mật khẩu</label><br>
            <input type="password" id="password" name="password" required autocomplete="current-password">
        </p>
        <p>
            <input type="checkbox" id="remember" name="remember">
            <label for="remember">Ghi nhớ đăng nhập</label>
        </p>
        <p><button type="submit" class="btn">Đăng nhập</button></p>
    </form>
    <p>Chưa có tài khoản? <a href="<?= e(url('register.php')) ?>">Đăng ký</a></p>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
