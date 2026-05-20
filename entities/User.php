<?php

class User
{
    public function __construct(
        private int $id,
        private string $name,
        private string $email,
        private string $password,
        private string $role,
        private int $points
    ) {}

    public function getId(): int {
         return $this->id;
          }
    public function getName(): string {
         return $this->name; 
         }
    public function getRole(): string {
         return $this->role; 
         }
}