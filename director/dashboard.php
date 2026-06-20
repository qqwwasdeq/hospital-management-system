<?php
require_once '../config.php';
require_once '../includes/auth.php';
require_once '../includes/db.php';
requireRole('director');

$total_patients = $pdo->query("SELECT COUNT(*) FROM patients")->fetchColumn();
$total_completed = $pdo->query("SELECT COUNT(*) FROM appointments WHERE status = 'completed'")->fetchColumn();

$stmt = $pdo->query("
    SELECT d.full_name, COUNT(a.id) as appointment_count
    FROM doctors d
    LEFT JOIN appointments a ON d.id = a.doctor_id AND a.status = 'completed'
    GROUP BY d.id
");
$workload = $stmt->fetchAll();

include '../includes/header.php';
?>

<h2>Панель Директора: Аналитика Больницы</h2>

<div class="row mb-4">
    <div class="col-md-6">
        <div class="card bg-info text-white shadow-sm">
            <div class="card-body text-center">
                <h5 class="card-title">Всего Зарегистрировано Пациентов</h5>
                <h2 class="card-text display-4"><?php echo $total_patients; ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card bg-success text-white shadow-sm">
            <div class="card-body text-center">
                <h5 class="card-title">Завершенных Приемов</h5>
                <h2 class="card-text display-4"><?php echo $total_completed; ?></h2>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-header bg-dark text-white">
        <h4 class="mb-0">Загруженность Врачей (Завершенные Приемы)</h4>
    </div>
    <div class="card-body">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>ФИО Врача</th>
                    <th>Проведено Приемов</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($workload as $row): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['full_name']); ?></td>
                    <td><?php echo $row['appointment_count']; ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
