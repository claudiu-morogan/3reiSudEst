<?php
require_once APP_ROOT . '/app/models/Album.php';

// Handle form submissions
$message = null;
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'create_album') {
        try {
            Album::create([
                'title' => $_POST['title'],
                'release_year' => (int)$_POST['release_year'],
                'cover_image' => $_POST['cover_image'] ?? null,
                'description' => $_POST['description'] ?? null,
                'spotify_url' => $_POST['spotify_url'] ?? null,
                'apple_music_url' => $_POST['apple_music_url'] ?? null,
                'sort_order' => (int)($_POST['sort_order'] ?? 0),
                'is_published' => isset($_POST['is_published']) ? 1 : 0
            ]);
            $message = 'Album adăugat cu succes!';
        } catch (Exception $e) {
            $error = 'Eroare la adăugarea albumului: ' . $e->getMessage();
        }
    } elseif ($action === 'update_album') {
        try {
            Album::update((int)$_POST['id'], [
                'title' => $_POST['title'],
                'release_year' => (int)$_POST['release_year'],
                'cover_image' => $_POST['cover_image'] ?? null,
                'description' => $_POST['description'] ?? null,
                'spotify_url' => $_POST['spotify_url'] ?? null,
                'apple_music_url' => $_POST['apple_music_url'] ?? null,
                'sort_order' => (int)($_POST['sort_order'] ?? 0),
                'is_published' => isset($_POST['is_published']) ? 1 : 0
            ]);
            $message = 'Album actualizat cu succes!';
        } catch (Exception $e) {
            $error = 'Eroare la actualizarea albumului: ' . $e->getMessage();
        }
    } elseif ($action === 'delete_album') {
        try {
            Album::delete((int)$_POST['id']);
            $message = 'Album șters cu succes!';
        } catch (Exception $e) {
            $error = 'Eroare la ștergerea albumului: ' . $e->getMessage();
        }
    } elseif ($action === 'add_track') {
        try {
            Album::addTrack((int)$_POST['album_id'], [
                'title' => $_POST['track_title'],
                'track_number' => (int)$_POST['track_number'],
                'duration' => $_POST['duration'] ?? null,
                'youtube_url' => $_POST['youtube_url'] ?? null
            ]);
            $message = 'Melodie adăugată cu succes!';
        } catch (Exception $e) {
            $error = 'Eroare la adăugarea melodiei: ' . $e->getMessage();
        }
    } elseif ($action === 'delete_track') {
        try {
            Album::deleteTrack((int)$_POST['track_id']);
            $message = 'Melodie ștearsă cu succes!';
        } catch (Exception $e) {
            $error = 'Eroare la ștergerea melodiei: ' . $e->getMessage();
        }
    }
}

// Get all albums (including unpublished)
$albums = Album::getAll(true);

// Check if editing album
$editingAlbum = null;
if (isset($_GET['edit'])) {
    $editingAlbum = Album::getById((int)$_GET['edit']);
}

