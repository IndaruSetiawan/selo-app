<?php
namespace App\Controllers\Customer;

use App\Core\Controller;

class PaymentController extends Controller {
    public function index(): void {
        $this -> render('customer/payment', [
            'title' => 'Payment!',
            'styles' => [],
            'scripts' => ['checkout','payment'],
        ], 'customer');
    }
}