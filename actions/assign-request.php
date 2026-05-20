<?php

require '../config/Database.php';
require '../repositories/HelpRequestRepository.php';

$pdo = Database::connect();
$repo = new HelpRequestRepository($pdo);

$repo->assign($_POST['request_id'], $_POST['tutor_id']);

header("Location: ../pages/requests/index.php");