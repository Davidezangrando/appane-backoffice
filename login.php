<?php
require_once __DIR__ . '/config/database.php';

// DA FARE: gestione login
// if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//     $username = $_POST['username'];
//     $password = $_POST['password'];
//
//     $stmt = $conn->prepare("SELECT * FROM tAdmin WHERE Username = ?");
//     $stmt->execute([$username]);
//     $admin = $stmt->fetch();
//
//     if ($admin && password_verify($password, $admin['Password'])) {
//         $_SESSION['admin'] = $admin['idAdmin'];
//         header('Location: /appane-backoffice/menu.php');
//         exit;
//     } else {
//         $errore = 'Username o password errati.';
//     }
// }
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — AppPane Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="<?= SITE_URL ?>/assets/css/admin.css" rel="stylesheet">
</head>
<body>

<div class="login-wrapper">
    <div class="login-card">

        <div class="text-center mb-4">
            <div style="font-size:2.5rem; color:#8B5E3C;">
                <i class="bi bi-shop-window"></i>
            </div>
            <h4 class="fw-bold mt-1" style="color:#5C3A1E;">AppPane Admin</h4>
            <p class="text-muted small">Accedi al pannello di gestione</p>
        </div>

        <?php if (isset($errore)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($errore) ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label class="form-label fw-semibold">Username</label>
                <input type="text" name="username" class="form-control" placeholder="Inserisci username" required autofocus>
            </div>
            <div class="mb-4">
                <label class="form-label fw-semibold">Password</label>
                <input type="password" name="password" class="form-control" placeholder="Inserisci password" required>
            </div>
            <button type="submit" class="btn btn-bread w-100">
                <i class="bi bi-box-arrow-in-right me-2"></i>Accedi
            </button>
        </form>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
