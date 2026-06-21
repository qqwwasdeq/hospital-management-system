<?php
require_once 'includes/config.php';
require_once 'includes/auth.php';
require_once 'includes/db.php';
requireRole('patient');

if (isset($_GET['slot_id'])) {
    $slot_id = $_GET['slot_id'];
    $user_id = $_SESSION['user_id'];
    try {
        $pdo->beginTransaction();
        $stmt = $pdo->prepare("SELECT id FROM patients WHERE user_id = ?");
        $stmt->execute([$user_id]);
        $patient = $stmt->fetch();
        $patient_id = $patient['id'];
        $stmt = $pdo->prepare("SELECT doctor_id, is_available FROM schedule WHERE id = ?");
        $stmt->execute([$slot_id]);
        $slot = $stmt->fetch();
        if ($slot && $slot['is_available']) {
            $stmt = $pdo->prepare("UPDATE schedule SET is_available = 0 WHERE id = ?");
            $stmt->execute([$slot_id]);
            $stmt = $pdo->prepare("INSERT INTO appointments (patient_id, doctor_id, schedule_id, status) VALUES (?, ?, ?, 'booked')");
            $stmt->execute([$patient_id, $slot['doctor_id'], $slot_id]);
            $pdo->commit();
            header("Location: patient_dashboard.php?booked=1");
            exit;
        } else {
            $pdo->rollBack();
            die("Слот не доступен.");
        }
    } catch (PDOException $e) {
        $pdo->rollBack();
        die("Ошибка: " . $e->getMessage());
    }
}
?>
