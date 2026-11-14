<?php
namespace App\Controllers\Admin;

use App\Core\Controller;

class AnalyticsController extends Controller {
    public function index(): void {
        $this->authorize('analytics.read');
        $this -> render('admin/analytics', [
            'title' => 'Analytics',
            'styles' => ['analytics'],
            'scripts' => ['analytics'],
            'vendorScripts' => ['https://cdn.jsdelivr.net/npm/apexcharts'],
        ], 'admin');
    }
}