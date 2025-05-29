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
            const url = (window.SALES_INDEX_URL || '') + '?search=' + encodeURIComponent(query);

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

// Script para dropdowns de filtros e tooltips
document.addEventListener('DOMContentLoaded', function() {
    // Para cada dropdown de filtro na página
    document.querySelectorAll('.dropdown-filtros').forEach(function(dropdownEl) {
        // Impede o fechamento ao clicar em qualquer parte interna do dropdown, exceto nos botões
        dropdownEl.querySelector('.dropdown-menu').addEventListener('mousedown', function(e) {
            if (
                e.target.closest('button[type="submit"]') ||
                e.target.closest('a.btn-outline-secondary')
            ) {
                // Permite o clique normal
            } else {
                e.stopPropagation();
            }
        });

        // Função para fechar o dropdown deste filtro
        function closeDropdown() {
            var dropdownToggle = dropdownEl.querySelector('.dropdown-toggle');
            var dropdown = bootstrap.Dropdown.getOrCreateInstance(dropdownToggle);
            dropdown.hide();
        }

        // Ao clicar em "Aplicar"
        var submitBtn = dropdownEl.querySelector('button[type="submit"]');
        if (submitBtn) {
            submitBtn.addEventListener('click', function() {
                setTimeout(closeDropdown, 100); // Fecha após submit
            });
        }

        // Ao clicar em "Limpar"
        var clearBtn = dropdownEl.querySelector('a.btn-outline-secondary');
        if (clearBtn) {
            clearBtn.addEventListener('click', function() {
                closeDropdown();
            });
        }
    });

    // Ativa tooltips do Bootstrap (caso use tooltips nos filtros)
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    tooltipTriggerList.forEach(function(tooltipTriggerEl) {
        new bootstrap.Tooltip(tooltipTriggerEl)
    });
});
