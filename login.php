<?php
require_once 'config.php';
require_once 'includes/auth.php';
require_once 'includes/db.php';

$error = '';
if (isset($_GET['msg'])) {
    $error = htmlspecialchars($_GET['msg']);
}

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

<div class="row justify-content-center">
    <div class="col-md-4">
        <div class="card shadow">
            <div class="card-header bg-primary text-white text-center"><h4>Вход в систему</h4></div>
            <div class="card-body">
                <?php if (isset($_GET['registered'])): ?>
                    <div class="alert alert-success">Регистрация прошла успешно! Пожалуйста, войдите.</div>
                <?php endif; ?>
                <?php if ($error): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Логин</label>
                        <input type="text" name="login" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Пароль</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Войти</button>
                </form>
            </div>
            <div class="card-footer text-center">
                <a href="/register.php">Нет аккаунта? Зарегистрируйтесь как Пациент</a>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
