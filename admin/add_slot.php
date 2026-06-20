<?php
require_once '../config.php';
require_once '../includes/auth.php';
require_once '../includes/db.php';
requireRole('admin');

$doctors = $pdo->query("SELECT id, full_name FROM doctors")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $doctor_id = $_POST['doctor_id'];
    $date = $_POST['date'];
    $time = $_POST['time'];

    $stmt = $pdo->prepare("INSERT INTO schedule (doctor_id, appointment_date, appointment_time, is_available) VALUES (?, ?, ?, 1)");
    $stmt->execute([$doctor_id, $date, $time]);

    header("Location: /admin/dashboard.php?slot_added=1");
    exit;
}

include '../includes/header.php';
?>

<h2>Добавить новый слот в расписание</h2>

<div class="card col-md-6 shadow-sm">
    <div class="card-body">
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Врач</label>
                <select name="doctor_id" class="form-select" required>
                    <?php foreach ($doctors as $doc): ?>
                        <option value="<?php echo $doc['id']; ?>"><?php echo htmlspecialchars($doc['full_name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Дата</label>
                <input type="date" name="date" class="form-control" required min="<?php echo date('Y-m-d'); ?>">
            </div>
            <div class="mb-3">
                <label class="form-label">Время</label>
                <input type="time" name="time" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Добавить слот</button>
            <a href="/admin/dashboard.php" class="btn btn-secondary">Назад</a>
        </form>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
