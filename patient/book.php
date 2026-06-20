<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';
requireRole('patient');

if (isset($_GET['slot_id'])) {
    $slot_id = $_GET['slot_id'];
    $user_id = $_SESSION['user_id'];

    try {
        $pdo->beginTransaction();

        // Get patient_id
        $stmt = $pdo->prepare("SELECT id FROM patients WHERE user_id = ?");
        $stmt->execute([$user_id]);
        $patient = $stmt->fetch();
        $patient_id = $patient['id'];

        // Get slot info
        $stmt = $pdo->prepare("SELECT doctor_id, is_available FROM schedule WHERE id = ?");
        $stmt->execute([$slot_id]);
        $slot = $stmt->fetch();

        if ($slot && $slot['is_available']) {
            // Update schedule
            $stmt = $pdo->prepare("UPDATE schedule SET is_available = 0 WHERE id = ?");
            $stmt->execute([$slot_id]);

            // Create appointment
            $stmt = $pdo->prepare("INSERT INTO appointments (patient_id, doctor_id, schedule_id, status) VALUES (?, ?, ?, 'booked')");
            $stmt->execute([$patient_id, $slot['doctor_id'], $slot_id]);

            $pdo->commit();
            header("Location: dashboard.php?booked=1");
            exit;
        } else {
            $pdo->rollBack();
            die("Slot is no longer available.");
        }
    } catch (PDOException $e) {
        $pdo->rollBack();
        die("Booking failed: " . $e->getMessage());
    }
}
?>
