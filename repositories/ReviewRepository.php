<?php

class ReviewRepository
{
    public function __construct(private PDO $pdo) {}

    public function create(int $helpRequestId, int $rating, string $comment): void
    {
        if ($rating < 1 || $rating > 5) {
            throw new Exception("Invalid rating");
        }

        $stmt = $this->pdo->prepare("
            INSERT INTO reviews (help_request_id, rating, comment)
            VALUES (?, ?, ?)
        ");

        $stmt->execute([$helpRequestId, $rating, $comment]);
    }
}