<?php

class Review
{
    public function __construct(
        private int $id,
        private int $help_request_id,
        private int $rating,
        private string $comment
    ) {
        if ($rating < 1 || $rating > 5) {
            throw new Exception("Rating must be 1-5");
        }
    }
}