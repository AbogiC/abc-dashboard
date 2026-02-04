<?php
require_once __DIR__ . '/BaseModel.php';

class CalendarEvent extends BaseModel
{
    protected $table = 'calendar_events';

    public function __construct()
    {
        parent::__construct();
    }

    public function getByUserId($userId, $month = null, $year = null)
    {
        $where = "WHERE user_id = ?";
        $params = [$userId];

        if ($month && $year) {
            $where .= " AND MONTH(event_date) = ? AND YEAR(event_date) = ?";
            $params[] = $month;
            $params[] = $year;
        }

        $stmt = $this->db->prepare("
            SELECT ce.*,
                   GROUP_CONCAT(DISTINCT u.username) as participants
            FROM calendar_events ce
            LEFT JOIN event_participants ep ON ce.id = ep.event_id
            LEFT JOIN users u ON ep.user_id = u.id
            $where
            GROUP BY ce.id
            ORDER BY event_date, event_time
        ");

        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function getByDateRange($userId, $startDate, $endDate)
    {
        $stmt = $this->db->prepare("
            SELECT ce.*,
                   GROUP_CONCAT(DISTINCT u.username) as participants
            FROM calendar_events ce
            LEFT JOIN event_participants ep ON ce.id = ep.event_id
            LEFT JOIN users u ON ep.user_id = u.id
            WHERE ce.user_id = ? 
            AND ce.event_date BETWEEN ? AND ?
            GROUP BY ce.id
            ORDER BY event_date, event_time
        ");

        $stmt->execute([$userId, $startDate, $endDate]);
        return $stmt->fetchAll();
    }

    public function getUpcoming($userId, $limit = 10)
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM calendar_events
            WHERE user_id = ? 
            AND (event_date > CURDATE() OR (event_date = CURDATE() AND event_time > CURTIME()))
            ORDER BY event_date, event_time
            LIMIT ?
        ");

        $stmt->execute([$userId, $limit]);
        return $stmt->fetchAll();
    }
}
?>