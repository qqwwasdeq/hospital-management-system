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

    echo "База данных инициализирована.\n";

    // Хэшированный пароль для всех
    $pass = password_hash('password123', PASSWORD_DEFAULT);

    // 1. Пользователи
    $users = [
        ['admin', $pass, 'admin'],
        ['director', $pass, 'director'],
        ['doc_ivanov', $pass, 'doctor'],
        ['doc_petrov', $pass, 'doctor'],
        ['doc_sidorova', $pass, 'doctor'],
        ['doc_kuznetsov', $pass, 'doctor'],
        ['doc_smirnov', $pass, 'doctor'],
        ['patient_test', $pass, 'patient']
    ];

    $stmt = $pdo->prepare("INSERT INTO users (login, password_hash, role) VALUES (?, ?, ?)");
    foreach ($users as $u) { $stmt->execute($u); }
    echo "Пользователи созданы.\n";

    // 2. Врачи
    $doctors = [
        ['Иванов А.А.', 'Терапевт', '101', 3],
        ['Петров С.П.', 'Хирург', '202', 4],
        ['Сидорова Е.М.', 'Кардиолог', '303', 5],
        ['Кузнецов Д.В.', 'Офтальмолог', '404', 6],
        ['Смирнов И.И.', 'Невролог', '505', 7]
    ];

    $stmt = $pdo->prepare("INSERT INTO doctors (full_name, specialization, room_num, user_id) VALUES (?, ?, ?, ?)");
    foreach ($doctors as $d) { $stmt->execute($d); }
    echo "Врачи созданы.\n";

    // 3. Пациент
    $pdo->exec("INSERT INTO patients (user_id, full_name, passport, insurance_policy, medical_card_num)
                VALUES (8, 'Тестовый Пациент', '1234 567890', 'OMS-999', 'MC-12345')");
    echo "Тестовый пациент создан.\n";

    // 4. Расписание
    $today = date('Y-m-d');
    $tomorrow = date('Y-m-d', strtotime('+1 day'));

    $schedule = [
        [1, $today, '09:00:00', 0], // Иванов - занято
        [1, $today, '10:00:00', 1],
        [2, $today, '09:00:00', 1],
        [3, $today, '11:00:00', 0], // Сидорова - занято
        [4, $tomorrow, '10:00:00', 1],
        [5, $tomorrow, '14:00:00', 1],
        [1, $tomorrow, '09:00:00', 1],
        [2, $tomorrow, '11:00:00', 1],
        [3, $tomorrow, '15:00:00', 1],
        [4, $today, '16:00:00', 1]
    ];

    $stmt = $pdo->prepare("INSERT INTO schedule (doctor_id, appointment_date, appointment_time, is_available) VALUES (?, ?, ?, ?)");
    foreach ($schedule as $s) { $stmt->execute($s); }
    echo "Слоты расписания созданы.\n";

    // 5. Записи на прием
    $appointments = [
        [1, 1, 1, 'booked'], // Пациент 1 к Иванову (слот 1)
        [1, 3, 4, 'booked']  // Пациент 1 к Сидоровой (слот 4)
    ];

    $stmt = $pdo->prepare("INSERT INTO appointments (patient_id, doctor_id, schedule_id, status) VALUES (?, ?, ?, ?)");
    foreach ($appointments as $a) { $stmt->execute($a); }
    echo "Записи созданы.\n";

} catch (PDOException $e) {
    echo "Ошибка: " . $e->getMessage() . "\n";
}
?>
