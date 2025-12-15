import 'bootstrap';

// Основной JavaScript файл

document.addEventListener('DOMContentLoaded', function() {

    // Автоматическое скрытие алертов через 5 секунд
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 5000);
    });

    // Подтверждение удаления
    const deleteForms = document.querySelectorAll('form[action*="destroy"]');
    deleteForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!confirm('Вы уверены, что хотите удалить этот элемент?')) {
                e.preventDefault();
            }
        });
    });

    // Валидация форм
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const requiredFields = form.querySelectorAll('[required]');
            let isValid = true;

            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    field.classList.add('is-invalid');

                    // Создаем сообщение об ошибке
                    let errorDiv = field.nextElementSibling;
                    if (!errorDiv || !errorDiv.classList.contains('invalid-feedback')) {
                        errorDiv = document.createElement('div');
                        errorDiv.className = 'invalid-feedback';
                        field.parentNode.appendChild(errorDiv);
                    }
                    errorDiv.textContent = 'Это поле обязательно для заполнения';
                } else {
                    field.classList.remove('is-invalid');
                }
            });

            if (!isValid) {
                e.preventDefault();
                e.stopPropagation();
            }
        });
    });

    // Удаление сообщений об ошибках при вводе
    const inputs = document.querySelectorAll('input, textarea, select');
    inputs.forEach(input => {
        input.addEventListener('input', function() {
            this.classList.remove('is-invalid');
        });
    });

    // Плавная прокрутка
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            const href = this.getAttribute('href');
            if (href !== '#') {
                e.preventDefault();
                const target = document.querySelector(href);
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            }
        });
    });

    // Подсветка активных строк таблицы
    const tableRows = document.querySelectorAll('tbody tr');
    tableRows.forEach(row => {
        row.addEventListener('mouseenter', function() {
            this.style.backgroundColor = 'rgba(67, 97, 238, 0.05)';
        });

        row.addEventListener('mouseleave', function() {
            this.style.backgroundColor = '';
        });
    });

    // Обработка модальных окон
    const modalTriggers = document.querySelectorAll('[data-bs-toggle="modal"]');
    modalTriggers.forEach(trigger => {
        trigger.addEventListener('click', function() {
            const modalId = this.getAttribute('data-bs-target');
            const modal = document.querySelector(modalId);
            if (modal) {
                modal.classList.add('fade');
            }
        });
    });

    // Динамическое обновление счетчиков
    function updateCounters() {
        // Здесь можно добавить динамическое обновление счетчиков
        // если будет реализован WebSocket или AJAX
    }

    // Инициализация tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Инициализация popovers
    const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
    popoverTriggerList.map(function(popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl);
    });

    // Обработка выпадающих списков
    const dropdowns = document.querySelectorAll('.dropdown-toggle');
    dropdowns.forEach(dropdown => {
        dropdown.addEventListener('click', function(e) {
            const menu = this.nextElementSibling;
            if (menu && menu.classList.contains('dropdown-menu')) {
                menu.classList.toggle('show');
            }
        });
    });

    // Закрытие выпадающих меню при клике вне
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.dropdown')) {
            document.querySelectorAll('.dropdown-menu.show').forEach(menu => {
                menu.classList.remove('show');
            });
        }
    });

    // Анимация загрузки
    const submitButtons = document.querySelectorAll('button[type="submit"]');
    submitButtons.forEach(button => {
        button.addEventListener('click', function() {
            if (this.form && this.form.checkValidity()) {
                const originalText = this.innerHTML;
                this.innerHTML = '<span class="spinner-border spinner-border-sm" role="status"></span> Обработка...';
                this.disabled = true;

                // Восстановление кнопки через 5 секунд на случай ошибки
                setTimeout(() => {
                    this.innerHTML = originalText;
                    this.disabled = false;
                }, 5000);
            }
        });
    });

    // Автоматический выбор первого элемента в выпадающих списках
    const selectElements = document.querySelectorAll('select');
    selectElements.forEach(select => {
        if (!select.value && select.options.length > 0) {
            select.value = select.options[0].value;
        }
    });

    // Обработка специальных клавиш
    document.addEventListener('keydown', function(e) {
        // Ctrl + S для сохранения
        if ((e.ctrlKey || e.metaKey) && e.key === 's') {
            e.preventDefault();
            const submitButton = document.querySelector('button[type="submit"]');
            if (submitButton) {
                submitButton.click();
            }
        }

        // Escape для закрытия модальных окон
        if (e.key === 'Escape') {
            const openModal = document.querySelector('.modal.show');
            if (openModal) {
                const modal = bootstrap.Modal.getInstance(openModal);
                if (modal) {
                    modal.hide();
                }
            }
        }
    });
});
