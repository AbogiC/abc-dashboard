<?php
require_once __DIR__ . '/../models/CalendarEvent.php';
require_once __DIR__ . '/../utils/Response.php';
require_once __DIR__ . '/../utils/Validator.php';
require_once __DIR__ . '/../middleware/AuthMiddleware.php';

class CalendarController
{
    private $calendarModel;
    private $userModel;

    public function __construct()
    {
        $this->calendarModel = new CalendarEvent();
        $this->userModel = new User();
    }

    public function handleRequest($method, $id = null)
    {
        try {
            switch ($method) {
                case 'GET':
                    if ($id) {
                        $this->getEvent($id);
                    } else {
                        $this->getEvents();
                    }
                    break;
                case 'POST':
                    $this->createEvent();
                    break;
                case 'PUT':
                    $this->updateEvent($id);
                    break;
                case 'DELETE':
                    $this->deleteEvent($id);
                    break;
                default:
                    Response::error('Method not allowed', HTTP_METHOD_NOT_ALLOWED);
            }
        } catch (Exception $e) {
            Response::error('Server error: ' . $e->getMessage(), HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    private function getEvents()
    {
        $auth = AuthMiddleware::authenticate();
        $userId = $auth['user_id'];

        $month = $_GET['month'] ?? date('m');
        $year = $_GET['year'] ?? date('Y');

        $events = $this->calendarModel->getByUserId($userId, $month, $year);
        Response::json($events);
    }

    private function getEvent($id)
    {
        $auth = AuthMiddleware::authenticate();
        $userId = $auth['user_id'];

        $event = $this->calendarModel->find($id);

        if (!$event || $event['user_id'] != $userId) {
            Response::error('Event not found', HTTP_NOT_FOUND);
        }

        Response::json($event);
    }

    private function createEvent()
    {
        $auth = AuthMiddleware::authenticate();
        $userId = $auth['user_id'];

        $data = json_decode(file_get_contents('php://input'), true);

        // Validate required fields
        $errors = Validator::validateRequired($data, ['title', 'event_date']);
        if (!empty($errors)) {
            Response::error('Validation failed', HTTP_BAD_REQUEST, $errors);
        }

        // Prepare event data
        $eventData = [
            'user_id' => $userId,
            'title' => Validator::sanitize($data['title']),
            'description' => Validator::sanitize($data['description'] ?? ''),
            'event_date' => Validator::sanitize($data['event_date']),
            'event_time' => $data['event_time'] ?? null,
            'end_time' => $data['end_time'] ?? null,
            'type' => Validator::sanitize($data['type'] ?? 'meeting'),
            'location' => Validator::sanitize($data['location'] ?? ''),
            'color' => Validator::sanitize($data['color'] ?? '#0d6efd'),
            'is_all_day' => $data['is_all_day'] ?? false
        ];

        // Validate date
        $dateError = Validator::validateDate($eventData['event_date']);
        if ($dateError) {
            Response::error($dateError, HTTP_BAD_REQUEST);
        }

        // Create event
        $eventId = $this->calendarModel->create($eventData);

        // Log activity
        $this->userModel->logActivity(
            $userId,
            'event_create',
            "Created event: {$eventData['title']}",
            'event',
            $eventId
        );

        Response::json(['id' => $eventId, 'message' => 'Event created successfully'], HTTP_CREATED);
    }

    private function updateEvent($id)
    {
        $auth = AuthMiddleware::authenticate();
        $userId = $auth['user_id'];

        // Check if event exists and belongs to user
        $event = $this->calendarModel->find($id);
        if (!$event || $event['user_id'] != $userId) {
            Response::error('Event not found', HTTP_NOT_FOUND);
        }

        $data = json_decode(file_get_contents('php://input'), true);

        // Prepare update data
        $updateData = [];
        $allowedFields = ['title', 'description', 'event_date', 'event_time', 'end_time', 'type', 'location', 'color', 'is_all_day'];

        foreach ($allowedFields as $field) {
            if (isset($data[$field])) {
                $updateData[$field] = Validator::sanitize($data[$field]);
            }
        }

        if (empty($updateData)) {
            Response::error('No data provided for update', HTTP_BAD_REQUEST);
        }

        // Update event
        $this->calendarModel->update($id, $updateData);

        // Log activity
        $this->userModel->logActivity(
            $userId,
            'event_update',
            "Updated event: {$event['title']}",
            'event',
            $id
        );

        Response::json(['message' => 'Event updated successfully']);
    }

    private function deleteEvent($id)
    {
        $auth = AuthMiddleware::authenticate();
        $userId = $auth['user_id'];

        // Check if event exists and belongs to user
        $event = $this->calendarModel->find($id);
        if (!$event || $event['user_id'] != $userId) {
            Response::error('Event not found', HTTP_NOT_FOUND);
        }

        // Delete event
        $this->calendarModel->delete($id);

        // Log activity
        $this->userModel->logActivity(
            $userId,
            'event_delete',
            "Deleted event: {$event['title']}",
            'event',
            $id
        );

        Response::json(['message' => 'Event deleted successfully']);
    }

    public function getUpcoming()
    {
        $auth = AuthMiddleware::authenticate();
        $userId = $auth['user_id'];

        $limit = $_GET['limit'] ?? 10;
        $events = $this->calendarModel->getUpcoming($userId, $limit);

        Response::json($events);
    }

    public function getByDateRange()
    {
        $auth = AuthMiddleware::authenticate();
        $userId = $auth['user_id'];

        $startDate = $_GET['start_date'] ?? date(DATE_FORMAT);
        $endDate = $_GET['end_date'] ?? date(DATE_FORMAT, strtotime('+1 month'));

        $events = $this->calendarModel->getByDateRange($userId, $startDate, $endDate);
        Response::json($events);
    }
}
?>