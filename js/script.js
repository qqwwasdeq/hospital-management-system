document.addEventListener('DOMContentLoaded', () => {
    // Плавное появление страницы
    document.body.classList.add('loaded');

    // Анимация чисел в статистике
    const stats = document.querySelectorAll('.stat-number');
    stats.forEach(stat => {
        const target = parseInt(stat.innerText);
        if (isNaN(target)) return;

        let count = 0;
        const speed = 2000 / target;

        const updateCount = () => {
            if (count < target) {
                count++;
                stat.innerText = count + (stat.innerText.includes('+') ? '+' : '');
                setTimeout(updateCount, speed);
            } else {
                stat.innerText = target + (stat.innerText.includes('+') ? '+' : '');
            }
        };
        stat.innerText = '0' + (stat.innerText.includes('+') ? '+' : '');
        updateCount();
    });

    // Обработка алертов
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0';
            alert.style.transition = 'opacity 0.5s ease';
            setTimeout(() => alert.remove(), 500);
        }, 5000);
    });

    // --- Маски ввода ---

    const passportInput = document.getElementById('passport');
    const phoneInput = document.getElementById('phone');

    if (passportInput) {
        passportInput.addEventListener('input', (e) => {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 10) value = value.slice(0, 10);

            if (value.length > 4) {
                value = value.slice(0, 4) + ' ' + value.slice(4);
            }
            e.target.value = value;
        });
    }

    if (phoneInput) {
        phoneInput.addEventListener('input', (e) => {
            let value = e.target.value.replace(/\D/g, '');

            // Если начинается с 7 или 8, убираем первую цифру для единообразия
            if (value.startsWith('7') || value.startsWith('8')) {
                value = value.slice(1);
            }

            if (value.length > 10) value = value.slice(0, 10);

            let formatted = '+7 ';
            if (value.length > 0) {
                formatted += '(' + value.slice(0, 3);
            }
            if (value.length > 3) {
                formatted += ') ' + value.slice(3, 6);
            }
            if (value.length > 6) {
                formatted += '-' + value.slice(6, 8);
            }
            if (value.length > 8) {
                formatted += '-' + value.slice(8, 10);
            }

            e.target.value = (value.length > 0) ? formatted : '';
        });

        // Блокировка удаления префикса +7
        phoneInput.addEventListener('keydown', (e) => {
            if (e.key === 'Backspace' && e.target.value.length <= 4) {
                // Не позволяем удалять "+7 "
            }
        });
    }
});
