(function() {
    let debounceTimeout = null;
    let lastController = null;
    let lastQuery = null;

    function dynamicSearchHandler(e) {
        const searchInput = e.target;
        if (!searchInput || searchInput.id !== 'search-input') return;

        const query = searchInput.value;
        if (query === lastQuery) return;
        lastQuery = query;

        if (debounceTimeout) clearTimeout(debounceTimeout);

        debounceTimeout = setTimeout(() => {
            if (lastController) lastController.abort();
            lastController = new AbortController();

            const selectionStart = searchInput.selectionStart;
            const selectionEnd = searchInput.selectionEnd;
            const url = (window.PRODUCTS_INDEX_URL || '') + '?search=' + encodeURIComponent(query);

            fetch(url, { signal: lastController.signal })
                .then(response => {
                    if (!response.ok) throw new Error('Erro ao buscar dados');
                    return response.text();
                })
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const newContent = doc.querySelector('.container-fluid.py-4');
                    if (newContent) {
                        document.querySelector('.container-fluid.py-4').innerHTML = newContent.innerHTML;
                        attachDynamicSearch(); // Reanexa o evento ao novo input
                        const newSearchInput = document.getElementById('search-input');
                        if (newSearchInput) {
                            newSearchInput.focus();
                            newSearchInput.setSelectionRange(selectionStart, selectionEnd);
                        }
                    }
                })
                .catch(error => {
                    if (error.name !== 'AbortError') {
                        console.error('Erro ao buscar dados:', error);
                    }
                });
        }, 300);
    }

    function attachDynamicSearch() {
        const searchInput = document.getElementById('search-input');
        if (searchInput && !searchInput._dynamicSearchAttached) {
            searchInput.addEventListener('input', dynamicSearchHandler);
            searchInput._dynamicSearchAttached = true;
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        attachDynamicSearch();
    });
})();
