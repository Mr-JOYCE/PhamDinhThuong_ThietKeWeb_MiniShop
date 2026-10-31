<?php
require __DIR__ . '/../includes/init.php';
require_admin();

$statuses = order_statuses();

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    $orderIdInput = $_POST['order_id'] ?? null;
    $orderId = is_scalar($orderIdInput) ? filter_var($orderIdInput, FILTER_VALIDATE_INT) : false;
    $statusInput = $_POST['status'] ?? null;
    $newStatus = is_string($statusInput) ? $statusInput : '';

    if ($orderId === false || $orderId < 1 || !isset($statuses[$newStatus])) {
        set_flash('error', 'Mã đơn hàng hoặc trạng thái không hợp lệ.');
    } else {
        $exists = $pdo->prepare('SELECT id FROM orders WHERE id = ?');
        $exists->execute([$orderId]);
        if (!$exists->fetchColumn()) {
            set_flash('error', 'Không tìm thấy đơn hàng.');
        } else {
            $update = $pdo->prepare('UPDATE orders SET status = ? WHERE id = ?');
            $update->execute([$newStatus, $orderId]);
            set_flash('success', 'Đã cập nhật trạng thái đơn hàng #' . $orderId . '.');
        }
    }
    redirect('admin/orders.php');
}

$filterInput = $_GET['status'] ?? '';
$filter = is_string($filterInput) && isset($statuses[$filterInput]) ? $filterInput : '';
if ($filter !== '') {
    $stmt = $pdo->prepare('SELECT * FROM orders WHERE status = ? ORDER BY id DESC');
    $stmt->execute([$filter]);
} else {
    $stmt = $pdo->query('SELECT * FROM orders ORDER BY id DESC');
}
$orders = $stmt->fetchAll();

$pageTitle = 'Quản lý đơn hàng';
require __DIR__ . '/../includes/header.php';
?>
<section class="admin-page">
    <div class="page-heading">
        <div>
            <h2>Quản lý đơn hàng</h2>
            <p class="muted">Theo dõi đơn và cập nhật tiến độ giao hàng.</p>
        </div>
        <a href="<?= e(url('admin/products.php')) ?>" class="btn btn-quiet">Quản lý sản phẩm</a>
    </div>

    <nav class="category-bar status-filters" aria-label="Lọc đơn hàng">
        <a href="<?= e(url('admin/orders.php')) ?>" class="<?= $filter === '' ? 'active' : '' ?>">Tất cả</a>
        <?php foreach ($statuses as $value => $label): ?>
            <a href="<?= e(url('admin/orders.php?status=' . rawurlencode($value))) ?>"
               class="<?= $filter === $value ? 'active' : '' ?>"><?= e($label) ?></a>
        <?php endforeach; ?>
    </nav>

    <?php if (!$orders): ?>
        <div class="empty-state">
            <h3>Chưa có đơn hàng</h3>
            <p>Đơn hàng phù hợp sẽ xuất hiện tại đây.</p>
        </div>
    <?php else: ?>
        <div class="table-wrap">
            <table class="admin-table orders-table">
                <thead>
                    <tr>
                        <th>Mã đơn</th>
                        <th>Ngày đặt</th>
                        <th>Người nhận</th>
                        <th>Tổng tiền</th>
                        <th>Trạng thái</th>
                        <th>Chi tiết</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                        <tr>
                            <td>#<?= (int) $order['id'] ?></td>
                            <td><?= e(date('d/m/Y H:i', strtotime($order['created_at']))) ?></td>
                            <td><?= e($order['fullname']) ?><span class="table-subtext"><?= e($order['phone']) ?></span></td>
                            <td><?= format_price($order['total']) ?></td>
                            <td>
                                <form action="<?= e(url('admin/orders.php')) ?>" method="post" class="status-form">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="order_id" value="<?= (int) $order['id'] ?>">
                                    <select name="status" aria-label="Trạng thái đơn hàng #<?= (int) $order['id'] ?>">
                                        <?php foreach ($statuses as $value => $label): ?>
                                            <option value="<?= e($value) ?>"
                                                <?= $order['status'] === $value ? 'selected' : '' ?>>
                                                <?= e($label) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <button type="submit" class="btn btn-small">Lưu</button>
                                </form>
                            </td>
                            <td>
                                <a href="<?= e(url('order-detail.php?id=' . (int) $order['id'])) ?>">Xem đơn</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</section>
<?php require __DIR__ . '/../includes/footer.php'; ?>
