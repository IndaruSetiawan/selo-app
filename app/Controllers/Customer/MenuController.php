<?php
namespace App\Controllers\Customer;

use App\Core\Controller;
use App\Models\Menu;

class MenuController extends Controller {
    public function index(): void {
        $menus = array_map(fn(array $m) => [
            'id' => (string) $m['id_menu'],
            'name' => $m['menu_name'],
            'category' => $m['category'],
            'price' => $m['price'],
            'image' => menu_image_url($m['image']),
            'desc' => (string) $m['description'],
        ], (new Menu())->all(true));

        $this -> render('customer/menu', [
            'title' => 'Self Order',
            'styles' => [],
            'scripts' => ['menu', 'order', 'checkout'],
            'jsData' => ['MENU_DATA' => $menus],
            'htmlClass' => 'locked',
            'bodyClass' => 'locked',
        ], 'customer');
    }
}