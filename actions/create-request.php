<?php

require '../config/Database.php';
require '../repositories/HelpRequestRepository.php';

$pdo = Database::connect();
$repo = new HelpRequestRepository($pdo);

$repo->create([
    'title' => $_POST['title'],
    'description' => $_POST['description'],
    'technology' => $_POST['technology'],
    'student_id' => $_POST['student_id']
]);

header("Location: ../pages/requests/index.php");
exit();