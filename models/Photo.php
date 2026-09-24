<?php

class Photo extends Model
{

    // التحقق من صحة بيانات الصورة (العنوان مطلوب)
    public function validate(array $data): array
    {
        $errors = [];
        $title = trim($data['title'] ?? '');

        if ($title === '') {
            $errors[] = 'Title is required.';
        } elseif (strlen($title) > 200) {
            $errors[] = 'Title must be at most 200 characters.';
        }

        return $errors;
    }

    // حفظ بيانات صورة جديدة في قاعدة البيانات
    public function create(int $userId, string $fileName, string $title, ?string $description = null): int
    {
        $sql = 'INSERT INTO photos (user_id, file_name, title, description)
                VALUES (:user_id, :file_name, :title, :description)';

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':user_id'     => $userId,
            ':file_name'   => $fileName,
            ':title'       => $title,
            ':description' => $description,
        ]);

        return (int) $this->db->lastInsertId();
    }

    // جلب كل الصور مع اسم صاحب كل صورة (للجاليري)
    public function allWithAuthors(): array
    {
        $sql = 'SELECT p.*, u.first_name AS author_first_name, u.last_name AS author_last_name
                FROM photos p
                INNER JOIN users u ON u.id = p.user_id
                ORDER BY p.date_time DESC';

        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    // جلب صورة واحدة مع اسم صاحبها (لصفحة التفاصيل)
    public function findByIdWithAuthor(int $id): ?array
    {
        $sql = 'SELECT p.*, u.first_name AS author_first_name, u.last_name AS author_last_name
                FROM photos p
                INNER JOIN users u ON u.id = p.user_id
                WHERE p.id = :id
                LIMIT 1';

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        $photo = $stmt->fetch();

        return $photo === false ? null : $photo;
    }

    // حذف صورة من قاعدة البيانات
    public function delete(int $id): void
    {
        $stmt = $this->db->prepare('DELETE FROM photos WHERE id = :id');
        $stmt->execute([':id' => $id]);
    }

    // عدد الصور المرفوعة (للإحصائيات في الصفحة الرئيسية)
    public function count(): int
    {
        return (int) $this->db->query('SELECT COUNT(*) FROM photos')->fetchColumn();
    }

    // جلب أحدث الصور المرفوعة (لعرضها في الصفحة الرئيسية)
    public function recentWithAuthors(int $limit = 6): array
    {
        $sql = 'SELECT p.*, u.first_name AS author_first_name, u.last_name AS author_last_name
                FROM photos p
                INNER JOIN users u ON u.id = p.user_id
                ORDER BY p.date_time DESC
                LIMIT :limit';

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
