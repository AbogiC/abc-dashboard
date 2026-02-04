<?php
require_once __DIR__ . '/../models/Project.php';
require_once __DIR__ . '/../utils/Response.php';
require_once __DIR__ . '/../utils/Validator.php';
require_once __DIR__ . '/../middleware/AuthMiddleware.php';

class ProjectController
{
    private $projectModel;
    private $userModel;

    public function __construct()
    {
        $this->projectModel = new Project();
        $this->userModel = new User();
    }

    public function handleRequest($method, $id = null)
    {
        try {
            switch ($method) {
                case 'GET':
                    if ($id) {
                        $this->getProject($id);
                    } else {
                        $this->getProjects();
                    }
                    break;
                case 'POST':
                    $this->createProject();
                    break;
                case 'PUT':
                    $this->updateProject($id);
                    break;
                case 'DELETE':
                    $this->deleteProject($id);
                    break;
                default:
                    Response::error('Method not allowed', HTTP_METHOD_NOT_ALLOWED);
            }
        } catch (Exception $e) {
            Response::error('Server error: ' . $e->getMessage(), HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    private function getProjects()
    {
        $auth = AuthMiddleware::authenticate();
        $userId = $auth['user_id'];

        $page = $_GET['page'] ?? 1;
        $limit = $_GET['limit'] ?? PAGINATION_LIMIT;
        $status = $_GET['status'] ?? null;

        $conditions = ['user_id' => $userId];
        if ($status) {
            $conditions['status'] = $status;
        }

        $projects = $this->projectModel->findAll($conditions, $limit, ($page - 1) * $limit);
        $total = $this->projectModel->count($conditions);

        Response::json(Response::paginate($projects, $page, $limit, $total));
    }

    private function getProject($id)
    {
        $auth = AuthMiddleware::authenticate();
        $userId = $auth['user_id'];

        $project = $this->projectModel->find($id);

        if (!$project || $project['user_id'] != $userId) {
            Response::error('Project not found', HTTP_NOT_FOUND);
        }

        Response::json($project);
    }

    private function createProject()
    {
        $auth = AuthMiddleware::authenticate();
        $userId = $auth['user_id'];

        $data = json_decode(file_get_contents('php://input'), true);

        // Validate required fields
        $errors = Validator::validateRequired($data, ['name', 'category']);
        if (!empty($errors)) {
            Response::error('Validation failed', HTTP_BAD_REQUEST, $errors);
        }

        // Prepare project data
        $projectData = [
            'user_id' => $userId,
            'name' => Validator::sanitize($data['name']),
            'description' => Validator::sanitize($data['description'] ?? ''),
            'category' => Validator::sanitize($data['category']),
            'status' => Validator::sanitize($data['status'] ?? 'pending'),
            'progress' => (int) ($data['progress'] ?? 0),
            'due_date' => $data['due_date'] ?? null,
            'color' => Validator::sanitize($data['color'] ?? '#0d6efd'),
            'icon' => Validator::sanitize($data['icon'] ?? '📁')
        ];

        // Validate date if provided
        if ($projectData['due_date']) {
            $dateError = Validator::validateDate($projectData['due_date']);
            if ($dateError) {
                Response::error($dateError, HTTP_BAD_REQUEST);
            }
        }

        // Create project
        $projectId = $this->projectModel->create($projectData);

        // Log activity
        $this->userModel->logActivity(
            $userId,
            'project_create',
            "Created project: {$projectData['name']}",
            'project',
            $projectId
        );

        Response::json(['id' => $projectId, 'message' => 'Project created successfully'], HTTP_CREATED);
    }

    private function updateProject($id)
    {
        $auth = AuthMiddleware::authenticate();
        $userId = $auth['user_id'];

        // Check if project exists and belongs to user
        $project = $this->projectModel->find($id);
        if (!$project || $project['user_id'] != $userId) {
            Response::error('Project not found', HTTP_NOT_FOUND);
        }

        $data = json_decode(file_get_contents('php://input'), true);

        // Prepare update data
        $updateData = [];
        $allowedFields = ['name', 'description', 'category', 'status', 'progress', 'due_date', 'color', 'icon'];

        foreach ($allowedFields as $field) {
            if (isset($data[$field])) {
                $updateData[$field] = Validator::sanitize($data[$field]);
            }
        }

        if (empty($updateData)) {
            Response::error('No data provided for update', HTTP_BAD_REQUEST);
        }

        // Update project
        $this->projectModel->update($id, $updateData);

        // Log activity
        $this->userModel->logActivity(
            $userId,
            'project_update',
            "Updated project: {$project['name']}",
            'project',
            $id
        );

        Response::json(['message' => 'Project updated successfully']);
    }

    private function deleteProject($id)
    {
        $auth = AuthMiddleware::authenticate();
        $userId = $auth['user_id'];

        // Check if project exists and belongs to user
        $project = $this->projectModel->find($id);
        if (!$project || $project['user_id'] != $userId) {
            Response::error('Project not found', HTTP_NOT_FOUND);
        }

        // Delete project
        $this->projectModel->delete($id);

        // Log activity
        $this->userModel->logActivity(
            $userId,
            'project_delete',
            "Deleted project: {$project['name']}",
            'project',
            $id
        );

        Response::json(['message' => 'Project deleted successfully']);
    }

    public function getStats()
    {
        $auth = AuthMiddleware::authenticate();
        $userId = $auth['user_id'];

        $stats = $this->projectModel->getStats($userId);
        Response::json($stats);
    }
}
?>