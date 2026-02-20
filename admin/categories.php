<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/helpers.php';

require_admin_auth();

$pdo = db();
$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $name = trim($_POST['name'] ?? '');

    try {
        if ($action === 'create' && $name !== '') {
            $stmt = $pdo->prepare('INSERT INTO categories(name) VALUES(?)');
            $stmt->execute([$name]);
            $success = 'Kategori eklendi.';
        }

        if ($action === 'update') {
            $id = (int) ($_POST['id'] ?? 0);
            $stmt = $pdo->prepare('UPDATE categories SET name = ? WHERE id = ?');
            $stmt->execute([$name, $id]);
            $success = 'Kategori güncellendi.';
        }

        if ($action === 'delete') {
            $id = (int) ($_POST['id'] ?? 0);
            $stmt = $pdo->prepare('DELETE FROM categories WHERE id = ?');
            $stmt->execute([$id]);
            $success = 'Kategori silindi.';
        }
    } catch (Throwable $e) {
        $error = 'İşlem sırasında hata oluştu: ' . $e->getMessage();
    }
}

$categories = $pdo->query('SELECT id, name, created_at FROM categories ORDER BY id DESC')->fetchAll();

require __DIR__ . '/header.php';
?>
<div class="card">
    <h2>Yeni Kategori</h2>
    <?php if ($success): ?><div class="alert"><?= e($success) ?></div><?php endif; ?>
    <?php if ($error): ?><div class="alert error"><?= e($error) ?></div><?php endif; ?>
    <form method="post" class="grid2">
        <input type="hidden" name="action" value="create">
        <input type="text" name="name" placeholder="Kategori adı" required>
        <button class="btn">Ekle</button>
    </form>
</div>

<div class="card">
    <h2>Kategoriler</h2>
    <table>
        <thead><tr><th>ID</th><th>Ad</th><th>Tarih</th><th>İşlem</th></tr></thead>
        <tbody>
        <?php foreach ($categories as $cat): ?>
            <tr>
                <td><?= (int) $cat['id'] ?></td>
                <td>
                    <form method="post" style="display:flex;gap:8px;">
                        <input type="hidden" name="action" value="update">
                        <input type="hidden" name="id" value="<?= (int) $cat['id'] ?>">
                        <input type="text" name="name" value="<?= e($cat['name']) ?>" required>
                        <button class="btn">Güncelle</button>
                    </form>
                </td>
                <td><?= e($cat['created_at']) ?></td>
                <td>
                    <form method="post" onsubmit="return confirm('Silmek istiyor musunuz?')">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="id" value="<?= (int) $cat['id'] ?>">
                        <button class="btn btn-danger">Sil</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php require __DIR__ . '/footer.php'; ?>
