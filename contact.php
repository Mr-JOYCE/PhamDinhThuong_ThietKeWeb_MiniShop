<?php
require __DIR__ . '/includes/init.php';
$pageTitle = 'Liên hệ';
require __DIR__ . '/includes/header.php';
?>
<section>
    <h2>Liên hệ với MiniShop</h2>
    <form class="form" id="contact-form" method="post">
        <p>
            <label for="fullname">Họ tên</label><br>
            <input type="text" id="fullname" name="fullname" required autocomplete="name">
        </p>
        <p>
            <label for="email">Email</label><br>
            <input type="email" id="email" name="email" required autocomplete="email">
        </p>
        <p>
            <label for="subject">Tiêu đề</label><br>
            <input type="text" id="subject" name="subject" required>
        </p>
        <p>
            <label for="message">Nội dung</label><br>
            <textarea id="message" name="message" rows="6" cols="40" required></textarea>
            <span class="error" id="message-error" aria-live="polite"></span>
        </p>
        <p><button type="submit" class="btn">Gửi</button></p>
    </form>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
