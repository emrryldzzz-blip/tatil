<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/helpers.php';

if (is_admin_logged_in()) {
    redirect('/admin/index.php');
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = db()->prepare('SELECT id, username, password_hash FROM users WHERE username = ? LIMIT 1');
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password_hash'])) {
        $_SESSION['admin_user_id'] = (int) $user['id'];
        $_SESSION['admin_username'] = $user['username'];
        redirect('/admin/index.php');
    }

    $error = 'Kullanıcı adı veya şifre hatalı.';
}

require __DIR__ . '/header.php';
?>
<div class="card" style="max-width:420px;margin:0 auto;">
    <h2>Admin Giriş</h2>
    <?php if ($error): ?><div class="alert error"><?= e($error) ?></div><?php endif; ?>
    <form method="post">
        <label>Kullanıcı Adı</label>
        <input type="text" name="username" required>
        <label style="margin-top:10px;display:block;">Şifre</label>
        <input type="password" name="password" required>
        <button class="btn" style="margin-top:14px;">Giriş Yap</button>
    </form>
</div>
<?php require __DIR__ . '/footer.php'; ?>
