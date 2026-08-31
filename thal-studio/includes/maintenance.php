<?php
function thal_site_root(?string $baseDir = null): string {
    return $baseDir ? dirname($baseDir) : dirname(__DIR__, 2);
}

function thal_maintenance_lock_path(?string $baseDir = null): string {
    return thal_site_root($baseDir) . '/maintenance.lock';
}

function thal_maintenance_settings(?string $baseDir = null): array {
    $baseDir = $baseDir ?: dirname(__DIR__);
    $file = $baseDir . '/data/settings/maintenance.json';
    $defaults = ['secret' => '', 'message' => ''];
    if (!is_file($file)) return $defaults;
    $data = json_decode((string)file_get_contents($file), true);
    return is_array($data) ? array_merge($defaults, $data) : $defaults;
}

function thal_maintenance_is_on(?string $baseDir = null): bool {
    return is_file(thal_maintenance_lock_path($baseDir));
}

function thal_maintenance_preview_url(?string $baseDir = null): string {
    $settings = thal_maintenance_settings($baseDir);
    $secret = (string)($settings['secret'] ?? '');
    if ($secret === '') return '';
    return 'https://thalphotographie.ch/?preview_key=' . $secret;
}
