<?php
require_once 'config.php';
require_once 'auth.php';
require_once 'db.php';
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

include 'header.php';
?>

<div class="card">
    <div class="card-header">
        <h2>Панель Директора</h2>
    </div>

    <div class="card-grid">
        <div class="card bg-primary-card">
            <h3>Всего Пациентов</h3>
            <p class="stat-value"><?php echo $total_patients; ?></p>
        </div>
        <div class="card bg-success-card">
            <h3>Завершено Приемов</h3>
            <p class="stat-value"><?php echo $total_completed; ?></p>
        </div>
    </div>

    <div class="card">
        <h3>Загруженность Врачей</h3>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Врач</th>
                        <th>Приемов</th>
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
</div>

<?php include 'footer.php'; ?>
