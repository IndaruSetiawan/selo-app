<?php
namespace App\Models;

use App\Core\Database;
use PDO;

class Analytics {
    public PDO $db;

    public function __construct() {
        $this->db = Database::connect();
    }

    public function report(): array {
        $rows = $this->db->query(
            "SELECT YEAR(o.order_date) AS yr, MONTH(o.order_date) AS mo,
                    m.id_menu, m.menu_name, m.category,
                    SUM(d.quantity) AS qty,
                    SUM(d.item_subtotal) AS rev,
                    SUM(d.quantity * m.cost) AS cost
            FROM order_details d
            JOIN orders o ON o.id_order = d.id_order
            JOIN menu m ON m.id_menu = d.id_menu
            WHERE d.status = 1 AND o.is_deleted = 0 AND o.status <> 3
                AND EXISTS (SELECT 1 FROM payments p
                            WHERE p.id_order = o.id_order  AND p.payment_status = 'paid')
                GROUP BY YEAR(o.order_date), MONTH(o.order_date), m.id_menu, m.menu_name, m.category
                ORDER BY yr, mo"
        )->fetchAll();

        $now = $this->db->query(
            "SELECT YEAR(CURDATE()) AS y, MONTH(CURDATE()) AS m"
        )->fetch();
        $thisYear = (int) $now['y'];
        $thisMonth = (int) $now['m'] - 1;

        $menus = [];
        $pos = [];
        foreach ($rows as $row) {
            $id = (int) $row['id_menu'];
            if (!isset($pos[$id])) {
                $pos[$id] = count($menus);
                $menus[] = [
                    'name' => $row['menu_name'],
                    'category' => Menu::CATEGORIES[$row['category']] ?? ucfirst((string) $row['category']),
                ];
            }
        }

        $years = [$thisYear];
        foreach ($rows as $row) {
            $years[] = (int) $row['yr'];
        }
        $years = array_values(array_unique($years));
        sort($years);

        $blank = array_fill(0, count($menus), 0);
        $data = [];
        $lastMonth = [];
        foreach ($years as $year) {
            $lastMonth[$year] = $year === $thisYear ? $thisMonth : 11;
            $data[$year] = [];
            for ($m = 0; $m <= $lastMonth[$year]; $m++) {
                $data[$year][] = ['qty' => $blank, 'rev' => $blank, 'sales' => 0, 'cost' => 0];
            }
        }
        foreach ($rows as $row) {
            $year = (int) $row['yr'];
            $m = (int) $row['mo'] - 1;
            $i = $pos[(int) $row['id_menu']];
            if (!isset($data[$year][$m])) {
                continue;
            }
            $rev = (int) round((float) $row['rev']);
            $cost = (int) round((float) $row['cost']);
            $data[$year][$m]['qty'][$i] = (int) $row['qty'];
            $data[$year][$m]['rev'][$i] = $rev;
            $data[$year][$m]['sales'] += $rev;
            $data[$year][$m]['cost'] += $cost;
        }

        return [
            'years' => $years,
            'menus' => $menus,
            'data' => $data,
            'lastMonth' => $lastMonth,
        ];
    }
}