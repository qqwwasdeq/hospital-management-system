<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';
requireRole('doctor');

$appointment_id = $_GET['id'] ?? null;
if (!$appointment_id) {
    header("Location: dashboard.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT id FROM doctors WHERE user_id = ?");
$stmt->execute([$user_id]);
$doctor = $stmt->fetch();
$doctor_id = $doctor['id'];

// Verify appointment belongs to this doctor and is still booked
$stmt = $pdo->prepare("
    SELECT a.*, p.full_name as patient_name
    FROM appointments a
    JOIN patients p ON a.patient_id = p.id
    WHERE a.id = ? AND a.doctor_id = ? AND a.status = 'booked'
");
$stmt->execute([$appointment_id, $doctor_id]);
$appointment = $stmt->fetch();

if (!$appointment) {
    die("Appointment not found or already completed.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $symptoms = $_POST['symptoms'];
    $diagnosis = $_POST['diagnosis'];
    $treatment = $_POST['treatment'];

    $stmt = $pdo->prepare("
        UPDATE appointments
        SET symptoms = ?, diagnosis = ?, treatment = ?, status = 'completed'
        WHERE id = ?
    ");
    $stmt->execute([$symptoms, $diagnosis, $treatment, $appointment_id]);

    header("Location: dashboard.php?completed=1");
    exit;
}

include '../includes/header.php';
?>

<h2>Complete Appointment</h2>
<p><strong>Patient:</strong> <?php echo htmlspecialchars($appointment['patient_name']); ?></p>

<div class="card">
    <div class="card-body">
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Symptoms</label>
                <textarea name="symptoms" class="form-control" rows="3" required></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Diagnosis</label>
                <textarea name="diagnosis" class="form-control" rows="3" required></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Treatment</label>
                <textarea name="treatment" class="form-control" rows="3" required></textarea>
            </div>
            <button type="submit" class="btn btn-success">Save and Complete</button>
            <a href="dashboard.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
