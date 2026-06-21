<?php
require_once 'includes/config.php';
require_once 'includes/auth.php';
include 'includes/header.php';
?>

<section class="hero-section">
    <div class="container">
        <h1>Добро пожаловать в <?php echo SITE_NAME; ?></h1>
        <p>Ваше здоровье — наш приоритет. Мы объединяем опыт лучших специалистов и современные технологии для заботы о вас.</p>
        <div class="hero-actions">
            <a href="doctors_schedule.php" class="btn btn-primary">Записаться на прием</a>
            <?php if(!isset($_SESSION['user_id'])): ?>
                <a href="register.php" class="btn btn-secondary">Стать пациентом</a>
            <?php endif; ?>
        </div>
    </div>
</section>

<div class="container">
    <div class="stats-container">
        <div class="stat-item">
            <span class="stat-number">20+</span>
            <span class="stat-label">Направлений</span>
        </div>
        <div class="stat-item">
            <span class="stat-number">50+</span>
            <span class="stat-label">Врачей высшей категории</span>
        </div>
        <div class="stat-item">
            <span class="stat-number">800+</span>
            <span class="stat-label">Довольных пациентов</span>
        </div>
    </div>

    <div class="card-grid">
        <div class="card">
            <h3>Часы работы</h3>
            <ul style="list-style: none; padding: 0;">
                <li>Пн - Пт: 08:00 - 20:00</li>
                <li>Сб: 09:00 - 15:00</li>
                <li>Вс: Выходной</li>
            </ul>
        </div>
        <div class="card">
            <h3>Контактная информация</h3>
            <p><strong>Адрес:</strong> ул. Медицинская, д. 42, г. Город</p>
            <p><strong>Телефон:</strong> +7 (495) 000-00-00</p>
            <p><strong>Email:</strong> info@cityhospital.ru</p>
        </div>
        <div class="card">
            <h3>Экстренная помощь</h3>
            <p>Наше приемное отделение работает круглосуточно 24/7 для оказания неотложной помощи.</p>
            <a href="tel:103" class="btn btn-danger btn-block">Вызвать помощь</a>
        </div>
    </div>

    <div class="card-grid">
        <div class="card">
            <h2>Для Пациентов</h2>
            <p>Управляйте своими записями, просматривайте назначения и историю болезней в личном кабинете.</p>
            <a href="patient_dashboard.php" class="btn btn-primary">Личный кабинет</a>
        </div>
        <div class="card">
            <h2>Для Врачей</h2>
            <p>Доступ к актуальному расписанию и электронным медицинским картам пациентов.</p>
            <a href="doctor_dashboard.php" class="btn btn-primary">Вход для врачей</a>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
