<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SITE_NAME; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/style.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand" href="/index.php"><?php echo SITE_NAME; ?></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item"><a class="nav-link" href="/index.php">Главная</a></li>
                <li class="nav-item"><a class="nav-link" href="/doctors_schedule.php">Врачи и Расписание</a></li>
            </ul>
            <ul class="navbar-nav">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php
                            switch($_SESSION['role']) {
                                case 'patient': echo '/patient/dashboard.php'; break;
                                case 'doctor': echo '/doctor/dashboard.php'; break;
                                case 'admin': echo '/admin/dashboard.php'; break;
                                case 'director': echo '/director/dashboard.php'; break;
                            }
                        ?>">Мой Личный Кабинет (<?php
                            $roles_ru = ['patient' => 'Пациент', 'doctor' => 'Врач', 'admin' => 'Админ', 'director' => 'Директор'];
                            echo $roles_ru[$_SESSION['role']] ?? $_SESSION['role'];
                        ?>)</a>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="/logout.php">Выход</a></li>
                <?php else: ?>
                    <li class="nav-item"><a class="nav-link" href="/login.php">Вход</a></li>
                    <li class="nav-item"><a class="nav-link" href="/register.php">Регистрация</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
<div class="container">
