<?php

class HelpRequest
{
    public function __construct(
        private int $id,
        private string $title,
        private string $description,
        private string $technology,
        private Status $status,
        private int $student_id,
        private ?int $tutor_id
    ) {}

    public function getId(): int { return $this->id; }
    public function getStatus(): Status { return $this->status; }

    public function assign(int $tutorId): void
    {
        $this->tutor_id = $tutorId;
        $this->status = Status::ASSIGNED;
    }

    public function resolve(): void
    {
        $this->status = Status::RESOLVED;
    }
}