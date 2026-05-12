document.addEventListener('DOMContentLoaded', function () {
    const popup = document.querySelector('[data-cabsb-popup]');
    const popupClose = document.querySelector('[data-cabsb-popup-close]');

    if (popup) {
        setTimeout(function () {
            popup.classList.add('active');
        }, 2500);

        if (popupClose) {
            popupClose.addEventListener('click', function () {
                popup.classList.remove('active');
            });
        }

        popup.addEventListener('click', function (event) {
            if (event.target === popup) {
                popup.classList.remove('active');
            }
        });
    }

    const offcanvas = document.querySelector('[data-cabsb-offcanvas]');
    const openButtons = document.querySelectorAll('[data-cabsb-open]');
    const closeButton = document.querySelector('[data-cabsb-close]');

    openButtons.forEach(function (button) {
        button.addEventListener('click', function () {
            if (offcanvas) {
                offcanvas.classList.add('active');
            }
        });
    });

    if (closeButton && offcanvas) {
        closeButton.addEventListener('click', function () {
            offcanvas.classList.remove('active');
        });
    }
});
