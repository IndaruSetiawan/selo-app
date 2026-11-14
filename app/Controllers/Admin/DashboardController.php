<?php
namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Models\Order;

class DashboardController extends Controller {
    public function index(): void {
        if(empty($_SESSION['staff_id'])) {
        header('Location:  /admin/login');
        exit;
    }
        $this->authorize('dashboard.read');

        $orders = new Order();
        $this -> render('admin/dashboard', [
            'title' => 'Dashboard',
            'styles' => [],
            'scripts' => [],
            'stats' => $orders->todayStats(),
            'recent' =>$orders->recent(5),
        ], 'admin');
    }
}