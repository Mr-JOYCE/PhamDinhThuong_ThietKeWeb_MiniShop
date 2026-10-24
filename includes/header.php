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
        <nav class="main-nav" aria-label="Điều hướng chính">
            <?php
            $navigation = [
                ['href' => 'index.php', 'page' => 'index.php', 'label' => 'Trang chủ'],
                ['href' => 'index.php#products', 'page' => null, 'label' => 'Sản phẩm'],
                ['href' => 'about.php', 'page' => 'about.php', 'label' => 'Giới thiệu'],
                ['href' => 'contact.php', 'page' => 'contact.php', 'label' => 'Liên hệ'],
                ['href' => 'cart.php', 'page' => 'cart.php', 'label' => 'Giỏ hàng'],
                ['href' => 'login.php', 'page' => 'login.php', 'label' => 'Đăng nhập'],
                ['href' => 'register.php', 'page' => 'register.php', 'label' => 'Đăng ký'],
            ];
            ?>
            <?php foreach ($navigation as $item): ?>
                <a href="<?= e(url($item['href'])) ?>"
                   class="<?= $item['page'] !== null && $currentPage === $item['page'] ? 'active' : '' ?>"
                   <?= $item['page'] !== null && $currentPage === $item['page'] ? 'aria-current="page"' : '' ?>>
                    <?= e($item['label']) ?>
                    <?php if ($item['page'] === 'cart.php'): ?>
                        (<span id="cart-count" class="cart-count">0</span>)
                    <?php endif; ?>
                </a>
            <?php endforeach; ?>
        </nav>
    </header>

    <main>
