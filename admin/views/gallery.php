<?php
require_once APP_ROOT . '/app/models/Gallery.php';

// Handle form submissions
$message = null;
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'create') {
        try {
            Gallery::create([
                'title' => $_POST['title'],
                'description' => $_POST['description'] ?? null,
                'image_path' => $_POST['image_path'],
                'thumbnail_path' => $_POST['thumbnail_path'] ?? $_POST['image_path'],
                'category' => $_POST['category'] ?? 'general',
                'sort_order' => (int)($_POST['sort_order'] ?? 0),
                'is_published' => isset($_POST['is_published']) ? 1 : 0
            ]);
            $message = 'Imagine adăugată cu succes!';
        } catch (Exception $e) {
            $error = 'Eroare la adăugarea imaginii: ' . $e->getMessage();
        }
    } elseif ($action === 'update') {
        try {
            Gallery::update((int)$_POST['id'], [
                'title' => $_POST['title'],
                'description' => $_POST['description'] ?? null,
                'image_path' => $_POST['image_path'],
                'thumbnail_path' => $_POST['thumbnail_path'] ?? $_POST['image_path'],
                'category' => $_POST['category'] ?? 'general',
                'sort_order' => (int)($_POST['sort_order'] ?? 0),
                'is_published' => isset($_POST['is_published']) ? 1 : 0
            ]);
            $message = 'Imagine actualizată cu succes!';
        } catch (Exception $e) {
            $error = 'Eroare la actualizarea imaginii: ' . $e->getMessage();
        }
    } elseif ($action === 'delete') {
        try {
            Gallery::delete((int)$_POST['id']);
            $message = 'Imagine ștearsă cu succes!';
        } catch (Exception $e) {
            $error = 'Eroare la ștergerea imaginii: ' . $e->getMessage();
        }
    }
}

// Get all gallery items (including unpublished)
$galleryItems = Gallery::getAll(true);

