<?php

$pageTitle = 'Home';
require __DIR__ . '/../layout/header.php';
?>

<div class="hero-section mb-5">
    <div class="row align-items-center">
        <div class="col-lg-7">
            <h1 class="display-5 fw-bold">Alzikrayat</h1>
            <p class="lead">
                Alzikrayat ("our memories") is a simple photo-sharing space where you
                can upload the moments that matter, describe the story behind them,
                and comment on the memories your friends share back.
            </p>
            <div class="d-flex flex-wrap gap-2 mt-3">
                <a href="/photos" class="btn btn-light">Explore the Gallery</a>
                <?php if ($currentUser === null): ?>
                    <a href="/register" class="btn btn-outline-light">Create an Account</a>
                <?php else: ?>
                    <a href="/photo/upload" class="btn btn-outline-light">Upload a Photo</a>
                <?php endif; ?>
                <a href="/about" class="btn btn-outline-light">About Us</a>
            </div>
        </div>
    </div>
</div>

<div class="row mb-5 g-3">
    <div class="col-md-4">
        <div class="card stat-card p-3">
            <div class="stat-number text-primary"><?= (int) $stats['users'] ?></div>
            <div class="text-muted">Registered Members</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card stat-card p-3">
            <div class="stat-number text-primary"><?= (int) $stats['photos'] ?></div>
            <div class="text-muted">Photos Shared</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card stat-card p-3">
            <div class="stat-number text-primary"><?= (int) $stats['comments'] ?></div>
            <div class="text-muted">Comments Exchanged</div>
        </div>
    </div>
</div>

<h2 class="mb-3">Recent Memories</h2>
<?php if (empty($recentPhotos)): ?>
    <p class="text-muted">No photos have been shared yet — be the first to upload one!</p>
<?php else: ?>
    <div class="row row-cols-2 row-cols-md-3 row-cols-lg-6 g-3 mb-4">
        <?php foreach ($recentPhotos as $photo): ?>
            <div class="col">
                <a href="/photo/<?= (int) $photo['id'] ?>">
                    <img src="/images/uploads/<?= htmlspecialchars($photo['file_name']) ?>"
                         class="img-fluid rounded" alt="<?= htmlspecialchars($photo['title']) ?>"
                         style="height: 120px; width: 100%; object-fit: cover;">
                </a>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php require __DIR__ . '/../layout/footer.php'; ?>
