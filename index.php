<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/helpers.php';

$posts = db()->query('SELECT p.id, p.title, p.slug, p.content, p.featured_image, p.created_at, c.name AS category_name FROM posts p LEFT JOIN categories c ON c.id = p.category_id WHERE p.is_published = 1 ORDER BY p.created_at DESC')->fetchAll();
?>
<!doctype html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mini CMS</title>
    <style>
        body{font-family:Arial,sans-serif;margin:0;background:#f8fafc;color:#111827}
        .container{max-width:1100px;margin:0 auto;padding:24px}
        h1{text-align:center;margin-bottom:24px}
        .grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(260px,1fr));gap:18px}
        .card{background:#fff;border-radius:14px;overflow:hidden;box-shadow:0 2px 12px rgba(0,0,0,.08)}
        .cover{height:180px;background:#e5e7eb;display:flex;align-items:center;justify-content:center;color:#6b7280}
        .cover img{width:100%;height:100%;object-fit:cover}
        .body{padding:14px}
        a{text-decoration:none;color:#1d4ed8}
    </style>
</head>
<body>
<div class="container">
    <h1>Yazılar</h1>
    <div class="grid">
        <?php foreach ($posts as $post): ?>
            <article class="card">
                <div class="cover">
                    <?php if (!empty($post['featured_image'])): ?>
                        <img src="<?= e($post['featured_image']) ?>" alt="<?= e($post['title']) ?>">
                    <?php else: ?>
                        Görsel Yok
                    <?php endif; ?>
                </div>
                <div class="body">
                    <small><?= e($post['category_name'] ?? 'Genel') ?> • <?= e(date('d.m.Y', strtotime($post['created_at']))) ?></small>
                    <h3><?= e($post['title']) ?></h3>
                    <p><?= e(mb_strimwidth(strip_tags($post['content']), 0, 110, '...')) ?></p>
                    <a href="/post.php?slug=<?= urlencode($post['slug']) ?>">Devamını oku →</a>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
</div>
</body>
</html>