// Check if editing
$editingItem = null;
if (isset($_GET['edit'])) {
    $editingItem = Gallery::getById((int)$_GET['edit']);
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
    .form-group input[type="number"],
    .form-group select,
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
        min-height: 80px;
    }

    .form-group input:focus,
    .form-group select:focus,
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

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }

    .gallery-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 1.5rem;
        background: #fff;
        padding: 1.5rem;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .gallery-item {
        position: relative;
        background: #f5f5f7;
        border-radius: 8px;
        overflow: hidden;
    }

    .gallery-item-image {
        width: 100%;
        aspect-ratio: 4/3;
        object-fit: cover;
    }

    .gallery-item-info {
        padding: 1rem;
    }

    .gallery-item-title {
        font-weight: 600;
        margin-bottom: 0.25rem;
        font-size: 0.9375rem;
    }

    .gallery-item-category {
        color: #86868b;
        font-size: 0.8125rem;
        margin-bottom: 0.75rem;
    }

    .gallery-item-actions {
        display: flex;
        gap: 0.5rem;
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

    .image-preview {
        margin-top: 0.5rem;
        max-width: 300px;
        border-radius: 4px;
    }
</style>

<?php if ($message): ?>
    <div class="alert alert-success"><?= htmlspecialchars($message) ?></div>
<?php endif; ?>

<?php if ($error): ?>
    <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<div class="content-header">
    <h3>Galerie</h3>
    <?php if (!$editingItem): ?>
        <button class="btn btn-primary" onclick="document.getElementById('galleryForm').scrollIntoView({behavior: 'smooth'})">
            + Adaugă Imagine
        </button>
    <?php endif; ?>
</div>

<!-- Gallery Form -->
<div class="form-card" id="galleryForm">
    <h4 style="margin-bottom: 1.5rem;"><?= $editingItem ? 'Editează Imagine' : 'Imagine Nouă' ?></h4>

    <form method="POST">
        <input type="hidden" name="action" value="<?= $editingItem ? 'update' : 'create' ?>">
        <?php if ($editingItem): ?>
            <input type="hidden" name="id" value="<?= $editingItem['id'] ?>">
        <?php endif; ?>

        <div class="form-group">
            <label for="title">Titlu *</label>
            <input type="text" id="title" name="title" required
                   value="<?= htmlspecialchars($editingItem['title'] ?? '') ?>">
        </div>

        <div class="form-group">
            <label for="description">Descriere</label>
            <textarea id="description" name="description"><?= htmlspecialchars($editingItem['description'] ?? '') ?></textarea>
        </div>

        <div class="form-group">
            <label for="image_path">URL Imagine *</label>
            <input type="text" id="image_path" name="image_path" required
                   value="<?= htmlspecialchars($editingItem['image_path'] ?? '') ?>"
                   placeholder="https://example.com/gallery-image.jpg"
                   onchange="document.getElementById('preview').src = this.value">
            <?php if ($editingItem && $editingItem['image_path']): ?>
                <img id="preview" src="<?= htmlspecialchars($editingItem['image_path']) ?>"
                     alt="Preview" class="image-preview">
            <?php else: ?>
                <img id="preview" src="" alt="Preview" class="image-preview" style="display: none;"
                     onload="this.style.display = 'block';" onerror="this.style.display = 'none';">
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label for="thumbnail_path">URL Miniatură (opțional, se folosește imaginea principală dacă lipsește)</label>
            <input type="text" id="thumbnail_path" name="thumbnail_path"
                   value="<?= htmlspecialchars($editingItem['thumbnail_path'] ?? '') ?>"
                   placeholder="https://example.com/gallery-thumb.jpg">
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="category">Categorie</label>
                <select id="category" name="category">
                    <option value="general" <?= ($editingItem['category'] ?? 'general') === 'general' ? 'selected' : '' ?>>General</option>
                    <option value="concert" <?= ($editingItem['category'] ?? '') === 'concert' ? 'selected' : '' ?>>Concert</option>
                    <option value="backstage" <?= ($editingItem['category'] ?? '') === 'backstage' ? 'selected' : '' ?>>Backstage</option>
                    <option value="press" <?= ($editingItem['category'] ?? '') === 'press' ? 'selected' : '' ?>>Presă</option>
                    <option value="promo" <?= ($editingItem['category'] ?? '') === 'promo' ? 'selected' : '' ?>>Promoțional</option>
                </select>
            </div>

            <div class="form-group">
                <label for="sort_order">Ordine Afișare</label>
                <input type="number" id="sort_order" name="sort_order"
                       value="<?= htmlspecialchars($editingItem['sort_order'] ?? 0) ?>"
                       min="0">
            </div>
        </div>

        <div class="form-group">
            <div class="checkbox-group">
                <input type="checkbox" id="is_published" name="is_published"
                       <?= ($editingItem['is_published'] ?? 1) ? 'checked' : '' ?>>
                <label for="is_published" style="margin: 0;">Publicat</label>
            </div>
        </div>

        <div style="display: flex; gap: 0.75rem; margin-top: 1.5rem;">
            <button type="submit" class="btn btn-primary">
                <?= $editingItem ? 'Actualizează' : 'Adaugă' ?> Imagine
            </button>
            <?php if ($editingItem): ?>
                <a href="<?= base_url('admin/?page=gallery') ?>" class="btn btn-secondary">
                    Anulează
                </a>
            <?php endif; ?>
        </div>
    </form>
</div>

<!-- Gallery Items -->
<?php if (empty($galleryItems)): ?>
    <div class="form-card" style="text-align: center; color: #86868b; padding: 3rem;">
        Nu există imagini adăugate încă.
    </div>
<?php else: ?>
    <div class="gallery-grid">
        <?php foreach ($galleryItems as $item): ?>
            <div class="gallery-item">
                <img src="<?= htmlspecialchars($item['thumbnail_path'] ?: $item['image_path']) ?>"
                     alt="<?= htmlspecialchars($item['title']) ?>"
                     class="gallery-item-image">

                <div class="gallery-item-info">
                    <div class="gallery-item-title"><?= htmlspecialchars($item['title']) ?></div>
                    <div class="gallery-item-category">
                        <?= ucfirst(htmlspecialchars($item['category'])) ?>
                        •
                        <?php if ($item['is_published']): ?>
                            <span class="badge badge-success">Publicat</span>
                        <?php else: ?>
                            <span class="badge badge-secondary">Draft</span>
                        <?php endif; ?>
                    </div>

                    <div class="gallery-item-actions">
                        <a href="<?= base_url('admin/?page=gallery&edit=' . $item['id']) ?>"
                           class="btn btn-primary btn-small">Editează</a>

                        <form method="POST" style="display: inline;"
                              onsubmit="return confirm('Sigur vrei să ștergi această imagine?');">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="id" value="<?= $item['id'] ?>">
                            <button type="submit" class="btn btn-danger btn-small">Șterge</button>
                        </form>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
