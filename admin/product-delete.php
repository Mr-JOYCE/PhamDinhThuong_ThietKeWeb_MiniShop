<?php
require __DIR__ . '/../includes/init.php';
require_admin();

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
    redirect('admin/products.php');
}

$idInput = $_POST['id'] ?? null;
$id = is_scalar($idInput) ? filter_var($idInput, FILTER_VALIDATE_INT) : false;
if ($id === false || $id < 1) {
    set_flash('error', 'Mã sản phẩm không hợp lệ.');
    redirect('admin/products.php');
}

try {
    $stmt = $pdo->prepare('DELETE FROM products WHERE id = ?');
    $stmt->execute([$id]);
    if ($stmt->rowCount() === 0) {
        set_flash('error', 'Sản phẩm không tồn tại.');
    } else {
        set_flash('success', 'Đã xóa sản phẩm.');
    }
} catch (PDOException $exception) {
    if ($exception->getCode() !== '23000') {
        throw $exception;
    }
    set_flash('error', 'Không thể xóa sản phẩm đã có trong đơn hàng. Hãy đặt tồn kho về 0 để ngừng bán.');
}

redirect('admin/products.php');
