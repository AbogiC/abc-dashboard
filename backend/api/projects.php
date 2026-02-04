<?php
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../controllers/ProjectController.php';

// Handle CORS
$origin = $_SERVER['HTTP_ORIGIN'] ?? '';
if (in_array($origin, ALLOWED_ORIGINS)) {
    header("Access-Control-Allow-Origin: $origin");
}
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Credentials: true");
header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit;
}

$controller = new ProjectController();
$method = $_SERVER['REQUEST_METHOD'];

// Get ID from URL if present
$url = $_SERVER['REQUEST_URI'];
$segments = explode('/', trim($url, '/'));
$id = null;

// Check if last segment is numeric (ID) or a special endpoint
$lastSegment = end($segments);
if (is_numeric($lastSegment)) {
    $id = (int) $lastSegment;
} else if ($lastSegment == 'stats') {
    $controller->getStats();
    exit;
}

// Handle request
$controller->handleRequest($method, $id);
?>