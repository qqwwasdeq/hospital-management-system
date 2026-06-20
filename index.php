<?php
require_once 'config.php';
require_once 'includes/auth.php';
include 'includes/header.php';
?>

<div class="p-5 mb-4 bg-light rounded-3 border">
    <div class="container-fluid py-5">
        <h1 class="display-5 fw-bold">Добро пожаловать в <?php echo SITE_NAME; ?></h1>
        <p class="col-md-8 fs-4">Качественное медицинское обслуживание в один клик. Управляйте своими записями и медицинскими данными легко.</p>
        <div class="d-grid gap-2 d-md-flex justify-content-md-start">
            <a href="/doctors_schedule.php" class="btn btn-primary btn-lg px-4 me-md-2">Врачи и Расписание</a>
            <?php if(!isset($_SESSION['user_id'])): ?>
                <a href="/register.php" class="btn btn-outline-secondary btn-lg px-4">Зарегистрироваться</a>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="row g-4 py-5 row-cols-1 row-cols-lg-3">
    <div class="col d-flex align-items-start">
        <div>
            <h2>Пациентам</h2>
            <p>Записывайтесь на прием, просматривайте историю посещений и управляйте своим здоровьем в личном кабинете.</p>
            <a href="/patient/dashboard.php" class="btn btn-primary">Личный Кабинет Пациента</a>
        </div>
    </div>
    <div class="col d-flex align-items-start">
        <div>
            <h2>Врачам</h2>
            <p>Получите доступ к расписанию приемов и ведите медицинские карты пациентов в удобном интерфейсе.</p>
            <a href="/doctor/dashboard.php" class="btn btn-primary">Личный Кабинет Врача</a>
        </div>
    </div>
    <div class="col d-flex align-items-start">
        <div>
            <h2>Персонал</h2>
            <p>Инструменты для регистраторов и администрации для обеспечения бесперебойной работы больницы.</p>
            <a href="/login.php" class="btn btn-primary">Вход для Персонала</a>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
