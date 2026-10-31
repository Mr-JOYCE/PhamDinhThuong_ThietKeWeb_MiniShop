<?php
require __DIR__ . '/includes/init.php';

// Đã đăng nhập rồi thì không cần vào trang này nữa
if (is_logged_in()) {
    redirect('index.php');
}

$error = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim(is_string($_POST['email'] ?? null) ? $_POST['email'] : '');
    $password = is_string($_POST['password'] ?? null) ? $_POST['password'] : '';

    $stmt = $pdo->prepare('SELECT id, fullname, password_hash, role FROM users WHERE email = ?');
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password_hash'])) {
        // Đổi mã phiên ngay sau khi đăng nhập để chống chiếm phiên
        session_regenerate_id(true);
        $_SESSION['user'] = [
            'id'       => $user['id'],
            'fullname' => $user['fullname'],
            'role'     => $user['role'],
        ];
        set_flash('success', 'Xin chào ' . $user['fullname'] . '!');
        redirect('index.php');
    }

    // Không nói rõ sai email hay sai mật khẩu, để không lộ email nào đã đăng ký
    $error = 'Email hoặc mật khẩu không đúng.';
}

$pageTitle = 'Đăng nhập';
require __DIR__ . '/includes/header.php';
?>
<h2>Đăng nhập</h2>

<form action="<?= e(url('login.php')) ?>" method="post" class="form">
    <?php if ($error): ?>
        <p class="alert alert-error"><?= e($error) ?></p>
    <?php endif; ?>
    <p>
        <label for="email">Email</label><br>
        <input type="email" id="email" name="email" value="<?= e($email) ?>" required>
    </p>
    <p>
        <label for="password">Mật khẩu</label><br>
        <input type="password" id="password" name="password" required>
    </p>
    <p>
        <button type="submit" class="btn">Đăng nhập</button>
    </p>
    <p>Chưa có tài khoản? <a href="<?= e(url('register.php')) ?>">Đăng ký</a></p>
</form>
<?php require __DIR__ . '/includes/footer.php'; ?>
