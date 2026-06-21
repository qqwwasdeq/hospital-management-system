<?php
require_once 'config.php';
require_once 'auth.php';
require_once 'db.php';
requireRole('patient');

$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT id FROM patients WHERE user_id = ?");
$stmt->execute([$user_id]);
$patient = $stmt->fetch();
$patient_id = $patient['id'];

$stmt = $pdo->prepare("
    SELECT a.id, d.full_name as doctor_name, s.appointment_date, s.appointment_time, a.status
    FROM appointments a
    JOIN doctors d ON a.doctor_id = d.id
    JOIN schedule s ON a.schedule_id = s.id
    WHERE a.patient_id = ? AND a.status = 'booked'
    ORDER BY s.appointment_date, s.appointment_time
");
$stmt->execute([$patient_id]);
$appointments = $stmt->fetchAll();

include 'header.php';
?>

<div class="card">
    <div class="card-header">
        <h2>Мои Предстоящие Приемы</h2>
    </div>

    <?php if (isset($_GET['booked'])): ?>
        <div class="alert alert-success">Прием успешно забронирован!</div>
    <?php endif; ?>
    <?php if (isset($_GET['cancelled'])): ?>
        <div class="alert alert-warning">Прием отменен.</div>
    <?php endif; ?>

    <?php if (empty($appointments)): ?>
        <p>У вас нет предстоящих приемов. <a href="doctors_schedule.php">Записаться на прием</a>.</p>
    <?php else: ?>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Врач</th>
                        <th>Дата</th>
                        <th>Время</th>
                        <th>Статус</th>
                        <th>Действие</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($appointments as $app): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($app['doctor_name']); ?></td>
                        <td><?php echo htmlspecialchars($app['appointment_date']); ?></td>
                        <td><?php echo htmlspecialchars($app['appointment_time']); ?></td>
                        <td><span class="badge bg-primary">Забронировано</span></td>
                        <td>
                            <a href="patient_cancel.php?id=<?php echo $app['id']; ?>" class="btn btn-danger" onclick="return confirm('Вы уверены?')">Отменить</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>
