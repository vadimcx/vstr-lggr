<?php

require_once 'dataHandler.php';
require_once 'ipHandler.php';

$config = require 'config.php';
$allowedReferer = $config['allowedReferer'];

// === REJECT IF REFERER IS INVALID (Both GET & POST) ===
if (!isset($_SERVER['HTTP_REFERER']) || strpos($_SERVER['HTTP_REFERER'], $allowedReferer) !== 0) {
    http_response_code(403);
    exit('NOT ALLOWED');
}

// === HANDLE POST REQUEST (Visitor Logging) ===
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $pageUrl = $input['pageUrl'] ?? null;

    if (!filter_var($pageUrl, FILTER_VALIDATE_URL)) {
        http_response_code(400);
        exit('Invalid URL');
    }

    $visitorData = buildVisitorData();
    if ($visitorData) {
        $visitorData['called_url'] = $pageUrl;

        insertOrUpdateVisitor($visitorData, $config);
        http_response_code(200);
        echo json_encode(['status' => 'success']);
    } else {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'Visitor data not available']);
    }

    exit;
}

// === HANDLE GET REQUEST (Fetch Visitors Between Dates) ===
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $startDate = $_GET['startDate'] ?? null;
    $endDate = $_GET['endDate'] ?? null;

    if (!$startDate || !$endDate) {
        http_response_code(400);
        exit(json_encode(['status' => 'error', 'message' => 'Missing startDate or endDate']));
    }

    if (!validateDate($startDate) || !validateDate($endDate)) {
        http_response_code(400);
        exit(json_encode(['status' => 'error', 'message' => 'Invalid date format. Use YYYY-MM-DD']));
    }

    $visitors = getVisitorsBetweenDates($startDate, $endDate, $config);

    header('Content-Type: application/json');
    echo json_encode([
        'status' => 'success',
        'count' => count($visitors),
        'data' => $visitors
    ]);
    exit;
}

// === UNSUPPORTED METHOD ===
http_response_code(405);
exit(json_encode(['status' => 'error', 'message' => 'Method Not Allowed']));


// === HELPER: Date Format Validation ===
function validateDate($date, $format = 'Y-m-d') {
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) === $date;
}
