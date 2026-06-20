<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';
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

include '../includes/header.php';
?>

<h2>Doctor Dashboard: <?php echo htmlspecialchars($doctor['full_name']); ?></h2>
<h4>Today's Appointments (<?php echo $today; ?>)</h4>

<?php if (empty($appointments)): ?>
    <div class="alert alert-info">No appointments scheduled for today.</div>
<?php else: ?>
    <table class="table table-hover">
        <thead>
            <tr>
                <th>Time</th>
                <th>Patient</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($appointments as $app): ?>
            <tr>
                <td><?php echo htmlspecialchars($app['appointment_time']); ?></td>
                <td><?php echo htmlspecialchars($app['patient_name']); ?></td>
                <td><span class="badge bg-primary"><?php echo ucfirst($app['status']); ?></span></td>
                <td>
                    <a href="complete_appointment.php?id=<?php echo $app['id']; ?>" class="btn btn-sm btn-success">Complete Appointment</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<?php include '../includes/footer.php'; ?>
