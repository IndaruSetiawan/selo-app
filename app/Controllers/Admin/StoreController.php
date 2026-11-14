<?php
namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Upload;
use App\Models\Menu;

class StoreController extends Controller {
    public function index(): void {
        $this->authorize('store.read');
        $canWrite = can('store.write');
        $this -> render('admin/store', [
            'title' => 'My Store',
            'styles' => ['store'],
            'scripts' => ['store'],
            'vendorScripts' => ['https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js'],
            'bodyEnd' => $canWrite ? ['admin/store_modals'] : [],
            'menus' => (new Menu())->all(),
            'categories' => Menu::CATEGORIES,
            'canWrite' => $canWrite,
        ], 'admin');
    }

    public function save(): void {
        $this->authorize('store.write');   
        csrf_verify();                     

        $model = new Menu();
        $by = (string) $_SESSION['staff_id'];

        $rawId = $_POST['id_menu'] ?? '';
        $id = (is_string($rawId) && ctype_digit($rawId)) ? (int) $rawId : 0;

        $name = trim((string) ($_POST['name'] ?? ''));
        $category = (string) ($_POST['category'] ?? '');
        $description = trim((string) ($_POST['description'] ?? ''));
        $cost = $_POST['cost'] ?? '';
        $price = $_POST['price'] ?? '';

        $error = null;
        if ($name === '' || mb_strlen($name) > 100) {
            $error = 'Name is required (max 100 characters).';
        } elseif (!isset(Menu::CATEGORIES[$category])) {
            $error = 'Please choose a valid category.';
        } elseif (mb_strlen($description) > 1000) {
            $error = 'Description is too long (max 1000 characters).';
        } elseif (!is_string($cost) || !ctype_digit($cost) || !is_string($price) || !ctype_digit($price)
                || strlen($cost) > 10 || strlen($price) > 10) {
            $error = 'Cost and price must be whole numbers (0 or more).';
        } elseif ($id > 0 && !$model->find($id)) {
            $error = 'Menu not found.';
        }

        $newImage = null;
        if ($error === null) {
            [$newImage, $error] = Upload::menuImage($_FILES['picture'] ?? null);
        }

        if ($error !== null) {
            $_SESSION['error'] = $error;      
            header('Location: /admin/store');
            exit;
        }

        $data = [
            'name' => $name, 'category' => $category, 'description' => $description,
            'cost' => (int) $cost, 'price' => (int) $price, 'image' => $newImage,
        ];

        if ($id > 0) {
            $old = $model->find($id);
            $model->update($id, $data, $by);
            if ($newImage !== null) {
                Upload::deleteMenuImage($old['image'] ?? null);  
            }
            $_SESSION['success'] = 'Menu updated.';
        } else {
            $model->create($data, $by);
            $_SESSION['success'] = 'Menu added.';
        }

        header('Location: /admin/store');
        exit;
    }

    public function delete(): void {
        $this->authorize('store.write');
        csrf_verify();

        $rawId = $_POST['id_menu'] ?? '';

        if (!is_string($rawId) || !ctype_digit($rawId)) {
            $_SESSION['error'] = 'Invalid menu.';
        } elseif ((new Menu())->softDelete((int) $rawId, (string) $_SESSION['staff_id'])) {
            $_SESSION['success'] = 'Menu deleted.';
        } else {
            $_SESSION['error'] = 'Menu not found.';
        }

        header('Location: /admin/store');
        exit;
    }
}