<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body class="<?php
    if (basename($_SERVER['PHP_SELF']) == 'doctor_login.php') {
        echo 'theme-doctor-login';
    } elseif (isset($_SESSION['role'])) {
        echo 'theme-' . $_SESSION['role'];
    }
?>">
<nav class="navbar">
    <div class="container">
        <a class="nav-brand" href="index.php"><?php echo SITE_NAME; ?></a>
        <ul class="nav-links">
            <li><a href="index.php">Главная</a></li>
            <li><a href="doctors_schedule.php">Расписание</a></li>
            <?php if (isset($_SESSION['user_id'])): ?>
                <li>
                    <a href="<?php
                        switch($_SESSION['role']) {
                            case 'patient': echo 'patient_dashboard.php'; break;
                            case 'doctor': echo 'doctor_dashboard.php'; break;
                            case 'admin': echo 'admin_dashboard.php'; break;
                            case 'director': echo 'director_dashboard.php'; break;
                        }
                    ?>">Кабинет</a>
                </li>
                <li><a href="logout.php">Выход</a></li>
            <?php else: ?>
                <li><a href="login.php">Вход</a></li>
                <li><a href="register.php">Регистрация</a></li>
            <?php endif; ?>
        </ul>
    </div>
</nav>
<div class="container main-content">
