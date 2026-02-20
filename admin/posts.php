<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/helpers.php';

require_admin_auth();

$pdo = db();
$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    try {
        if ($action === 'create' || $action === 'update') {
            $title = trim($_POST['title'] ?? '');
            $slug = trim($_POST['slug'] ?? '');
            $content = trim($_POST['content'] ?? '');
            $categoryId = (int) ($_POST['category_id'] ?? 0);
            $isPublished = isset($_POST['is_published']) ? 1 : 0;

            if ($title === '' || $slug === '' || $content === '') {
                throw new RuntimeException('Başlık, slug ve içerik zorunludur.');
            }

            $newImage = upload_featured_image($_FILES['featured_image'] ?? []);

            if ($action === 'create') {
                $stmt = $pdo->prepare('INSERT INTO posts(category_id, title, slug, content, featured_image, is_published) VALUES(?, ?, ?, ?, ?, ?)');
                $stmt->execute([$categoryId ?: null, $title, $slug, $content, $newImage, $isPublished]);
                $success = 'Yazı eklendi.';
            } else {
                $id = (int) ($_POST['id'] ?? 0);

                if ($newImage === null) {
                    $stmt = $pdo->prepare('UPDATE posts SET category_id = ?, title = ?, slug = ?, content = ?, is_published = ? WHERE id = ?');
                    $stmt->execute([$categoryId ?: null, $title, $slug, $content, $isPublished, $id]);
                } else {
                    $oldStmt = $pdo->prepare('SELECT featured_image FROM posts WHERE id = ?');
                    $oldStmt->execute([$id]);
                    $oldImage = $oldStmt->fetchColumn();

                    $stmt = $pdo->prepare('UPDATE posts SET category_id = ?, title = ?, slug = ?, content = ?, featured_image = ?, is_published = ? WHERE id = ?');
                    $stmt->execute([$categoryId ?: null, $title, $slug, $content, $newImage, $isPublished, $id]);

                    if ($oldImage && str_starts_with($oldImage, '/uploads/')) {
                        @unlink(__DIR__ . '/..' . $oldImage);
                    }
                }

                $success = 'Yazı güncellendi.';
            }
        }

        if ($action === 'delete') {
            $id = (int) ($_POST['id'] ?? 0);
            $stmt = $pdo->prepare('SELECT featured_image FROM posts WHERE id = ?');
            $stmt->execute([$id]);
            $image = $stmt->fetchColumn();

            $del = $pdo->prepare('DELETE FROM posts WHERE id = ?');
            $del->execute([$id]);

            if ($image && str_starts_with($image, '/uploads/')) {
                @unlink(__DIR__ . '/..' . $image);
            }

            $success = 'Yazı silindi.';
        }
    } catch (Throwable $e) {
        $error = 'İşlem başarısız: ' . $e->getMessage();
    }
}

$categories = $pdo->query('SELECT id, name FROM categories ORDER BY name')->fetchAll();
$posts = $pdo->query('SELECT p.*, c.name AS category_name FROM posts p LEFT JOIN categories c ON c.id = p.category_id ORDER BY p.created_at DESC')->fetchAll();

require __DIR__ . '/header.php';
?>
<div class="card">
    <h2>Yeni Yazı</h2>
    <?php if ($success): ?><div class="alert"><?= e($success) ?></div><?php endif; ?>
    <?php if ($error): ?><div class="alert error"><?= e($error) ?></div><?php endif; ?>
    <form method="post" enctype="multipart/form-data">
        <input type="hidden" name="action" value="create">
        <div class="grid2">
            <div>
                <label>Başlık</label>
                <input type="text" name="title" required>
            </div>
            <div>
                <label>Slug</label>
                <input type="text" name="slug" required>
            </div>
        </div>
        <div class="grid2" style="margin-top:10px;">
            <div>
                <label>Kategori</label>
                <select name="category_id">
                    <option value="">Seçiniz</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= (int) $cat['id'] ?>"><?= e($cat['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label>Öne Çıkan Görsel</label>
                <input type="file" name="featured_image" accept="image/*">
            </div>
        </div>
        <label style="margin-top:10px;display:block;">İçerik</label>
        <textarea name="content" rows="6" required></textarea>
        <label style="display:flex;gap:8px;align-items:center;margin-top:10px;">
            <input style="width:auto" type="checkbox" name="is_published" checked> Yayınla
        </label>
        <button class="btn" style="margin-top:10px;">Kaydet</button>
    </form>
</div>

<div class="card">
    <h2>Yazılar</h2>
    <table>
        <thead><tr><th>Yazı</th><th>Kategori</th><th>Durum</th><th>İşlemler</th></tr></thead>
        <tbody>
        <?php foreach ($posts as $post): ?>
            <tr>
                <td>
                    <strong><?= e($post['title']) ?></strong><br>
                    <small>/<?= e($post['slug']) ?></small>
                </td>
                <td><?= e($post['category_name'] ?? '-') ?></td>
                <td><?= (int) $post['is_published'] === 1 ? 'Yayında' : 'Taslak' ?></td>
                <td>
                    <details>
                        <summary>Düzenle</summary>
                        <form method="post" enctype="multipart/form-data" style="margin-top:8px;display:grid;gap:8px;">
                            <input type="hidden" name="action" value="update">
                            <input type="hidden" name="id" value="<?= (int) $post['id'] ?>">
                            <input type="text" name="title" value="<?= e($post['title']) ?>" required>
                            <input type="text" name="slug" value="<?= e($post['slug']) ?>" required>
                            <select name="category_id">
                                <option value="">Seçiniz</option>
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?= (int) $cat['id'] ?>" <?= (int) $post['category_id'] === (int) $cat['id'] ? 'selected' : '' ?>><?= e($cat['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <textarea name="content" rows="5" required><?= e($post['content']) ?></textarea>
                            <input type="file" name="featured_image" accept="image/*">
                            <label><input style="width:auto" type="checkbox" name="is_published" <?= (int) $post['is_published'] === 1 ? 'checked' : '' ?>> Yayında</label>
                            <button class="btn">Güncelle</button>
                        </form>
                    </details>
                    <form method="post" onsubmit="return confirm('Yazıyı silmek istiyor musunuz?')" style="margin-top:8px;">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="id" value="<?= (int) $post['id'] ?>">
                        <button class="btn btn-danger">Sil</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php require __DIR__ . '/footer.php'; ?>
