document.addEventListener('DOMContentLoaded', function() {
    // Filtro de Pesquisa Dinâmica
    const searchInputs = document.querySelectorAll('.searchCategory');
    searchInputs.forEach(input => {
        input.addEventListener('input', function() {
            const type = input.id.replace('searchCategory', '');
            const searchTerm = input.value.toLowerCase();
            const categoryList = document.getElementById(`categoryList${type}`);
            const categories = categoryList.getElementsByTagName('li');

            let found = false;

            Array.from(categories).forEach(category => {
                const categoryName = category.querySelector('.mb-1.text-dark.text-sm')
                    .textContent.toLowerCase();
                if (categoryName.includes(searchTerm)) {
                    category.style.display = 'flex';
                    found = true;
                } else {
                    category.style.display = 'none';
                }
            });

            if (!found) {
                categoryList.innerHTML =
                    '<li class="list-group-item text-center">Nenhuma categoria encontrada</li>';
            }
        });
    });
});
