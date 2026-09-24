<?php

$pageTitle = 'Register';
require __DIR__ . '/../layout/header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-7 col-lg-6">
        <h2 class="mb-3">Create an account</h2>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <ul class="mb-0">
                    <?php foreach ($errors as $error): ?>
                        <li><?= htmlspecialchars($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form id="registerForm" method="POST" action="/register" novalidate>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="first_name" class="form-label">First name</label>
                    <input type="text" class="form-control" id="first_name" name="first_name"
                           pattern="[A-Za-z]+" maxlength="50" required
                           value="<?= htmlspecialchars($old['first_name'] ?? '') ?>">
                    <div class="invalid-feedback">Letters only, up to 50 characters.</div>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="last_name" class="form-label">Last name</label>
                    <input type="text" class="form-control" id="last_name" name="last_name"
                           pattern="[A-Za-z]+" maxlength="50" required
                           value="<?= htmlspecialchars($old['last_name'] ?? '') ?>">
                    <div class="invalid-feedback">Letters only, up to 50 characters.</div>
                </div>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required
                       value="<?= htmlspecialchars($old['email'] ?? '') ?>">
                <div class="invalid-feedback">Please enter a valid, unused email address.</div>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required minlength="6">
                <div class="invalid-feedback">Password must be at least 6 characters.</div>
            </div>

            <div class="mb-3">
                <label for="location" class="form-label">Location <span class="text-muted">(optional)</span></label>
                <input type="text" class="form-control" id="location" name="location" maxlength="100"
                       value="<?= htmlspecialchars($old['location'] ?? '') ?>">
            </div>

            <div class="mb-3">
                <label for="occupation" class="form-label">Occupation <span class="text-muted">(optional)</span></label>
                <input type="text" class="form-control" id="occupation" name="occupation" maxlength="100"
                       value="<?= htmlspecialchars($old['occupation'] ?? '') ?>">
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">About you <span class="text-muted">(optional)</span></label>
                <textarea class="form-control" id="description" name="description" rows="3"><?= htmlspecialchars($old['description'] ?? '') ?></textarea>
            </div>

            <button type="submit" class="btn btn-primary w-100">Register</button>
        </form>

        <p class="mt-3 text-center">
            Already have an account? <a href="/login">Login here</a>
        </p>
    </div>
</div>

<script>
// التحقق من بيانات التسجيل في المتصفح قبل الإرسال
(function () {
    var form = document.getElementById('registerForm');
    var namePattern = /^[A-Za-z]+$/;
    var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    form.addEventListener('submit', function (event) {
        var fields = {
            first_name: document.getElementById('first_name'),
            last_name: document.getElementById('last_name'),
            email: document.getElementById('email'),
            password: document.getElementById('password')
        };
        var valid = true;

        Object.keys(fields).forEach(function (key) {
            fields[key].classList.remove('is-invalid');
        });

        if (!namePattern.test(fields.first_name.value.trim()) || fields.first_name.value.length > 50) {
            fields.first_name.classList.add('is-invalid');
            valid = false;
        }
        if (!namePattern.test(fields.last_name.value.trim()) || fields.last_name.value.length > 50) {
            fields.last_name.classList.add('is-invalid');
            valid = false;
        }
        if (!emailPattern.test(fields.email.value.trim())) {
            fields.email.classList.add('is-invalid');
            valid = false;
        }
        if (fields.password.value.length < 6) {
            fields.password.classList.add('is-invalid');
            valid = false;
        }

        if (!valid) {
            event.preventDefault();
        }
    });
})();
</script>

<?php require __DIR__ . '/../layout/footer.php'; ?>
