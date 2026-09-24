<?php

class HomeController extends Controller
{

    // عرض الصفحة الرئيسية مع الإحصائيات وأحدث الصور
    public function index(): void
    {
        $userModel = new User();
        $photoModel = new Photo();
        $commentModel = new Comment();

        $stats = [
            'users'    => $userModel->count(),
            'photos'   => $photoModel->count(),
            'comments' => $commentModel->count(),
        ];

        $recentPhotos = $photoModel->recentWithAuthors(6);

        $this->render('home/index', [
            'stats'        => $stats,
            'recentPhotos' => $recentPhotos,
        ]);
    }

    // عرض صفحة من نحن
    public function about(): void
    {
        $this->render('home/about', []);
    }
}
