-- Таблица пользователей (общая для авторизации)
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    login VARCHAR(50) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('patient', 'doctor', 'admin', 'director') NOT NULL
);

-- Данные пациентов (Процесс 1)
CREATE TABLE patients (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    full_name VARCHAR(100) NOT NULL,
    passport VARCHAR(20) UNIQUE NOT NULL,
    insurance_policy VARCHAR(20) UNIQUE NOT NULL,
    medical_card_num VARCHAR(20) UNIQUE NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

-- Данные врачей
CREATE TABLE doctors (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    full_name VARCHAR(100) NOT NULL,
    specialization VARCHAR(100) NOT NULL,
    room_num VARCHAR(10) NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

-- Сетка расписания (Процесс 2)
CREATE TABLE schedule (
    id INT AUTO_INCREMENT PRIMARY KEY,
    doctor_id INT NOT NULL,
    appointment_date DATE NOT NULL,
    appointment_time TIME NOT NULL,
    is_available BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (doctor_id) REFERENCES doctors(id) ON DELETE CASCADE
);

-- Журнал приемов (Процесс 2 и 3)
CREATE TABLE appointments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    patient_id INT NOT NULL,
    doctor_id INT NOT NULL,
    schedule_id INT NOT NULL,
    status ENUM('booked', 'completed', 'cancelled') DEFAULT 'booked',
    symptoms TEXT,
    diagnosis TEXT,
    treatment TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (patient_id) REFERENCES patients(id) ON DELETE CASCADE,
    FOREIGN KEY (doctor_id) REFERENCES doctors(id) ON DELETE CASCADE,
    FOREIGN KEY (schedule_id) REFERENCES schedule(id) ON DELETE CASCADE
);
