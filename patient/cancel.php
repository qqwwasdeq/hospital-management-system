<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';
requireRole('patient');

if (isset($_GET['id'])) {
    $appointment_id = $_GET['id'];
    $user_id = $_SESSION['user_id'];

    try {
        $pdo->beginTransaction();

        // Get patient_id
        $stmt = $pdo->prepare("SELECT id FROM patients WHERE user_id = ?");
        $stmt->execute([$user_id]);
        $patient = $stmt->fetch();
        $patient_id = $patient['id'];

        // Get appointment info
        $stmt = $pdo->prepare("SELECT schedule_id FROM appointments WHERE id = ? AND patient_id = ?");
        $stmt->execute([$appointment_id, $patient_id]);
        $app = $stmt->fetch();

        if ($app) {
            // Update appointment status
            $stmt = $pdo->prepare("UPDATE appointments SET status = 'cancelled' WHERE id = ?");
            $stmt->execute([$appointment_id]);

            // Free up the slot
            $stmt = $pdo->prepare("UPDATE schedule SET is_available = 1 WHERE id = ?");
            $stmt->execute([$app['schedule_id']]);

            $pdo->commit();
            header("Location: dashboard.php?cancelled=1");
            exit;
        } else {
            $pdo->rollBack();
            die("Appointment not found or not yours.");
        }
    } catch (PDOException $e) {
        $pdo->rollBack();
        die("Cancellation failed: " . $e->getMessage());
    }
}
?>
