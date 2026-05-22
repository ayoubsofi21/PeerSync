    <?php

class HelpRequest
{
    private int $id;
    private string $title;
    private string $description;
    private string $technology;
    private string $status;
    private string $author;
    private ?array $tutor = null;
    private string $date;
    private ?int $rating = null;

    public function __construct(
        int $id,
        string $title,
        string $description,
        string $technology,
        string $status,
        string $author,
        string $date
    ) {
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->technology = $technology;
        $this->status = $status;
        $this->author = $author;
        $this->date = $date;
    }

    // GETTERS ONLY (important critère ENAA)
    public function getId(): int { return $this->id; }
    public function getTitle(): string { return $this->title; }
    public function getDescription(): string { return $this->description; }
    public function getTechnology(): string { return $this->technology; }
    public function getStatus(): string { return $this->status; }
    public function getAuthor(): string { return $this->author; }
    public function getDate(): string { return $this->date; }

    public function getTutor(): ?array { return $this->tutor; }
    public function getRating(): ?int { return $this->rating; }

    public function assignTo(array $tutor): void
    {
        if ($this->author === ($tutor['name'] ?? '')) {
            throw new Exception("Un tuteur ne peut pas s'assigner lui-même");
        }

        $this->tutor = $tutor;
        $this->status = "ASSIGNED";
    }

    public function resolve(): void
    {
        $this->status = "RESOLVED";
    }
}
?>