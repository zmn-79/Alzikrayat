<?php

class User extends Model
{

    // التحقق من صحة بيانات المستخدم (الاسم، الإيميل، الباسورد) قبل الحفظ
    public function validate(array $data): array
    {
        $errors = [];

        $firstName = trim($data['first_name'] ?? '');
        $lastName  = trim($data['last_name'] ?? '');
        $email     = trim($data['email'] ?? '');
        $password  = (string) ($data['password'] ?? '');

        if ($firstName === '') {
            $errors[] = 'First name is required.';
        } elseif (strlen($firstName) > 50) {
            $errors[] = 'First name must be at most 50 characters.';
        } elseif (!preg_match('/^[A-Za-z]+$/', $firstName)) {
            $errors[] = 'First name must contain letters only.';
        }

        if ($lastName === '') {
            $errors[] = 'Last name is required.';
        } elseif (strlen($lastName) > 50) {
            $errors[] = 'Last name must be at most 50 characters.';
        } elseif (!preg_match('/^[A-Za-z]+$/', $lastName)) {
            $errors[] = 'Last name must contain letters only.';
        }

        if ($email === '') {
            $errors[] = 'Email is required.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Email must be a valid email address.';
        } elseif ($this->findByEmail($email) !== null) {
            $errors[] = 'This email is already registered.';
        }

        if ($password === '') {
            $errors[] = 'Password is required.';
        } elseif (strlen($password) < 6) {
            $errors[] = 'Password must be at least 6 characters.';
        }

        return $errors;
    }

    // إضافة مستخدم جديد في قاعدة البيانات
    public function create(
        string $firstName,
        string $lastName,
        string $email,
        string $passwordHash,
        ?string $location = null,
        ?string $description = null,
        ?string $occupation = null
    ): int {
        $sql = 'INSERT INTO users (first_name, last_name, email, password, location, description, occupation)
                VALUES (:first_name, :last_name, :email, :password, :location, :description, :occupation)';

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':first_name'  => $firstName,
            ':last_name'   => $lastName,
            ':email'       => $email,
            ':password'    => $passwordHash,
            ':location'    => $location,
            ':description' => $description,
            ':occupation'  => $occupation,
        ]);

        return (int) $this->db->lastInsertId();
    }

    // البحث عن مستخدم بالإيميل
    public function findByEmail(string $email): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE email = :email LIMIT 1');
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch();

        return $user === false ? null : $user;
    }

    // البحث عن مستخدم بالـ id
    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE id = :id LIMIT 1');
        $stmt->execute([':id' => $id]);
        $user = $stmt->fetch();

        return $user === false ? null : $user;
    }

    // عدد المستخدمين المسجلين (للإحصائيات في الصفحة الرئيسية)
    public function count(): int
    {
        return (int) $this->db->query('SELECT COUNT(*) FROM users')->fetchColumn();
    }
}
