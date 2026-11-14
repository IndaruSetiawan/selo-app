<?php
namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Models\Staff;

class TeamController extends Controller {
    private const ROLES = ['admin', 'staff'];

    public function index(): void {
        $this->authorize('team.read');
        $this->render('admin/team', [
            'title'   => 'Team',
            'styles'  => ['team'],
            'scripts' => [],
            'members' => (new Staff())->all(),
        ], 'admin');
    }

    public function approve(): void {
        $id = $this->target();
        if ($id !== null) {
            $ok = (new Staff())->approve($id, (string) $_SESSION['staff_id']);
            $_SESSION[$ok ? 'success' : 'error'] = $ok ? 'Staff approved.' : 'Staff not found or already active.';
        }
        $this->back();
    }

    public function reject(): void {
        $id = $this->target();
        if ($id !== null) {
            $ok = (new Staff())->remove($id, (string) $_SESSION['staff_id']);
            $_SESSION[$ok ? 'success' : 'error'] = $ok ? 'Staff removed.' : 'Staff not found.';
        }
        $this->back();
    }

    public function changeRole(): void {
        $id = $this->target();
        if ($id !== null) {
            $role = (string) ($_POST['role'] ?? '');
            if (!in_array($role, self::ROLES, true)) {
                $_SESSION['error'] = 'Invalid role.';
            } else {
                (new Staff())->setRole($id, $role, (string) $_SESSION['staff_id']);
                $_SESSION['success'] = 'Role updated.';
            }
        }
        $this->back();
    }

    private function target(): ?int {
        $this->authorize('team.write');
        csrf_verify();

        $id = filter_var($_POST['id_staff'] ?? null, FILTER_VALIDATE_INT);
        if ($id === false || $id < 1) {
            $_SESSION['error'] = 'Invalid staff.';
            return null;
        }
        if ($id === (int) $_SESSION['staff_id']) {
            $_SESSION['error'] = 'You cannot change your own account.';
            return null;
        }
        return $id;
    }

    private function back(): never {
        header('Location: /admin/team');
        exit;
    }
}