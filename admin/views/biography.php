<?php
require_once APP_ROOT . '/app/models/Biography.php';

// Handle form submissions
$message = null;
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'create') {
        try {
            Biography::create([
                'title' => $_POST['title'],
                'content' => $_POST['content'],
                'year_from' => !empty($_POST['year_from']) ? (int)$_POST['year_from'] : null,
                'year_to' => !empty($_POST['year_to']) ? (int)$_POST['year_to'] : null,
                'sort_order' => (int)($_POST['sort_order'] ?? 0),
                'is_published' => isset($_POST['is_published']) ? 1 : 0
            ]);
            $message = 'Secțiune adăugată cu succes!';
        } catch (Exception $e) {
            $error = 'Eroare la adăugarea secțiunii: ' . $e->getMessage();
        }
    } elseif ($action === 'update') {
        try {
            Biography::update((int)$_POST['id'], [
                'title' => $_POST['title'],
                'content' => $_POST['content'],
                'year_from' => !empty($_POST['year_from']) ? (int)$_POST['year_from'] : null,
                'year_to' => !empty($_POST['year_to']) ? (int)$_POST['year_to'] : null,
                'sort_order' => (int)($_POST['sort_order'] ?? 0),
                'is_published' => isset($_POST['is_published']) ? 1 : 0
            ]);
            $message = 'Secțiune actualizată cu succes!';
        } catch (Exception $e) {
            $error = 'Eroare la actualizarea secțiunii: ' . $e->getMessage();
        }
    } elseif ($action === 'delete') {
        try {
            Biography::delete((int)$_POST['id']);
            $message = 'Secțiune ștearsă cu succes!';
        } catch (Exception $e) {
            $error = 'Eroare la ștergerea secțiunii: ' . $e->getMessage();
        }
    }
}

// Get all biography sections (including unpublished)
$sections = Biography::getAll(true);

// Check if editing
$editingSection = null;
if (isset($_GET['edit'])) {
    $editingSection = Biography::getById((int)$_GET['edit']);
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
        min-height: 200px;
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

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }

    .form-row-triple {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
        gap: 1rem;
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

    .content-preview {
        color: #86868b;
        font-size: 0.875rem;
        max-width: 400px;
        overflow: hidden;
        text-overflow: ellipsis;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
    }
</style>

<?php if ($message): ?>
    <div class="alert alert-success"><?= htmlspecialchars($message) ?></div>
<?php endif; ?>

<?php if ($error): ?>
    <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<div class="content-header">
    <h3>Biografie</h3>
    <?php if (!$editingSection): ?>
        <button class="btn btn-primary" onclick="document.getElementById('biographyForm').scrollIntoView({behavior: 'smooth'})">
            + Adaugă Secțiune
        </button>
    <?php endif; ?>
</div>

<!-- Biography Form -->
<div class="form-card" id="biographyForm">
    <h4 style="margin-bottom: 1.5rem;"><?= $editingSection ? 'Editează Secțiune' : 'Secțiune Nouă' ?></h4>

    <form method="POST">
        <input type="hidden" name="action" value="<?= $editingSection ? 'update' : 'create' ?>">
        <?php if ($editingSection): ?>
            <input type="hidden" name="id" value="<?= $editingSection['id'] ?>">
        <?php endif; ?>

        <div class="form-group">
            <label for="title">Titlu Secțiune *</label>
            <input type="text" id="title" name="title" required
                   value="<?= htmlspecialchars($editingSection['title'] ?? '') ?>"
                   placeholder="ex: Începuturile, Succesul International, etc.">
        </div>

        <div class="form-group">
            <label for="content">Conținut *</label>
            <textarea id="content" name="content" required><?= htmlspecialchars($editingSection['content'] ?? '') ?></textarea>
        </div>

        <div class="form-row-triple">
            <div class="form-group">
                <label for="year_from">An Început (opțional)</label>
                <input type="number" id="year_from" name="year_from"
                       value="<?= htmlspecialchars($editingSection['year_from'] ?? '') ?>"
                       placeholder="ex: 1997"
                       min="1990" max="<?= date('Y') ?>">
            </div>

            <div class="form-group">
                <label for="year_to">An Sfârșit (opțional)</label>
                <input type="number" id="year_to" name="year_to"
                       value="<?= htmlspecialchars($editingSection['year_to'] ?? '') ?>"
                       placeholder="ex: 2000"
                       min="1990" max="<?= date('Y') ?>">
            </div>

            <div class="form-group">
                <label for="sort_order">Ordine Afișare</label>
                <input type="number" id="sort_order" name="sort_order"
                       value="<?= htmlspecialchars($editingSection['sort_order'] ?? 0) ?>"
                       min="0">
            </div>
        </div>

        <div class="form-group">
            <div class="checkbox-group">
                <input type="checkbox" id="is_published" name="is_published"
                       <?= ($editingSection['is_published'] ?? 1) ? 'checked' : '' ?>>
                <label for="is_published" style="margin: 0;">Publicat</label>
            </div>
        </div>

        <div style="display: flex; gap: 0.75rem; margin-top: 1.5rem;">
            <button type="submit" class="btn btn-primary">
                <?= $editingSection ? 'Actualizează' : 'Adaugă' ?> Secțiune
            </button>
            <?php if ($editingSection): ?>
                <a href="<?= base_url('admin/?page=biography') ?>" class="btn btn-secondary">
                    Anulează
                </a>
            <?php endif; ?>
        </div>
    </form>
</div>

<!-- Biography Sections List -->
<div class="table-container">
    <table>
        <thead>
            <tr>
                <th>Titlu</th>
                <th>Perioadă</th>
                <th>Conținut</th>
                <th>Status</th>
                <th>Ordine</th>
                <th>Acțiuni</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($sections)): ?>
                <tr>
                    <td colspan="6" style="text-align: center; color: #86868b; padding: 2rem;">
                        Nu există secțiuni adăugate încă.
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach ($sections as $section): ?>
                    <tr>
                        <td><strong><?= htmlspecialchars($section['title']) ?></strong></td>
                        <td>
                            <?php if (($section['year_from'] ?? null) || ($section['year_to'] ?? null)): ?>
                                <?= $section['year_from'] ?? '?' ?> - <?= $section['year_to'] ?? 'prezent' ?>
                            <?php else: ?>
                                <span style="color: #86868b;">—</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="content-preview"><?= htmlspecialchars($section['content']) ?></div>
                        </td>
                        <td>
                            <?php if ($section['is_published']): ?>
                                <span class="badge badge-success">Publicat</span>
                            <?php else: ?>
                                <span class="badge badge-secondary">Draft</span>
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($section['sort_order']) ?></td>
                        <td>
                            <a href="<?= base_url('admin/?page=biography&edit=' . $section['id']) ?>"
                               class="btn btn-primary btn-small">Editează</a>

                            <form method="POST" style="display: inline;"
                                  onsubmit="return confirm('Sigur vrei să ștergi această secțiune?');">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?= $section['id'] ?>">
                                <button type="submit" class="btn btn-danger btn-small">Șterge</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>
