<?php
function e(?string $v): string {
    return htmlspecialchars((string) $v, ENT_QUOTES, 'UTF-8');
}

function asset(string $path): string {
    return '/assets/' . ltrim($path, '/');
}

function is_active(string $url): bool {
    $path = rtrim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/') ?: '/';
    return $path === $url;
}

function partial(string $name, array $data = []): void {
    extract($data);
    require APP_PATH . "/Views/partials/$name.php";
}

function can(string $permission): bool {
    static $map = null;
    $map ??= require ROOT_PATH . '/config/permissions.php';

    $perms = $map[$_SESSION['role'] ?? ''] ?? [];

    return in_array('*', $perms, true) || in_array($permission, $perms, true);
}

function rupiah(float|int|string|null $n): string {
    return 'Rp ' . number_format((float) $n, 0, ',', '.');
}

function csrf_token(): string {
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

function csrf_verify(): void {
    $token = $_POST['csrf'] ?? '';

    if (empty($_SESSION['csrf']) || !is_string($token) || !hash_equals($_SESSION['csrf'], $token)) {
        http_response_code(419);
        exit('Invalid CSRF token.');
    }
}

function menu_code(int|string $id): string {
    return 'M' . str_pad((string) $id, 3, '0', STR_PAD_LEFT);
}

function menu_image_url(?string $path): string {
    if ($path) {
        return '/' . ltrim($path, '/');
    }

    return 'data:image/svg+xml;utf8,' . rawurlencode(
        '<svg xmlns="http://www.w3.org/2000/svg" width="80" height="80">'
        . '<rect width="80" height="80" fill="#e3e3e3"/><circle cx="40" cy="40" r="18" fill="#c9c9c9"/></svg>'
    );
}

function json_response(array $data, int $code = 200): never {
    http_response_code($code);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

function request_json(): array {
    $data = json_decode(file_get_contents('php://input') ?: '', true);
    return is_array($data) ? $data : [];
}

function csrf_verify_header(): void {
    $token = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
    $sess  = $_SESSION['csrf'] ?? '';
    $ok    = $sess !== '' && is_string($token) && hash_equals($sess, $token);

    if (!$ok) {
        json_response(['ok' => false, 'message' => 'Session invalid. Reload the page.'], 419);
    }
}