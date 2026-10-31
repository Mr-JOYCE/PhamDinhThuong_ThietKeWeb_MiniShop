<?php
require __DIR__ . '/includes/init.php';

$categoryInput = $_GET['category'] ?? '';
$categoryId = is_scalar($categoryInput) ? filter_var($categoryInput, FILTER_VALIDATE_INT) : false;
$categoryId = $categoryId !== false && $categoryId > 0 ? $categoryId : 0;
$keywordInput = $_GET['q'] ?? '';
$keyword = is_string($keywordInput) ? trim($keywordInput) : '';
$sortInput = $_GET['sort'] ?? '';
$sort = is_string($sortInput) ? $sortInput : '';

$categories = $pdo->query('SELECT id, name FROM categories ORDER BY id')->fetchAll();
$sql = 'SELECT id, name, price, image, stock FROM products';
$conditions = [];
$parameters = [];

if ($categoryId > 0) {
    $conditions[] = 'category_id = ?';
    $parameters[] = $categoryId;
}

if ($keyword !== '') {
    $conditions[] = 'name LIKE ?';
    $parameters[] = '%' . $keyword . '%';
}

if ($conditions) {
    $sql .= ' WHERE ' . implode(' AND ', $conditions);
}

$orderBy = [
    'price_asc' => 'price ASC',
    'price_desc' => 'price DESC',
];
$sql .= ' ORDER BY ' . ($orderBy[$sort] ?? 'id ASC');

$statement = $pdo->prepare($sql);
$statement->execute($parameters);
$products = $statement->fetchAll();

$pageTitle = 'Trang chủ';
require __DIR__ . '/includes/header.php';
?>
<section class="shop-hero">
    <div class="shop-hero-copy">
        <p class="hero-kicker">Phụ kiện công nghệ mỗi ngày</p>
        <h2>Thiết bị tốt cho góc học tập của bạn</h2>
        <p>Chọn phụ kiện thiết thực, dễ dùng và hợp túi tiền — từ chuột, bàn phím đến âm thanh và lưu trữ.</p>
        <a href="#products" class="btn btn-accent">Khám phá sản phẩm</a>
    </div>
    <div class="shop-hero-image">
        <img src="<?= e(url('images/sp02.jpg')) ?>" alt="Bàn phím cơ K2">
        <span class="hero-note">Chọn đúng phụ kiện<br>học tập hiệu quả hơn</span>
    </div>
</section>
<section id="products">
    <div class="section-heading">
        <div>
            <p class="section-kicker">Danh mục cửa hàng</p>
            <h2>Sản phẩm</h2>
        </div>
        <p class="muted"><?= count($products) ?> sản phẩm phù hợp</p>
    </div>

    <form action="<?= e(url('index.php')) ?>" method="get" class="search-form">
        <input type="search" name="q" value="<?= e($keyword) ?>" placeholder="Tìm sản phẩm..."
               aria-label="Tìm sản phẩm">
        <?php if ($categoryId > 0): ?>
            <input type="hidden" name="category" value="<?= (int) $categoryId ?>">
        <?php endif; ?>
        <?php if (isset($orderBy[$sort])): ?>
            <input type="hidden" name="sort" value="<?= e($sort) ?>">
        <?php endif; ?>
        <button type="submit" class="btn">Tìm</button>
    </form>

    <nav class="category-bar" aria-label="Lọc theo danh mục">
        <a href="<?= e(url('index.php')) ?>" class="<?= $categoryId === 0 ? 'active' : '' ?>">Tất cả</a>
        <?php foreach ($categories as $category): ?>
            <?php
            $categoryParameters = ['category' => (int) $category['id']];
            if ($keyword !== '') {
                $categoryParameters['q'] = $keyword;
            }
            if (isset($orderBy[$sort])) {
                $categoryParameters['sort'] = $sort;
            }
            ?>
            <a href="<?= e(url('index.php?' . http_build_query($categoryParameters))) ?>"
               class="<?= $categoryId === (int) $category['id'] ? 'active' : '' ?>">
                <?= e($category['name']) ?>
            </a>
        <?php endforeach; ?>
    </nav>

    <form action="<?= e(url('index.php')) ?>" method="get" class="sort-form">
        <?php if ($categoryId > 0): ?>
            <input type="hidden" name="category" value="<?= (int) $categoryId ?>">
        <?php endif; ?>
        <?php if ($keyword !== ''): ?>
            <input type="hidden" name="q" value="<?= e($keyword) ?>">
        <?php endif; ?>
        <label for="sort">Sắp xếp:</label>
        <select id="sort" name="sort">
            <option value="" <?= $sort === '' ? 'selected' : '' ?>>Mặc định</option>
            <option value="price_asc" <?= $sort === 'price_asc' ? 'selected' : '' ?>>Giá tăng dần</option>
            <option value="price_desc" <?= $sort === 'price_desc' ? 'selected' : '' ?>>Giá giảm dần</option>
        </select>
        <button type="submit" class="btn">Sắp xếp</button>
    </form>

    <p role="status">Tìm thấy <?= count($products) ?> sản phẩm</p>

    <?php if (!$products): ?>
        <p>Không tìm thấy sản phẩm nào.</p>
    <?php endif; ?>

    <div class="product-list">
        <?php foreach ($products as $product): ?>
            <article class="product-card">
                <img src="<?= e(url($product['image'])) ?>" alt="<?= e($product['name']) ?>">
                <h3><?= e($product['name']) ?></h3>
                <p class="price"><?= format_price($product['price']) ?></p>
                <a href="<?= e(url('product.php?id=' . (int) $product['id'])) ?>" class="btn">Xem chi tiết</a>
                <?php if ((int) $product['stock'] > 0): ?>
                    <form action="<?= e(url('cart-add.php')) ?>" method="post" class="inline-form">
                        <?= csrf_field() ?>
                        <input type="hidden" name="product_id" value="<?= (int) $product['id'] ?>">
                        <button type="submit" class="btn btn-accent">Thêm vào giỏ</button>
                    </form>
                <?php else: ?>
                    <p class="muted">Hết hàng</p>
                <?php endif; ?>
            </article>
        <?php endforeach; ?>
    </div>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
