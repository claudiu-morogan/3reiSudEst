<?php
require_once APP_ROOT . '/app/models/Concert.php';

// Handle form submissions
$message = null;
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'create') {
        try {
            Concert::create([
                'title' => $_POST['title'],
                'venue' => $_POST['venue'],
                'city' => $_POST['city'],
                'country' => $_POST['country'] ?? 'România',
                'event_date' => $_POST['event_date'],
                'event_time' => $_POST['event_time'] ?? null,
                'description' => $_POST['description'] ?? null,
                'ticket_url' => $_POST['ticket_url'] ?? null,
                'venue_map_url' => $_POST['venue_map_url'] ?? null,
                'poster_image' => $_POST['poster_image'] ?? null,
                'price_info' => $_POST['price_info'] ?? null,
                'status' => $_POST['status'] ?? 'upcoming',
                'is_published' => isset($_POST['is_published']) ? 1 : 0
            ]);
            $message = 'Concert adăugat cu succes!';
        } catch (Exception $e) {
            $error = 'Eroare la adăugarea concertului: ' . $e->getMessage();
        }
    } elseif ($action === 'update') {
        try {
            Concert::update((int)$_POST['id'], [
                'title' => $_POST['title'],
                'venue' => $_POST['venue'],
                'city' => $_POST['city'],
                'country' => $_POST['country'] ?? 'România',
                'event_date' => $_POST['event_date'],
                'event_time' => $_POST['event_time'] ?? null,
                'description' => $_POST['description'] ?? null,
                'ticket_url' => $_POST['ticket_url'] ?? null,
                'venue_map_url' => $_POST['venue_map_url'] ?? null,
                'poster_image' => $_POST['poster_image'] ?? null,
                'price_info' => $_POST['price_info'] ?? null,
                'status' => $_POST['status'] ?? 'upcoming',
                'is_published' => isset($_POST['is_published']) ? 1 : 0
            ]);
            $message = 'Concert actualizat cu succes!';
        } catch (Exception $e) {
            $error = 'Eroare la actualizarea concertului: ' . $e->getMessage();
        }
    } elseif ($action === 'delete') {
        try {
            Concert::delete((int)$_POST['id']);
            $message = 'Concert șters cu succes!';
        } catch (Exception $e) {
            $error = 'Eroare la ștergerea concertului: ' . $e->getMessage();
        }
    }
}

// Get all concerts (including unpublished)
$concerts = Concert::getAll(true, false);

