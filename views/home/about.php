<?php

$pageTitle = 'About Us';
require __DIR__ . '/../layout/header.php';
?>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <h2 class="mb-4">About Alzikrayat</h2>

        <p>
            Alzikrayat — Arabic for "our memories" — is a course project built for the
            Advanced Web Technologies module at Sudan University of Science and Technology's
            College of Computer Science and Information Technology.
        </p>

        <p>
            The goal of the project is to give students hands-on practice building a
            complete web application from first principles: a hand-written
            Model-View-Controller framework, a manual regex-based routing engine, raw
            parameterized SQL against a normalized MySQL schema, and session-based
            authentication — all without relying on a backend framework or an ORM.
        </p>

        <p>
            As a photo-sharing application, Alzikrayat lets registered members upload
            photographs, describe the memories behind them, browse a shared gallery, and
            leave comments on each other's uploads — a small but complete example of a
            real-world, database-driven web application.
        </p>

        <p class="text-muted">
            Course: Advanced Web Technologies &middot; Architecture: MVC Pattern + 3-Tier
            Architecture &middot; Database: MySQL
        </p>
    </div>
</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>
