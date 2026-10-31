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
    <?php
    $currentPage = basename($_SERVER['SCRIPT_NAME'] ?? '');
    $requestPath = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '');
    $isAdminArea = strpos($requestPath, '/admin/') !== false;
    ?>
    <header class="site-header">
        <div class="header-inner">
            <a class="brand" href="<?= e(url('index.php')) ?>" aria-label="MiniShop - Trang chủ">
                <span class="brand-mark" aria-hidden="true">M</span>
                <span><?= e(APP_NAME) ?></span>
            </a>
            <nav class="main-nav" aria-label="Điều hướng chính">
            <a href="<?= e(url('index.php')) ?>" class="<?= $currentPage === 'index.php' && !$isAdminArea ? 'active' : '' ?>">Trang chủ</a>
            <a href="<?= e(url('about.php')) ?>" class="<?= $currentPage === 'about.php' ? 'active' : '' ?>">Giới thiệu</a>
            <a href="<?= e(url('contact.php')) ?>" class="<?= $currentPage === 'contact.php' ? 'active' : '' ?>">Liên hệ</a>
            <a href="<?= e(url('cart.php')) ?>" class="nav-cart <?= $currentPage === 'cart.php' ? 'active' : '' ?>">
                Giỏ hàng <span id="cart-count" class="cart-count"><?= e(cart_count()) ?></span>
            </a>
            <?php if (is_logged_in()): ?>
                <?php if (is_admin()): ?>
                    <a href="<?= e(url('admin/products.php')) ?>" class="<?= $isAdminArea ? 'active' : '' ?>">Quản trị</a>
                <?php endif; ?>
                <a href="<?= e(url('orders.php')) ?>" class="<?= $currentPage === 'orders.php' ? 'active' : '' ?>">Đơn hàng</a>
                <form action="<?= e(url('logout.php')) ?>" method="post" class="nav-form">
                    <?= csrf_field() ?>
                    <button type="submit" class="nav-logout">Đăng xuất</button>
                </form>
            <?php else: ?>
                <a href="<?= e(url('login.php')) ?>" class="<?= $currentPage === 'login.php' ? 'active' : '' ?>">Đăng nhập</a>
                <a href="<?= e(url('register.php')) ?>" class="<?= $currentPage === 'register.php' ? 'active' : '' ?>">Đăng ký</a>
            <?php endif; ?>
            </nav>
        </div>
    </header>

    <main>
        <?php $flash = get_flash(); ?>
        <?php if ($flash): ?>
            <p class="alert alert-<?= e($flash['type']) ?>"><?= e($flash['message']) ?></p>
        <?php endif; ?>
