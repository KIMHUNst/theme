document.addEventListener('DOMContentLoaded', function () {
    const toggle = document.querySelector('[data-darkmode-toggle]');

    if (!toggle) {
        return;
    }

    const saved = localStorage.getItem('cab-darkmode');

    if (saved === 'enabled') {
        document.documentElement.classList.add('cab-darkmode');
    }

    toggle.addEventListener('click', function () {
        document.documentElement.classList.toggle('cab-darkmode');

        if (document.documentElement.classList.contains('cab-darkmode')) {
            localStorage.setItem('cab-darkmode', 'enabled');
        } else {
            localStorage.removeItem('cab-darkmode');
        }
    });
});
