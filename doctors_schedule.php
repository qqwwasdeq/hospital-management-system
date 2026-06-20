<?php
require_once 'config.php';
require_once 'includes/auth.php';
require_once 'includes/db.php';

$stmt = $pdo->query("
    SELECT s.id, d.full_name, d.specialization, s.appointment_date, s.appointment_time, s.is_available
    FROM schedule s
    JOIN doctors d ON s.doctor_id = d.id
    WHERE s.appointment_date >= date('now')
    ORDER BY s.appointment_date, s.appointment_time
");
$slots = $stmt->fetchAll();

include 'includes/header.php';
?>

<h2>Doctors & Available Time Slots</h2>
<p class="text-muted">Browse our specialists and book an appointment.</p>

<?php if (empty($slots)): ?>
    <div class="alert alert-info">No available slots found.</div>
<?php else: ?>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Doctor</th>
                <th>Specialization</th>
                <th>Date</th>
                <th>Time</th>
                <th>Availability</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($slots as $slot): ?>
            <tr>
                <td><?php echo htmlspecialchars($slot['full_name']); ?></td>
                <td><?php echo htmlspecialchars($slot['specialization']); ?></td>
                <td><?php echo htmlspecialchars($slot['appointment_date']); ?></td>
                <td><?php echo htmlspecialchars($slot['appointment_time']); ?></td>
                <td>
                    <?php if ($slot['is_available']): ?>
                        <span class="badge bg-success">Available</span>
                    <?php else: ?>
                        <span class="badge bg-secondary">Booked</span>
                    <?php endif; ?>
                </td>
                <td>
                    <?php if ($slot['is_available']): ?>
                        <?php if (isLoggedIn()): ?>
                            <?php if (getRole() === 'patient'): ?>
                                <a href="patient/book.php?slot_id=<?php echo $slot['id']; ?>" class="btn btn-sm btn-primary">Book Appointment</a>
                            <?php else: ?>
                                <button class="btn btn-sm btn-secondary" disabled title="Only patients can book">Book Appointment</button>
                            <?php endif; ?>
                        <?php else: ?>
                            <a href="login.php?msg=Please login or register to book an appointment" class="btn btn-sm btn-outline-primary">Book Appointment</a>
                        <?php endif; ?>
                    <?php else: ?>
                        <button class="btn btn-sm btn-secondary" disabled>Unavailable</button>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>
