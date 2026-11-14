<?php
namespace App\Models;

use App\Core\Database;
use PDO;

class Staff {
    private PDO $db;
    private string $table = 'staff';

    public function __construct() {
        $this->db = Database::connect();
    }

    public function findByGoogleId(string $googleId): array|false {
        $stmt = $this->db->prepare('SELECT * FROM staff WHERE google_id = ? AND is_deleted = 0 AND status = 1 LIMIT 1');
        $stmt->execute([$googleId]);
        return $stmt->fetch();
    }

    public function findByEmail(string $email): array|false {
        $stmt = $this->db->prepare('SELECT * FROM staff WHERE email = ? AND is_deleted = 0 AND status = 1 LIMIT 1');
        $stmt->execute([$email]);
        return $stmt->fetch();
    }

    public function linkGoogle(int $id, string $googleId, ?string $avatar): void {
        $stmt = $this->db->prepare('UPDATE staff SET google_id = ?, avatar = ? WHERE id_staff = ?');
        $stmt->execute([$googleId, $avatar, $id]);
    }

    public function emailExists($email): bool {
        $stmt = $this->db->prepare("SELECT 1 FROM {$this->table} WHERE email = ? LIMIT 1");
        $stmt->execute([$email]);
        return $stmt->fetchColumn();
    }

    public function create($name, $phone, $email, $password) {
        $sql = "INSERT INTO " . $this->table . " (name, phone, email, password, auth_provider, status) VALUES (:name, :phone, :email, :password, 'local', 0)";

        return $this->db->prepare($sql)->execute([
            ':name' => $name,
            ':phone' => $phone,
            ':email' => $email,
            ':password' => password_hash($password, PASSWORD_DEFAULT),
        ]);
    }

    public function findById($id): array|false {
        $stmt = $this->db->prepare("SELECT*FROM " . $this->table . " WHERE id_staff = ? AND is_deleted = 0 AND status = 1 LIMIT 1");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function setRole($id, $role, $by) {
            $stmt = $this->db->prepare("UPDATE " . $this->table . " SET role = ?, last_updated_by = ?, last_updated_date = NOW() WHERE id_staff = ? AND is_deleted = 0");
            return $stmt->execute([$role, $by, $id]);
        }   

    public function all(): array {
        return $this->db->query(
            "SELECT id_staff, name, email, phone, role, status
            FROM staff WHERE is_deleted = 0
            ORDER BY status ASC, name ASC"
        )->fetchAll();
    }

    public function approve(int $id, string $by): bool {
        $stmt = $this->db->prepare(
            "UPDATE staff SET status = 1, last_updated_by = ?, last_updated_date = NOW()
            WHERE id_staff = ? AND is_deleted = 0"
        );
        $stmt->execute([$by, $id]);
        return $stmt->rowCount();
    }

    public function remove(int $id, string $by): bool {
        $stmt = $this->db->prepare(
            "UPDATE staff SET is_deleted = 1, last_updated_by = ?, last_updated_date = NOW()
            WHERE id_staff = ? AND is_deleted = 0"
        );
        $stmt->execute([$by, $id]);
        return $stmt->rowCount() > 0;
    }
}