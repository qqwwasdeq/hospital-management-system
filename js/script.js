document.addEventListener('DOMContentLoaded', () => {
    // Плавное появление страницы
    document.body.classList.add('loaded');

    // Анимация чисел в статистике (простая версия)
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

    // Обработка алертов (авто-скрытие)
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0';
            alert.style.transition = 'opacity 0.5s ease';
            setTimeout(() => alert.remove(), 500);
        }, 5000);
    });
});
