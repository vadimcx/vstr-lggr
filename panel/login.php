<?php
require_once 'auth.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
        $error = 'TOKEN ERR.';
    } elseif (attemptLogin($_POST['username'], $_POST['password'])) {
        header('Location: admin.php');
        exit;
    } else {
        $error = 'USERNAME OR PASSWORD INCORECT';
    }
}

$csrfToken = generateCsrfToken();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
   <link rel="stylesheet" href="css/chota.min.css">
    <link rel="stylesheet" href="css/panel.login.css">

</head>
<body>

<div class="centered">
    <div class="card login-box">
        <div class="card-body">
            <h2 class="is-center">🔐 Admin Login</h2>

            <?php if (!empty($_GET['timeout'])): ?>
                <p class="is-warning">⏱️ SESSION TIME OUT</p>
            <?php endif; ?>

            <?php if ($error): ?>
                <p class="is-error"><?= htmlspecialchars($error) ?></p>
            <?php endif; ?>

            <form method="POST">
                <div class="form-group">
                    <label for="username">User</label>
                    <input id="username" type="text" name="username" required>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input id="password" type="password" name="password" required>
                </div>

                <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">

                <button type="submit" class="button primary">Login</button>
            </form>
        </div>
    </div>
</div>

</body>
</html>
