<?php

$pageTitle = 'Login';
require __DIR__ . '/../layout/header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <h2 class="mb-3">Login</h2>

        <?php if (!empty($_GET['registered'])): ?>
            <div class="alert alert-success">Registration successful. Please log in.</div>
        <?php endif; ?>

        <?php if (!empty($lastLogin)): ?>
            <div class="alert alert-info">
                Last login from this computer was <?= htmlspecialchars($lastLogin) ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <ul class="mb-0">
                    <?php foreach ($errors as $error): ?>
                        <li><?= htmlspecialchars($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form id="loginForm" method="POST" action="/login" novalidate>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required
                       value="<?= htmlspecialchars($old['email'] ?? '') ?>">
                <div class="invalid-feedback">Please enter a valid email address.</div>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required minlength="6">
                <div class="invalid-feedback">Password must be at least 6 characters.</div>
            </div>
            <button type="submit" class="btn btn-primary w-100">Login</button>
        </form>

        <p class="mt-3 text-center">
            Don't have an account? <a href="/register">Register here</a>
        </p>
    </div>
</div>

<script>
// التحقق من البريد والباسورد في المتصفح قبل إرسال الفورم
(function () {
    var form = document.getElementById('loginForm');
    form.addEventListener('submit', function (event) {
        var email = document.getElementById('email');
        var password = document.getElementById('password');
        var valid = true;

        [email, password].forEach(function (field) {
            field.classList.remove('is-invalid');
        });

        var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailPattern.test(email.value.trim())) {
            email.classList.add('is-invalid');
            valid = false;
        }

        if (password.value.length < 6) {
            password.classList.add('is-invalid');
            valid = false;
        }

        if (!valid) {
            event.preventDefault();
        }
    });
})();
</script>

<?php require __DIR__ . '/../layout/footer.php'; ?>
