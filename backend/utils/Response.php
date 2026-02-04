<?php
class Response
{
    public static function json($data = null, $status = HTTP_OK, $message = '')
    {
        self::setHeaders();

        $response = [
            'success' => $status >= 200 && $status < 300,
            'status' => $status,
            'message' => $message,
            'data' => $data,
            'timestamp' => date(DATETIME_FORMAT)
        ];

        http_response_code($status);
        echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        exit;
    }

    public static function error($message = 'An error occurred', $status = HTTP_BAD_REQUEST, $errors = [])
    {
        self::setHeaders();

        $response = [
            'success' => false,
            'status' => $status,
            'message' => $message,
            'errors' => $errors,
            'timestamp' => date(DATETIME_FORMAT)
        ];

        http_response_code($status);
        echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        exit;
    }

    private static function setHeaders()
    {
        // Set CORS headers
        $origin = $_SERVER['HTTP_ORIGIN'] ?? '';
        if (in_array($origin, ALLOWED_ORIGINS)) {
            header("Access-Control-Allow-Origin: $origin");
        }

        header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
        header("Access-Control-Allow-Credentials: true");
        header("Content-Type: application/json; charset=UTF-8");
        header("Access-Control-Max-Age: 3600");

        // Handle preflight requests
        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
            http_response_code(200);
            exit;
        }
    }

    public static function paginate($data, $page, $limit, $total)
    {
        return [
            'data' => $data,
            'pagination' => [
                'page' => (int) $page,
                'limit' => (int) $limit,
                'total' => (int) $total,
                'pages' => ceil($total / $limit)
            ]
        ];
    }
}
?>