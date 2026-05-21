<?php
require_once __DIR__ . "/../entities/HelpRequest.php";
class HelpRequestRepository
{
    public function __construct(private PDO $pdo) {}

    public function create(array $data): void
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO help_requests
            (title, description, technology, student_id)
            VALUES (?, ?, ?, ?)
        ");

        $stmt->execute([
            $data['title'],
            $data['description'],
            $data['technology'],
            $data['student_id']
        ]);
    }

    public function assign(int $id, int $tutorId): void
    {
        $stmt = $this->pdo->prepare("
            UPDATE help_requests
            SET tutor_id = ?, status = 'assigned'
            WHERE id = ?
        ");

        $stmt->execute([$tutorId, $id]);
    }

    public function resolve(int $id): void
    {
        $stmt = $this->pdo->prepare("
            UPDATE help_requests
            SET status = 'resolved'
            WHERE id = ?
        ");

        $stmt->execute([$id]);
    }
    public function findAll(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM help_requests ORDER BY id DESC");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map(function ($r) {
            return [
                'id' => $r['id'],
                'title' => $r['title'],
                'tech' => $r['technology'],
                'desc' => $r['description'],
                'status' => $r['status'],
                'author' => "Student #" . $r['student_id'],
                'date' => $r['created_at'] ?? "now"
            ];
        }, $rows);
    }
}