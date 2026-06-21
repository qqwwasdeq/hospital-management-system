<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<nav class="navbar">
    <div class="container">
        <a class="nav-brand" href="index.php"><?php echo SITE_NAME; ?></a>
        <ul class="nav-links">
            <li><a href="index.php">Главная</a></li>
            <li><a href="doctors_schedule.php">Врачи и Расписание</a></li>
            <?php if (isset($_SESSION['user_id'])): ?>
                <li>
                    <a href="<?php
                        switch($_SESSION['role']) {
                            case 'patient': echo 'patient_dashboard.php'; break;
                            case 'doctor': echo 'doctor_dashboard.php'; break;
                            case 'admin': echo 'admin_dashboard.php'; break;
                            case 'director': echo 'director_dashboard.php'; break;
                        }
                    ?>">Кабинет (<?php
                        $roles_ru = ['patient' => 'Пациент', 'doctor' => 'Врач', 'admin' => 'Админ', 'director' => 'Директор'];
                        echo $roles_ru[$_SESSION['role']] ?? $_SESSION['role'];
                    ?>)</a>
                </li>
                <li><a href="logout.php">Выход</a></li>
            <?php else: ?>
                <li><a href="login.php">Вход</a></li>
                <li><a href="register.php">Регистрация</a></li>
            <?php endif; ?>
        </ul>
    </div>
</nav>
<div class="container">
