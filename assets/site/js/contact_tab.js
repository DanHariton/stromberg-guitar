(function () {
    if (!Boolean(document.querySelector('.contact-tab-link'))) {
        return;
    }

    document.querySelectorAll('.contact-tab-link').forEach(link => link.addEventListener('click', (e) => {
        e.preventDefault();
        let currentState = link.classList.contains('active');

        document.querySelectorAll('.contact-tab-link').forEach(l => l.classList.remove('active'));
        document.querySelectorAll('.contact-tabs-content .item').forEach(item => item.classList.remove('show'));

        link.classList.toggle('active', !currentState);
        document.querySelector(link.getAttribute('href')).classList.toggle('show', !currentState);
    }));
})();