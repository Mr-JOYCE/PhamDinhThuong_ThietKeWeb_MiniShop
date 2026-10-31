<?php
require __DIR__ . '/includes/init.php';

$idInput = $_GET['id'] ?? '';
$id = is_scalar($idInput) ? filter_var($idInput, FILTER_VALIDATE_INT) : false;
$id = $id !== false && $id > 0 ? $id : 0;

$statement = $pdo->prepare(
    'SELECT p.id, p.category_id, p.name, p.price, p.description, p.image, p.stock,
            c.name AS category_name
     FROM products p
     JOIN categories c ON c.id = p.category_id
     WHERE p.id = ?'
);
$statement->execute([$id]);
$product = $statement->fetch();

if (!$product) {
    http_response_code(404);
    $pageTitle = 'Không tìm thấy sản phẩm';
    require __DIR__ . '/includes/header.php';
    ?>
    <section>
        <h2>Không tìm thấy sản phẩm</h2>
        <p>Sản phẩm bạn đang tìm không tồn tại hoặc đã được gỡ bỏ.</p>
        <p><a href="<?= e(url('index.php')) ?>">&laquo; Quay lại danh sách sản phẩm</a></p>
    </section>
    <?php
    require __DIR__ . '/includes/footer.php';
    exit;
}

$relatedStatement = $pdo->prepare(
    'SELECT id, name, price, image
     FROM products
     WHERE category_id = ? AND id <> ?
     ORDER BY id
     LIMIT 3'
);
$relatedStatement->execute([(int) $product['category_id'], (int) $product['id']]);
$relatedProducts = $relatedStatement->fetchAll();

$pageTitle = $product['name'];
require __DIR__ . '/includes/header.php';
?>
<article class="product-detail">
    <div class="product-image">
        <img src="<?= e(url($product['image'])) ?>" alt="<?= e($product['name']) ?>">
    </div>
    <div class="product-info">
        <h2><?= e($product['name']) ?></h2>
        <p class="price">Giá: <strong><?= format_price($product['price']) ?></strong></p>
        <p><?= nl2br(e($product['description'] ?? '')) ?></p>

        <h3>Thông tin sản phẩm</h3>
        <table>
            <tbody>
                <tr>
                    <th scope="row">Mã sản phẩm</th>
                    <td><?= (int) $product['id'] ?></td>
                </tr>
                <tr>
                    <th scope="row">Danh mục</th>
                    <td><?= e($product['category_name']) ?></td>
                </tr>
                <tr>
                    <th scope="row">Tình trạng</th>
                    <td><?= (int) $product['stock'] > 0
                        ? 'Còn ' . (int) $product['stock'] . ' sản phẩm'
                        : 'Hết hàng' ?></td>
                </tr>
            </tbody>
        </table>

        <?php if ((int) $product['stock'] > 0): ?>
            <form action="<?= e(url('cart-add.php')) ?>" method="post" class="cart-actions">
                <input type="hidden" name="product_id" value="<?= (int) $product['id'] ?>">
                <input type="number" name="quantity" value="1" min="1" max="<?= (int) $product['stock'] ?>">
                <button type="submit" class="btn btn-accent">Thêm vào giỏ</button>
            </form>
        <?php endif; ?>
        <p><a href="<?= e(url('index.php')) ?>">&laquo; Quay lại danh sách sản phẩm</a></p>
    </div>
</article>

<?php if ($relatedProducts): ?>
    <section>
        <h2>Sản phẩm cùng danh mục</h2>
        <div class="product-list">
            <?php foreach ($relatedProducts as $relatedProduct): ?>
                <article class="product-card">
                    <img src="<?= e(url($relatedProduct['image'])) ?>" alt="<?= e($relatedProduct['name']) ?>">
                    <h3><?= e($relatedProduct['name']) ?></h3>
                    <p class="price"><?= format_price($relatedProduct['price']) ?></p>
                    <a class="btn" href="<?= e(url('product.php?id=' . (int) $relatedProduct['id'])) ?>">
                        Xem chi tiết
                    </a>
                </article>
            <?php endforeach; ?>
        </div>
    </section>
<?php endif; ?>
<?php require __DIR__ . '/includes/footer.php'; ?>
