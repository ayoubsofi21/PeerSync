<?php

class Badge
{
    public function __construct(
        private int $id,
        private string $name,
        private string $description,
        private int $required_points
    ) {}
}