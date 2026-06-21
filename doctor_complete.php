<?php
require_once 'config.php';
require_once 'auth.php';
require_once 'db.php';
requireRole('doctor');

$appointment_id = $_GET['id'] ?? null;
if (!$appointment_id) {
    header("Location: doctor_dashboard.php");
    exit;
}
$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT id FROM doctors WHERE user_id = ?");
$stmt->execute([$user_id]);
$doctor = $stmt->fetch();
$doctor_id = $doctor['id'];

$stmt = $pdo->prepare("
    SELECT a.*, p.full_name as patient_name
    FROM appointments a
    JOIN patients p ON a.patient_id = p.id
    WHERE a.id = ? AND a.doctor_id = ? AND a.status = 'booked'
");
$stmt->execute([$appointment_id, $doctor_id]);
$appointment = $stmt->fetch();

if (!$appointment) { die("Ошибка."); }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $symptoms = $_POST['symptoms'];
    $diagnosis = $_POST['diagnosis'];
    $treatment = $_POST['treatment'];
    $stmt = $pdo->prepare("UPDATE appointments SET symptoms = ?, diagnosis = ?, treatment = ?, status = 'completed' WHERE id = ?");
    $stmt->execute([$symptoms, $diagnosis, $treatment, $appointment_id]);
    header("Location: doctor_dashboard.php?completed=1");
    exit;
}
include 'header.php';
?>
<div class="card card-complete">
    <h2>Завершение Приема</h2>
    <p>Пациент: <?php echo htmlspecialchars($appointment['patient_name']); ?></p>
    <form method="POST">
        <div class="form-group">
            <label class="form-label">Симптомы</label>
            <textarea name="symptoms" class="form-control" rows="3" required></textarea>
        </div>
        <div class="form-group">
            <label class="form-label">Диагноз</label>
            <textarea name="diagnosis" class="form-control" rows="3" required></textarea>
        </div>
        <div class="form-group">
            <label class="form-label">Лечение</label>
            <textarea name="treatment" class="form-control" rows="3" required></textarea>
        </div>
        <button type="submit" class="btn btn-success">Завершить</button>
        <a href="doctor_dashboard.php" class="btn btn-secondary">Отмена</a>
    </form>
</div>
<?php include 'footer.php'; ?>
