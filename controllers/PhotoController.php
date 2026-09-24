<?php

class PhotoController extends Controller
{

    private const UPLOAD_DIR = __DIR__ . '/../public/images/uploads/';

    private const MAX_FILE_SIZE = 5 * 1024 * 1024;

    private const ALLOWED_MIME_TO_EXT = [
        'image/jpeg' => 'jpg',
        'image/png'  => 'png',
        'image/gif'  => 'gif',
        'image/webp' => 'webp',
    ];

    // عرض الجاليري بكل الصور
    public function index(): void
    {
        $photoModel = new Photo();
        $photos = $photoModel->allWithAuthors();

        $this->render('photos/index', [
            'photos' => $photos,
        ]);
    }

    // عرض فورم رفع صورة جديدة (لازم يكون مسجل دخول)
    public function showUploadForm(): void
    {
        $this->requireAuth();

        $this->render('photos/upload', [
            'errors' => [],
            'old'    => [],
        ]);
    }

    // التحقق من الصورة ورفعها فعلياً وحفظ بياناتها
    public function store(): void
    {
        $userId = $this->requireAuth();
        $photoModel = new Photo();

        $data = [
            'title'       => $_POST['title'] ?? '',
            'description' => $_POST['description'] ?? '',
        ];

        $errors = $photoModel->validate($data);

        $uploadedFile = $_FILES['photo'] ?? null;
        $storedFileName = null;

        if ($uploadedFile === null || $uploadedFile['error'] === UPLOAD_ERR_NO_FILE) {
            $errors[] = 'Please choose an image file to upload.';
        } elseif ($uploadedFile['error'] !== UPLOAD_ERR_OK) {
            $errors[] = 'The file could not be uploaded. Please try again.';
        } elseif ($uploadedFile['size'] > self::MAX_FILE_SIZE) {
            $errors[] = 'The image must be smaller than 5 MB.';
        } else {
            $imageInfo = @getimagesize($uploadedFile['tmp_name']);
            $mimeType = $imageInfo['mime'] ?? null;

            if ($imageInfo === false || !isset(self::ALLOWED_MIME_TO_EXT[$mimeType])) {
                $errors[] = 'Only JPEG, PNG, GIF, or WEBP images are allowed.';
            } else {
                $extension = self::ALLOWED_MIME_TO_EXT[$mimeType];
                $storedFileName = uniqid('photo_', true) . '.' . $extension;
            }
        }

        if (!empty($errors)) {
            $this->render('photos/upload', [
                'errors' => $errors,
                'old'    => $data,
            ]);
            return;
        }

        $destination = self::UPLOAD_DIR . $storedFileName;

        if (!move_uploaded_file($uploadedFile['tmp_name'], $destination)) {
            $this->render('photos/upload', [
                'errors' => ['The file could not be saved on the server.'],
                'old'    => $data,
            ]);
            return;
        }

        $photoId = $photoModel->create(
            $userId,
            $storedFileName,
            trim($data['title']),
            trim($data['description']) ?: null
        );

        $this->redirect('/photo/' . $photoId);
    }

    // عرض تفاصيل صورة واحدة مع التعليقات بتاعتها
    public function show(string $id): void
    {
        $photoModel = new Photo();
        $photo = $photoModel->findByIdWithAuthor((int) $id);

        if ($photo === null) {
            http_response_code(404);
            echo 'Photo not found.';
            return;
        }

        $commentModel = new Comment();
        $comments = $commentModel->findByPhotoIdWithAuthors((int) $id);

        $this->render('photos/show', [
            'photo'    => $photo,
            'comments' => $comments,
        ]);
    }

    // حذف صورة بعد التأكد إن صاحبها هو اللي بيحذف
    public function delete(string $id): void
    {
        $userId = $this->requireAuth();
        $photoModel = new Photo();

        $photo = $photoModel->findByIdWithAuthor((int) $id);

        if ($photo === null) {
            http_response_code(404);
            echo 'Photo not found.';
            return;
        }

        if ((int) $photo['user_id'] !== $userId) {
            http_response_code(403);
            echo 'You are not allowed to delete this photo.';
            return;
        }

        $filePath = self::UPLOAD_DIR . $photo['file_name'];
        if (is_file($filePath)) {
            unlink($filePath);
        }

        $photoModel->delete((int) $id);

        $this->redirect('/photos');
    }
}
