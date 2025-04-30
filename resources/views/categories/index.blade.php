@extends('layouts.user_type.auth')

@section('content')
<div class="container-fluid py-4">
    @include('message.alert')
    <div class="row">
        @foreach (['product' => 'Produtos', 'transaction' => 'Transações'] as $type => $titulo)
            <div class="col-md-6 mt-4">
                <div class="card h-100 mb-4">
                    <div class="card-header pb-0 px-3">
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="mb-0">Categorias: {{ $titulo }}</h6>
                            </div>
                            <div class="col-md-6 text-end">
                                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createCategoryModal" data-type="{{ $type }}" data-title="{{ $titulo }}">
                                    Criar Categoria ({{ $titulo }})
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body pt-4 p-3">
                        <!-- Filtro de Pesquisa -->
                        <div class="mb-3">
                            <input type="text" class="form-control searchCategory" id="searchCategory{{ $type }}" placeholder="Pesquisar categorias...">
                        </div>

                        <ul class="list-group" id="categoryList{{ $type }}">
                            @if ($type === 'product')
                                @forelse ($productCategories as $category)
                                    <li class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
                                        <div class="d-flex align-items-center">
                                            <button
                                                class="btn btn-icon-only btn-rounded mb-0 me-3 btn-sm d-flex align-items-center justify-content-center"
                                                style="background-color: {{ $category->hexcolor_category }}; color: #fff; width: 50px; height: 50px;">
                                                <i class="{{ $category->icone }}" style="font-size: 1.5rem;"></i>
                                            </button>
                                            <div class="d-flex flex-column" style="opacity: 0.7;">
                                                <h6 class="mb-1 text-dark text-sm">{{ $category->name }}</h6>
                                                <span class="text-xs">{{ $category->desc_category }}</span>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <button
                                                type="button"
                                                class="btn btn-warning"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editCategoryModal"
                                                data-id="{{ $category->id_category }}"
                                                data-name="{{ $category->name }}"
                                                data-desc="{{ $category->desc_category }}"
                                                data-color="{{ $category->hexcolor_category }}"
                                                data-parent-id="{{ $category->parent_id }}"
                                                data-detailed-desc="{{ $category->descricao_detalhada }}"
                                                data-tags="{{ $category->tags }}"
                                                data-regras="{{ $category->regras_auto_categorizacao }}"
                                                data-is-active="{{ $category->is_active }}"
                                                data-type="{{ $category->type }}">
                                                <i class="bi icons8-edit"></i>
                                            </button>
                                            <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteCategoryModal" data-id="{{ $category->id_category }}">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </li>
                                @empty
                                    <li class="list-group-item text-center">Nenhuma categoria adicionada</li>
                                @endforelse
                            @elseif ($type === 'transaction')
                                @forelse ($transactionCategories as $category)
                                    <li class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
                                        <div class="d-flex align-items-center">
                                            <button
                                                class="btn btn-icon-only btn-rounded mb-0 me-3 btn-sm d-flex align-items-center justify-content-center"
                                                style="background-color: {{ $category->hexcolor_category }}; color: #fff; width: 50px; height: 50px;">
                                                <i class="{{ $category->icone }}" style="font-size: 1.5rem;"></i>
                                            </button>
                                            <div class="d-flex flex-column" style="opacity: 0.7;">
                                                <h6 class="mb-1 text-dark text-sm">{{ $category->name }}</h6>
                                                <span class="text-xs">{{ $category->desc_category }}</span>
                                                <span class="text-xs">Tipo: {{ $category->tipo }}</span>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <button
                                                type="button"
                                                class="btn btn-warning"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editCategoryModal"
                                                data-id="{{ $category->id_category }}"
                                                data-name="{{ $category->name }}"
                                                data-desc="{{ $category->desc_category }}"
                                                data-color="{{ $category->hexcolor_category }}"
                                                data-parent-id="{{ $category->parent_id }}"
                                                data-detailed-desc="{{ $category->descricao_detalhada }}"
                                                data-tags="{{ $category->tags }}"
                                                data-regras="{{ $category->regras_auto_categorizacao }}"
                                                data-is-active="{{ $category->is_active }}"
                                                data-type="{{ $category->type }}">
                                                <i class="bi icons8-edit"></i>
                                            </button>
                                            <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteCategoryModal" data-id="{{ $category->id_category }}">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </li>
                                @empty
                                    <li class="list-group-item text-center">Nenhuma categoria adicionada</li>
                                @endforelse
                            @endif
                        </ul>

                        <!-- Paginação -->
                        <div class="d-flex justify-content-center">
                            @if ($type === 'product')
                                {{ $productCategories->links() }} <!-- Paginação para produtos -->
                            @elseif ($type === 'transaction')
                                {{ $transactionCategories->links() }} <!-- Paginação para transações -->
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Incluindo a view create e passando $categories -->
    @include('categories.create', ['categories' => $categories, 'userId' => auth()->id()])
</div>


<script>
    document.addEventListener('DOMContentLoaded', function () {
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
                    const categoryName = category.querySelector('.mb-1.text-dark.text-sm').textContent.toLowerCase();
                    if (categoryName.includes(searchTerm)) {
                        category.style.display = 'flex'; // Exibe a categoria
                        found = true;
                    } else {
                        category.style.display = 'none'; // Esconde a categoria
                    }
                });

                if (!found) {
                    categoryList.innerHTML = '<li class="list-group-item text-center">Nenhuma categoria encontrada</li>';
                }
            });
        });
    });
</script>

    @include('categories.edit')
    @include('categories.delete')

@endsection
