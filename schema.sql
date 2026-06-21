-- Создание базы данных
CREATE DATABASE IF NOT EXISTS hospital_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE hospital_db;

-- Таблица пользователей
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    login VARCHAR(50) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('patient', 'doctor', 'admin', 'director') NOT NULL
) ENGINE=InnoDB;

-- Данные пациентов
CREATE TABLE IF NOT EXISTS patients (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NULL,
    full_name VARCHAR(255) NOT NULL,
    passport VARCHAR(50) UNIQUE NOT NULL,
    phone VARCHAR(20) NOT NULL,
    insurance_policy VARCHAR(50) UNIQUE NOT NULL,
    medical_card_num VARCHAR(50) UNIQUE NOT NULL,
    CONSTRAINT fk_patient_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- Данные врачей
CREATE TABLE IF NOT EXISTS doctors (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NULL,
    full_name VARCHAR(255) NOT NULL,
    specialization VARCHAR(255) NOT NULL,
    room_num VARCHAR(20) NOT NULL,
    CONSTRAINT fk_doctor_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- Сетка расписания
CREATE TABLE IF NOT EXISTS schedule (
    id INT AUTO_INCREMENT PRIMARY KEY,
    doctor_id INT NOT NULL,
    appointment_date DATE NOT NULL,
    appointment_time TIME NOT NULL,
    is_available BOOLEAN DEFAULT TRUE,
    CONSTRAINT fk_schedule_doctor FOREIGN KEY (doctor_id) REFERENCES doctors(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Журнал приемов
CREATE TABLE IF NOT EXISTS appointments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    patient_id INT NOT NULL,
    doctor_id INT NOT NULL,
    schedule_id INT NOT NULL,
    status ENUM('booked', 'completed', 'cancelled') DEFAULT 'booked',
    symptoms TEXT,
    diagnosis TEXT,
    treatment TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_appt_patient FOREIGN KEY (patient_id) REFERENCES patients(id) ON DELETE CASCADE,
    CONSTRAINT fk_appt_doctor FOREIGN KEY (doctor_id) REFERENCES doctors(id) ON DELETE CASCADE,
    CONSTRAINT fk_appt_schedule FOREIGN KEY (schedule_id) REFERENCES schedule(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ==========================================
-- ПРЕДВАРИТЕЛЬНОЕ ЗАПОЛНЕНИЕ ДАННЫМИ (SEED)
-- ==========================================

-- Тестовые аккаунты
INSERT INTO users (login, password_hash, role) VALUES
('admin', '$2y$10$BaopYmW6ioeIMbfaXRoLMe2deD9alhsk9WaZpxcHcxKuwhqHUBzyq', 'admin'),       -- пароль: admin
('ivanov', '$2y$10$w0a4VwWlR6Ht72f0aQXeBOeAwIM3TXdIrwyT2c0.cVWheZblxZQFS', 'doctor'),      -- пароль: doc123
('petrov', '$2y$10$kcNNGo.k3i8xdvRdcQQeZ.GvBstRLMN4bL1y6m5gSDX10/ofvMLQS', 'doctor'),      -- пароль: doc456
('sidorova', '$2y$10$eHtoBT6IsL47ffrvzM0zNuxzpVOJUuNYwYIYvgLQBMqfCBsz0pRre', 'doctor'),    -- пароль: doc789
('patient', '$2y$10$FV3OmXregvrXOumoM7L1QOrtyWdGesER3yDRB1F.VH.HDy2oCMjhq', 'patient');    -- пароль: pat123

-- 2. Врачи
INSERT INTO doctors (full_name, specialization, room_num, user_id) VALUES
('Иванов А.А.', 'Терапевт', '101', 2),
('Петров С.П.', 'Хирург', '202', 3),
('Сидорова Е.М.', 'Кардиолог', '303', 4),
('Кузнецов Д.В.', 'Офтальмолог', '404', NULL),
('Смирнов И.И.', 'Невролог', '505', NULL);

-- 3. Тестовый пациент (привязка к user_id 5)
INSERT INTO patients (user_id, full_name, passport, phone, insurance_policy, medical_card_num)
VALUES (5, 'Тестовый Пациент', '1234 567890', '+7 (900) 123-45-67', 'OMS-999', 'MC-12345');

-- 4. Сетка расписания
INSERT INTO schedule (doctor_id, appointment_date, appointment_time, is_available) VALUES
(1, CURDATE(), '09:00:00', 0),
(1, CURDATE(), '10:00:00', 1),
(2, CURDATE(), '09:00:00', 1),
(3, CURDATE(), '11:00:00', 0),
(4, DATE_ADD(CURDATE(), INTERVAL 1 DAY), '10:00:00', 1),
(5, DATE_ADD(CURDATE(), INTERVAL 1 DAY), '14:00:00', 1),
(1, DATE_ADD(CURDATE(), INTERVAL 1 DAY), '09:00:00', 1),
(2, DATE_ADD(CURDATE(), INTERVAL 1 DAY), '11:00:00', 1),
(3, DATE_ADD(CURDATE(), INTERVAL 1 DAY), '15:00:00', 1),
(4, CURDATE(), '16:00:00', 1);

-- 5. Записи на прием
INSERT INTO appointments (patient_id, doctor_id, schedule_id, status) VALUES
(1, 1, 1, 'booked'),
(1, 3, 4, 'booked');
