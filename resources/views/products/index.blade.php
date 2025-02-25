@extends('layouts.user_type.auth')

@section('content')
<div class="container mt-4">
    <!-- Botão para abrir o Modal alinhado à direita -->
    <div class="d-flex justify-content-end">
        <a href="#" class="btn bg-gradient-primary btn-sm mb-0" data-bs-toggle="modal" data-bs-target="#modalAddProduct">Adicionar Produto</a>
    </div>

    <!-- Tabela de produtos ou outras exibições -->
    <div class="row mt-4">
        @foreach($products as $product)
        <div class="col-md-3">
            <div class="card position-relative">
                <!-- Exibição da Imagem do Produto -->
                <img src="{{ asset('storage/products/'.$product->image) }}" class="card-img-top" alt="{{ $product->name }}">

                <!-- Botões de Editar e Excluir -->
                <div class="position-absolute top-0 end-0 p-2">
                    <!-- Botão de Editar (Pincel) -->
                    <a href="javascript:void(0)" class="btn btn-primary btn-sm p-1" data-bs-toggle="modal" data-bs-target="#modalEditProduct{{ $product->id }}" title="Editar">
                        <!-- Ícone de edição (pincel) -->
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                            <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                        </svg>
                    </a>

                    <!-- Botão de Excluir (Lixeira) -->
                    <form action="{{ route('products.destroy', $product->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm p-1" title="Excluir">
                            <!-- Ícone de lixeira -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3" viewBox="0 0 16 16">
                                <path d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5M11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47M8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5" />
                            </svg>
                        </button>
                    </form>
                </div>


                <div class="card-body">
                    <!-- Nome do Produto (Centralizado e Primeira Letra Maiúscula) -->
                    <h5 class="card-title text-center">{{ ucwords($product->name) }}</h5>

                    <!-- Descrição do Produto (Centralizada e Primeira Letra Maiúscula) -->
                    <p class="card-text text-center text-truncate" style="max-width: 250px;">{{ ucwords($product->description) }}</p>

                    <!-- Informações Adicionais (Agora em 2 Linhas) -->
                    <div class="row">
                        <div class="col-6">
                            <p><strong>Preço:</strong> R$ {{ number_format($product->price, 2, ',', '.') }}</p>
                            <p><strong>Qtd:</strong> {{ $product->stock_quantity }}</p>
                        </div>
                        <div class="col-6">
                            <p><strong>Categoria:</strong> {{ $product->category->name ?? 'N/A' }}</p>
                            <p><strong>Código:</strong> {{ $product->product_code }}</p>
                            <!-- Status do Produto -->
                            <p><strong>Status:</strong>
                                <span class="badge
                                @if($product->status == 'active') badge-success
                                @elseif($product->status == 'inactive') badge-secondary
                                @else badge-danger @endif">
                                    {{ ucfirst($product->status) }}
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>




    <!-- Modal de Edição de Produto -->
    <div class="modal fade" id="modalEditProduct{{ $product->id }}" tabindex="-1" aria-labelledby="modalEditProductLabel{{ $product->id }}" aria-hidden="true">
        <div class="modal-dialog modal-xl"> <!-- Aumentei a largura do modal -->
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEditProductLabel{{ $product->id }}">Editar Produto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <!-- Coluna da esquerda com os detalhes do produto -->
                                <div class="col-md-8  text-center">
                                    <!-- Linha 1: Nome do Produto e Descrição -->
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="name" class="col-form-label text-center">Nome do Produto</label>
                                                <input type="text" name="name" id="name" class="form-control" value="{{ $product->name }}" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="product_code" class="col-form-label text-center">Código do Produto</label>
                                                <input type="text" name="product_code" id="product_code" class="form-control" value="{{ $product->product_code }}" required>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Linha 2: Preço e Quantidade -->
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="price" class="col-form-label text-center">Preço</label>
                                                <input type="number" name="price" id="price" class="form-control" value="{{ $product->price }}" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="stock_quantity" class="col-form-label text-center">Qtd</label>
                                                <input type="number" name="stock_quantity" id="stock_quantity" class="form-control" value="{{ $product->stock_quantity }}" required>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Linha 3: Categoria e Status -->
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="category_id" class=" col-form-label text-center">Categoria</label>
                                                <select name="category_id" id="category_id" class="form-control" required>
                                                    @foreach($categories as $category)
                                                    <option value="{{ $category->id_category }}" {{ $product->category_id == $category->id_category ? 'selected' : '' }}>{{ $category->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="status" class="col-form-label  text-center">Status</label>
                                                <select name="status" id="status" class="form-control" required>
                                                    <option value="active" {{ $product->status == 'active' ? 'selected' : '' }}>Ativo</option>
                                                    <option value="inactive" {{ $product->status == 'inactive' ? 'selected' : '' }}>Inativo</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Coluna da direita com a foto -->
                                <div class="col-md-4 text-center">
                                    <div class="position-relative">

                                        <!-- Foto do Produto -->
                                        <img src="{{ asset('storage/products/'.$product->image) }}" id="productImage" class="img-fluid mb-3" alt="{{ $product->name }}" style="width: 150px; height: 150px; object-fit: cover;">

                                        <!-- Área para selecionar imagem -->
                                        <input type="file" name="image" id="image" class="form-control" style="display: none;" onchange="previewImage(event)">

                                        <!-- Clickable Area to Change Image -->
                                        <label for="image" style="cursor: pointer; position: absolute; top: 0; right: 0; bottom: 0; left: 0; background: rgba(0, 0, 0, 0.5); display: flex; align-items: center; justify-content: center; color: white; font-size: 16px;">
                                            Clique para trocar a imagem
                                        </label>
                                    </div>

                                    <div class="position-relative">
                                        <div class="form-group">
                                            <label for="description" class="col-form-label">Descrição</label>
                                            <textarea name="description" id="description" class="form-control" rows="3">{{ $product->description }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Centralizar o botão de atualizar produto -->
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary">Atualizar Produto</button>
                            </div>
                        </form>
                    </div>


                </div>
            </div>
        </div>
    </div>

    <script>
        function previewImage(event) {
            var reader = new FileReader();
            reader.onload = function() {
                var output = document.getElementById('productImage');
                output.src = reader.result;
            };
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>


    @endforeach

<!-- Modal de Adicionar Produto -->
<div class="modal fade" id="modalAddProduct" tabindex="-1" aria-labelledby="modalAddProductLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl"> <!-- Modal Ampliado -->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalAddProductLabel">Adicionar Novo Produto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <!-- Formulário de Criação de Produto -->
                    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <!-- Coluna da Esquerda com os Detalhes do Produto -->
                            <div class="col-md-8 text-center">
                                <!-- Linha 1: Nome do Produto e Código do Produto -->
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="name" class="col-form-label text-center">Nome do Produto</label>
                                            <input type="text" name="name" id="name" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="product_code" class="col-form-label text-center">Código do Produto</label>
                                            <input type="text" name="product_code" id="product_code" class="form-control" required>
                                        </div>
                                    </div>
                                </div>

                                <!-- Linha 2: Preço e Quantidade -->
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="price" class="col-form-label text-center">Preço</label>
                                            <input type="number" name="price" id="price" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="stock_quantity" class="col-form-label text-center">Quantidade em Estoque</label>
                                            <input type="number" name="stock_quantity" id="stock_quantity" class="form-control" required>
                                        </div>
                                    </div>
                                </div>

                                <!-- Linha 3: Categoria e Status -->
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="category_id" class="col-form-label text-center">Categoria</label>
                                            <select name="category_id" id="category_id" class="form-control" required>
                                                @if($categories->isEmpty())
                                                    <option value="N/A" selected>N/A</option>
                                                @else
                                                    @foreach($categories as $category)
                                                    <option value="{{ $category->id_category }}">{{ $category->name }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="status" class="col-form-label text-center">Status</label>
                                            <select name="status" id="status" class="form-control" required>
                                                <option value="active">Ativo</option>
                                                <option value="inactive">Inativo</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Coluna da Direita com a Foto -->
                            <div class="col-md-4 text-center">
                                <div class="position-relative">
                                    <!-- Foto do Produto -->
                                    <img src="{{ asset('storage/products/'.$product->image ?? 'default.jpg') }}" id="productImage" class="img-fluid mb-3" alt="Imagem do Produto" style="width: 150px; height: 150px; object-fit: cover;">

                                    <!-- Área para selecionar imagem -->
                                    <input type="file" name="image" id="image" class="form-control" style="display: none;" onchange="previewImage(event)">

                                    <!-- Clickable Area to Change Image -->
                                    <label for="image" style="cursor: pointer; position: absolute; top: 0; right: 0; bottom: 0; left: 0; background: rgba(0, 0, 0, 0.5); display: flex; align-items: center; justify-content: center; color: white; font-size: 16px;">
                                        Clique para trocar a imagem
                                    </label>
                                </div>
                                <div class="position-relative">
                                        <div class="form-group">
                                            <label for="description" class="col-form-label">Descrição</label>
                                            <textarea name="description" id="description" class="form-control" rows="3"></textarea>
                                        </div>
                                    </div>
                            </div>
                        </div>

                        <!-- Centralizar o botão de Adicionar Produto -->
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <button type="submit" class="btn btn-primary">Adicionar Produto</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>



</div>



@endsection