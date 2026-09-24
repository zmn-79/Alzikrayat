<?php

$pageTitle = 'Gallery';
require __DIR__ . '/../layout/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
    <h2 class="mb-0">Photo Gallery</h2>
    <?php if ($currentUser !== null): ?>
        <a href="/photo/upload" class="btn btn-primary">Upload Photo</a>
    <?php endif; ?>
</div>

<?php if (!empty($photos)): ?>
    <div class="btn-group mb-4" role="group" aria-label="Gallery display style" id="galleryStyleSwitcher">
        <button type="button" class="btn btn-outline-secondary active" data-style="grid-3">3-Column</button>
        <button type="button" class="btn btn-outline-secondary" data-style="grid-4">4-Column</button>
        <button type="button" class="btn btn-outline-secondary" data-style="list">List</button>
        <button type="button" class="btn btn-outline-secondary" data-style="slider">Slider</button>
    </div>
<?php endif; ?>

<?php if (empty($photos)): ?>
    <p class="text-muted">No photos have been uploaded yet.</p>
<?php else: ?>
    <div id="galleryContainer" class="row row-cols-1 row-cols-md-3 g-4 gallery-grid-3">
        <?php foreach ($photos as $photo): ?>
            <div class="col gallery-item">
                <div class="card h-100">
                    <a href="/photo/<?= (int) $photo['id'] ?>">
                        <img src="/images/uploads/<?= htmlspecialchars($photo['file_name']) ?>"
                             class="card-img-top" alt="<?= htmlspecialchars($photo['title']) ?>">
                    </a>
                    <div class="card-body">
                        <h5 class="card-title">
                            <a href="/photo/<?= (int) $photo['id'] ?>" class="text-decoration-none">
                                <?= htmlspecialchars($photo['title']) ?>
                            </a>
                        </h5>
                        <p class="card-text text-muted mb-0">
                            By <?= htmlspecialchars($photo['author_first_name'] . ' ' . $photo['author_last_name']) ?>
                        </p>
                        <p class="card-text"><small class="text-muted"><?= htmlspecialchars($photo['date_time']) ?></small></p>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<style>
    .gallery-grid-3 .card-img-top,
    .gallery-grid-4 .card-img-top {
        height: 220px;
        object-fit: cover;
    }
</style>

<script>
// تغيير شكل عرض الجاليري (3 أعمدة / 4 أعمدة / قائمة / سلايدر) بدون إعادة تحميل الصفحة
(function () {
    var switcher = document.getElementById('galleryStyleSwitcher');
    if (!switcher) {
        return;
    }

    var container = document.getElementById('galleryContainer');
    var buttons = switcher.querySelectorAll('button');

    var styleClasses = {
        'grid-3': 'row row-cols-1 row-cols-md-3 g-4 gallery-grid-3',
        'grid-4': 'row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4 gallery-grid-4',
        'list':   'gallery-list',
        'slider': 'gallery-slider'
    };

    buttons.forEach(function (button) {
        button.addEventListener('click', function () {
            buttons.forEach(function (b) { b.classList.remove('active'); });
            button.classList.add('active');

            var style = button.getAttribute('data-style');
            container.id = 'galleryContainer';
            container.className = styleClasses[style];
        });
    });
})();
</script>

<?php require __DIR__ . '/../layout/footer.php'; ?>
