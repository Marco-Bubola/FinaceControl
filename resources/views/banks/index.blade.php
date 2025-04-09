@extends('layouts.user_type.auth')

@section('content')

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-lg-8">
            <div class="row">
                @forelse ($banks as $index => $bank)
                <div class="col-xl-6 mb-xl-0 mb-4 card-item {{ $index != 0 ? 'd-none' : '' }}" data-index="{{ $index }}">
                    <div class="card bg-transparent shadow-xl">
                        <div class="overflow-hidden position-relative border-radius-xl" style="background-image: url('../assets/img/curved-images/curved14.jpg');">
                            <span class="mask bg-gradient-dark"></span>
                            <!-- Botões de Ação -->

                            <div class="card-body position-relative z-index-1 p-3">
                                <i class="fas fa-wifi text-white p-2"></i>
                                <!-- Botão de Visualizar Cartão -->
                                <a href="{{ route('invoices.index', ['bank_id' => $bank->id]) }}" class="btn bg-gradient-info mb-0" title="Visualizar Cartão">
                                    <i class="fas fa-eye"></i>&nbsp;
                                </a><!-- Botão de Trocar Cartão -->
                                <button class="btn bg-gradient-warning mb-0" title="Trocar Cartão" data-index="{{ $index }}">
                                    <i class="fas fa-exchange-alt"></i>&nbsp;
                                </button>

                                <h5 class="text-white mt-4 mb-5 pb-2">{{ $bank->description }}</h5>
                                <div class="d-flex justify-content-between">
                                    <div class="d-flex">
                                        <div class="me-4">
                                            <p class="text-white text-sm opacity-8 mb-0">Titular do Cartão</p>
                                            <h6 class="text-white mb-0">{{ $bank->name }}</h6>
                                        </div>
                                        <div>
                                            <p class="text-white text-sm opacity-8 mb-0">Validade</p>
                                            <h6 class="text-white mb-0">{{ \Carbon\Carbon::parse($bank->start_date)->format('d/m') }} - {{ \Carbon\Carbon::parse($bank->end_date)->format('d/m') }}</h6>
                                        </div>
                                    </div>
                                    <div class="ms-auto w-20 d-flex align-items-end justify-content-end">
                                        <img class="w-60 mt-2" src="../assets/img/logos/mastercard.png" alt="logo">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-xl-6 mb-xl-0 mb-4 text-center">
                    <p class="text-muted">Você ainda não tem cartões cadastrados.</p>
                    <a class="btn bg-gradient-dark mb-0" data-bs-toggle="modal" data-bs-target="#addCardModal"><i class="fas fa-plus"></i>&nbsp;&nbsp;Adicionar Novo Cartão</a>
                </div>
                @endempty

                <script>
                    // Função para mudar o cartão
                    function changeCard(button) {
                        const index = button.getAttribute('data-index'); // Pega o índice do atributo data-index
                        console.log("Índice do cartão: ", index); // Verifique no console se o índice está correto

                        const cards = document.querySelectorAll('.card-item');

                        // Esconde todos os cartões
                        cards.forEach(card => {
                            card.classList.add('d-none');
                        });

                        // Mostra o cartão com o índice selecionado
                        const activeCard = document.querySelector(`.card-item[data-index="${index}"]`);
                        if (activeCard) {
                            activeCard.classList.remove('d-none');
                        } else {
                            console.log('Cartão não encontrado com o índice:', index); // Verifique se o cartão foi encontrado
                        }
                    }

                    // Aplique a função para todos os botões "Trocar Cartão"
                    document.querySelectorAll('.btn.bg-gradient-warning').forEach(button => {
                        button.addEventListener('click', function() {
                            changeCard(button); // Passa o próprio botão como referência
                        });
                    });
                </script>

                <div class="col-xl-6">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header mx-4 p-3 text-center">
                                    <div class="icon icon-shape icon-lg bg-gradient-primary shadow text-center border-radius-lg">
                                        <i class="fas fa-landmark opacity-10"></i>
                                    </div>
                                </div>
                                <div class="card-body pt-0 p-3 text-center">
                                    <h6 class="text-center mb-0">Salário</h6>
                                    <span class="text-xs">Belong Interactive</span>
                                    <hr class="horizontal dark my-3">
                                    <h5 class="mb-0">+$2000</h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mt-md-0 mt-4">
                            <div class="card">
                                <div class="card-header mx-4 p-3 text-center">
                                    <div class="icon icon-shape icon-lg bg-gradient-primary shadow text-center border-radius-lg">
                                        <i class="fab fa-paypal opacity-10"></i>
                                    </div>
                                </div>
                                <div class="card-body pt-0 p-3 text-center">
                                    <h6 class="text-center mb-0">Paypal</h6>
                                    <span class="text-xs">Pagamento Freelance</span>
                                    <hr class="horizontal dark my-3">
                                    <h5 class="mb-0">$455.00</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 mb-lg-0 mb-4">
                    <div class="card mt-4">
                        <div class="card-header pb-0 p-3">
                            <div class="row">
                                <div class="col-6 d-flex align-items-center">
                                    <h6 class="mb-0">Método de Pagamento</h6>
                                </div>
                                <div class="col-6 text-end">
                                    <a class="btn bg-gradient-dark mb-0" data-bs-toggle="modal" data-bs-target="#addCardModal"><i class="fas fa-plus"></i>&nbsp;&nbsp;Adicionar Novo Cartão</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-3">
                            <div class="row">
                                @foreach ($banks as $bank)
                                <div class="col-md-6 mb-md-0 mb-4">
                                    <div class="card card-body border card-plain border-radius-lg d-flex align-items-center flex-row">
                                        <img class="w-10 me-3 mb-0" src="../assets/img/logos/mastercard.png" alt="logo">
                                        <h6 class="mb-0">{{ \Carbon\Carbon::parse($bank->start_date)->format('d/m') }} - {{ \Carbon\Carbon::parse($bank->end_date)->format('d/m') }}</h6>
                                        <i class="fas fa-pencil-alt ms-auto text-dark cursor-pointer" data-bs-toggle="tooltip" data-bs-placement="top" title="Editar Cartão"></i>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal para Adicionar Novo Cartão -->
        <div class="modal fade" id="addCardModal" tabindex="-1" aria-labelledby="addCardModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addCardModalLabel">Adicionar Novo Cartão</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action="{{ route('banks.store') }}">
                            @csrf
                            <div class="mb-3">
                                <label for="name" class="form-label">Titular do Cartão</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">Número do Cartão</label>
                                <input type="text" class="form-control" id="description" name="description" required>
                            </div>
                            <div class="mb-3">
                                <label for="start_date" class="form-label">Data de Início</label>
                                <input type="date" class="form-control" id="start_date" name="start_date" required>
                            </div>
                            <div class="mb-3">
                                <label for="end_date" class="form-label">Data de Término</label>
                                <input type="date" class="form-control" id="end_date" name="end_date" required>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                                <button type="submit" class="btn btn-primary">Salvar Cartão</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card h-100">
                <div class="card-header pb-0 p-3">
                    <div class="row">
                        <div class="col-6 d-flex align-items-center">
                            <h6 class="mb-0">Faturas</h6>
                        </div>
                        <div class="col-6 text-end">
                            <button class="btn btn-outline-primary btn-sm mb-0">Ver Todas</button>
                        </div>
                    </div>
                </div>
                <div class="card-body p-3 pb-0">
                    <ul class="list-group">
                        <li class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
                            <div class="d-flex flex-column">
                                <h6 class="mb-1 text-dark font-weight-bold text-sm">1º de Março, 2020</h6>
                                <span class="text-xs">#MS-415646</span>
                            </div>
                            <div class="d-flex align-items-center text-sm">
                                $180
                                <button class="btn btn-link text-dark text-sm mb-0 px-0 ms-4"><i class="fas fa-file-pdf text-lg me-1"></i> PDF</button>
                            </div>
                        </li>
                        <li class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
                            <div class="d-flex flex-column">
                                <h6 class="text-dark mb-1 font-weight-bold text-sm">10 de Fevereiro, 2021</h6>
                                <span class="text-xs">#RV-126749</span>
                            </div>
                            <div class="d-flex align-items-center text-sm">
                                $250
                                <button class="btn btn-link text-dark text-sm mb-0 px-0 ms-4"><i class="fas fa-file-pdf text-lg me-1"></i> PDF</button>
                            </div>
                        </li>
                        <li class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
                            <div class="d-flex flex-column">
                                <h6 class="text-dark mb-1 font-weight-bold text-sm">5 de Abril, 2020</h6>
                                <span class="text-xs">#FB-212562</span>
                            </div>
                            <div class="d-flex align-items-center text-sm">
                                $560
                                <button class="btn btn-link text-dark text-sm mb-0 px-0 ms-4"><i class="fas fa-file-pdf text-lg me-1"></i> PDF</button>
                            </div>
                        </li>
                        <li class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
                            <div class="d-flex flex-column">
                                <h6 class="text-dark mb-1 font-weight-bold text-sm">25 de Junho, 2019</h6>
                                <span class="text-xs">#QW-103578</span>
                            </div>
                            <div class="d-flex align-items-center text-sm">
                                $120
                                <button class="btn btn-link text-dark text-sm mb-0 px-0 ms-4"><i class="fas fa-file-pdf text-lg me-1"></i> PDF</button>
                            </div>
                        </li>
                        <li class="list-group-item border-0 d-flex justify-content-between ps-0 border-radius-lg">
                            <div class="d-flex flex-column">
                                <h6 class="text-dark mb-1 font-weight-bold text-sm">1º de Março, 2019</h6>
                                <span class="text-xs">#AR-803481</span>
                            </div>
                            <div class="d-flex align-items-center text-sm">
                                $300
                                <button class="btn btn-link text-dark text-sm mb-0 px-0 ms-4"><i class="fas fa-file-pdf text-lg me-1"></i> PDF</button>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-7 mt-4">
            <div class="card">
                <div class="card-header pb-0 px-3">
                    <h6 class="mb-0">Informações de Faturamento</h6>
                </div>
                <div class="card-body pt-4 p-3">
                    <ul class="list-group">
                        <li class="list-group-item border-0 d-flex p-4 mb-2 bg-gray-100 border-radius-lg">
                            <div class="d-flex flex-column">
                                <h6 class="mb-3 text-sm">Oliver Liam</h6>
                                <span class="mb-2 text-xs">Nome da Empresa: <span class="text-dark font-weight-bold ms-sm-2">Viking Burrito</span></span>
                                <span class="mb-2 text-xs">Endereço de Email: <span class="text-dark ms-sm-2 font-weight-bold">oliver@burrito.com</span></span>
                                <span class="text-xs">Número de VAT: <span class="text-dark ms-sm-2 font-weight-bold">FRB1235476</span></span>
                            </div>
                            <div class="ms-auto text-end">
                                <a class="btn btn-link text-danger text-gradient px-3 mb-0" href="javascript:;"><i class="far fa-trash-alt me-2"></i>Excluir</a>
                                <a class="btn btn-link text-dark px-3 mb-0" href="javascript:;"><i class="fas fa-pencil-alt text-dark me-2" aria-hidden="true"></i>Editar</a>
                            </div>
                        </li>
                        <li class="list-group-item border-0 d-flex p-4 mb-2 mt-3 bg-gray-100 border-radius-lg">
                            <div class="d-flex flex-column">
                                <h6 class="mb-3 text-sm">Lucas Harper</h6>
                                <span class="mb-2 text-xs">Nome da Empresa: <span class="text-dark font-weight-bold ms-sm-2">Stone Tech Zone</span></span>
                                <span class="mb-2 text-xs">Endereço de Email: <span class="text-dark ms-sm-2 font-weight-bold">lucas@stone-tech.com</span></span>
                                <span class="text-xs">Número de VAT: <span class="text-dark ms-sm-2 font-weight-bold">FRB1235476</span></span>
                            </div>
                            <div class="ms-auto text-end">
                                <a class="btn btn-link text-danger text-gradient px-3 mb-0" href="javascript:;"><i class="far fa-trash-alt me-2"></i>Excluir</a>
                                <a class="btn btn-link text-dark px-3 mb-0" href="javascript:;"><i class="fas fa-pencil-alt text-dark me-2" aria-hidden="true"></i>Editar</a>
                            </div>
                        </li>
                        <li class="list-group-item border-0 d-flex p-4 mb-2 mt-3 bg-gray-100 border-radius-lg">
                            <div class="d-flex flex-column">
                                <h6 class="mb-3 text-sm">Ethan James</h6>
                                <span class="mb-2 text-xs">Nome da Empresa: <span class="text-dark font-weight-bold ms-sm-2">Fiber Notion</span></span>
                                <span class="mb-2 text-xs">Endereço de Email: <span class="text-dark ms-sm-2 font-weight-bold">ethan@fiber.com</span></span>
                                <span class="text-xs">Número de VAT: <span class="text-dark ms-sm-2 font-weight-bold">FRB1235476</span></span>
                            </div>
                            <div class="ms-auto text-end">
                                <a class="btn btn-link text-danger text-gradient px-3 mb-0" href="javascript:;"><i class="far fa-trash-alt me-2"></i>Excluir</a>
                                <a class="btn btn-link text-dark px-3 mb-0" href="javascript:;"><i class="fas fa-pencil-alt text-dark me-2" aria-hidden="true"></i>Editar</a>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-5 mt-4">
            <div class="card h-100 mb-4">
                <div class="card-header pb-0 px-3">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="mb-0">Suas Transações</h6>
                        </div>
                        <div class="col-md-6 d-flex justify-content-end align-items-center">
                            <i class="far fa-calendar-alt me-2"></i>
                            <small>{{ \Carbon\Carbon::now()->format('d - m Y') }}</small>
                        </div>
                    </div>
                </div>

                <div class="card-body pt-4 p-3">
                    @if ($hasInvoices)
                    <p>Você ainda não tem transações.</p>
                    @else
                    <h6 class="text-uppercase text-body text-xs font-weight-bolder mb-3">Mais Recentes</h6>
                    <ul class="list-group">
                        @foreach ($invoices as $invoice)
                        <li class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
                            <div class="d-flex align-items-center">
                                <button class="btn btn-icon-only btn-rounded {{ $invoice->value < 0 ? 'btn-outline-danger' : 'btn-outline-success' }} mb-0 me-3 btn-sm d-flex align-items-center justify-content-center">
                                    <i class="{{ $invoice->value < 0 ? 'fas fa-arrow-down' : 'fas fa-arrow-up' }}"></i>
                                </button>
                                <div class="d-flex flex-column">
                                    <h6 class="mb-1 text-dark text-sm">{{ $invoice->description }}</h6>
                                    <span class="text-xs">{{ \Carbon\Carbon::parse($invoice->created_at)->format('d M Y, às H:i A') }}</span>
                                </div>
                            </div>
                            <div class="d-flex align-items-center {{ $invoice->value < 0 ? 'text-danger text-gradient' : 'text-success text-gradient' }} text-sm font-weight-bold">
                                {{ $invoice->value < 0 ? '-' : '+' }} $ {{ number_format(abs($invoice->value), 2) }}
                            </div>
                        </li>
                        @endforeach
                    </ul>
                    <h6 class="text-uppercase text-body text-xs font-weight-bolder my-3">Ontem</h6>
                    <ul class="list-group">
                        @foreach ($invoices as $invoice)
                        @if (\Carbon\Carbon::parse($invoice->created_at)->isYesterday())
                        <li class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
                            <div class="d-flex align-items-center">
                                <button class="btn btn-icon-only btn-rounded {{ $invoice->value < 0 ? 'btn-outline-danger' : 'btn-outline-success' }} mb-0 me-3 btn-sm d-flex align-items-center justify-content-center">
                                    <i class="{{ $invoice->value < 0 ? 'fas fa-arrow-down' : 'fas fa-arrow-up' }}"></i>
                                </button>
                                <div class="d-flex flex-column">
                                    <h6 class="mb-1 text-dark text-sm">{{ $invoice->description }}</h6>
                                    <span class="text-xs">{{ \Carbon\Carbon::parse($invoice->created_at)->format('d M Y, às H:i A') }}</span>
                                </div>
                            </div>
                            <div class="d-flex align-items-center {{ $invoice->value < 0 ? 'text-danger text-gradient' : 'text-success text-gradient' }} text-sm font-weight-bold">
                                {{ $invoice->value < 0 ? '-' : '+' }} $ {{ number_format(abs($invoice->value), 2) }}
                            </div>
                        </li>
                        @endif
                        @endforeach
                    </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
