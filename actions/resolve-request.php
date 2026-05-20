<?php

require '../config/Database.php';
require '../repositories/HelpRequestRepository.php';

$pdo = Database::connect();
$repo = new HelpRequestRepository($pdo);

$repo->resolve($_POST['request_id']);

header("Location: ../pages/requests/index.php");