@extends('layouts.user_type.auth')

@section('content')
<div class="container mt-4">
    <!-- Exibir erros de validação -->
    @if ($errors->any())
    <div id="error-message" class="alert alert-danger alert-dismissible fade show" role="alert">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" onclick="closeAlert('error-message')"></button>
        <div id="error-timer" class="alert-timer">30s</div>
    </div>
    @endif

    <!-- Exibir sucesso -->
    @if (session('success'))
    <div id="success-message" class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" onclick="closeAlert('success-message')"></button>
        <div id="success-timer" class="alert-timer">30s</div>
    </div>
    @endif
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Função para iniciar o timer e ocultar a mensagem após 30 segundos
            function startTimer(messageId, timerId) {
                let timerValue = 30;
                const timerElement = document.getElementById(timerId);
                const messageElement = document.getElementById(messageId);

                // Atualiza o temporizador a cada segundo
                const interval = setInterval(function() {
                    if (timerValue > 0) {
                        timerElement.innerHTML = `${timerValue--}s`;
                    } else {
                        clearInterval(interval);
                        // Fecha a mensagem após 30 segundos e recarrega a página
                        messageElement.classList.remove('show');
                        messageElement.classList.add('fade');
                        location.reload(); // Recarregar a página após 30 segundos
                    }
                }, 1000); // Atualiza a cada segundo
            }

            // Iniciar o timer para a mensagem de erro (se existir)
            const errorMessage = document.getElementById('error-message');
            if (errorMessage) {
                startTimer('error-message', 'error-timer');
            }

            // Iniciar o timer para a mensagem de sucesso (se existir)
            const successMessage = document.getElementById('success-message');
            if (successMessage) {
                startTimer('success-message', 'success-timer');
            }

            // Configuração para mostrar que a página voltou ao estado original
            const closeButton = document.querySelectorAll('.btn-close');
            closeButton.forEach(button => {
                button.addEventListener('click', function() {
                    // Resetando o timer de 30 segundos e voltando a página ao estado original
                    document.getElementById('error-message')?.classList.remove('show');
                    document.getElementById('success-message')?.classList.remove('show');
                });
            });
        });

        // Função para fechar o alerta ao clicar no "X"
        function closeAlert(messageId) {
            document.getElementById(messageId).classList.remove('show');
            document.getElementById(messageId).classList.add('fade');
        }
    </script>

    <style>
        .alert-timer {
            position: absolute;
            top: 10px;
            right: 40px;
            background-color: #ff9800;
            color: white;
            padding: 5px 10px;
            font-size: 12px;
            border-radius: 50%;
            display: inline-block;
            margin-top: 5px;
        }

        .alert-dismissible .btn-close {
            position: absolute;
            top: 10px;
            right: 10px;
            padding: 5px 10px;
            color: #fff;
            background: transparent;
            border: none;
        }
    </style>

    <!-- Filtro e Pesquisa -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="row w-100">
            <!-- Coluna de Filtro (Meio) -->
            <div class="col-md-4 mb-3">
                <form action="{{ route('products.index') }}" method="GET" class="d-flex align-items-center w-100">
                    <select name="filter" class="form-control w-100" onchange="this.form.submit()">
                        <option value="">Filtrar</option>
                        <option value="created_at" {{ request('filter') == 'created_at' ? 'selected' : '' }}>Últimos Adicionados</option>
                        <option value="updated_at" {{ request('filter') == 'updated_at' ? 'selected' : '' }}>Últimos Atualizados</option>
                        <option value="name_asc" {{ request('filter') == 'name_asc' ? 'selected' : '' }}>Nome A-Z</option>
                        <option value="name_desc" {{ request('filter') == 'name_desc' ? 'selected' : '' }}>Nome Z-A</option>
                        <option value="price_asc" {{ request('filter') == 'price_asc' ? 'selected' : '' }}>Preço A-Z</option>
                        <option value="price_desc" {{ request('filter') == 'price_desc' ? 'selected' : '' }}>Preço Z-A</option>
                    </select>
                </form>
            </div>

            <!-- Coluna de Pesquisa (Esquerda) -->
            <div class="col-md-4 mb-3">
                <form action="{{ route('products.index') }}" method="GET" class="d-flex align-items-center w-100">
                    <div class="input-group w-100">
                        <input type="text" name="search" class="form-control w-65 h-25" placeholder="Pesquisar por nome ou código" value="{{ request('search') }}">
                        <button class="btn btn-primary h-20" type="submit">Pesquisar</button>
                    </div>
                </form>
            </div>

            <!-- Filtro de Categoria (Direita) -->
            <div class="col-md-4 mb-3">
                <form action="{{ route('products.index') }}" method="GET" class="d-flex align-items-center w-100">
                    <select name="category" class="form-control w-100" onchange="this.form.submit()">
                        <option value="">Filtrar por Categoria</option>
                        @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </form>
            </div>

            <!-- Botões de Adicionar Produto e Upload -->
            <div class="col-md-4 text-end">
                <a href="#" class="btn bg-gradient-primary btn-sm mb-0" data-bs-toggle="modal" data-bs-target="#modalAddProduct">Adicionar Produto</a>
                <a href="#" class="btn bg-gradient-secondary btn-sm mb-0 ms-2" data-bs-toggle="modal" data-bs-target="#modalUploadProduct">Fazer Upload</a>
            </div>
        </div>
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

                            <p><strong>Venda:</strong> R$ {{ number_format($product->price_sale, 2, ',', '.') }}</p>
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

        @endforeach
    </div>
