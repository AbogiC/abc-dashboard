<?php
require_once __DIR__ . '/BaseModel.php';

class User extends BaseModel
{
    protected $table = 'users';

    public function __construct()
    {
        parent::__construct();
    }

    public function findByUsername($username)
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        return $stmt->fetch();
    }

    public function findByEmail($email)
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch();
    }

    public function verifyPassword($password, $hash)
    {
        return password_verify($password, $hash);
    }

    public function hashPassword($password)
    {
        return password_hash($password, PASSWORD_BCRYPT);
    }

    public function getProfile($userId)
    {
        $stmt = $this->db->prepare("
            SELECT 
                u.*,
                COUNT(DISTINCT p.id) as total_projects,
                COUNT(DISTINCT t.id) as total_tasks,
                COUNT(DISTINCT ce.id) as total_events,
                GROUP_CONCAT(DISTINCT s.name) as skill_names
            FROM users u
            LEFT JOIN projects p ON u.id = p.user_id
            LEFT JOIN tasks t ON u.id = t.user_id
            LEFT JOIN calendar_events ce ON u.id = ce.user_id
            LEFT JOIN skills s ON u.id = s.user_id
            WHERE u.id = ?
            GROUP BY u.id
        ");

        $stmt->execute([$userId]);
        $profile = $stmt->fetch();

        if ($profile) {
            $stmt = $this->db->prepare("
                SELECT name, level, color
                FROM skills
                WHERE user_id = ?
                ORDER BY level DESC
            ");
            $stmt->execute([$userId]);
            $profile['skills'] = $stmt->fetchAll();

            $stmt = $this->db->prepare("
                SELECT 
                    action_type,
                    description,
                    created_at
                FROM activity_log
                WHERE user_id = ?
                ORDER BY created_at DESC
                LIMIT 10
            ");
            $stmt->execute([$userId]);
            $profile['recent_activity'] = $stmt->fetchAll();
        }

        return $profile;
    }

    public function logActivity($userId, $actionType, $description, $resourceType = null, $resourceId = null)
    {
        $ipAddress = $_SERVER['REMOTE_ADDR'] ?? null;
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? null;

        $stmt = $this->db->prepare("
            INSERT INTO activity_log 
            (user_id, action_type, description, resource_type, resource_id, ip_address, user_agent)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");

        return $stmt->execute([
            $userId,
            $actionType,
            $description,
            $resourceType,
            $resourceId,
            $ipAddress,
            $userAgent
        ]);
    }
}
?>