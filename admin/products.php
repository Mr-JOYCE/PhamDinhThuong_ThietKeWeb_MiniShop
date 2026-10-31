<?php
require __DIR__ . '/../includes/init.php';
require_admin();

$perPage = 10;
$totalProducts = (int) $pdo->query('SELECT COUNT(*) FROM products')->fetchColumn();
$totalPages = max(1, (int) ceil($totalProducts / $perPage));
$pageInput = $_GET['page'] ?? '1';
$pageValue = is_scalar($pageInput) ? filter_var($pageInput, FILTER_VALIDATE_INT) : false;
$page = min(max($pageValue !== false ? $pageValue : 1, 1), $totalPages);

$stmt = $pdo->prepare(
    'SELECT p.id, p.name, p.price, p.stock, p.image, c.name AS category_name
     FROM products p
     JOIN categories c ON c.id = p.category_id
     ORDER BY p.id DESC
     LIMIT ? OFFSET ?'
);
$stmt->bindValue(1, $perPage, PDO::PARAM_INT);
$stmt->bindValue(2, ($page - 1) * $perPage, PDO::PARAM_INT);
$stmt->execute();
$products = $stmt->fetchAll();

$pageTitle = 'Quản lý sản phẩm';
require __DIR__ . '/../includes/header.php';
?>
<section class="admin-page">
    <div class="page-heading">
        <div>
            <h2>Quản lý sản phẩm</h2>
            <p class="muted">Thêm, cập nhật thông tin và kiểm soát tồn kho.</p>
        </div>
        <div class="admin-actions">
            <a href="<?= e(url('admin/orders.php')) ?>" class="btn">Quản lý đơn hàng</a>
            <a href="<?= e(url('admin/product-form.php')) ?>" class="btn btn-accent">Thêm sản phẩm</a>
        </div>
    </div>

    <?php if (!$products): ?>
        <div class="empty-state">
            <h3>Chưa có sản phẩm</h3>
            <p>Thêm sản phẩm đầu tiên để bắt đầu quản lý cửa hàng.</p>
            <a href="<?= e(url('admin/product-form.php')) ?>" class="btn btn-accent">Thêm sản phẩm</a>
        </div>
    <?php else: ?>
        <div class="table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Mã</th>
                        <th>Ảnh</th>
                        <th>Sản phẩm</th>
                        <th>Danh mục</th>
                        <th>Giá</th>
                        <th>Tồn kho</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $product): ?>
                        <tr>
                            <td>#<?= (int) $product['id'] ?></td>
                            <td><img src="<?= e(url($product['image'])) ?>" alt="" class="thumb"></td>
                            <td class="product-name-cell"><?= e($product['name']) ?></td>
                            <td><?= e($product['category_name']) ?></td>
                            <td><?= format_price($product['price']) ?></td>
                            <td><?= (int) $product['stock'] ?></td>
                            <td>
                                <div class="row-actions">
                                    <a href="<?= e(url('admin/product-form.php?id=' . (int) $product['id'])) ?>"
                                       class="btn btn-small">Sửa</a>
                                    <form action="<?= e(url('admin/product-delete.php')) ?>" method="post"
                                          class="inline-form" data-confirm="Xóa sản phẩm này?">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="id" value="<?= (int) $product['id'] ?>">
                                        <button type="submit" class="btn btn-danger btn-small">Xóa</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <?php if ($totalPages > 1): ?>
            <nav class="pagination" aria-label="Phân trang sản phẩm">
                <?php for ($pageNumber = 1; $pageNumber <= $totalPages; $pageNumber++): ?>
                    <a href="<?= e(url('admin/products.php?page=' . $pageNumber)) ?>"
                       class="<?= $pageNumber === $page ? 'active' : '' ?>"
                       <?= $pageNumber === $page ? 'aria-current="page"' : '' ?>>
                        <?= $pageNumber ?>
                    </a>
                <?php endfor; ?>
            </nav>
        <?php endif; ?>
    <?php endif; ?>
</section>
<?php require __DIR__ . '/../includes/footer.php'; ?>
