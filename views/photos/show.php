<?php

$pageTitle = $photo['title'];
require __DIR__ . '/../layout/header.php';
?>

<div class="row">
    <div class="col-lg-8">
        <img src="/images/uploads/<?= htmlspecialchars($photo['file_name']) ?>"
             class="img-fluid rounded mb-3" alt="<?= htmlspecialchars($photo['title']) ?>">
    </div>
    <div class="col-lg-4">
        <h2><?= htmlspecialchars($photo['title']) ?></h2>
        <p class="text-muted mb-1">
            By <?= htmlspecialchars($photo['author_first_name'] . ' ' . $photo['author_last_name']) ?>
        </p>
        <p class="text-muted"><small><?= htmlspecialchars($photo['date_time']) ?></small></p>

        <?php if (!empty($photo['description'])): ?>
            <p><?= nl2br(htmlspecialchars($photo['description'])) ?></p>
        <?php endif; ?>

        <?php if ($currentUser !== null && (int) $currentUser['id'] === (int) $photo['user_id']): ?>
            <a href="/photo/<?= (int) $photo['id'] ?>/delete"
               class="btn btn-outline-danger btn-sm"
               onclick="return confirm('Delete this photo? This cannot be undone.');">
                Delete Photo
            </a>
        <?php endif; ?>
    </div>
</div>

<hr class="my-4">

<div id="comments-section">
    <h4>Comments (<span id="comment-count"><?= count($comments) ?></span>)</h4>

    <ul id="comment-list" class="list-group mb-3">
        <?php foreach ($comments as $comment): ?>
            <li class="list-group-item">
                <strong><?= htmlspecialchars($comment['author_first_name'] . ' ' . $comment['author_last_name']) ?></strong>
                <small class="text-muted">— <?= htmlspecialchars($comment['date_time']) ?></small>
                <p class="mb-0"><?= nl2br(htmlspecialchars($comment['comment'])) ?></p>
            </li>
        <?php endforeach; ?>
    </ul>

    <?php if ($currentUser !== null): ?>
        <form id="commentForm" novalidate>
            <input type="hidden" name="photo_id" value="<?= (int) $photo['id'] ?>">
            <div class="mb-2">
                <label for="comment" class="form-label">Add a comment</label>
                <textarea class="form-control" id="comment" name="comment" rows="2" required></textarea>
                <div class="invalid-feedback">Please enter a comment.</div>
            </div>
            <div id="comment-error" class="text-danger small mb-2" style="display:none;"></div>
            <button type="submit" class="btn btn-primary btn-sm">Post Comment</button>
        </form>
    <?php else: ?>
        <p class="text-muted"><a href="/login">Log in</a> to add a comment.</p>
    <?php endif; ?>
</div>

<script>
// إرسال التعليق بدون إعادة تحميل الصفحة وإضافته للقائمة مباشرة
(function () {
    var form = document.getElementById('commentForm');
    if (!form) {
        return;
    }

    var textarea = document.getElementById('comment');
    var errorBox = document.getElementById('comment-error');
    var list = document.getElementById('comment-list');
    var countBadge = document.getElementById('comment-count');

    // تنظيف النص من أي وسوم HTML قبل عرضه (حماية من XSS)
    function escapeHtml(str) {
        var div = document.createElement('div');
        div.textContent = str;
        return div.innerHTML;
    }

    form.addEventListener('submit', function (event) {
        event.preventDefault();
        errorBox.style.display = 'none';
        textarea.classList.remove('is-invalid');

        var text = textarea.value.trim();
        if (text === '') {
            textarea.classList.add('is-invalid');
            return;
        }

        var formData = new FormData(form);

        fetch('/comment/store', {
            method: 'POST',
            body: formData
        })
            .then(function (response) {
                return response.json().then(function (data) {
                    return { ok: response.ok, data: data };
                });
            })
            .then(function (result) {
                if (!result.ok || !result.data.success) {
                    var message = (result.data.errors && result.data.errors[0]) ||
                                   result.data.error || 'Could not post comment.';
                    errorBox.textContent = message;
                    errorBox.style.display = 'block';
                    return;
                }

                var comment = result.data.comment;
                var li = document.createElement('li');
                li.className = 'list-group-item';
                li.innerHTML = '<strong>' + escapeHtml(comment.author_first_name + ' ' + comment.author_last_name) + '</strong>' +
                    ' <small class="text-muted">— ' + escapeHtml(comment.date_time) + '</small>' +
                    '<p class="mb-0">' + escapeHtml(comment.comment).replace(/\n/g, '<br>') + '</p>';
                list.appendChild(li);

                countBadge.textContent = parseInt(countBadge.textContent, 10) + 1;
                textarea.value = '';
            })
            .catch(function () {
                errorBox.textContent = 'Network error — please try again.';
                errorBox.style.display = 'block';
            });
    });
})();
</script>

<?php require __DIR__ . '/../layout/footer.php'; ?>
