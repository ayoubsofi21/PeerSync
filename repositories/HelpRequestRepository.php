<?php

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
}