<?php
require_once 'includes/config.php';
require_once 'includes/auth.php';
require_once 'includes/db.php';

$error = '';
if (isset($_GET['msg'])) { $error = htmlspecialchars($_GET['msg']); }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = $_POST['login'];
    $password = $_POST['password'];
    $stmt = $pdo->prepare("SELECT * FROM users WHERE login = ?");
    $stmt->execute([$login]);
    $user = $stmt->fetch();
    if ($user && password_verify($password, $user['password_hash'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['login'] = $user['login'];
        redirectBasedOnRole($user['role']);
    } else {
        $error = "Неверный логин или пароль.";
    }
}
include 'includes/header.php';
?>
<div class="card card-login">
    <div class="card-header">
        <h2>Вход в систему</h2>
    </div>
    <?php if (isset($_GET['registered'])): ?>
        <div class="alert alert-success">Регистрация успешна!</div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>
    <form method="POST">
        <div class="form-group">
            <label class="form-label">Логин</label>
            <input type="text" name="login" class="form-control" required>
        </div>
        <div class="form-group">
            <label class="form-label">Пароль</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary btn-block">Войти</button>
    </form>
    <p class="text-center mt-15">
        <a href="register.php">Зарегистрироваться как Пациент</a>
    </p>
</div>
<?php include 'includes/footer.php'; ?>