// Check if managing tracks for an album
$managingTracks = null;
if (isset($_GET['tracks'])) {
    $managingTracks = Album::getWithTracks((int)$_GET['tracks']);
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

    .btn-success {
        background: #28a745;
        color: #fff;
    }

    .btn-success:hover {
        background: #218838;
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
        min-height: 100px;
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

    .table-container {
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        margin-bottom: 2rem;
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

    .album-cover-thumb {
        width: 50px;
        height: 50px;
        object-fit: cover;
        border-radius: 4px;
    }

    .tracks-section {
        background: #f8f9fa;
        padding: 1.5rem;
        border-radius: 8px;
        margin-top: 1rem;
    }

    .tracks-section h5 {
        margin-bottom: 1rem;
        color: #1d1d1f;
    }

    .track-item {
        background: #fff;
        padding: 0.75rem 1rem;
        border-radius: 6px;
        margin-bottom: 0.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .track-info {
        flex: 1;
    }

    .track-number {
        display: inline-block;
        width: 30px;
        color: #6c757d;
        font-weight: 600;
    }

    .track-title {
        font-weight: 500;
        color: #1d1d1f;
    }

    .track-duration {
        color: #6c757d;
        font-size: 0.875rem;
        margin-left: 1rem;
    }

    .breadcrumb {
        margin-bottom: 1.5rem;
        display: flex;
        gap: 0.5rem;
        align-items: center;
        color: #6c757d;
        font-size: 0.9375rem;
    }

    .breadcrumb a {
        color: #007aff;
        text-decoration: none;
    }

    .breadcrumb a:hover {
        text-decoration: underline;
    }
</style>

<?php if ($message): ?>
    <div class="alert alert-success"><?= htmlspecialchars($message) ?></div>
<?php endif; ?>

<?php if ($error): ?>
    <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<?php if ($managingTracks): ?>
    <!-- Track Management View -->
    <div class="breadcrumb">
        <a href="<?= base_url('admin/?page=discography') ?>">← Înapoi la Discografie</a>
        <span>/</span>
        <span><?= htmlspecialchars($managingTracks['title']) ?></span>
    </div>

    <div class="content-header">
        <h3>Melodii - <?= htmlspecialchars($managingTracks['title']) ?></h3>
    </div>

    <!-- Add Track Form -->
    <div class="form-card">
        <h4 style="margin-bottom: 1.5rem;">Adaugă Melodie Nouă</h4>

        <form method="POST">
            <input type="hidden" name="action" value="add_track">
            <input type="hidden" name="album_id" value="<?= $managingTracks['id'] ?>">

            <div class="form-row">
                <div class="form-group">
                    <label for="track_number">Număr Piesă *</label>
                    <input type="number" id="track_number" name="track_number" required
                           value="<?= count($managingTracks['tracks']) + 1 ?>"
                           min="1">
                </div>

                <div class="form-group">
                    <label for="duration">Durată (ex: 3:45)</label>
                    <input type="text" id="duration" name="duration"
                           placeholder="3:45"
                           pattern="[0-9]{1,2}:[0-9]{2}">
                </div>
            </div>

            <div class="form-group">
                <label for="track_title">Titlu Melodie *</label>
                <input type="text" id="track_title" name="track_title" required
                       placeholder="ex: Clipe">
            </div>

            <div class="form-group">
                <label for="youtube_url">URL YouTube</label>
                <input type="text" id="youtube_url" name="youtube_url"
                       placeholder="https://www.youtube.com/watch?v=...">
            </div>

            <button type="submit" class="btn btn-success">
                + Adaugă Melodie
            </button>
        </form>
    </div>

    <!-- Tracks List -->
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Titlu</th>
                    <th>Durată</th>
                    <th>YouTube</th>
                    <th>Acțiuni</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($managingTracks['tracks'])): ?>
                    <tr>
                        <td colspan="5" style="text-align: center; color: #86868b; padding: 2rem;">
                            Nu există melodii adăugate pentru acest album.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($managingTracks['tracks'] as $track): ?>
                        <tr>
                            <td><strong><?= htmlspecialchars($track['track_number']) ?></strong></td>
                            <td><?= htmlspecialchars($track['title']) ?></td>
                            <td><?= htmlspecialchars($track['duration'] ?: '—') ?></td>
                            <td>
                                <?php if ($track['youtube_url']): ?>
                                    <a href="<?= htmlspecialchars($track['youtube_url']) ?>"
                                       target="_blank"
                                       class="btn btn-primary btn-small">
                                        ▶ Vezi
                                    </a>
                                <?php else: ?>
                                    —
                                <?php endif; ?>
                            </td>
                            <td>
                                <form method="POST" style="display: inline;"
                                      onsubmit="return confirm('Sigur vrei să ștergi această melodie?');">
                                    <input type="hidden" name="action" value="delete_track">
                                    <input type="hidden" name="track_id" value="<?= $track['id'] ?>">
                                    <button type="submit" class="btn btn-danger btn-small">Șterge</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

<?php else: ?>
    <!-- Album Management View -->
    <div class="content-header">
        <h3>Discografie</h3>
        <?php if (!$editingAlbum): ?>
            <button class="btn btn-primary" onclick="document.getElementById('albumForm').scrollIntoView({behavior: 'smooth'})">
                + Adaugă Album
            </button>
        <?php endif; ?>
    </div>

    <!-- Album Form -->
    <div class="form-card" id="albumForm">
        <h4 style="margin-bottom: 1.5rem;"><?= $editingAlbum ? 'Editează Album' : 'Album Nou' ?></h4>

        <form method="POST">
            <input type="hidden" name="action" value="<?= $editingAlbum ? 'update_album' : 'create_album' ?>">
            <?php if ($editingAlbum): ?>
                <input type="hidden" name="id" value="<?= $editingAlbum['id'] ?>">
            <?php endif; ?>

            <div class="form-row">
                <div class="form-group">
                    <label for="title">Titlu *</label>
                    <input type="text" id="title" name="title" required
                           value="<?= htmlspecialchars($editingAlbum['title'] ?? '') ?>">
                </div>

                <div class="form-group">
                    <label for="release_year">An Lansare *</label>
                    <input type="number" id="release_year" name="release_year" required
                           value="<?= htmlspecialchars($editingAlbum['release_year'] ?? date('Y')) ?>"
                           min="1990" max="<?= date('Y') + 1 ?>">
                </div>
            </div>

            <div class="form-group">
                <label for="cover_image">URL Copertă</label>
                <input type="text" id="cover_image" name="cover_image"
                       value="<?= htmlspecialchars($editingAlbum['cover_image'] ?? '') ?>"
                       placeholder="https://example.com/album-cover.jpg">
            </div>

            <div class="form-group">
                <label for="description">Descriere</label>
                <textarea id="description" name="description"><?= htmlspecialchars($editingAlbum['description'] ?? '') ?></textarea>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="spotify_url">URL Spotify</label>
                    <input type="text" id="spotify_url" name="spotify_url"
                           value="<?= htmlspecialchars($editingAlbum['spotify_url'] ?? '') ?>"
                           placeholder="https://open.spotify.com/album/...">
                </div>

                <div class="form-group">
                    <label for="apple_music_url">URL Apple Music</label>
                    <input type="text" id="apple_music_url" name="apple_music_url"
                           value="<?= htmlspecialchars($editingAlbum['apple_music_url'] ?? '') ?>"
                           placeholder="https://music.apple.com/album/...">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="sort_order">Ordine Afișare</label>
                    <input type="number" id="sort_order" name="sort_order"
                           value="<?= htmlspecialchars($editingAlbum['sort_order'] ?? 0) ?>"
                           min="0">
                </div>

                <div class="form-group">
                    <label>&nbsp;</label>
                    <div class="checkbox-group">
                        <input type="checkbox" id="is_published" name="is_published"
                               <?= ($editingAlbum['is_published'] ?? 1) ? 'checked' : '' ?>>
                        <label for="is_published" style="margin: 0;">Publicat</label>
                    </div>
                </div>
            </div>

            <div style="display: flex; gap: 0.75rem; margin-top: 1.5rem;">
                <button type="submit" class="btn btn-primary">
                    <?= $editingAlbum ? 'Actualizează' : 'Adaugă' ?> Album
                </button>
                <?php if ($editingAlbum): ?>
                    <a href="<?= base_url('admin/?page=discography') ?>" class="btn btn-secondary">
                        Anulează
                    </a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <!-- Albums List -->
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Copertă</th>
                    <th>Titlu</th>
                    <th>An</th>
                    <th>Melodii</th>
                    <th>Status</th>
                    <th>Acțiuni</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($albums)): ?>
                    <tr>
                        <td colspan="6" style="text-align: center; color: #86868b; padding: 2rem;">
                            Nu există albume adăugate încă.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($albums as $album): ?>
                        <?php $albumWithTracks = Album::getWithTracks($album['id']); ?>
                        <tr>
                            <td>
                                <?php if ($album['cover_image']): ?>
                                    <img src="<?= htmlspecialchars($album['cover_image']) ?>"
                                         alt="Cover" class="album-cover-thumb">
                                <?php else: ?>
                                    <div style="width: 50px; height: 50px; background: #e5e5ea; border-radius: 4px;"></div>
                                <?php endif; ?>
                            </td>
                            <td><strong><?= htmlspecialchars($album['title']) ?></strong></td>
                            <td><?= htmlspecialchars($album['release_year']) ?></td>
                            <td>
                                <a href="<?= base_url('admin/?page=discography&tracks=' . $album['id']) ?>"
                                   class="btn btn-success btn-small">
                                    🎵 <?= count($albumWithTracks['tracks']) ?> melodii
                                </a>
                            </td>
                            <td>
                                <?php if ($album['is_published']): ?>
                                    <span class="badge badge-success">Publicat</span>
                                <?php else: ?>
                                    <span class="badge badge-secondary">Draft</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="<?= base_url('admin/?page=discography&edit=' . $album['id']) ?>"
                                   class="btn btn-primary btn-small">Editează</a>

                                <form method="POST" style="display: inline;"
                                      onsubmit="return confirm('Sigur vrei să ștergi acest album? Toate melodiile vor fi șterse.');">
                                    <input type="hidden" name="action" value="delete_album">
                                    <input type="hidden" name="id" value="<?= $album['id'] ?>">
                                    <button type="submit" class="btn btn-danger btn-small">Șterge</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>
