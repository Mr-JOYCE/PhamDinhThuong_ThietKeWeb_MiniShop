<?php
require __DIR__ . '/../includes/init.php';
require_admin();

$idInput = $_GET['id'] ?? null;
$id = $idInput === null ? 0 : (is_scalar($idInput) ? filter_var($idInput, FILTER_VALIDATE_INT) : false);
if ($id === false || $id < 0) {
    set_flash('error', 'Mã sản phẩm không hợp lệ.');
    redirect('admin/products.php');
}

$product = [
    'category_id' => 0,
    'name' => '',
    'price' => '',
    'stock' => '',
    'description' => '',
    'image' => 'images/sp01.jpg',
];

if ($id > 0) {
    $stmt = $pdo->prepare(
        'SELECT id, category_id, name, price, stock, description, image FROM products WHERE id = ?'
    );
    $stmt->execute([$id]);
    $existingProduct = $stmt->fetch();
    if (!$existingProduct) {
        set_flash('error', 'Sản phẩm không tồn tại.');
        redirect('admin/products.php');
    }
    $product = $existingProduct;
}

$categories = $pdo->query('SELECT id, name FROM categories ORDER BY id')->fetchAll();
$categoryIds = array_map('intval', array_column($categories, 'id'));
$errors = [];

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    $categoryInput = $_POST['category_id'] ?? null;
    $categoryId = is_scalar($categoryInput) ? filter_var($categoryInput, FILTER_VALIDATE_INT) : false;
    $name = trim(is_string($_POST['name'] ?? null) ? $_POST['name'] : '');
    $priceInput = $_POST['price'] ?? null;
    $price = is_scalar($priceInput) ? filter_var($priceInput, FILTER_VALIDATE_INT) : false;
    $stockInput = $_POST['stock'] ?? null;
    $stock = is_scalar($stockInput) ? filter_var($stockInput, FILTER_VALIDATE_INT) : false;
    $description = trim(is_string($_POST['description'] ?? null) ? $_POST['description'] : '');

    $product['category_id'] = $categoryId !== false ? $categoryId : 0;
    $product['name'] = $name;
    $product['price'] = is_scalar($priceInput) ? (string) $priceInput : '';
    $product['stock'] = is_scalar($stockInput) ? (string) $stockInput : '';
    $product['description'] = $description;

    if ($categoryId === false || !in_array($categoryId, $categoryIds, true)) {
        $errors['category_id'] = 'Hãy chọn danh mục hợp lệ.';
    }
    if (mb_strlen($name) < 2 || mb_strlen($name) > 200) {
        $errors['name'] = 'Tên sản phẩm phải có từ 2 đến 200 ký tự.';
    }
    if ($price === false || $price < 1000) {
        $errors['price'] = 'Giá phải là số nguyên từ 1.000 đ trở lên.';
    }
    if ($stock === false || $stock < 0) {
        $errors['stock'] = 'Tồn kho phải là số nguyên từ 0 trở lên.';
    }

    $uploadedImage = null;
    if (!$errors && isset($_FILES['image']) && ($_FILES['image']['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE) {
        try {
            $uploadedImage = upload_image($_FILES['image']);
            $product['image'] = $uploadedImage;
        } catch (RuntimeException $exception) {
            $errors['image'] = $exception->getMessage();
        }
    }

    if (!$errors) {
        $values = [
            $product['category_id'],
            $product['name'],
            $price,
            $stock,
            $product['description'],
            $product['image'],
        ];

        try {
            if ($id > 0) {
                $stmt = $pdo->prepare(
                    'UPDATE products
                     SET category_id = ?, name = ?, price = ?, stock = ?, description = ?, image = ?
                     WHERE id = ?'
                );
                $values[] = $id;
                $stmt->execute($values);
                set_flash('success', 'Đã cập nhật sản phẩm.');
            } else {
                $stmt = $pdo->prepare(
                    'INSERT INTO products (category_id, name, price, stock, description, image)
                     VALUES (?, ?, ?, ?, ?, ?)'
                );
                $stmt->execute($values);
                set_flash('success', 'Đã thêm sản phẩm.');
            }
        } catch (PDOException $exception) {
            if ($uploadedImage !== null) {
                $uploadedPath = __DIR__ . '/../' . $uploadedImage;
                if (is_file($uploadedPath) && !unlink($uploadedPath)) {
                    error_log('MiniShop could not remove an unused upload: ' . $uploadedPath);
                }
            }
            throw $exception;
        }
        redirect('admin/products.php');
    }
}

$pageTitle = $id > 0 ? 'Sửa sản phẩm' : 'Thêm sản phẩm';
$action = 'admin/product-form.php' . ($id > 0 ? '?id=' . (int) $id : '');
require __DIR__ . '/../includes/header.php';
?>
<section class="admin-page">
    <div class="page-heading">
        <div>
            <h2><?= e($pageTitle) ?></h2>
            <p class="muted">Thông tin sản phẩm sẽ hiển thị trong cửa hàng.</p>
        </div>
    </div>

    <form action="<?= e(url($action)) ?>" method="post" enctype="multipart/form-data" class="form admin-form">
        <?= csrf_field() ?>
        <p>
            <label for="name">Tên sản phẩm</label>
            <input type="text" id="name" name="name" value="<?= e($product['name']) ?>"
                   required minlength="2" maxlength="200">
            <span class="error"><?= e($errors['name'] ?? '') ?></span>
        </p>
        <p>
            <label for="category_id">Danh mục</label>
            <select id="category_id" name="category_id" required>
                <option value="">Chọn danh mục</option>
                <?php foreach ($categories as $category): ?>
                    <option value="<?= (int) $category['id'] ?>"
                        <?= (int) $category['id'] === (int) $product['category_id'] ? 'selected' : '' ?>>
                        <?= e($category['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <span class="error"><?= e($errors['category_id'] ?? '') ?></span>
        </p>
        <div class="form-grid">
            <p>
                <label for="price">Giá (đ)</label>
                <input type="number" id="price" name="price" value="<?= e($product['price']) ?>"
                       min="1000" step="1" required>
                <span class="error"><?= e($errors['price'] ?? '') ?></span>
            </p>
            <p>
                <label for="stock">Tồn kho</label>
                <input type="number" id="stock" name="stock" value="<?= e($product['stock']) ?>"
                       min="0" step="1" required>
                <span class="error"><?= e($errors['stock'] ?? '') ?></span>
            </p>
        </div>
        <p>
            <label for="description">Mô tả</label>
            <textarea id="description" name="description" rows="5"><?= e($product['description']) ?></textarea>
        </p>
        <p>
            <label for="image">Ảnh sản phẩm (JPG, PNG, WebP; tối đa 2 MB)</label>
            <span class="image-preview">
                <img src="<?= e(url($product['image'])) ?>" alt="Ảnh hiện tại của sản phẩm" class="thumb">
                <span><?= $id > 0 ? 'Ảnh hiện tại' : 'Ảnh mặc định' ?></span>
            </span>
            <input type="file" id="image" name="image" accept="image/jpeg,image/png,image/webp">
            <span class="error"><?= e($errors['image'] ?? '') ?></span>
        </p>
        <div class="form-actions">
            <button type="submit" class="btn btn-accent">Lưu sản phẩm</button>
            <a href="<?= e(url('admin/products.php')) ?>" class="btn btn-quiet">Hủy</a>
        </div>
    </form>
</section>
<?php require __DIR__ . '/../includes/footer.php'; ?>