// Check if editing
$editingConcert = null;
if (isset($_GET['edit'])) {
    $editingConcert = Concert::getById((int)$_GET['edit']);
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
        display: inline-block;
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
    .form-group input[type="date"],
    .form-group input[type="time"],
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
        min-height: 100px;
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

    .form-row-triple {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
        gap: 1rem;
    }

    .table-container {
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        overflow-x: auto;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        min-width: 900px;
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
        white-space: nowrap;
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
        white-space: nowrap;
    }

    .badge-success {
        background: #d4edda;
        color: #155724;
    }

    .badge-secondary {
        background: #e2e3e5;
        color: #383d41;
    }

    .badge-primary {
        background: #cfe2ff;
        color: #084298;
    }

    .badge-warning {
        background: #fff3cd;
        color: #856404;
    }

    .badge-danger {
        background: #f8d7da;
        color: #721c24;
    }

    .concert-date {
        font-weight: 600;
        color: #1d1d1f;
    }

    .concert-time {
        color: #86868b;
        font-size: 0.875rem;
    }

    .concert-location {
        color: #1d1d1f;
    }

    .concert-venue {
        color: #86868b;
        font-size: 0.875rem;
    }

    .status-cell {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }
</style>

<?php if ($message): ?>
    <div class="alert alert-success"><?= htmlspecialchars($message) ?></div>
<?php endif; ?>

<?php if ($error): ?>
    <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<div class="content-header">
    <h3>Concerte</h3>
    <?php if (!$editingConcert): ?>
        <button class="btn btn-primary" onclick="document.getElementById('concertForm').scrollIntoView({behavior: 'smooth'})">
            + Adaugă Concert
        </button>
    <?php endif; ?>
</div>

<!-- Concert Form -->
<div class="form-card" id="concertForm">
    <h4 style="margin-bottom: 1.5rem;"><?= $editingConcert ? 'Editează Concert' : 'Concert Nou' ?></h4>

    <form method="POST">
        <input type="hidden" name="action" value="<?= $editingConcert ? 'update' : 'create' ?>">
        <?php if ($editingConcert): ?>
            <input type="hidden" name="id" value="<?= $editingConcert['id'] ?>">
        <?php endif; ?>

        <div class="form-group">
            <label for="title">Titlu Concert *</label>
            <input type="text" id="title" name="title" required
                   value="<?= htmlspecialchars($editingConcert['title'] ?? '') ?>"
                   placeholder="ex: 3 Sud Est - Concert de Crăciun">
        </div>

        <div class="form-row-triple">
            <div class="form-group">
                <label for="event_date">Data *</label>
                <input type="date" id="event_date" name="event_date" required
                       value="<?= htmlspecialchars($editingConcert['event_date'] ?? '') ?>">
            </div>

            <div class="form-group">
                <label for="event_time">Ora</label>
                <input type="time" id="event_time" name="event_time"
                       value="<?= htmlspecialchars($editingConcert['event_time'] ?? '') ?>">
            </div>

            <div class="form-group">
                <label for="status">Status *</label>
                <select id="status" name="status" required>
                    <option value="upcoming" <?= ($editingConcert['status'] ?? 'upcoming') === 'upcoming' ? 'selected' : '' ?>>Viitor</option>
                    <option value="sold_out" <?= ($editingConcert['status'] ?? '') === 'sold_out' ? 'selected' : '' ?>>Sold Out</option>
                    <option value="cancelled" <?= ($editingConcert['status'] ?? '') === 'cancelled' ? 'selected' : '' ?>>Anulat</option>
                    <option value="completed" <?= ($editingConcert['status'] ?? '') === 'completed' ? 'selected' : '' ?>>Trecut</option>
                </select>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="venue">Locație/Sală *</label>
                <input type="text" id="venue" name="venue" required
                       value="<?= htmlspecialchars($editingConcert['venue'] ?? '') ?>"
                       placeholder="ex: Sala Palatului">
            </div>

            <div class="form-group">
                <label for="city">Oraș *</label>
                <input type="text" id="city" name="city" required
                       value="<?= htmlspecialchars($editingConcert['city'] ?? '') ?>"
                       placeholder="ex: București">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="country">Țară</label>
                <input type="text" id="country" name="country"
                       value="<?= htmlspecialchars($editingConcert['country'] ?? 'România') ?>"
                       placeholder="România">
            </div>

            <div class="form-group">
                <label for="price_info">Informații Preț</label>
                <input type="text" id="price_info" name="price_info"
                       value="<?= htmlspecialchars($editingConcert['price_info'] ?? '') ?>"
                       placeholder="ex: 100-250 RON">
            </div>
        </div>

        <div class="form-group">
            <label for="description">Descriere</label>
            <textarea id="description" name="description"><?= htmlspecialchars($editingConcert['description'] ?? '') ?></textarea>
        </div>

        <div class="form-group">
            <label for="poster_image">URL Poster</label>
            <input type="text" id="poster_image" name="poster_image"
                   value="<?= htmlspecialchars($editingConcert['poster_image'] ?? '') ?>"
                   placeholder="https://example.com/concert-poster.jpg">
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="ticket_url">Link Bilete</label>
                <input type="text" id="ticket_url" name="ticket_url"
                       value="<?= htmlspecialchars($editingConcert['ticket_url'] ?? '') ?>"
                       placeholder="https://bilete.ro/...">
            </div>

            <div class="form-group">
                <label for="venue_map_url">Link Hartă Locație</label>
                <input type="text" id="venue_map_url" name="venue_map_url"
                       value="<?= htmlspecialchars($editingConcert['venue_map_url'] ?? '') ?>"
                       placeholder="https://maps.google.com/...">
            </div>
        </div>

        <div class="form-group">
            <div class="checkbox-group">
                <input type="checkbox" id="is_published" name="is_published"
                       <?= ($editingConcert['is_published'] ?? 1) ? 'checked' : '' ?>>
                <label for="is_published" style="margin: 0;">Publicat</label>
            </div>
        </div>

        <div style="display: flex; gap: 0.75rem; margin-top: 1.5rem;">
            <button type="submit" class="btn btn-primary">
                <?= $editingConcert ? 'Actualizează' : 'Adaugă' ?> Concert
            </button>
            <?php if ($editingConcert): ?>
                <a href="<?= base_url('admin/?page=concerts') ?>" class="btn btn-secondary">
                    Anulează
                </a>
            <?php endif; ?>
        </div>
    </form>
</div>

<!-- Concerts List -->
<div class="table-container">
    <table>
        <thead>
            <tr>
                <th>Data & Ora</th>
                <th>Titlu</th>
                <th>Locație</th>
                <th>Preț</th>
                <th>Status</th>
                <th>Publicat</th>
                <th>Acțiuni</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($concerts)): ?>
                <tr>
                    <td colspan="7" style="text-align: center; color: #86868b; padding: 2rem;">
                        Nu există concerte adăugate încă.
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach ($concerts as $concert): ?>
                    <tr>
                        <td>
                            <div class="concert-date">
                                <?= Concert::formatDate($concert['event_date']) ?>
                            </div>
                            <?php if ($concert['event_time']): ?>
                                <div class="concert-time">
                                    <?= Concert::formatTime($concert['event_time']) ?>
                                </div>
                            <?php endif; ?>
                        </td>
                        <td><strong><?= htmlspecialchars($concert['title']) ?></strong></td>
                        <td>
                            <div class="concert-location">
                                <?= htmlspecialchars($concert['city']) ?>
                                <?php if ($concert['country'] !== 'România'): ?>
                                    , <?= htmlspecialchars($concert['country']) ?>
                                <?php endif; ?>
                            </div>
                            <div class="concert-venue"><?= htmlspecialchars($concert['venue']) ?></div>
                        </td>
                        <td><?= htmlspecialchars($concert['price_info'] ?: '—') ?></td>
                        <td>
                            <?php $statusBadge = Concert::getStatusBadge($concert['status']); ?>
                            <span class="badge <?= $statusBadge['class'] ?>">
                                <?= $statusBadge['text'] ?>
                            </span>
                        </td>
                        <td>
                            <?php if ($concert['is_published']): ?>
                                <span class="badge badge-success">Da</span>
                            <?php else: ?>
                                <span class="badge badge-secondary">Nu</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="<?= base_url('admin/?page=concerts&edit=' . $concert['id']) ?>"
                               class="btn btn-primary btn-small">Editează</a>

                            <form method="POST" style="display: inline;"
                                  onsubmit="return confirm('Sigur vrei să ștergi acest concert?');">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?= $concert['id'] ?>">
                                <button type="submit" class="btn btn-danger btn-small">Șterge</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>
