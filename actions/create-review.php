<?php

require '../config/Database.php';
require '../repositories/ReviewRepository.php';

$pdo = Database::connect();
$repo = new ReviewRepository($pdo);

$repo->create(
    $_POST['help_request_id'],
    $_POST['rating'],
    $_POST['comment']
);

header("Location: ../pages/dashboard/student.php");