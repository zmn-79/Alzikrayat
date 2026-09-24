<?php

$pageTitle = 'Upload Photo';
require __DIR__ . '/../layout/header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-7 col-lg-6">
        <h2 class="mb-3">Upload a Photo</h2>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <ul class="mb-0">
                    <?php foreach ($errors as $error): ?>
                        <li><?= htmlspecialchars($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form id="uploadForm" method="POST" action="/photo/store" enctype="multipart/form-data" novalidate>
            <div class="mb-3">
                <label for="title" class="form-label">Title</label>
                <input type="text" class="form-control" id="title" name="title" maxlength="200" required
                       value="<?= htmlspecialchars($old['title'] ?? '') ?>">
                <div class="invalid-feedback">A title is required (max 200 characters).</div>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Description <span class="text-muted">(optional)</span></label>
                <textarea class="form-control" id="description" name="description" rows="3"><?= htmlspecialchars($old['description'] ?? '') ?></textarea>
            </div>

            <div class="mb-3">
                <label for="photo" class="form-label">Photo</label>
                <input type="file" class="form-control" id="photo" name="photo" accept="image/jpeg,image/png,image/gif,image/webp" required>
                <div class="invalid-feedback">Please choose a JPEG, PNG, GIF, or WEBP image under 5&nbsp;MB.</div>
                <div class="form-text">Accepted formats: JPEG, PNG, GIF, WEBP. Max size: 5&nbsp;MB.</div>
            </div>

            <button type="submit" class="btn btn-primary w-100">Upload</button>
        </form>
    </div>
</div>

<script>
// التحقق من العنوان والصورة في المتصفح قبل الرفع
(function () {
    var form = document.getElementById('uploadForm');
    var maxSize = 5 * 1024 * 1024;
    var allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];

    form.addEventListener('submit', function (event) {
        var title = document.getElementById('title');
        var photo = document.getElementById('photo');
        var valid = true;

        [title, photo].forEach(function (field) {
            field.classList.remove('is-invalid');
        });

        if (title.value.trim() === '' || title.value.length > 200) {
            title.classList.add('is-invalid');
            valid = false;
        }

        if (photo.files.length === 0) {
            photo.classList.add('is-invalid');
            valid = false;
        } else {
            var file = photo.files[0];
            if (allowedTypes.indexOf(file.type) === -1 || file.size > maxSize) {
                photo.classList.add('is-invalid');
                valid = false;
            }
        }

        if (!valid) {
            event.preventDefault();
        }
    });
})();
</script>

<?php require __DIR__ . '/../layout/footer.php'; ?>
