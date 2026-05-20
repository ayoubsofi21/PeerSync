<?php

class Database
{
    public static function connect(): PDO
    {
        try {
            $pdo = new PDO("mysql:host=localhost;dbname=peersync","root","");
            // echo "Database connected successfully";
            return $pdo;
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
        }
        }
// $db=Database::connect();