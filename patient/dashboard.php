<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';
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

include '../includes/header.php';
?>

<h2>My Upcoming Appointments</h2>

<?php if (isset($_GET['booked'])): ?>
    <div class="alert alert-success">Appointment booked successfully!</div>
<?php endif; ?>
<?php if (isset($_GET['cancelled'])): ?>
    <div class="alert alert-warning">Appointment cancelled.</div>
<?php endif; ?>

<?php if (empty($appointments)): ?>
    <p>You have no upcoming appointments. <a href="/doctors_schedule.php">Book one now</a>.</p>
<?php else: ?>
    <table class="table">
        <thead>
            <tr>
                <th>Doctor</th>
                <th>Date</th>
                <th>Time</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($appointments as $app): ?>
            <tr>
                <td><?php echo htmlspecialchars($app['doctor_name']); ?></td>
                <td><?php echo htmlspecialchars($app['appointment_date']); ?></td>
                <td><?php echo htmlspecialchars($app['appointment_time']); ?></td>
                <td><span class="badge bg-primary"><?php echo ucfirst($app['status']); ?></span></td>
                <td>
                    <a href="cancel.php?id=<?php echo $app['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Cancel</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<?php include '../includes/footer.php'; ?>
