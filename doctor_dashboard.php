<?php
require_once 'includes/config.php';
require_once 'includes/auth.php';
require_once 'includes/db.php';
requireRole('doctor');

$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT id, full_name FROM doctors WHERE user_id = ?");
$stmt->execute([$user_id]);
$doctor = $stmt->fetch();
$doctor_id = $doctor['id'];

$today = date('Y-m-d');
$stmt = $pdo->prepare("
    SELECT a.id, p.full_name as patient_name, s.appointment_time, a.status
    FROM appointments a
    JOIN patients p ON a.patient_id = p.id
    JOIN schedule s ON a.schedule_id = s.id
    WHERE a.doctor_id = ? AND s.appointment_date = ? AND a.status = 'booked'
    ORDER BY s.appointment_time
");
$stmt->execute([$doctor_id, $today]);
$appointments = $stmt->fetchAll();

include 'includes/header.php';
?>

<div class="card">
    <div class="card-header">
        <h2>Кабинет Врача: <?php echo htmlspecialchars($doctor['full_name']); ?></h2>
        <h4>Приемы на сегодня (<?php echo $today; ?>)</h4>
    </div>

    <?php if (isset($_GET['completed'])): ?>
        <div class="alert alert-success">Прием успешно завершен.</div>
    <?php endif; ?>

    <?php if (empty($appointments)): ?>
        <div class="alert alert-info">На сегодня приемов не запланировано.</div>
    <?php else: ?>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Время</th>
                        <th>Пациент</th>
                        <th>Статус</th>
                        <th>Действие</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($appointments as $app): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($app['appointment_time']); ?></td>
                        <td><?php echo htmlspecialchars($app['patient_name']); ?></td>
                        <td><span class="badge bg-primary">Забронировано</span></td>
                        <td>
                            <a href="doctor_complete.php?id=<?php echo $app['id']; ?>" class="btn btn-success">Завершить прием</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
