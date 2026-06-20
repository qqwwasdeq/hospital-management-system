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
        $error = "All fields are required.";
    } else {
        try {
            $pdo->beginTransaction();

            $stmt = $pdo->prepare("INSERT INTO users (login, password_hash, role) VALUES (?, ?, 'patient')");
            $stmt->execute([$login, password_hash($password, PASSWORD_DEFAULT)]);
            $user_id = $pdo->lastInsertId();

            $medical_card_num = 'MC-' . strtoupper(substr(md5(uniqid()), 0, 8));
            $stmt = $pdo->prepare("INSERT INTO patients (user_id, full_name, passport, insurance_policy, medical_card_num) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$user_id, $full_name, $passport, $insurance_policy, $medical_card_num]);

            $pdo->commit();
            header("Location: login.php?registered=1");
            exit;
        } catch (PDOException $e) {
            $pdo->rollBack();
            $error = "Registration failed: " . $e->getMessage();
        }
    }
}

include 'includes/header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-primary text-white"><h4>Patient Registration</h4></div>
            <div class="card-body">
                <?php if ($error): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="full_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Passport</label>
                        <input type="text" name="passport" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Insurance Policy</label>
                        <input type="text" name="insurance_policy" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Login</label>
                        <input type="text" name="login" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Register</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
