<?php
require_once APP_ROOT . '/app/models/News.php';

// Handle form submissions
$message = null;
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'create') {
        try {
            News::create([
                'title' => $_POST['title'],
                'content' => $_POST['content'],
                'excerpt' => $_POST['excerpt'] ?? null,
                'featured_image' => $_POST['featured_image'] ?? null,
                'is_published' => isset($_POST['is_published']) ? 1 : 0
            ]);
            $message = 'Știre adăugată cu succes!';
        } catch (Exception $e) {
            $error = 'Eroare la adăugarea știrii: ' . $e->getMessage();
        }
    } elseif ($action === 'update') {
        try {
            News::update((int)$_POST['id'], [
                'title' => $_POST['title'],
                'content' => $_POST['content'],
                'excerpt' => $_POST['excerpt'] ?? null,
                'featured_image' => $_POST['featured_image'] ?? null,
                'is_published' => isset($_POST['is_published']) ? 1 : 0
            ]);
            $message = 'Știre actualizată cu succes!';
        } catch (Exception $e) {
            $error = 'Eroare la actualizarea știrii: ' . $e->getMessage();
        }
    } elseif ($action === 'delete') {
        try {
            News::delete((int)$_POST['id']);
            $message = 'Știre ștearsă cu succes!';
        } catch (Exception $e) {
            $error = 'Eroare la ștergerea știrii: ' . $e->getMessage();
        }
    }
}

// Get all news items (including unpublished)
$newsItems = News::getAll(true);

// Check if editing
$editingNews = null;
if (isset($_GET['edit'])) {
    $editingNews = News::getById((int)$_GET['edit']);
}
?>

