<?php
require_once 'config.php';
require_once 'auth.php';
include 'header.php';
?>

<section class="hero-section">
    <h1>Добро пожаловать в <?php echo SITE_NAME; ?></h1>
    <p>Качественное медицинское обслуживание в один клик. Управляйте своими записями и медицинскими данными легко.</p>
    <div class="hero-actions">
        <a href="doctors_schedule.php" class="btn btn-primary">Врачи и Расписание</a>
        <?php if(!isset($_SESSION['user_id'])): ?>
            <a href="register.php" class="btn btn-secondary">Зарегистрироваться</a>
        <?php endif; ?>
    </div>
</section>

<div class="card-grid">
    <div class="card">
        <h2>Пациентам</h2>
        <p>Записывайтесь на прием, просматривайте историю посещений и управляйте своим здоровьем в личном кабинете.</p>
        <a href="patient_dashboard.php" class="btn btn-primary">Личный Кабинет Пациента</a>
    </div>
    <div class="card">
        <h2>Врачам</h2>
        <p>Получите доступ к расписанию приемов и ведите медицинские карты пациентов в удобном интерфейсе.</p>
        <a href="doctor_dashboard.php" class="btn btn-primary">Личный Кабинет Врача</a>
    </div>
    <div class="card">
        <h2>Персонал</h2>
        <p>Инструменты для регистраторов и администрации для обеспечения бесперебойной работы больницы.</p>
        <a href="login.php" class="btn btn-primary">Вход для Персонала</a>
    </div>
</div>

<?php include 'footer.php'; ?>
