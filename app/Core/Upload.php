<?php
namespace App\Core;

use TypeError;

class Upload {
    private const MAX_BYTES = 10 * 1024 * 1024;
    private const TYPES = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/webp' => 'webp',
    ];

    public static function menuImage(?array $file): array {
        if (!$file || ($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
            return [null, null];
        }
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return [null, 'Upload failed. Please try again.'];
        }
        if ($file['size'] > self::MAX_BYTES) {
            return [null, 'Picture is too large (Max 10 MB).'];
        }

        $mime = (new \finfo(FILEINFO_MIME_TYPE))->file($file['tmp_name']);
        if (!isset(self::TYPES[$mime]) || @getimagesize($file['tmp_name']) === false) {
            return [null, 'Picture must be a JPG, PNG, or WEBP image.'];
        }

        $dir = ROOT_PATH . '/public/uploads/menus';
        if (!is_dir($dir) && !mkdir($dir, 0755, true) && !is_dir($dir)) {
            return [null, 'Cannor create upload folder.'];
        }
        
        $name = bin2hex(random_bytes(16)) . '.' . self::TYPES[$mime];
        if (!move_uploaded_file($file['tmp_name'], "$dir/$name")) {
            return [null, 'Cannot save the picture.'];
        }
        return ["uploads/menus/$name", null];
    }

    public static function deleteMenuImage(?string $path): void {
        if ($path && str_starts_with($path, 'uploads/menus/') && !str_contains($path, '..')) {
            $file = ROOT_PATH . '/public/' . $path;
            if (is_file($file)) {
                @unlink($file);
            }
        }
    }
}