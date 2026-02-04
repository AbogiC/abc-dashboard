<?php
require_once __DIR__ . '/../config/database.php';

abstract class BaseModel
{
    protected $db;
    protected $table;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function find($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function findAll($conditions = [], $limit = PAGINATION_LIMIT, $offset = 0)
    {
        $where = '';
        $params = [];

        if (!empty($conditions)) {
            $where = 'WHERE ' . implode(' AND ', array_map(function ($key) {
                return "$key = ?";
            }, array_keys($conditions)));
            $params = array_values($conditions);
        }

        $stmt = $this->db->prepare("SELECT * FROM {$this->table} $where LIMIT ? OFFSET ?");
        $params[] = $limit;
        $params[] = $offset;
        $stmt->execute($params);

        return $stmt->fetchAll();
    }

    public function count($conditions = [])
    {
        $where = '';
        $params = [];

        if (!empty($conditions)) {
            $where = 'WHERE ' . implode(' AND ', array_map(function ($key) {
                return "$key = ?";
            }, array_keys($conditions)));
            $params = array_values($conditions);
        }

        $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM {$this->table} $where");
        $stmt->execute($params);
        return $stmt->fetch()['count'];
    }

    public function create($data)
    {
        $fields = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));

        $stmt = $this->db->prepare("INSERT INTO {$this->table} ($fields) VALUES ($placeholders)");
        $stmt->execute(array_values($data));

        return $this->db->lastInsertId();
    }

    public function update($id, $data)
    {
        $set = implode(', ', array_map(function ($key) {
            return "$key = ?";
        }, array_keys($data)));

        $params = array_values($data);
        $params[] = $id;

        $stmt = $this->db->prepare("UPDATE {$this->table} SET $set WHERE id = ?");
        return $stmt->execute($params);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
?>