</div>


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
                                            <label for="price_sale" class="col-form-label text-center">Venda</label>
                                            <input type="number" name="price_sale" id="price_sale" class="form-control" required>
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
                                    <!-- Foto do Produto (Apenas o Campo de Upload) -->
                                    <img src="{{ asset('storage/products/default.jpg') }}" id="productImage" class="img-fluid mb-3" alt="Imagem do Produto" style="width: 150px; height: 150px; object-fit: cover;">

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
@foreach($products as $product)
<!-- Modal de Edição de Produto -->
<div class="modal fade" id="modalEditProduct{{ $product->id }}" tabindex="-1" aria-labelledby="modalEditProductLabel{{ $product->id }}" aria-hidden="true">
    <div class="modal-dialog modal-xl">
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
                            <!-- Detalhes do Produto (A mesma estrutura que você já usou) -->
                            <div class="col-md-8 text-center">
                                <!-- Nome, Código do Produto, Preço e Quantidade -->
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

                                <!-- Preço e Quantidade -->
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="price" class="col-form-label text-center">Preço</label>
                                            <input type="number" name="price" id="price" class="form-control" value="{{ $product->price }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="price_sale" class="col-form-label text-center">Venda</label>
                                            <input type="number" name="price_sale" id="price_sale" class="form-control" value="{{ $product->price_sale }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="stock_quantity" class="col-form-label text-center">Quantidade em Estoque</label>
                                            <input type="number" name="stock_quantity" id="stock_quantity" class="form-control" value="{{ $product->stock_quantity }}" required>
                                        </div>
                                    </div>
                                </div>

                                <!-- Categoria e Status -->
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

                            <!-- Foto -->
                            <div class="col-md-4 text-center">
                                <div class="position-relative">
                                    <img src="{{ asset('storage/products/'.$product->image) }}" id="productImage" class="img-fluid mb-3" alt="{{ $product->name }}" style="width: 150px; height: 150px; object-fit: cover;">
                                    <input type="file" name="image" id="image" class="form-control" style="display: none;" onchange="previewImage(event)">
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

                        <div class="text-center">
                            <button type="submit" class="btn btn-primary">Atualizar Produto</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

@endforeach

@endsection