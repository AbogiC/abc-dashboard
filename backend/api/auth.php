<?php
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../utils/Response.php';
require_once __DIR__ . '/../utils/Validator.php';
require_once __DIR__ . '/../middleware/AuthMiddleware.php';

// Handle CORS
header("Access-Control-Allow-Origin: " . (ALLOWED_ORIGINS[0] ?? '*'));
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Credentials: true");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit;
}

$userModel = new User();
$method = $_SERVER['REQUEST_METHOD'];

try {
    switch ($method) {
        case 'POST':
            $action = $_GET['action'] ?? 'login';

            switch ($action) {
                case 'login':
                    login($userModel);
                    break;
                case 'register':
                    register($userModel);
                    break;
                case 'logout':
                    logout();
                    break;
                default:
                    Response::error('Invalid action', HTTP_BAD_REQUEST);
            }
            break;
        case 'GET':
            $action = $_GET['action'] ?? 'profile';

            switch ($action) {
                case 'profile':
                    getProfile($userModel);
                    break;
                case 'verify':
                    verifyToken();
                    break;
                default:
                    Response::error('Invalid action', HTTP_BAD_REQUEST);
            }
            break;
        default:
            Response::error('Method not allowed', HTTP_METHOD_NOT_ALLOWED);
    }
} catch (Exception $e) {
    Response::error('Server error: ' . $e->getMessage(), HTTP_INTERNAL_SERVER_ERROR);
}

function login($userModel)
{
    $data = json_decode(file_get_contents('php://input'), true);

    // Validate required fields
    $errors = Validator::validateRequired($data, ['username', 'password']);
    if (!empty($errors)) {
        Response::error('Validation failed', HTTP_BAD_REQUEST, $errors);
    }

    $username = Validator::sanitize($data['username']);
    $password = $data['password'];

    // Find user by username or email
    $user = $userModel->findByUsername($username);
    if (!$user) {
        $user = $userModel->findByEmail($username);
    }

    if (!$user || !$userModel->verifyPassword($password, $user['password_hash'])) {
        Response::error('Invalid username or password', HTTP_UNAUTHORIZED);
    }

    // Generate token
    $token = AuthMiddleware::generateToken($user['id'], $user['username'], $user['role']);

    // Log activity
    $userModel->logActivity($user['id'], 'login', 'User logged in');

    Response::json([
        'token' => $token,
        'user' => [
            'id' => $user['id'],
            'username' => $user['username'],
            'email' => $user['email'],
            'full_name' => $user['full_name'],
            'avatar_url' => $user['avatar_url'],
            'role' => $user['role']
        ]
    ]);
}

function register($userModel)
{
    $data = json_decode(file_get_contents('php://input'), true);

    // Validate required fields
    $errors = Validator::validateRequired($data, ['username', 'email', 'password']);
    if (!empty($errors)) {
        Response::error('Validation failed', HTTP_BAD_REQUEST, $errors);
    }

    // Validate email
    $emailError = Validator::validateEmail($data['email']);
    if ($emailError) {
        Response::error($emailError, HTTP_BAD_REQUEST);
    }

    // Validate password
    $passwordError = Validator::validatePassword($data['password']);
    if ($passwordError) {
        Response::error($passwordError, HTTP_BAD_REQUEST);
    }

    // Check if username already exists
    if ($userModel->findByUsername($data['username'])) {
        Response::error('Username already exists', HTTP_BAD_REQUEST);
    }

    // Check if email already exists
    if ($userModel->findByEmail($data['email'])) {
        Response::error('Email already exists', HTTP_BAD_REQUEST);
    }

    // Prepare user data
    $userData = [
        'username' => Validator::sanitize($data['username']),
        'email' => Validator::sanitize($data['email']),
        'password_hash' => $userModel->hashPassword($data['password']),
        'full_name' => Validator::sanitize($data['full_name'] ?? ''),
        'role' => 'user'
    ];

    // Create user
    $userId = $userModel->create($userData);

    Response::json(['id' => $userId, 'message' => 'User registered successfully'], HTTP_CREATED);
}

function logout()
{
    // Invalidate token on client side
    Response::json(['message' => 'Logged out successfully']);
}

function getProfile($userModel)
{
    $auth = AuthMiddleware::authenticate();
    $userId = $auth['user_id'];

    $profile = $userModel->getProfile($userId);

    if (!$profile) {
        Response::error('Profile not found', HTTP_NOT_FOUND);
    }

    Response::json($profile);
}

function verifyToken()
{
    try {
        $auth = AuthMiddleware::authenticate();
        Response::json(['valid' => true, 'user' => $auth]);
    } catch (Exception $e) {
        Response::json(['valid' => false, 'message' => $e->getMessage()]);
    }
}
?>