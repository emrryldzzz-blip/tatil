<?php
require_once __DIR__ . '/../includes/helpers.php';
?>
<!doctype html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mini CMS Admin</title>
    <style>
        body{font-family:Arial,sans-serif;margin:0;background:#f4f6fb;color:#1f2937}
        .wrap{max-width:1080px;margin:0 auto;padding:24px}
        .top{display:flex;justify-content:space-between;align-items:center;margin-bottom:20px}
        .nav a{margin-right:12px;text-decoration:none;color:#1d4ed8;font-weight:600}
        .card{background:#fff;padding:18px;border-radius:12px;box-shadow:0 3px 10px rgba(0,0,0,.06);margin-bottom:16px}
        table{width:100%;border-collapse:collapse}
        th,td{padding:10px;border-bottom:1px solid #e5e7eb;text-align:left;vertical-align:top}
        input,textarea,select{width:100%;padding:9px;border:1px solid #d1d5db;border-radius:8px;box-sizing:border-box}
        .btn{display:inline-block;padding:8px 12px;border-radius:8px;background:#1d4ed8;color:#fff;text-decoration:none;border:none;cursor:pointer}
        .btn-danger{background:#b91c1c}
        .alert{padding:10px;border-radius:8px;margin-bottom:12px;background:#dcfce7;color:#166534}
        .error{background:#fee2e2;color:#991b1b}
        .grid2{display:grid;grid-template-columns:1fr 1fr;gap:12px}
    </style>
</head>
<body>
<div class="wrap">
    <div class="top">
        <h1>Mini CMS Yönetim</h1>
        <?php if (is_admin_logged_in()): ?>
        <div class="nav">
            <a href="<?= e(url('admin/index.php')) ?>">Panel</a>
            <a href="<?= e(url('admin/categories.php')) ?>">Kategoriler</a>
            <a href="<?= e(url('admin/posts.php')) ?>">Yazılar</a>
            <a href="<?= e(url('admin/logout.php')) ?>">Çıkış</a>
        </div>
        <?php endif; ?>
    </div>
