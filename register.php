<?php
require_once 'config.php';
require_once 'includes/auth.php';
require_once 'includes/db.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = $_POST['full_name'];
    $passport = $_POST['passport'];
    $insurance_policy = $_POST['insurance_policy'];
    $login = $_POST['login'];
    $password = $_POST['password'];

    if (empty($full_name) || empty($passport) || empty($insurance_policy) || empty($login) || empty($password)) {
        $error = "Все поля обязательны для заполнения.";
    } else {
        try {
            $pdo->beginTransaction();

            $stmt = $pdo->prepare("INSERT INTO users (login, password_hash, role) VALUES (?, ?, 'patient')");
            $stmt->execute([$login, password_hash($password, PASSWORD_DEFAULT)]);
            $user_id = $pdo->lastInsertId();

            $medical_card_num = 'МК-' . strtoupper(substr(md5(uniqid()), 0, 8));
            $stmt = $pdo->prepare("INSERT INTO patients (user_id, full_name, passport, insurance_policy, medical_card_num) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$user_id, $full_name, $passport, $insurance_policy, $medical_card_num]);

            $pdo->commit();
            header("Location: /login.php?registered=1");
            exit;
        } catch (PDOException $e) {
            $pdo->rollBack();
            $error = "Ошибка регистрации: " . $e->getMessage();
        }
    }
}

include 'includes/header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow">
            <div class="card-header bg-primary text-white"><h4>Регистрация Пациента</h4></div>
            <div class="card-body">
                <?php if ($error): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">ФИО</label>
                        <input type="text" name="full_name" class="form-control" required placeholder="Иванов Иван Иванович">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Паспорт</label>
                        <input type="text" name="passport" class="form-control" required placeholder="Серия и номер">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Полис ОМС</label>
                        <input type="text" name="insurance_policy" class="form-control" required placeholder="Номер полиса">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Логин</label>
                        <input type="text" name="login" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Пароль</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Зарегистрироваться</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
