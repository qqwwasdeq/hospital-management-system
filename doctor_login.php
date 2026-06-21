<?php
require_once 'includes/config.php';
require_once 'includes/auth.php';
require_once 'includes/db.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = $_POST['login'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE login = ? AND role = 'doctor'");
    $stmt->execute([$login]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password_hash'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['login'] = $user['login'];
        header("Location: doctor_dashboard.php");
        exit;
    } else {
        $error = "Неверный логин врача или пароль.";
    }
}
include 'includes/header.php';
?>

<div class="card auth-card-compact">
    <div class="card-header text-center">
        <h2>Вход для медперсонала</h2>
        <p>Пожалуйста, авторизуйтесь для доступа к кабинету</p>
    </div>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="form-group">
            <label class="form-label">Табельный номер (Логин)</label>
            <input type="text" name="login" class="form-control" placeholder="ivanov" required>
        </div>
        <div class="form-group">
            <label class="form-label">Пароль</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary btn-block btn-sm">Войти в кабинет</button>
    </form>

    <div class="mt-15 text-center">
        <a href="login.php" style="font-size: 0.9rem; color: var(--text-muted);">Вход для пациентов</a>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
