<?php
namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Models\Analytics;

class AnalyticsController extends Controller {
    public function index(): void {
        $this->authorize('analytics.read');
        $this -> render('admin/analytics', [
            'title' => 'Analytics',
            'styles' => ['analytics'],
            'scripts' => ['analytics'],
            'vendorScripts' => ['https://cdn.jsdelivr.net/npm/apexcharts'],
            'jsData' => ['ANALYTICS' => (new Analytics())->report()],
        ], 'admin');
    }
}