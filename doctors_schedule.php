<?php
require_once 'config.php';
require_once 'auth.php';
require_once 'db.php';

$stmt = $pdo->query("
    SELECT s.id, d.full_name, d.specialization, s.appointment_date, s.appointment_time, s.is_available
    FROM schedule s
    JOIN doctors d ON s.doctor_id = d.id
    WHERE s.appointment_date >= date('now')
    ORDER BY s.appointment_date, s.appointment_time
");
$slots = $stmt->fetchAll();

include 'header.php';
?>

<div class="card">
    <div class="card-header">
        <h2>Врачи и Доступное Расписание</h2>
        <p>Выберите специалиста и запишитесь на прием.</p>
    </div>

    <?php if (empty($slots)): ?>
        <div class="alert alert-info">Свободных слотов не найдено.</div>
    <?php else: ?>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Врач</th>
                        <th>Специализация</th>
                        <th>Дата</th>
                        <th>Время</th>
                        <th>Статус</th>
                        <th>Действие</th>
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
                                <span class="badge bg-success">Свободно</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">Занято</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($slot['is_available']): ?>
                                <?php if (isLoggedIn()): ?>
                                    <?php if (getRole() === 'patient'): ?>
                                        <a href="patient_book.php?slot_id=<?php echo $slot['id']; ?>" class="btn btn-primary">Записаться</a>
                                    <?php else: ?>
                                        <button class="btn btn-secondary" disabled>Записаться</button>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <a href="login.php?msg=Войдите, чтобы записаться" class="btn btn-primary">Записаться</a>
                                <?php endif; ?>
                            <?php else: ?>
                                <button class="btn btn-secondary" disabled>Недоступно</button>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>
