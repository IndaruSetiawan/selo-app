<?php
namespace App\Core;

abstract class Controller {
    protected function render(string $view, array $data = [], string $layout = 'admin'): void {
        extract($data);

        ob_start();
        require APP_PATH . "/Views/$view.php";
        $content = ob_get_clean();

        require APP_PATH . "/Views/layouts/$layout.php";
    }

    protected function requireLogin(): void {
    if(empty($_SESSION['staff_id'])) {
        header('Location: /admin/login');
        exit;
    }

    $staff = (new \App\Models\Staff())-> findById((int) $_SESSION['staff_id']);

    if (!$staff) {
        unset($_SESSION['staff_id'], $_SESSION['staff_name'], $_SESSION['role']);
        $_SESSION['error'] = 'Account not active.';
        header('Location: /Admin/login');
        exit;
    }
    $_SESSION['role'] = $staff['role'];
    }

    protected function authorize($permission) {
        $this->requireLogin();

        if(!can($permission)) {
            http_response_code(403);
            exit('403 - Access denied.');
        }
    }
    
    
}

