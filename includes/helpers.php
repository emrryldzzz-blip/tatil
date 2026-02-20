<?php

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

function config(string $section, ?string $key = null)
{
    static $config = null;

    if ($config === null) {
        $config = require __DIR__ . '/../config.php';
    }

    if (!array_key_exists($section, $config)) {
        return null;
    }

    if ($key === null) {
        return $config[$section];
    }

    return $config[$section][$key] ?? null;
}

function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function base_url(): string
{
    return rtrim((string) config('app', 'base_url'), '/');
}

function url(string $path = ''): string
{
    $normalizedPath = '/' . ltrim($path, '/');

    return base_url() . ($path === '' ? '' : $normalizedPath);
}

function redirect(string $path): void
{
    header('Location: ' . url($path));
    exit;
}

function is_admin_logged_in(): bool
{
    return !empty($_SESSION['admin_user_id']);
}

function require_admin_auth(): void
{
    if (!is_admin_logged_in()) {
        redirect('/admin/login.php');
    }
}

function upload_featured_image(array $file): ?string
{
    if (($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
        return null;
    }

    if (($file['error'] ?? UPLOAD_ERR_OK) !== UPLOAD_ERR_OK) {
        throw new RuntimeException('Görsel yüklenemedi.');
    }

    $allowed = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/webp' => 'webp',
    ];

    $mime = mime_content_type($file['tmp_name']);
    if (!isset($allowed[$mime])) {
        throw new RuntimeException('Sadece JPG, PNG veya WEBP yüklenebilir.');
    }

    if (($file['size'] ?? 0) > 2 * 1024 * 1024) {
        throw new RuntimeException('Maksimum 2MB görsel yükleyebilirsiniz.');
    }

    $filename = uniqid('post_', true) . '.' . $allowed[$mime];
    $target = __DIR__ . '/../uploads/' . $filename;

    if (!move_uploaded_file($file['tmp_name'], $target)) {
        throw new RuntimeException('Görsel kaydedilemedi.');
    }

    return 'uploads/' . $filename;
}
