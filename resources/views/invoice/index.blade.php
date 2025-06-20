@extends('layouts.user_type.auth')

@section('content')
<div class="container-fluid px-2 px-md-4 py-3" style="background: #f8fafc; min-height: 100vh;">
    <div class="mx-auto">
        <div class="rounded-4 ">

            <!-- Banco e ações -->
            <div class="d-flex flex-wrap align-items-center gap-3 ">
                <span class="d-flex align-items-center justify-content-center rounded-circle"
                    style="background: #e7f1ff; width: 54px; height: 54px;">
                    <i class="fas fa-wifi text-primary" style="font-size: 2rem;"></i>
                </span>
                <div class="flex-grow-1">
                    <div class="fw-bold fs-5 text-dark mb-1 d-flex align-items-center gap-2">
                        {{ $bank->description }}
                        <img class="ms-2" src="{{ $bank->caminho_icone }}" alt="logo"
                            style="height: 32px; filter: drop-shadow(0 2px 6px rgba(13,110,253,0.10));">
                    </div>
                    <div class="text-secondary small d-flex flex-wrap gap-3">
                        <span><i class="bi bi-person-badge me-1"></i><strong>Titular:</strong> {{ $bank->name }}</span>
                        <span><i class="bi bi-calendar-range me-1"></i><strong>Validade:</strong>
                            {{ \Carbon\Carbon::parse($bank->start_date)->format('d/m') }} -
                            {{ \Carbon\Carbon::parse($bank->end_date)->format('d/m') }}</span>
                    </div>
                </div>

                <div class="month-cards-group d-flex flex-wrap justify-content-center">
                    <!-- Calendário de seleção de mês -->
                    <div class="mb-3 d-flex justify-content-center">
                        <div id="monthPicker" class="custom-flatpickr-calendar" style="max-width: 260px;"></div>
                    </div>
                    <!-- Card Mês Anterior -->
                    <div class="month-card card border-0 shadow-sm bg-white text-center" id="card-previous-month">
                        <div class="card-body py-4 ">
                            <div class="mb-1 text-muted small fw-semibold">Mês Anterior</div>
                            <h5 class="card-title mb-3 d-flex align-items-center justify-content-center gap-2">
                                <i class="fas fa-chevron-left fa-lg text-primary"></i>
                                <span id="previous-month-name" class="fw-bold">{{ $previousMonthName }}</span>
                            </h5>
                            <a href="#" id="previous-month"
                                class="btn btn-outline-primary btn-change-month rounded-pill px-4 py-2 fw-semibold"
                                data-month="{{ $previousMonth }}">
                                <i class="fas fa-eye me-1"></i>
                                Ver <span id="previous-month-btn-name">{{ $previousMonthName }}</span>
                            </a>
                        </div>
                    </div>
                    <!-- Card Mês Atual -->
                    <div class="month-card card border-0 shadow bg-gradient text-white text-center month-card-current"
                        id="card-current-month">
                        <div class="card-body py-4 ">
                            <div class="mb-1 small fw-semibold" style="opacity:0.92;">Mês Atual</div>
                            <h5 class="card-title mb-3 d-flex align-items-center justify-content-center gap-2"
                                id="current-month-title">
                                <i class="bi bi-calendar3 fa-lg"></i>
                                <span id="current-month-name" class="fw-bold">{{ $currentMonthName }}</span>
                            </h5>
                            <div class="card-text small fw-semibold" style="opacity:0.92;">
                                <span id="current-month-range">
                                    {{ $currentStartDate->format('d/m') }} - {{ $currentEndDate->format('d/m') }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <!-- Card Próximo Mês -->
                    <div class="month-card card border-0 shadow-sm bg-white text-center" id="card-next-month">
                        <div class="card-body py-4">
                            <div class="mb-1 text-muted small fw-semibold">Próximo Mês</div>
                            <h5 class="card-title mb-3 d-flex align-items-center justify-content-center gap-2">
                                <span id="next-month-name" class="fw-bold">{{ $nextMonthName }}</span>
                                <i class="fas fa-chevron-right fa-lg text-primary"></i>
                            </h5>
                            <a href="#" id="next-month"
                                class="btn btn-outline-primary btn-change-month rounded-pill px-4 py-2 fw-semibold"
                                data-month="{{ $nextMonth }}">
                                <i class="fas fa-eye me-1"></i>
                                Ver <span id="next-month-btn-name">{{ $nextMonthName }}</span>
                            </a>
                        </div>
                    </div>
                </div>
                <!-- Bloco de Botões de Ação -->
                <div class="summary-actions d-flex flex-column justify-content-center align-items-center ms-2">
                    <button class="btn btn-primary mb-2 shadow-sm" data-bs-toggle="modal"
                        data-bs-target="#addTransactionModal" title="Adicionar transação">
                        <i class="bi bi-plus-circle fs-5"></i>
                    </button>
                    <button class="btn btn-outline-secondary shadow-sm" data-bs-toggle="modal"
                        data-bs-target="#uploadModal" title="Upload">
                        <i class="bi bi-upload fs-5"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="row ">
        <div class="col-lg-8">
            <div id="transactions-container">
                @include('invoice.transactions', [
                'eventsGroupedByMonthAndCategory' => $eventsGroupedByMonthAndCategory,
                'categories' => $categories,
                'clients' => $clients
                ])
            </div>
        </div>
        <div class="col-lg-4">
            <div class="summary-cards-area-modern my-2">
                <div class="d-flex flex-wrap gap-4 justify-content-center align-items-stretch">
                    <!-- Card: Preço Total -->
                    <div class="summary-card-modern flex-grow-1">
                        <div class="summary-icon-modern summary-total-modern mb-2">
                            <i class="bi bi-cash-coin"></i>
                        </div>
                        <div class="summary-label-modern">Preço Total</div>
                        <div class="summary-value-modern text-success" id="total-invoices">
                            R$ {{ number_format($totalInvoices, 2) }}
                        </div>
                    </div>
                    <!-- Card: Maior Fatura -->
                    <div class="summary-card-modern flex-grow-1">
                        <div class="summary-icon-modern summary-high-modern mb-2">
                            <i class="bi bi-arrow-up-circle-fill"></i>
                        </div>
                        <div class="summary-label-modern">Maior Fatura</div>
                        <div class="summary-value-modern text-danger" id="highest-invoice">
                            R$ {{ $highestInvoice ? number_format($highestInvoice->value, 2) : '0,00' }}
                        </div>
                    </div>
                    <!-- Card: Menor Fatura -->
                    <div class="summary-card-modern flex-grow-1">
                        <div class="summary-icon-modern summary-low-modern mb-2">
                            <i class="bi bi-arrow-down-circle-fill"></i>
                        </div>
                        <div class="summary-label-modern">Menor Fatura</div>
                        <div class="summary-value-modern text-warning" id="lowest-invoice">
                            R$ {{ $lowestInvoice ? number_format($lowestInvoice->value, 2) : '0,00' }}
                        </div>
                    </div>
                    <!-- Card: Total de Transações -->
                    <div class="summary-card-modern flex-grow-1">
                        <div class="summary-icon-modern summary-count-modern mb-2">
                            <i class="bi bi-list-ol"></i>
                        </div>
                        <div class="summary-label-modern">Total de Transações</div>
                        <div class="summary-value-modern text-info" id="total-transactions">
                            {{ $totalTransactions }}
                        </div>
                    </div>
                </div>
            </div>
            <div>
                <span id="no-data-message" style="display: none;">Sem dados</span>
                <canvas id="updateCategoryChart"></canvas>
            </div>
            <div>
                <canvas id="lineChart"></canvas> <!-- Gráfico de linha -->
            </div>
        </div>
    </div>

    <!-- Paginação -->
    <div class="d-flex justify-content-center mt-4">
        <nav>
            <ul class="pagination">
                <!-- ...existing code... -->
            </ul>
        </nav>
    </div>
</div>
<!-- Adiciona Flatpickr e plugin de seleção de mês -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/monthSelect/style.css">
<style>
/* Estilização customizada para o calendário Flatpickr */
.custom-flatpickr-calendar .flatpickr-calendar {
    border-radius: 18px !important;
    box-shadow: 0 4px 24px rgba(13,110,253,0.10), 0 1.5px 6px rgba(0,0,0,0.04);
    border: none;
    font-family: 'Inter', Arial, sans-serif;
    background: #f8fafc;
    margin: 0 auto;
}
.custom-flatpickr-calendar .flatpickr-months {
    border-radius: 18px 18px 0 0;
    background: #0d6efd;
    color: #fff;
    padding: 8px 0;
}
.custom-flatpickr-calendar .flatpickr-current-month {
    font-size: 1.2rem;
    font-weight: 600;
}
.custom-flatpickr-calendar .flatpickr-weekdays {
    background: #e7f1ff;
    border-radius: 0 0 0 0;
}
.custom-flatpickr-calendar .flatpickr-day {
    border-radius: 8px;
    font-size: 1.1rem;
    color: #0d6efd;
    transition: background 0.2s, color 0.2s;
}
.custom-flatpickr-calendar .flatpickr-day.selected,
.custom-flatpickr-calendar .flatpickr-day.startRange,
.custom-flatpickr-calendar .flatpickr-day.endRange {
    background: #0d6efd;
    color: #fff;
}
.custom-flatpickr-calendar .flatpickr-day:hover {
    background: #e7f1ff;
    color: #0d6efd;
}
.custom-flatpickr-calendar .flatpickr-monthDropdown-months,
.custom-flatpickr-calendar .flatpickr-monthDropdown-month {
    color: #0d6efd;
}
.custom-flatpickr-calendar .flatpickr-prev-month,
.custom-flatpickr-calendar .flatpickr-next-month {
    color: #fff;
    opacity: 0.8;
}
.custom-flatpickr-calendar .flatpickr-months .flatpickr-prev-month:hover,
.custom-flatpickr-calendar .flatpickr-months .flatpickr-next-month:hover {
    color: #ffc107;
    opacity: 1;
}
.custom-flatpickr-calendar .flatpickr-monthSelect-month {
    border-radius: 8px;
    font-size: 1.1rem;
    padding: 8px 0;
    color: #0d6efd;
    background: #fff;
    transition: background 0.2s, color 0.2s;
}
.custom-flatpickr-calendar .flatpickr-monthSelect-month.selected,
.custom-flatpickr-calendar .flatpickr-monthSelect-month:hover {
    background: #0d6efd;
    color: #fff;
}
</style>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/pt.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/monthSelect/index.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inicializa o flatpickr para seleção de mês, agora inline e em português
    var fp = flatpickr("#monthPicker", {
        dateFormat: "Y-m",
        defaultDate: "{{ $currentStartDate->format('Y-m') }}",
        locale: "pt",
        inline: true,
        plugins: [
            new monthSelectPlugin({
                shorthand: true,
                dateFormat: "Y-m",
                altFormat: "F Y"
            })
        ],
        onChange: function(selectedDates, dateStr, instance) {
            if (dateStr) {
                var month = dateStr + "-01";
                if (typeof updateMonthData === "function") {
                    updateMonthData(month);
                } else {
                    $('.btn-change-month[data-month="' + month + '"]').trigger('click');
                }
            }
        }
    });

    $(document).on('click', '.btn-change-month', function() {
        var month = $(this).data('month');
        if (month && fp) {
            fp.setDate(month, true, "Y-m-d");
        }
    });
});
</script>
<script>
window.INVOICES_INDEX_URL = "{{ route('invoices.index') }}";
window.INITIAL_CATEGORIES = @json($categoriesData);
window.TOTAL_INVOICES = {{$totalInvoices}};
window.EVENTS_DATA = @json($eventsDetailed);
window.BANK_ID = "{{ $bank->id_bank }}";
</script>
<script src="{{ asset('js/invoice.js') }}"></script>
@include('invoice.create')
@include('invoice.uploadInvoice')
<link rel="stylesheet" href="{{ asset('css/invoice.css') }}">
@endsection