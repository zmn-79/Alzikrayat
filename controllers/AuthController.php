<?php

class AuthController extends Controller
{

    private const LAST_LOGIN_COOKIE = 'alzikrayat_last_login';

    private const LAST_LOGIN_COOKIE_TTL = 7 * 24 * 60 * 60;

    // عرض صفحة تسجيل الدخول مع رسالة آخر دخول من الكوكي
    public function showLogin(): void
    {
        $lastLogin = $_COOKIE[self::LAST_LOGIN_COOKIE] ?? null;

        $this->render('auth/login', [
            'lastLogin' => $lastLogin,
            'errors'    => [],
            'old'       => [],
        ]);
    }

    // عرض صفحة إنشاء حساب جديد
    public function showRegister(): void
    {
        $this->render('auth/register', [
            'errors' => [],
            'old'    => [],
        ]);
    }

    // معالجة تسجيل مستخدم جديد وتشفير الباسورد
    public function register(): void
    {
        $userModel = new User();

        $data = [
            'first_name' => $_POST['first_name'] ?? '',
            'last_name'  => $_POST['last_name'] ?? '',
            'email'      => $_POST['email'] ?? '',
            'password'   => $_POST['password'] ?? '',
        ];

        $errors = $userModel->validate($data);

        if (!empty($errors)) {
            $this->render('auth/register', [
                'errors' => $errors,
                'old'    => $data,
            ]);
            return;
        }

        $passwordHash = password_hash($data['password'], PASSWORD_BCRYPT);

        $userModel->create(
            trim($data['first_name']),
            trim($data['last_name']),
            trim($data['email']),
            $passwordHash,
            trim($_POST['location'] ?? '') ?: null,
            trim($_POST['description'] ?? '') ?: null,
            trim($_POST['occupation'] ?? '') ?: null
        );

        $this->redirect('/login?registered=1');
    }

    // التحقق من بيانات الدخول وبدء الـ Session وتحديث كوكي آخر دخول
    public function login(): void
    {
        $userModel = new User();

        $email    = trim($_POST['email'] ?? '');
        $password = (string) ($_POST['password'] ?? '');

        if ($email === '' || $password === '') {
            $this->render('auth/login', [
                'lastLogin' => $_COOKIE[self::LAST_LOGIN_COOKIE] ?? null,
                'errors'    => ['Email and password are both required.'],
                'old'       => ['email' => $email],
            ]);
            return;
        }

        $user = $userModel->findByEmail($email);

        if ($user === null || !password_verify($password, $user['password'])) {
            $this->render('auth/login', [
                'lastLogin' => $_COOKIE[self::LAST_LOGIN_COOKIE] ?? null,
                'errors'    => ['Invalid email or password.'],
                'old'       => ['email' => $email],
            ]);
            return;
        }

        $_SESSION['user_id']    = $user['id'];
        $_SESSION['first_name'] = $user['first_name'];

        $timestamp = date('Y-m-d H:i:s');
        setcookie(
            self::LAST_LOGIN_COOKIE,
            $timestamp,
            time() + self::LAST_LOGIN_COOKIE_TTL,
            '/'
        );

        $this->redirect('/photos');
    }

    // إنهاء جلسة المستخدم (تسجيل الخروج)
    public function logout(): void
    {
        $_SESSION = [];
        session_destroy();

        $this->redirect('/login');
    }
}
