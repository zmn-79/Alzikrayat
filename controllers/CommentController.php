<?php

class CommentController extends Controller
{

    // إضافة تعليق جديد على صورة والرد بـ JSON عشان يظهر فوراً من غير ريلود
    public function store(): void
    {
        header('Content-Type: application/json');

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['user_id'])) {
            http_response_code(401);
            echo json_encode(['success' => false, 'error' => 'You must be logged in to comment.']);
            return;
        }

        $userId = (int) $_SESSION['user_id'];
        $photoId = (int) ($_POST['photo_id'] ?? 0);
        $commentText = trim($_POST['comment'] ?? '');

        $photoModel = new Photo();
        if ($photoModel->findByIdWithAuthor($photoId) === null) {
            http_response_code(404);
            echo json_encode(['success' => false, 'error' => 'Photo not found.']);
            return;
        }

        $commentModel = new Comment();
        $errors = $commentModel->validate(['comment' => $commentText]);

        if (!empty($errors)) {
            http_response_code(422);
            echo json_encode(['success' => false, 'errors' => $errors]);
            return;
        }

        $commentModel->create($photoId, $userId, $commentText);

        $userModel = new User();
        $author = $userModel->findById($userId);

        echo json_encode([
            'success' => true,
            'comment' => [
                'author_first_name' => $author['first_name'],
                'author_last_name'  => $author['last_name'],
                'comment'           => $commentText,
                'date_time'         => date('Y-m-d H:i:s'),
            ],
        ]);
    }
}
