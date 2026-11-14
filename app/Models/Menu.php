<?php
namespace App\Models;

use App\Core\Database;
use PDO;

class Menu {
    public const CATEGORIES = [
        'dish' => 'Dish',
        'snack' => 'Snack',
        'drink' => 'Drink',
    ];

    private PDO $db;
    public function __construct() {
        $this->db = Database::connect();
    }

    public function all(bool $onlyAvailable = false): array {
        $sql = "SELECT * FROM menu WHERE is_deleted = 0" . ($onlyAvailable ? " AND status = 1" : "") . " ORDER BY id_menu ASC";
        return $this->db->query($sql)->fetchAll();
    }

    public function find(int $id): array|false {
        $stmt = $this->db->prepare(
            "SELECT * FROM menu WHERE id_menu = ? AND is_deleted = 0 LIMIT 1"
        );
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create(array $d, string $by):int {
        $stmt = $this->db->prepare(
            "INSERT INTO menu (menu_name, category, description, cost, price, image, created_by) VALUES (?, ?, ?, ?, ?, ?, ?)"
        );
        $stmt->execute([$d['name'], $d['category'], $d['description'], $d['cost'], $d['price'], $d['image'], $by]);
        return (int) $this->db->lastInsertId();
    }

    public function update(int $id, array $d, string $by): bool {
        $sql = "UPDATE menu SET menu_name = ?, category = ?, description = ?, cost = ?, price = ?, last_updated_by = ?";
        $args = [$d['name'], $d['category'], $d['description'], $d['cost'], $d['price'], $by];

        if ($d['image'] !== null) {
            $sql .= ", image = ?";
            $args[] = $d['image'];
        }

        $sql .= " WHERE id_menu = ? AND is_deleted = 0";
        $args[] = $id;

        $stmt = $this->db->prepare($sql);
        $stmt->execute($args);
        return $stmt->rowCount() > 0;
    }

    public function softDelete(int $id, string $by): bool {
        $stmt = $this->db->prepare(
            "UPDATE menu SET is_deleted = 1, deleted_at = CURRENT_TIMESTAMP, last_update_by = ? WHERE id_menu = ? AND is_deleted = 0"
        );
        $stmt->execute([$by, $id]);
        return $stmt->rowCount();
    }
}