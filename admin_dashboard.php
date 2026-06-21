<?php
require_once 'includes/config.php';
require_once 'includes/auth.php';
require_once 'includes/db.php';
requireRole('admin');

if (isset($_POST['cancel_id'])) {
    $cancel_id = $_POST['cancel_id'];
    $pdo->beginTransaction();
    $stmt = $pdo->prepare("SELECT schedule_id FROM appointments WHERE id = ?");
    $stmt->execute([$cancel_id]);
    $app = $stmt->fetch();
    if ($app) {
        $stmt = $pdo->prepare("UPDATE appointments SET status = 'cancelled' WHERE id = ?");
        $stmt->execute([$cancel_id]);
        $stmt = $pdo->prepare("UPDATE schedule SET is_available = 1 WHERE id = ?");
        $stmt->execute([$app['schedule_id']]);
        $pdo->commit();
        $msg = "Прием успешно отменен.";
    } else {
        $pdo->rollBack();
    }
}

$stmt = $pdo->query("
    SELECT a.id, p.full_name as patient_name, d.full_name as doctor_name, s.appointment_date, s.appointment_time, a.status
    FROM appointments a
    JOIN patients p ON a.patient_id = p.id
    JOIN doctors d ON a.doctor_id = d.id
    JOIN schedule s ON a.schedule_id = s.id
    WHERE a.status = 'booked'
    ORDER BY s.appointment_date, s.appointment_time
");
$appointments = $stmt->fetchAll();

include 'includes/header.php';
?>

<div class="card">
    <div class="card-header">
        <h2>Панель Администратора</h2>
        <a href="admin_add_slot.php" class="btn btn-success">Добавить слот</a>
    </div>

    <h4>Активные Записи</h4>
    <?php if (isset($msg)): ?>
        <div class="alert alert-success"><?php echo $msg; ?></div>
    <?php endif; ?>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Дата и Время</th>
                    <th>Пациент</th>
                    <th>Врач</th>
                    <th>Действие</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($appointments as $app): ?>
                <tr>
                    <td><?php echo $app['appointment_date'] . ' ' . $app['appointment_time']; ?></td>
                    <td><?php echo htmlspecialchars($app['patient_name']); ?></td>
                    <td><?php echo htmlspecialchars($app['doctor_name']); ?></td>
                    <td>
                        <form method="POST" class="form-inline">
                            <input type="hidden" name="cancel_id" value="<?php echo $app['id']; ?>">
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Отменить запись?')">Отменить</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
