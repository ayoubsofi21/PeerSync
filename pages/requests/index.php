<?php
require '../../config/Database.php';

$pdo = Database::connect();

$requests = $pdo->query("SELECT * FROM help_requests")->fetchAll();
?>

<h1>All Requests</h1>

<?php foreach ($requests as $r): ?>
    <p>
        <?= $r['title'] ?> - <?= $r['status'] ?>
    </p>
<?php endforeach; ?>