<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/helpers.php';

$slug = trim($_GET['slug'] ?? '');
$stmt = db()->prepare('SELECT p.*, c.name AS category_name FROM posts p LEFT JOIN categories c ON c.id = p.category_id WHERE p.slug = ? AND p.is_published = 1 LIMIT 1');
$stmt->execute([$slug]);
$post = $stmt->fetch();

if (!$post) {
    http_response_code(404);
    echo 'Yazı bulunamadı.';
    exit;
}
?>
<!doctype html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($post['title']) ?></title>
    <style>
        body{font-family:Arial,sans-serif;max-width:850px;margin:0 auto;padding:24px;background:#f8fafc;color:#111827}
        .hero{width:100%;max-height:400px;object-fit:cover;border-radius:14px}
        a{color:#1d4ed8}
    </style>
</head>
<body>
    <p><a href="/">← Anasayfa</a></p>
    <?php if (!empty($post['featured_image'])): ?>
        <img class="hero" src="<?= e($post['featured_image']) ?>" alt="<?= e($post['title']) ?>">
    <?php endif; ?>
    <h1><?= e($post['title']) ?></h1>
    <small><?= e($post['category_name'] ?? 'Genel') ?> • <?= e(date('d.m.Y', strtotime($post['created_at']))) ?></small>
    <div style="margin-top:16px;line-height:1.7;"><?= nl2br(e($post['content'])) ?></div>
</body>
</html>
