// Função para buscar o endereço pelo CEP utilizando a API dos Correios
function searchAddressByCep() {
    var cep = document.getElementById('cep').value.replace(/\D/g, '');
    if (cep.length === 8) {
        var url = `https://viacep.com.br/ws/${cep}/json/`;
        fetch(url)
            .then(response => response.json())
            .then(data => {
                if (!data.erro) {
                    document.getElementById('address').value = data.logradouro;
                    document.getElementById('city').value = data.localidade;
                    document.getElementById('state').value = data.uf;
                    document.getElementById('district').value = data.bairro;
                    document.getElementById('addressFields').style.display = 'block';
                } else {
                    alert('CEP não encontrado!');
                }
            })
            .catch(error => {
                console.error('Erro ao buscar o CEP:', error);
            });
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const phoneInput = document.getElementById('phone');
    if (phoneInput) {
        phoneInput.addEventListener('input', function(e) {
            let phone = e.target.value.replace(/\D/g, '');
            if (phone.length <= 2) {
                phone = phone.replace(/^(\d{2})$/, '($1)');
            } else if (phone.length <= 7) {
                phone = phone.replace(/^(\d{2})(\d{1,5})$/, '($1) $2');
            } else {
                phone = phone.replace(/^(\d{2})(\d{1,5})(\d{1,4})$/, '($1) $2-$3');
            }
            e.target.value = phone;
        });
    }

    // Adicionar cliente dinamicamente
    const addClientForm = document.querySelector('#add-client-form');
    if (addClientForm) {
        addClientForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            fetch(addClientForm.getAttribute('action') || window.CLIENTS_STORE_URL, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': window.CLIENTS_CSRF_TOKEN
                }
            }).then(response => response.json())
            .then(data => {
                // Atualizar lista de clientes dinamicamente
            });
        });
    }

    // Editar cliente dinamicamente
    document.querySelectorAll('.edit-client').forEach(button => {
        button.addEventListener('click', function() {
            const clientId = this.dataset.id;
            // Abrir modal e carregar dados do cliente
        });
    });

    // Excluir cliente dinamicamente
    document.querySelectorAll('.delete-client').forEach(button => {
        button.addEventListener('click', function() {
            const clientId = this.dataset.id;
            fetch(`/clients/${clientId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': window.CLIENTS_CSRF_TOKEN
                }
            }).then(response => response.json())
            .then(data => {
                // Remover cliente da lista
            });
        });
    });
});

// Variáveis globais para CSRF e rotas (defina no Blade se necessário)
window.CLIENTS_STORE_URL = window.CLIENTS_STORE_URL || '';
window.CLIENTS_CSRF_TOKEN = window.CLIENTS_CSRF_TOKEN || '';

// Pesquisa dinâmica de clientes com debounce otimizado (sem observer)
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
            const url = (window.CLIENTS_INDEX_URL || '') + '?search=' + encodeURIComponent(query);

            fetch(url, { signal: lastController.signal })
                .then(response => {
                    if (!response.ok) throw new Error('Erro ao buscar dados');
                    return response.text();
                })
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    // O container correto é o .row.mt-4#client-list
                    const newContent = doc.querySelector('.row.mt-4#client-list');
                    if (newContent) {
                        document.querySelector('.row.mt-4#client-list').innerHTML = newContent.innerHTML;
                        // Não precisa reanexar o evento, pois o input não é recriado
                        // Recupera o input e restaura foco/cursor
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

    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('search-input');
        if (searchInput) {
            searchInput.addEventListener('input', dynamicSearchHandler);
            // Para depuração:
            // console.log('Listener de pesquisa dinâmica anexado ao input:', searchInput);
        } else {
            // Para depuração:
            // console.warn('Input de pesquisa dinâmica não encontrado!');
        }
    });
})();
