<?php
function thal_notification_settings(?string $baseDir = null): array {
    $baseDir = $baseDir ?: dirname(__DIR__);
    $file = $baseDir . '/data/settings/notifications.json';
    $defaults = ['enabled' => false, 'ntfy_topic' => ''];
    if (!is_file($file)) return $defaults;
    $data = json_decode((string)file_get_contents($file), true);
    return is_array($data) ? array_merge($defaults, $data) : $defaults;
}

function thal_send_ntfy_notification(string $title, string $message, ?string $baseDir = null, ?string $clickUrl = null): bool {
    $settings = thal_notification_settings($baseDir);
    $topic = trim((string)($settings['ntfy_topic'] ?? ''));
    if (empty($settings['enabled']) || $topic === '') return false;

    $payload = [
        'topic' => $topic,
        'title' => $title,
        'message' => $message,
        'priority' => 4,
        'tags' => ['camera_flash'],
    ];
    if ($clickUrl) $payload['click'] = $clickUrl;

    $ch = curl_init('https://ntfy.sh/');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload, JSON_UNESCAPED_UNICODE));
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
    $ok = @curl_exec($ch) !== false;
    curl_close($ch);
    return $ok;
}
