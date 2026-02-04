<?php
require_once __DIR__ . '/BaseModel.php';

class Project extends BaseModel
{
    protected $table = 'projects';

    public function __construct()
    {
        parent::__construct();
    }

    public function getByUserId($userId, $page = 1, $limit = PAGINATION_LIMIT)
    {
        $offset = ($page - 1) * $limit;

        $stmt = $this->db->prepare("
            SELECT p.*, 
                   GROUP_CONCAT(DISTINCT s.name) as skills,
                   COUNT(DISTINCT t.id) as total_tasks,
                   SUM(CASE WHEN t.status = 'completed' THEN 1 ELSE 0 END) as completed_tasks
            FROM projects p
            LEFT JOIN tasks t ON p.id = t.project_id
            LEFT JOIN skills s ON p.category = s.name AND s.user_id = p.user_id
            WHERE p.user_id = ?
            GROUP BY p.id
            ORDER BY p.created_at DESC
            LIMIT ? OFFSET ?
        ");

        $stmt->execute([$userId, $limit, $offset]);
        $projects = $stmt->fetchAll();

        // Get team members for each project
        foreach ($projects as &$project) {
            $stmt = $this->db->prepare("
                SELECT u.id, u.username, u.full_name, u.avatar_url, pm.role
                FROM project_members pm
                JOIN users u ON pm.user_id = u.id
                WHERE pm.project_id = ?
            ");
            $stmt->execute([$project['id']]);
            $project['team'] = $stmt->fetchAll();
        }

        $total = $this->count(['user_id' => $userId]);

        return [
            'projects' => $projects,
            'total' => $total,
            'page' => $page,
            'limit' => $limit
        ];
    }

    public function getStats($userId)
    {
        $stmt = $this->db->prepare("
            SELECT 
                status,
                COUNT(*) as count,
                AVG(progress) as avg_progress
            FROM projects
            WHERE user_id = ?
            GROUP BY status
        ");
        $stmt->execute([$userId]);

        $statusStats = $stmt->fetchAll();

        $stmt = $this->db->prepare("
            SELECT 
                DATE_FORMAT(due_date, '%Y-%m') as month,
                COUNT(*) as count
            FROM projects
            WHERE user_id = ? AND due_date >= CURDATE()
            GROUP BY DATE_FORMAT(due_date, '%Y-%m')
            ORDER BY month
            LIMIT 6
        ");
        $stmt->execute([$userId]);
        $upcomingStats = $stmt->fetchAll();

        return [
            'status_stats' => $statusStats,
            'upcoming_stats' => $upcomingStats
        ];
    }
}
?>