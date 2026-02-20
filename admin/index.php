<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/helpers.php';

require_admin_auth();

$totalPosts = (int) db()->query('SELECT COUNT(*) FROM posts')->fetchColumn();
$totalCategories = (int) db()->query('SELECT COUNT(*) FROM categories')->fetchColumn();

require __DIR__ . '/header.php';
?>
<div class="card">
    <h2>Hoş geldiniz, <?= e($_SESSION['admin_username'] ?? 'Admin') ?></h2>
    <p>Bu panelden tüm CMS işlemlerini yönetebilirsiniz.</p>
</div>
<div class="grid2">
    <div class="card"><strong>Toplam Yazı:</strong> <?= $totalPosts ?></div>
    <div class="card"><strong>Toplam Kategori:</strong> <?= $totalCategories ?></div>
</div>
<?php require __DIR__ . '/footer.php'; ?>
