<?php
header('Content-Type: application/json; charset=utf-8');

function fail_response(string $message, int $code = 400): void {
    http_response_code($code);
    echo json_encode(['ok' => false, 'error' => $message], JSON_UNESCAPED_UNICODE);
    exit;
}

$settingsFile = __DIR__ . '/thal-studio/data/settings/packs.json';
$icalUrl = '';
if (is_file($settingsFile)) {
    $json = json_decode((string)file_get_contents($settingsFile), true);
    if (is_array($json)) $icalUrl = trim((string)($json['google_calendar_ical_url'] ?? ''));
}

if ($icalUrl === '') {
    // Non configuré : pas de blocage, le formulaire reste utilisable normalement.
    echo json_encode(['ok' => true, 'busy' => []], JSON_UNESCAPED_UNICODE);
    exit;
}

if (!preg_match('#^https://calendar\.google\.com/calendar/ical/#', $icalUrl)) {
    fail_response('URL iCal invalide (doit provenir de calendar.google.com).', 500);
}

$context = stream_context_create(['http' => ['method' => 'GET', 'timeout' => 10, 'ignore_errors' => true]]);
$raw = @file_get_contents($icalUrl, false, $context);
if ($raw === false || $raw === '') fail_response('Impossible de lire le calendrier.', 502);

// Déplie les lignes iCal repliées (RFC 5545 : une ligne de continuation commence par un espace/tab).
$unfolded = preg_replace("/\r\n[ \t]/", '', $raw);
$lines = preg_split("/\r\n|\n/", (string)$unfolded);

function ical_value(string $line): string {
    $pos = strpos($line, ':');
    return $pos === false ? '' : substr($line, $pos + 1);
}

// Retourne [DateTime|null, bool $isDateOnly].
function ical_to_date(string $value): array {
    $value = trim($value);
    if (preg_match('/^(\d{4})(\d{2})(\d{2})$/', $value, $m)) {
        return [DateTime::createFromFormat('Y-m-d', "{$m[1]}-{$m[2]}-{$m[3]}"), true];
    }
    if (preg_match('/^(\d{4})(\d{2})(\d{2})T(\d{2})(\d{2})(\d{2})Z?$/', $value, $m)) {
        return [DateTime::createFromFormat('Y-m-d', "{$m[1]}-{$m[2]}-{$m[3]}"), false];
    }
    return [null, false];
}

$today = new DateTime('today');
$horizon = (new DateTime('today'))->modify('+18 months');

$busy = [];
$inEvent = false;
$dtStart = null;
$dtEnd = null;
$dtStartIsDateOnly = false;
$dtEndIsDateOnly = false;

foreach ($lines as $line) {
    if (stripos($line, 'BEGIN:VEVENT') === 0) {
        $inEvent = true;
        $dtStart = null;
        $dtEnd = null;
        $dtStartIsDateOnly = false;
        $dtEndIsDateOnly = false;
        continue;
    }
    if (stripos($line, 'END:VEVENT') === 0) {
        if ($inEvent && $dtStart) {
            $end = $dtEnd ?: (clone $dtStart);
            // DTEND d'un événement journée entière est exclusif (RFC 5545) : on recule d'un jour.
            if ($dtEnd && ($dtStartIsDateOnly || $dtEndIsDateOnly)) {
                $end = (clone $dtEnd)->modify('-1 day');
            }
            if ($end < $dtStart) $end = clone $dtStart;

            $cursor = clone $dtStart;
            $guard = 0;
            while ($cursor <= $end && $guard < 800) {
                if ($cursor >= $today && $cursor <= $horizon) {
                    $busy[$cursor->format('Y-m-d')] = true;
                }
                $cursor->modify('+1 day');
                $guard++;
            }
        }
        $inEvent = false;
        continue;
    }
    if (!$inEvent) continue;

    if (stripos($line, 'DTSTART') === 0) {
        [$dtStart, $dtStartIsDateOnly] = ical_to_date(ical_value($line));
    } elseif (stripos($line, 'DTEND') === 0) {
        [$dtEnd, $dtEndIsDateOnly] = ical_to_date(ical_value($line));
    }
}

$busyDates = array_values(array_keys($busy));
sort($busyDates);

echo json_encode(['ok' => true, 'busy' => $busyDates], JSON_UNESCAPED_UNICODE);
