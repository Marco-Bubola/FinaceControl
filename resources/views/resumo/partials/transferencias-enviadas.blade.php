<div class="row">
    @forelse ($enviadas as $transferencia)
    <div class="col-md-6">
        <div class="card mb-3 border-0 shadow-sm">
            <div class="card-body d-flex align-items-center gap-3">
                <button
                    class="btn btn-icon-only btn-rounded d-flex align-items-center justify-content-center"
                    style="border: 3px solid {{ $transferencia->category->hexcolor_category ?? '#ccc' }};
                    background-color: {{ $transferencia->category->hexcolor_category ?? '#ccc' }}20;
                    width: 50px; height: 50px;"
                    title="{{ $transferencia->category->name ?? 'Categoria não definida' }}"
                    data-bs-toggle="tooltip">
                    <i class="{{ $transferencia->category->icone ?? 'fas fa-question' }}"
                        style="font-size: 1.3rem;"></i>
                </button>
                <div class="flex-grow-1">
                    <h6 class="mb-1 text-dark">{{ e($transferencia->description) }}</h6>
                    <small class="text-muted">Data:
                        {{ \Carbon\Carbon::parse($transferencia->date)->format('d/m/Y') }}</small><br>
                    <small class="badge bg-primary fs-6">R$
                        {{ number_format($transferencia->value, 2, ',', '.') }}</small>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="d-flex flex-column align-items-center justify-content-center py-5">
            <div class="animated-icon mb-4">
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
                Nenhuma Transferência Enviada
            </h2>
            <p class="mb-4 text-secondary text-center" style="max-width: 480px; font-size:1.15rem; font-weight:500; line-height:1.6;">
                <span style="color:#0d6efd; font-weight:700;">Ops!</span> Não encontramos transferências enviadas para o mês selecionado.<br>
                <span style="color:#6ea8fe;">Realize uma nova transferência</span> para começar a registrar seus envios!
            </p>
        </div>
    </div>
    @endforelse
</div>
