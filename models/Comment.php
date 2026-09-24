<?php

class Comment extends Model
{

    // التحقق من إن نص التعليق مش فاضي
    public function validate(array $data): array
    {
        $errors = [];
        $text = trim($data['comment'] ?? '');

        if ($text === '') {
            $errors[] = 'Comment text is required.';
        }

        return $errors;
    }

    // حفظ تعليق جديد مرتبط بالصورة والمستخدم
    public function create(int $photoId, int $userId, string $text): int
    {
        $sql = 'INSERT INTO comments (photo_id, user_id, comment)
                VALUES (:photo_id, :user_id, :comment)';

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':photo_id' => $photoId,
            ':user_id'  => $userId,
            ':comment'  => $text,
        ]);

        return (int) $this->db->lastInsertId();
    }

    // جلب كل تعليقات صورة معينة مع اسم كل معلّق
    public function findByPhotoIdWithAuthors(int $photoId): array
    {
        $sql = 'SELECT c.*, u.first_name AS author_first_name, u.last_name AS author_last_name
                FROM comments c
                INNER JOIN users u ON u.id = c.user_id
                WHERE c.photo_id = :photo_id
                ORDER BY c.date_time ASC';

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':photo_id' => $photoId]);
        return $stmt->fetchAll();
    }

    // عدد كل التعليقات (للإحصائيات في الصفحة الرئيسية)
    public function count(): int
    {
        return (int) $this->db->query('SELECT COUNT(*) FROM comments')->fetchColumn();
    }
}
