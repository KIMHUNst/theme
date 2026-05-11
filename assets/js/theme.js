document.addEventListener('DOMContentLoaded', function () {
    const toggle = document.querySelector('[data-darkmode-toggle]');

    if (toggle) {
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
    }

    if ('serviceWorker' in navigator) {
        navigator.serviceWorker.register('/wp-content/themes/clean-approval-blog/service-worker.js').catch(function () {});
    }

    const searchField = document.querySelector('.search-field');
    const searchForm = document.querySelector('.search-form');

    if (searchField && searchForm && window.cabData) {
        const box = document.createElement('div');
        box.className = 'cab-live-search-results';
        searchForm.appendChild(box);

        let timer;
        searchField.addEventListener('input', function () {
            clearTimeout(timer);
            const term = searchField.value.trim();

            if (term.length < 2) {
                box.innerHTML = '';
                return;
            }

            timer = setTimeout(function () {
                const data = new FormData();
                data.append('action', 'cab_ajax_search');
                data.append('nonce', cabData.nonce);
                data.append('term', term);

                fetch(cabData.ajaxUrl, { method: 'POST', body: data })
                    .then(function (response) { return response.json(); })
                    .then(function (json) {
                        if (!json.success || !json.data.length) {
                            box.innerHTML = '<p>No quick results found.</p>';
                            return;
                        }

                        box.innerHTML = json.data.map(function (item) {
                            return '<a href="' + item.url + '"><strong>' + item.title + '</strong><span>' + item.date + '</span></a>';
                        }).join('');
                    });
            }, 250);
        });
    }

    const loadMore = document.querySelector('[data-load-more]');
    const postGrid = document.querySelector('.post-grid');

    if (loadMore && postGrid && window.cabData) {
        let page = 2;

        loadMore.addEventListener('click', function () {
            loadMore.disabled = true;
            loadMore.textContent = 'Loading...';

            const data = new FormData();
            data.append('action', 'cab_load_more_posts');
            data.append('nonce', cabData.nonce);
            data.append('page', page);

            fetch(cabData.ajaxUrl, { method: 'POST', body: data })
                .then(function (response) { return response.json(); })
                .then(function (json) {
                    if (json.success && json.data.html) {
                        postGrid.insertAdjacentHTML('beforeend', json.data.html);
                        page++;
                    }

                    if (!json.success || !json.data.hasMore) {
                        loadMore.remove();
                        return;
                    }

                    loadMore.disabled = false;
                    loadMore.textContent = 'Load More';
                })
                .catch(function () {
                    loadMore.disabled = false;
                    loadMore.textContent = 'Load More';
                });
        });
    }
});
