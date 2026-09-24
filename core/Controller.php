<?php

abstract class Controller
{

    // عرض ملف الـ View وتمرير البيانات ليه
    protected function render(string $viewPath, array $data = []): void
    {
        $viewFile = __DIR__ . '/../views/' . $viewPath . '.php';

        if (!file_exists($viewFile)) {
            throw new RuntimeException("View not found: {$viewPath}");
        }

        $currentUser = $this->getAuthUser();

        extract($data);
        require $viewFile;
    }

    // تحويل المستخدم لصفحة تانية
    protected function redirect(string $path): void
    {
        header('Location: ' . $path);
        exit;
    }

    // التأكد إن المستخدم مسجل دخول قبل تنفيذ أي عملية تحتاج تسجيل دخول
    protected function requireAuth(): int
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['user_id'])) {
            $this->redirect('/login');
        }

        return (int) $_SESSION['user_id'];
    }

    // جلب بيانات المستخدم المسجل دخول حالياً من الـ Session
    protected function getAuthUser(): ?array
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['user_id'])) {
            return null;
        }

        return [
            'id'         => $_SESSION['user_id'],
            'first_name' => $_SESSION['first_name'] ?? '',
        ];
    }
}
