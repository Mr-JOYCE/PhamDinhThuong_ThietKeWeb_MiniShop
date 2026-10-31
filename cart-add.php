<?php
require __DIR__ . '/includes/init.php';

// Trang này chỉ xử lý, không hiển thị: chỉ nhận POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('index.php');
}

$productIdInput = $_POST['product_id'] ?? null;
$quantityInput = $_POST['quantity'] ?? '1';
$productId = is_scalar($productIdInput) ? filter_var($productIdInput, FILTER_VALIDATE_INT) : false;
$quantity = is_scalar($quantityInput) ? filter_var($quantityInput, FILTER_VALIDATE_INT) : false;
if ($productId === false || $productId < 1 || $quantity === false || $quantity < 1) {
    set_flash('error', 'Thông tin sản phẩm hoặc số lượng không hợp lệ.');
    redirect('index.php');
}

// Sản phẩm phải tồn tại và còn hàng
$stmt = $pdo->prepare('SELECT id, name, stock FROM products WHERE id = ?');
$stmt->execute([$productId]);
$product = $stmt->fetch();

if (!$product || $product['stock'] < 1) {
    set_flash('error', 'Sản phẩm không tồn tại hoặc đã hết hàng.');
    redirect('index.php');
}

// Cộng dồn số lượng, không vượt quá tồn kho
$stock = (int) $product['stock'];
$current = min(max(0, (int) ($_SESSION['cart'][$productId] ?? 0)), $stock);
$addedQuantity = min($quantity, $stock);
$finalQuantity = min($current + $addedQuantity, $stock);
$_SESSION['cart'][$productId] = $finalQuantity;

if ($finalQuantity - $current < $quantity) {
    set_flash('success', 'Giỏ hàng đã được giới hạn theo số lượng còn hàng của "' . $product['name'] . '".');
} else {
    set_flash('success', 'Đã thêm "' . $product['name'] . '" vào giỏ hàng.');
}
redirect('cart.php');
