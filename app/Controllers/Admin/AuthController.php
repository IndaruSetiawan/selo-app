<?php
namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Models\Staff;
use League\OAuth2\Client\Provider\Google;

class AuthController extends Controller {
    public function index(): void {
        $this -> render('admin/login', [
            'title' => 'login',
            'styles' => [],
            'scripts' => [],
        ], 'auth');
    }

    private function provider(): Google {
        return new Google([
            'clientId' => $_ENV['GOOGLE_CLIENT_ID'],
            'clientSecret' => $_ENV['GOOGLE_CLIENT_SECRET'],
            'redirectUri' => $_ENV['GOOGLE_REDIRECT_URI'],
        ]);
    } 

    public function googleRedirect(): void {
        $provider = $this->provider();

        $url = $provider->getAuthorizationUrl([
            'scope' => ['openid', 'email', 'profile'],
            'prompt' => 'select_account',
        ]);
        $_SESSION['oauth2state'] = $provider->getState();

        header('Location: ' . $url);
        exit;
    }

    public function googleCallback() : void {
        $fail = function (string $msg): never {
            $_SESSION['error'] = $msg;
            header('Location: /admin/login');
            exit;
        };

        if (isset($_GET['error'])) {
            $fail('Login with Google canceled.');
        }

        $state = $_GET['state'] ?? '';
        if (empty($_SESSION['oauth2state']) || !hash_equals($_SESSION['oauth2state'], $state)){
            unset($_SESSION['oauth2state']);
            $fail('Request not valid. Please try again.');
        }
        unset($_SESSION['oauth2state']);

        if(empty($_GET['code'])) {
            $fail('Authorization not found.');
        }

        try {
            $provider = $this->provider();
            $token = $provider->getAccessToken('authorization_code', ['code' => $_GET['code']]);
            /** @var GoogleUser $google */
            $google = $provider->getResourceOwner($token);
            $raw = $google->toArray();

            if(empty($raw['email_verified'])) {
                $fail('Google email not yet verified.');
            }

            $staffModel = new Staff();

            $staff = $staffModel->findByGoogleId($google->getId());

            if (!$staff) {
                $staff = $staffModel->findByEmail($google->getEmail());

                if (!$staff) {
                    $fail('This email is not yet registered as staff');
                }
                $staffModel->linkGoogle((int) $staff['id_staff'], $google->getId(), $google->getAvatar());
            }

            session_regenerate_id(true);
            $_SESSION['staff_id'] = $staff['id_staff'];
            $_SESSION['staff_name'] = $staff['name'];
            $_SESSION['role'] = $staff['role'];

            header('Location: /admin');
            exit;
        } catch (\Throwable $e) {
            error_log('Google login error: ' . $e->getMessage());
            $fail('Login with Google Failed. Please try again.');
        }
    }

    public function register(): void {
        $name = trim($_POST['name'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if ($name === '' || $phone === '' || $email === '' || $password === '') {
            $_SESSION['error'] = 'Name, Phone, Email, and password are required.';
        } elseif ($phone !== '' && !preg_match('/^[0-9+\-\s]{8,20}$/', $phone)) {
            $_SESSION['error'] = 'Invalid phone number format.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = 'Invalid email format.';
        } elseif (strlen($password) < 8) {
            $_SESSION['error'] = 'Password must be at least 8 character long';
        } else {
            $staff = new Staff();

            if ($staff->emailExists($email)) {
                $_SESSION['error'] = 'Email is already registered.';
            } else {
                $staff->create($name, $phone, $email, $password);
                $_SESSION['success'] = 'Registration successful. Awaiting admin approval';
            }
        }
        header('Location: /admin/login');
        exit;
    }

    public function login(): void {
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';


        $staff = (new Staff())->findByEmail($email);

        if(!$staff || empty($staff['password']) || !password_verify($password, $staff['password'])) {
            $_SESSION['error'] = 'Wrong email or password, or account not active.';
            header('Location: /admin/login');
            exit;
        }

        session_regenerate_id(true);
        $_SESSION['staff_id'] = $staff['id_staff'];
        $_SESSION['staff_name'] = $staff['name'];
        $_SESSION['role'] = $staff['role'];

        header('Location: /admin');
        exit;
    }

    public function logout(): void {
    csrf_verify();

    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $p = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $p['path'], $p['domain'], $p['secure'], $p['httponly']);
    }
    session_destroy();

    session_start();
    session_regenerate_id(true);
    $_SESSION['success'] = 'You have been logged out.';

    header('Location: /admin/login');
    exit;
    }
}