<style>
    .content-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
    }

    .content-header h3 {
        font-size: 1.5rem;
        color: #1d1d1f;
    }

    .btn {
        padding: 0.625rem 1.25rem;
        border-radius: 6px;
        text-decoration: none;
        font-weight: 500;
        transition: all 0.2s;
        border: none;
        cursor: pointer;
        font-size: 0.875rem;
    }

    .btn-primary {
        background: #007aff;
        color: #fff;
    }

    .btn-primary:hover {
        background: #0051d5;
    }

    .btn-danger {
        background: #dc3545;
        color: #fff;
    }

    .btn-danger:hover {
        background: #b02a37;
    }

    .btn-secondary {
        background: #6c757d;
        color: #fff;
    }

    .btn-small {
        padding: 0.375rem 0.75rem;
        font-size: 0.8125rem;
        margin-right: 0.5rem;
    }

    .alert {
        padding: 1rem 1.25rem;
        border-radius: 6px;
        margin-bottom: 1.5rem;
    }

    .alert-success {
        background: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }

    .alert-error {
        background: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }

    .form-card {
        background: #fff;
        padding: 1.5rem;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        margin-bottom: 2rem;
    }

    .form-group {
        margin-bottom: 1.25rem;
    }

    .form-group label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 500;
        color: #1d1d1f;
    }

    .form-group input[type="text"],
    .form-group textarea {
        width: 100%;
        padding: 0.625rem 0.875rem;
        border: 1px solid #d1d1d6;
        border-radius: 6px;
        font-size: 0.9375rem;
        font-family: inherit;
    }

    .form-group textarea {
        resize: vertical;
        min-height: 100px;
    }

    .form-group textarea.tall {
        min-height: 300px;
    }

    .form-group input:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: #007aff;
    }

    .checkbox-group {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .checkbox-group input[type="checkbox"] {
        width: auto;
    }

    .table-container {
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    thead {
        background: #f5f5f7;
    }

    th {
        padding: 1rem;
        text-align: left;
        font-weight: 600;
        color: #1d1d1f;
        font-size: 0.875rem;
    }

    td {
        padding: 1rem;
        border-top: 1px solid #e5e5ea;
    }

    tbody tr:hover {
        background: #fafafa;
    }

    .badge {
        display: inline-block;
        padding: 0.25rem 0.625rem;
        border-radius: 12px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .badge-success {
        background: #d4edda;
        color: #155724;
    }

    .badge-secondary {
        background: #e2e3e5;
        color: #383d41;
    }

    .news-thumb {
        width: 80px;
        height: 50px;
        object-fit: cover;
        border-radius: 4px;
    }

    .excerpt-preview {
        color: #86868b;
        font-size: 0.875rem;
        max-width: 300px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
</style>

<?php if ($message): ?>
    <div class="alert alert-success"><?= htmlspecialchars($message) ?></div>
<?php endif; ?>

<?php if ($error): ?>
    <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<div class="content-header">
    <h3>Știri</h3>
    <?php if (!$editingNews): ?>
        <button class="btn btn-primary" onclick="document.getElementById('newsForm').scrollIntoView({behavior: 'smooth'})">
            + Adaugă Știre
        </button>
    <?php endif; ?>
</div>

<!-- News Form -->
<div class="form-card" id="newsForm">
    <h4 style="margin-bottom: 1.5rem;"><?= $editingNews ? 'Editează Știre' : 'Știre Nouă' ?></h4>

    <form method="POST">
        <input type="hidden" name="action" value="<?= $editingNews ? 'update' : 'create' ?>">
        <?php if ($editingNews): ?>
            <input type="hidden" name="id" value="<?= $editingNews['id'] ?>">
        <?php endif; ?>

        <div class="form-group">
            <label for="title">Titlu *</label>
            <input type="text" id="title" name="title" required
                   value="<?= htmlspecialchars($editingNews['title'] ?? '') ?>">
        </div>

        <div class="form-group">
            <label for="excerpt">Rezumat</label>
            <textarea id="excerpt" name="excerpt"
                      placeholder="Rezumat scurt pentru previzualizare (opțional)"><?= htmlspecialchars($editingNews['excerpt'] ?? '') ?></textarea>
        </div>

        <div class="form-group">
            <label for="content">Conținut *</label>
            <textarea id="content" name="content" class="tall" required><?= htmlspecialchars($editingNews['content'] ?? '') ?></textarea>
        </div>

        <div class="form-group">
            <label for="featured_image">URL Imagine Principală</label>
            <input type="text" id="featured_image" name="featured_image"
                   value="<?= htmlspecialchars($editingNews['featured_image'] ?? '') ?>"
                   placeholder="https://example.com/news-image.jpg">
        </div>

        <div class="form-group">
            <div class="checkbox-group">
                <input type="checkbox" id="is_published" name="is_published"
                       <?= ($editingNews['is_published'] ?? 1) ? 'checked' : '' ?>>
                <label for="is_published" style="margin: 0;">Publicat</label>
            </div>
        </div>

        <div style="display: flex; gap: 0.75rem; margin-top: 1.5rem;">
            <button type="submit" class="btn btn-primary">
                <?= $editingNews ? 'Actualizează' : 'Adaugă' ?> Știre
            </button>
            <?php if ($editingNews): ?>
                <a href="<?= base_url('admin/?page=news') ?>" class="btn btn-secondary">
                    Anulează
                </a>
            <?php endif; ?>
        </div>
    </form>
</div>

<!-- News List -->
<div class="table-container">
    <table>
        <thead>
            <tr>
                <th>Imagine</th>
                <th>Titlu</th>
                <th>Data</th>
                <th>Status</th>
                <th>Acțiuni</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($newsItems)): ?>
                <tr>
                    <td colspan="5" style="text-align: center; color: #86868b; padding: 2rem;">
                        Nu există știri adăugate încă.
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach ($newsItems as $news): ?>
                    <tr>
                        <td>
                            <?php if ($news['featured_image']): ?>
                                <img src="<?= htmlspecialchars($news['featured_image']) ?>"
                                     alt="Image" class="news-thumb">
                            <?php else: ?>
                                <div style="width: 80px; height: 50px; background: #e5e5ea; border-radius: 4px;"></div>
                            <?php endif; ?>
                        </td>
                        <td>
                            <strong><?= htmlspecialchars($news['title']) ?></strong>
                            <?php if ($news['excerpt']): ?>
                                <div class="excerpt-preview"><?= htmlspecialchars($news['excerpt']) ?></div>
                            <?php endif; ?>
                        </td>
                        <td><?= date('d.m.Y', strtotime($news['created_at'])) ?></td>
                        <td>
                            <?php if ($news['is_published']): ?>
                                <span class="badge badge-success">Publicat</span>
                            <?php else: ?>
                                <span class="badge badge-secondary">Draft</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="<?= base_url('admin/?page=news&edit=' . $news['id']) ?>"
                               class="btn btn-primary btn-small">Editează</a>

                            <form method="POST" style="display: inline;"
                                  onsubmit="return confirm('Sigur vrei să ștergi această știre?');">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?= $news['id'] ?>">
                                <button type="submit" class="btn btn-danger btn-small">Șterge</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>
