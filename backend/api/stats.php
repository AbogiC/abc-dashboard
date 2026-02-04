<?php
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../models/Project.php';
require_once __DIR__ . '/../models/CalendarEvent.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../utils/Response.php';
require_once __DIR__ . '/../middleware/AuthMiddleware.php';

// Handle CORS
$origin = $_SERVER['HTTP_ORIGIN'] ?? '';
if (in_array($origin, ALLOWED_ORIGINS)) {
    header("Access-Control-Allow-Origin: $origin");
}
header("Access-Control-Allow-Methods: GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Credentials: true");
header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit;
}

$auth = AuthMiddleware::authenticate();
$userId = $auth['user_id'];

$projectModel = new Project();
$calendarModel = new CalendarEvent();
$userModel = new User();

try {
    $method = $_SERVER['REQUEST_METHOD'];

    if ($method != 'GET') {
        Response::error('Method not allowed', HTTP_METHOD_NOT_ALLOWED);
    }

    // Get dashboard stats
    $projectStats = $projectModel->getStats($userId);

    // Get upcoming events
    $upcomingEvents = $calendarModel->getUpcoming($userId, 5);

    // Get task stats
    $db = Database::getConnection();
    $stmt = $db->prepare("
        SELECT 
            status,
            COUNT(*) as count
        FROM tasks
        WHERE user_id = ?
        GROUP BY status
    ");
    $stmt->execute([$userId]);
    $taskStats = $stmt->fetchAll();

    // Get recent activity
    $stmt = $db->prepare("
        SELECT 
            action_type,
            description,
            created_at
        FROM activity_log
        WHERE user_id = ?
        ORDER BY created_at DESC
        LIMIT 5
    ");
    $stmt->execute([$userId]);
    $recentActivity = $stmt->fetchAll();

    // Compile all stats
    $stats = [
        'projects' => $projectStats,
        'tasks' => $taskStats,
        'upcoming_events' => $upcomingEvents,
        'recent_activity' => $recentActivity
    ];

    Response::json($stats);

} catch (Exception $e) {
    Response::error('Server error: ' . $e->getMessage(), HTTP_INTERNAL_SERVER_ERROR);
}
?>