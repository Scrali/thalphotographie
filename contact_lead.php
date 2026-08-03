<?php
header('Content-Type: application/json; charset=utf-8');

function clean($v) { return trim((string)$v); }

$name = clean($_POST['nom'] ?? $_POST['name'] ?? '');
$company = clean($_POST['entreprise'] ?? $_POST['company'] ?? '');
$email = clean($_POST['email'] ?? '');
$message = clean($_POST['message'] ?? '');

if ($name === '' && $email === '' && $message === '') {
    echo json_encode(['ok' => false, 'error' => 'empty']);
    exit;
}

$contactSummary = "Message reçu via le formulaire de contact du site\n\n" .
    "Nom : {$name}\n" .
    ($company !== '' ? "Entreprise : {$company}\n" : '') .
    "Email : {$email}\n\n" .
    "Message : {$message}";

$entry = [
    'createdAt' => date('c'),
    'ip' => $_SERVER['REMOTE_ADDR'] ?? '',
    'name' => $company !== '' ? "{$name} ({$company})" : $name,
    'email' => $email,
    'phone' => '',
    'location' => '',
    'event_date' => '',
    'message' => $message,
    'contact_summary' => $contactSummary,
    'status' => 'new',
    'source' => 'contact_form',
];

$dir = __DIR__ . '/thal-studio/data/estimations';
if (!is_dir($dir)) mkdir($dir, 0755, true);

$id = date('Ymd_His') . '_' . substr(md5(json_encode($entry) . microtime()), 0, 8);
$entry['_id'] = $id . '.json';
$ok = file_put_contents($dir . '/' . $id . '.json', json_encode($entry, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE), LOCK_EX) !== false;

if ($ok) {
    thal_notify_new_lead($entry);
}

echo json_encode(['ok' => $ok, 'id' => $id]);

function thal_mail_safe(string $v): string {
    return trim(str_replace(["\r", "\n"], ' ', $v));
}

function thal_notify_new_lead(array $entry): void {
    $to = 'contact@thalphotographie.ch';
    $subject = 'Nouvelle demande sur le site — ' . thal_mail_safe((string)($entry['name'] ?: 'Contact'));

    $lines = [
        'Nouvelle demande reçue via le formulaire de contact du site.',
        '',
        'Nom : ' . $entry['name'],
        'Email : ' . $entry['email'],
    ];
    if (($entry['phone'] ?? '') !== '') $lines[] = 'Téléphone : ' . $entry['phone'];
    $lines[] = '';
    $lines[] = 'Message :';
    $lines[] = (string)$entry['message'];
    $lines[] = '';
    $lines[] = 'Voir la demande et créer un devis : https://thalphotographie.ch/thal-studio/estimations.php';
    $body = implode("\n", $lines);

    $headers = [
        'From: THAL Photographie <contact@thalphotographie.ch>',
        'Content-Type: text/plain; charset=UTF-8',
    ];
    $replyEmail = filter_var((string)($entry['email'] ?? ''), FILTER_VALIDATE_EMAIL);
    if ($replyEmail) $headers[] = 'Reply-To: ' . $replyEmail;

    @mail($to, mb_encode_mimeheader($subject, 'UTF-8'), $body, implode("\r\n", $headers));
}
