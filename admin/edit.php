<?php
require_once __DIR__ . '/../inc/config.php';
require_once __DIR__ . '/../inc/db.php';
require_once __DIR__ . '/../inc/csrf.php';
if (session_status() !== PHP_SESSION_ACTIVE) { session_start(); }
if (!isset($_SESSION['admin'])) { header('Location: index.php'); exit; }
$id = $_GET['id'] ?? null;
if (!$id) { header('Location: dashboard.php'); exit; }
$news = getNewsById($id);
if (!$news) { echo 'Știre inexistentă.'; exit; }
$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_check()) { $msg = 'CSRF token invalid.'; }
    else {
        $title = $_POST['title'] ?? '';
        $content = $_POST['content'] ?? '';
        if ($title && $content) {
            require_once __DIR__ . '/../inc/sanitize.php';
            $content = sanitize_html($content);
            updateNews($id, $title, $content);
            $msg = 'Știre actualizată.';
            $news = getNewsById($id);
        } else {
            $msg = 'Completați toate câmpurile.';
        }
    }
}
require_once __DIR__ . '/../inc/header.php';
?>
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card p-4">
            <h3>Edit știre</h3>
            <?php if ($msg) echo '<div class="alert alert-info">' . htmlspecialchars($msg) . '</div>'; ?>
            <form method="post">
                <?php echo csrf_field(); ?>
                <div class="mb-3">
                    <label class="form-label">Titlu</label>
                    <input class="form-control" name="title" value="<?= htmlspecialchars($news['title']) ?>" />
                </div>
                <div class="mb-3">
                    <label class="form-label">Conținut</label>
                    <div id="quill-editor" style="height:300px;"></div>
                    <input type="hidden" name="content" id="content-input" />
                </div>
                <button class="btn btn-primary" type="submit">Salvează</button>
                <a class="btn btn-secondary ms-2" href="dashboard.php">Înapoi</a>
            </form>
        </div>
    </div>
</div>
<!-- Quill WYSIWYG (free, open-source) -->
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
<script>
    var toolbarOptions = [['bold','italic','underline'], [{ 'list': 'ordered'}, { 'list': 'bullet' }], ['link','image']];
    var quill = new Quill('#quill-editor', { theme: 'snow', modules: { toolbar: toolbarOptions } });
    // Set initial content
    quill.root.innerHTML = <?= json_encode($news['content']) ?>;

    // Image upload handler (same as dashboard)
    function uploadImage(file, cb) {
        var form = new FormData();
        form.append('image', file);
    var xhr = new XMLHttpRequest();
    xhr.open('POST', '<?= base_url() ?>/admin/upload.php', true);
        xhr.onload = function() {
            if (xhr.status === 200) {
                try {
                    var res = JSON.parse(xhr.responseText);
                    if (res && res.url) cb(null, res.url);
                    else cb(new Error('Invalid response'));
                } catch (e) { cb(e); }
            } else {
                cb(new Error('Upload failed: ' + xhr.status));
            }
        };
        xhr.send(form);
    }
    quill.getModule('toolbar').addHandler('image', function() {
        var input = document.createElement('input');
        input.setAttribute('type', 'file');
        input.setAttribute('accept', 'image/*');
        input.onchange = function() {
            var file = input.files[0];
            if (!file) return;
            uploadImage(file, function(err, url) {
                if (err) { alert('Upload failed'); return; }
                var range = quill.getSelection(true);
                quill.insertEmbed(range.index, 'image', url);
                quill.setSelection(range.index + 1);
            });
        };
        input.click();
    });

    // On submit, copy HTML content to hidden input
    document.querySelector('form').addEventListener('submit', function(e){
        var html = quill.root.innerHTML;
        document.getElementById('content-input').value = html;
    });
</script>
<?php require_once __DIR__ . '/../inc/footer.php';
