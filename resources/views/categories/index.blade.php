@extends('layouts.user_type.auth')

@section('content')
<link rel="stylesheet" href="{{ asset('css/category.css') }}">
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
                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal"
                            data-bs-target="#createCategoryModal" data-type="{{ $type }}" data-title="{{ $titulo }}">
                            Criar Categoria ({{ $titulo }})
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body pt-4 p-3">
                <!-- Removido filtro de pesquisa -->
                <ul class="list-group" id="categoryList{{ $type }}">
                    @if ($type === 'product')
                    @forelse ($productCategories as $category)
                    <li class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
                        <div class="d-flex align-items-center">
                            <button
                                class="btn btn-icon-only btn-rounded mb-0 me-3 btn-sm d-flex align-items-center justify-content-center"
                                style="border: 3px solid {{ $category->hexcolor_category }}; color: #fff; width: 50px; height: 50px;">
                                <i class="{{ $category->icone }}" style="font-size: 1.5rem;"></i>
                            </button>
                            <div class="d-flex flex-column" style="opacity: 0.7;">
                                <h6 class="mb-1 text-dark text-sm">{{ $category->name }}</h6>
                                <span class="text-xs">{{ $category->desc_category }}</span>
                            </div>
                        </div>
                        <div class="d-flex align-items-center">
                            <button type="button" class="btn btn-warning" data-bs-toggle="modal"
                                data-bs-target="#editCategoryModal" data-id="{{ $category->id_category }}"
                                data-name="{{ $category->name }} " data-desc="{{ $category->desc_category }}"
                                data-color="{{ $category->hexcolor_category }}"
                                data-parent-id="{{ $category->parent_id }} "
                                data-detailed-desc="{{ $category->descricao_detalhada }}"
                                data-tags="{{ $category->tags }}"
                                data-regras="{{ $category->regras_auto_categorizacao }}"
                                data-is-active="{{ $category->is_active }}" data-type="{{ $category->type }}">
                                <i class="bi icons8-edit"></i>
                            </button>
                            <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteCategoryModal"
                                data-id="{{ $category->id_category }}">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </li>
                    @empty
                    <div class="col-12">
                        <div class="d-flex flex-column align-items-center justify-content-center py-5">
                            <div class="animated-icon mb-4">
                                <svg width="130" height="130" viewBox="0 0 130 130" fill="none">
                                    <circle cx="65" cy="65" r="62" stroke="#e3eafc" stroke-width="3" fill="#f8fafc" />
                                    <rect x="40" y="60" width="50" height="30" rx="10" fill="#e9f2ff" stroke="#6ea8fe"
                                        stroke-width="3" />
                                    <rect x="55" y="40" width="20" height="25" rx="6" fill="#f8fafc" stroke="#6ea8fe"
                                        stroke-width="3" />
                                    <rect x="50" y="95" width="30" height="8" rx="4" fill="#6ea8fe" opacity="0.18" />
                                    <circle cx="65" cy="75" r="6" fill="#6ea8fe" opacity="0.15" />
                                    <rect x="60" y="70" width="10" height="10" rx="3" fill="#6ea8fe" opacity="0.25" />
                                </svg>
                            </div>
                            <h2 class="fw-bold mb-3 text-primary"
                                style="font-size:2.5rem; letter-spacing:0.01em; text-shadow:0 2px 8px #e3eafc;">
                                Nenhuma Categoria Encontrada
                            </h2>
                            <p class="mb-4 text-secondary text-center"
                                style="max-width: 480px; font-size:1.25rem; font-weight:500; line-height:1.6;">
                                <span style="color:#0d6efd; font-weight:700;">Ops!</span> Você ainda não cadastrou
                                nenhuma categoria.<br>
                                <span style="color:#6ea8fe;">Crie sua primeira categoria</span> para organizar
                                melhor seus produtos e vendas!
                            </p>
                        </div>
                    </div>
                    @endforelse
                    @elseif ($type === 'transaction')
                    @forelse ($transactionCategories as $category)
                    <li class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
                        <div class="d-flex align-items-center">
                            <button
                                class="btn btn-icon-only btn-rounded mb-0 me-3 btn-sm d-flex align-items-center justify-content-center"
                                style="border: 3px solid {{ $category->hexcolor_category }}; color: #fff; width: 50px; height: 50px;">
                                <i class="{{ $category->icone }}" style="font-size: 1.5rem;"></i>
                            </button>
                            <div class="d-flex flex-column" style="opacity: 0.7;">
                                <h6 class="mb-1 text-dark text-sm">{{ $category->name }}</h6>
                                <span class="text-xs">{{ $category->desc_category }}</span>
                                <span class="text-xs">Tipo: {{ $category->tipo }}</span>
                            </div>
                        </div>
                        <div class="d-flex align-items-center">
                            <button type="button" class="btn btn-warning" data-bs-toggle="modal"
                                data-bs-target="#editCategoryModal" data-id="{{ $category->id_category }}"
                                data-name="{{ $category->name }}" data-desc="{{ $category->desc_category }}"
                                data-color="{{ $category->hexcolor_category }}"
                                data-parent-id="{{ $category->parent_id }}"
                                data-detailed-desc="{{ $category->descricao_detalhada }}"
                                data-tags="{{ $category->tags }}"
                                data-regras="{{ $category->regras_auto_categorizacao }}"
                                data-is-active="{{ $category->is_active }}" data-type="{{ $category->type }}">
                                <i class="bi icons8-edit"></i>
                            </button>
                            <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteCategoryModal"
                                data-id="{{ $category->id_category }}">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </li>
                    @empty
                    <div class="col-12">
                        <div class="d-flex flex-column align-items-center justify-content-center py-5">
                            <div class="animated-icon mb-4">
                                <svg width="130" height="130" viewBox="0 0 130 130" fill="none">
                                    <circle cx="65" cy="65" r="62" stroke="#e3eafc" stroke-width="3" fill="#f8fafc" />
                                    <rect x="40" y="60" width="50" height="30" rx="10" fill="#e9f2ff" stroke="#6ea8fe"
                                        stroke-width="3" />
                                    <rect x="55" y="40" width="20" height="25" rx="6" fill="#f8fafc" stroke="#6ea8fe"
                                        stroke-width="3" />
                                    <rect x="50" y="95" width="30" height="8" rx="4" fill="#6ea8fe" opacity="0.18" />
                                    <circle cx="65" cy="75" r="6" fill="#6ea8fe" opacity="0.15" />
                                    <rect x="60" y="70" width="10" height="10" rx="3" fill="#6ea8fe" opacity="0.25" />
                                </svg>
                            </div>
                            <h2 class="fw-bold mb-3 text-primary"
                                style="font-size:2.5rem; letter-spacing:0.01em; text-shadow:0 2px 8px #e3eafc;">
                                Nenhuma Categoria Encontrada
                            </h2>
                            <p class="mb-4 text-secondary text-center"
                                style="max-width: 480px; font-size:1.25rem; font-weight:500; line-height:1.6;">
                                <span style="color:#0d6efd; font-weight:700;">Ops!</span> Você ainda não cadastrou
                                nenhuma categoria.<br>
                                <span style="color:#6ea8fe;">Crie sua primeira categoria</span> para organizar
                                melhor seus produtos e vendas!
                            </p>
                        </div>
                    </div>
                    @endforelse
                    @endif
                </ul>

                <!-- Removido o bloco de paginação -->
            </div>
        </div>
    </div>
    @endforeach
</div>
<!-- Incluindo a view create e passando $categories -->
@include('categories.create', ['categories' => $categories, 'userId' => auth()->id()])

<script src="{{ asset('js/categories.js') }}"></script>
@include('categories.edit')
@include('categories.delete')

@endsection