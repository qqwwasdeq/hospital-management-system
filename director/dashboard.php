<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';
requireRole('director');

// Aggregate metrics
$total_patients = $pdo->query("SELECT COUNT(*) FROM patients")->fetchColumn();
$total_completed = $pdo->query("SELECT COUNT(*) FROM appointments WHERE status = 'completed'")->fetchColumn();

// Workload per doctor
$stmt = $pdo->query("
    SELECT d.full_name, COUNT(a.id) as appointment_count
    FROM doctors d
    LEFT JOIN appointments a ON d.id = a.doctor_id AND a.status = 'completed'
    GROUP BY d.id
");
$workload = $stmt->fetchAll();

include '../includes/header.php';
?>

<h2>Director Dashboard: Hospital Analytics</h2>

<div class="row mb-4">
    <div class="col-md-6">
        <div class="card bg-info text-white">
            <div class="card-body">
                <h5 class="card-title">Total Registered Patients</h5>
                <h2 class="card-text"><?php echo $total_patients; ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card bg-success text-white">
            <div class="card-body">
                <h5 class="card-title">Completed Appointments</h5>
                <h2 class="card-text"><?php echo $total_completed; ?></h2>
            </div>
        </div>
    </div>
</div>

<h4>Doctor Workload (Completed Appointments)</h4>
<table class="table table-bordered shadow-sm">
    <thead class="table-dark">
        <tr>
            <th>Doctor Name</th>
            <th>Appointments Conducted</th>
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

<?php include '../includes/footer.php'; ?>
