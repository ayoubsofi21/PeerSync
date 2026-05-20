<?php

session_start();

require '../../config/Database.php';

$pdo = Database::connect();

$error = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = $_POST['email'] ?? null;
    $password = $_POST['password'] ?? null;

    if (!$email || !$password) {
        $error = "Email and password required";
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$user) {
            $error = "User not found";
        } elseif (!password_verify($password, $user['password'])) {
            $error = "Wrong password";
        } else {

            $_SESSION['user'] = [
                'id' => $user['id'],
                'name' => $user['name'],
                'role' => $user['role']
            ];

            if ($user['role'] === 'student') {
                header("Location: ../pages/dashboard/student.php");
                exit;
            } elseif ($user['role'] === 'tutor') {
                header("Location: ../pages/dashboard/tutor.php");
                exit;
            } else {
                header("Location: ../pages/dashboard/admin.php");
                exit;
            }
        }
    }
}
?>

<!-- FORM (same file) -->
<form method="POST">

    <h1>Login</h1>

    <?php if ($error): ?>
        <p style="color:red;">
            <?= $error ?>
        </p>
    <?php endif; ?>

    <input name="email" placeholder="Email" required>
    <input name="password" type="password" placeholder="Password" required>

    <button type="submit">Login</button>

</form>