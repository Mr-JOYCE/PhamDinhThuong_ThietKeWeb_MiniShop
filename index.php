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
$sql = 'SELECT id, name, price, image FROM products';
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
<section id="products">
    <h2>Sản phẩm</h2>

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
                <button type="button" class="btn btn-accent add-to-cart"
                        data-id="<?= (int) $product['id'] ?>"
                        data-name="<?= e($product['name']) ?>"
                        data-price="<?= (int) $product['price'] ?>"
                        data-image="<?= e(url($product['image'])) ?>">Thêm vào giỏ</button>
            </article>
        <?php endforeach; ?>
    </div>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
