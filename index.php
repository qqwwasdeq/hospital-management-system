<?php
require_once 'config.php';
require_once 'includes/auth.php';
include 'includes/header.php';
?>

<div class="p-5 mb-4 bg-light rounded-3 border">
    <div class="container-fluid py-5">
        <h1 class="display-5 fw-bold">Welcome to <?php echo SITE_NAME; ?></h1>
        <p class="col-md-8 fs-4">Providing quality healthcare with a click of a button. Manage your appointments and medical records easily.</p>
        <div class="d-grid gap-2 d-md-flex justify-content-md-start">
            <a href="doctors_schedule.php" class="btn btn-primary btn-lg px-4 me-md-2">View Doctors & Schedule</a>
            <?php if(!isset($_SESSION['user_id'])): ?>
                <a href="register.php" class="btn btn-outline-secondary btn-lg px-4">Register Now</a>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="row g-4 py-5 row-cols-1 row-cols-lg-3">
    <div class="col d-flex align-items-start">
        <div>
            <h2>Patients</h2>
            <p>Book appointments, view your medical history, and manage your health from your personal dashboard.</p>
            <a href="/patient/dashboard.php" class="btn btn-primary">Patient Portal</a>
        </div>
    </div>
    <div class="col d-flex align-items-start">
        <div>
            <h2>Doctors</h2>
            <p>Access your daily schedule and provide top-notch care with our integrated appointment management system.</p>
            <a href="/doctor/dashboard.php" class="btn btn-primary">Doctor Portal</a>
        </div>
    </div>
    <div class="col d-flex align-items-start">
        <div>
            <h2>Staff</h2>
            <p>Administrative and director tools to ensure smooth hospital operations and insightful reporting.</p>
            <a href="/login.php" class="btn btn-primary">Staff Login</a>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
