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
                            <ul class="list-group">
                                @foreach ($categories->where('type', $type) as $category)
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
                                                @if ($type === 'transaction')
                                                    <span class="text-xs">Tipo: {{ $category->tipo }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <button
                                                type="button"
                                                class="btn btn-warning btn-sm"
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
                                                <i class="bi icons8-edit "></i>
                                            </button>
                                            <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteCategoryModal" data-id="{{ $category->id_category }}">
                                                Excluir
                                            </button>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    @include('categories.create', ['userId' => auth()->id()])
    @include('categories.edit')
    @include('categories.delete')

@endsection
