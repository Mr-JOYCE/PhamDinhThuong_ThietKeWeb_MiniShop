<?php
require __DIR__ . '/includes/init.php';

http_response_code(404);
$pageTitle = 'Không tìm thấy trang';
require __DIR__ . '/includes/header.php';
?>
<section class="empty-state not-found">
    <span class="not-found-code">404</span>
    <h2>Không tìm thấy trang</h2>
    <p>Địa chỉ bạn vừa mở không tồn tại hoặc đã được thay đổi.</p>
    <a href="<?= e(url('index.php')) ?>" class="btn btn-accent">Về trang chủ</a>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
