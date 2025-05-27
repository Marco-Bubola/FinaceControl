{{-- Faturas --}}
<div class="row">
    @forelse ($faturas as $fatura)
    <div class="col-md-6">
        <div class="fatura-card mb-3">
            <div class="fatura-card-body d-flex gap-3 align-items-start">
                <div class="fatura-icon rounded-circle d-flex align-items-center justify-content-center flex-shrink-0">
                    <i class="{{ $fatura->category->icone }}"></i>
                </div>
                <div class="flex-grow-1 d-flex flex-column justify-content-between">
                    <div>
                        <h6 class="mb-1 fatura-title" title="{{ $fatura->description }}">
                            {{ e($fatura->description) }}
                        </h6>
                        <small class="fatura-date">{{ \Carbon\Carbon::parse($fatura->invoice_date)->format('d/m/Y') }}</small>
                    </div>
                    <div class="fatura-actions d-flex flex-row align-items-center mt-2">
                        <label class="form-check-label mb-0 me-2 d-flex align-items-center form-switch form-check-primary">
                            <input type="checkbox" class="form-check-input dividir-checkbox me-1"
                                data-id="{{ $fatura->id }}"
                                {{ $fatura->dividida ? 'checked' : '' }}>
                            <span class="d-none d-sm-inline ms-1">Dividir</span>
                        </label>
                        <span class="fatura-badge ms-auto" id="valor-fatura-{{ $fatura->id }}">
                            <i class="bi bi-currency-dollar me-1"></i>
                            R$ {{ number_format($fatura->dividida ? $fatura->value / 2 : $fatura->value, 2, ',', '.') }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="d-flex flex-column align-items-center justify-content-center py-5">
            <div class="animated-icon mb-2">
                <svg width="130" height="130" viewBox="0 0 130 130" fill="none">
                    <circle cx="65" cy="65" r="62" stroke="#e3eafc" stroke-width="3" fill="#f8fafc"/>
                    <!-- Ícone de carteira triste -->
                    <rect x="40" y="60" width="50" height="30" rx="10" fill="#e9f2ff" stroke="#6ea8fe" stroke-width="3"/>
                    <rect x="55" y="40" width="20" height="25" rx="6" fill="#f8fafc" stroke="#6ea8fe" stroke-width="3"/>
                    <rect x="50" y="95" width="30" height="8" rx="4" fill="#6ea8fe" opacity="0.18"/>
                    <ellipse cx="60" cy="75" rx="3" ry="2" fill="#6ea8fe" opacity="0.25"/>
                    <ellipse cx="70" cy="75" rx="3" ry="2" fill="#6ea8fe" opacity="0.25"/>
                    <!-- Boca triste -->
                    <path d="M60 85 Q65 80 70 85" stroke="#6ea8fe" stroke-width="2" fill="none"/>
                </svg>
            </div>
            <h2 class="fw-bold mb-3 text-primary" style="font-size:2.2rem; letter-spacing:0.01em; text-shadow:0 2px 8px #e3eafc;">
                Nenhuma Fatura Registrada
            </h2>
            <p class="mb-2 text-secondary text-center" style="max-width: 480px; font-size:1.15rem; font-weight:500; line-height:1.6;">
                <span style="color:#0d6efd; font-weight:700;">Ops!</span> Não encontramos faturas para o mês selecionado.<br>
                <span style="color:#6ea8fe;">Adicione uma nova fatura</span> para começar a registrar seus lançamentos!
            </p>
        </div>
    </div>
    @endforelse
</div>
@if (empty($onlyFaturas) && $faturas->hasPages())
    <nav>
        <ul class="pagination justify-content-center mt-3">
            {{-- Previous Page Link --}}
            <li class="page-item {{ $faturas->onFirstPage() ? 'disabled' : '' }}">
                <a class="page-link"
                   href="{{ route('clientes.faturas.ajax', ['cliente' => $clienteId, 'page' => $faturas->currentPage() - 1]) }}"
                   tabindex="-1"
                   aria-disabled="{{ $faturas->onFirstPage() ? 'true' : 'false' }}">Anterior</a>
            </li>
            {{-- Pagination Elements --}}
            @for ($page = 1; $page <= $faturas->lastPage(); $page++)
                @if ($page == $faturas->currentPage())
                    <li class="page-item active"><span class="page-link">{{ $page }}</span></li>
                @else
                    <li class="page-item">
                        <a class="page-link"
                           href="{{ route('clientes.faturas.ajax', ['cliente' => $clienteId, 'page' => $page]) }}">{{ $page }}</a>
                    </li>
                @endif
            @endfor
            {{-- Next Page Link --}}
            <li class="page-item {{ $faturas->hasMorePages() ? '' : 'disabled' }}">
                <a class="page-link"
                   href="{{ route('clientes.faturas.ajax', ['cliente' => $clienteId, 'page' => $faturas->currentPage() + 1]) }}">Próxima</a>
            </li>
        </ul>
    </nav>
@endif
