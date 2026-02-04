<?php
// Error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'abc_dashboard');
define('DB_USER', 'root');
define('DB_PASS', 'tester');

// JWT Secret key
define('JWT_SECRET', 'your-secret-key-change-this');
define('JWT_ALGORITHM', 'HS256');

// CORS configuration
define('ALLOWED_ORIGINS', [
    'http://localhost:5173', // Vue dev server
    'http://localhost:3000',
    'http://localhost:8080',
    'http://localhost:8000'
]);

// API configuration
define('API_VERSION', 'v1');
define('PAGINATION_LIMIT', 10);

// Response codes
define('HTTP_OK', 200);
define('HTTP_CREATED', 201);
define('HTTP_BAD_REQUEST', 400);
define('HTTP_UNAUTHORIZED', 401);
define('HTTP_FORBIDDEN', 403);
define('HTTP_NOT_FOUND', 404);
define('HTTP_METHOD_NOT_ALLOWED', 405);
define('HTTP_INTERNAL_SERVER_ERROR', 500);

// Date format
define('DATE_FORMAT', 'Y-m-d');
define('DATETIME_FORMAT', 'Y-m-d H:i:s');
?>