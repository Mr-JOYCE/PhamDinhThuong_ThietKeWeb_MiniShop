<?php
require __DIR__ . '/includes/init.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('cart.php');
}

if (isset($_POST['clear'])) {
    // Nút "Xóa giỏ hàng"
    unset($_SESSION['cart']);
} elseif (isset($_POST['remove'])) {
    // Nút "Xóa" của một dòng: value của nút là mã sản phẩm
    $removeInput = $_POST['remove'];
    $productId = is_scalar($removeInput) ? filter_var($removeInput, FILTER_VALIDATE_INT) : false;
    if ($productId !== false && $productId > 0) {
        unset($_SESSION['cart'][$productId]);
    }
} else {
    // Nút "Cập nhật": qty là mảng [mã sản phẩm => số lượng]
    $quantities = $_POST['qty'] ?? [];
    if (is_array($quantities)) {
        $stockQuery = $pdo->prepare('SELECT stock FROM products WHERE id = ?');
        foreach ($quantities as $productId => $quantity) {
            $validatedId = filter_var($productId, FILTER_VALIDATE_INT);
            $validatedQuantity = is_scalar($quantity)
                ? filter_var($quantity, FILTER_VALIDATE_INT)
                : false;
            if ($validatedId === false || $validatedId < 1 || $validatedQuantity === false
                || !isset($_SESSION['cart'][$validatedId])) {
                continue;
            }
            if ($validatedQuantity < 1) {
                unset($_SESSION['cart'][$validatedId]);
            } else {
                $stockQuery->execute([$validatedId]);
                $stock = $stockQuery->fetchColumn();
                if ($stock === false || (int) $stock < 1) {
                    unset($_SESSION['cart'][$validatedId]);
                } else {
                    $_SESSION['cart'][$validatedId] = min($validatedQuantity, (int) $stock, 99);
                }
            }
        }
    }
}

set_flash('success', 'Đã cập nhật giỏ hàng.');
redirect('cart.php');
