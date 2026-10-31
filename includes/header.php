<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle ?? 'Trang chủ') ?> - <?= e(APP_NAME) ?></title>
    <link rel="stylesheet" href="<?= e(url('css/style.css')) ?>">
    <script src="<?= e(url('js/main.js')) ?>" defer></script>
</head>
<body>
    <header class="site-header">
        <h1><?= e(APP_NAME) ?></h1>
        <nav class="main-nav">
            <a href="<?= e(url('index.php')) ?>">Trang chủ</a>
            <a href="<?= e(url('about.php')) ?>">Giới thiệu</a>
            <a href="<?= e(url('contact.php')) ?>">Liên hệ</a>
            <a href="<?= e(url('cart.php')) ?>">Giỏ hàng (<span id="cart-count"><?= e(cart_count()) ?></span>)</a>
            <?php if (is_logged_in()): ?>
                <a href="<?= e(url('orders.php')) ?>">Đơn hàng</a>
                <a href="<?= e(url('logout.php')) ?>">Đăng xuất (<?= e(current_user()['fullname']) ?>)</a>
            <?php else: ?>
                <a href="<?= e(url('login.php')) ?>">Đăng nhập</a>
                <a href="<?= e(url('register.php')) ?>">Đăng ký</a>
            <?php endif; ?>
        </nav>
    </header>

    <main>
        <?php $flash = get_flash(); ?>
        <?php if ($flash): ?>
            <p class="alert alert-<?= e($flash['type']) ?>"><?= e($flash['message']) ?></p>
        <?php endif; ?>
