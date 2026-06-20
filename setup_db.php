<?php
$db_file = 'hospital.db';

try {
    if (file_exists($db_file)) {
        unlink($db_file);
    }
    $pdo = new PDO("sqlite:$db_file");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = file_get_contents('schema.sql');
    $pdo->exec($sql);

    echo "Database schema created successfully.\n";

    // Seed initial data
    $initial_users = [
        ['admin', 'admin', 'admin'],
        ['director', 'director', 'director'],
        ['doctor1', 'doctor1', 'doctor'],
        ['doctor2', 'doctor2', 'doctor'],
    ];

    $stmt = $pdo->prepare("INSERT INTO users (login, password_hash, role) VALUES (?, ?, ?)");
    foreach ($initial_users as $user) {
        $stmt->execute([$user[0], password_hash($user[1], PASSWORD_DEFAULT), $user[2]]);
    }
    echo "Initial users created.\n";

    // Get doctor user IDs
    $stmt = $pdo->query("SELECT id, login FROM users WHERE role = 'doctor'");
    $doctors = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmt_doc = $pdo->prepare("INSERT INTO doctors (user_id, full_name, specialization, room_num) VALUES (?, ?, ?, ?)");
    $stmt_doc->execute([$doctors[0]['id'], 'Dr. Smith', 'Cardiology', '101']);
    $stmt_doc->execute([$doctors[1]['id'], 'Dr. Jones', 'Neurology', '202']);
    echo "Doctor profiles created.\n";

    // Add some initial schedule slots
    $stmt_sched = $pdo->prepare("INSERT INTO schedule (doctor_id, appointment_date, appointment_time, is_available) VALUES (?, ?, ?, 1)");
    $doctor_ids = $pdo->query("SELECT id FROM doctors")->fetchAll(PDO::FETCH_COLUMN);

    $today = date('Y-m-d');
    $tomorrow = date('Y-m-d', strtotime('+1 day'));

    foreach ($doctor_ids as $doc_id) {
        $stmt_sched->execute([$doc_id, $today, '09:00:00']);
        $stmt_sched->execute([$doc_id, $today, '10:00:00']);
        $stmt_sched->execute([$doc_id, $tomorrow, '09:00:00']);
        $stmt_sched->execute([$doc_id, $tomorrow, '10:00:00']);
    }
    echo "Initial schedule slots created.\n";

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
