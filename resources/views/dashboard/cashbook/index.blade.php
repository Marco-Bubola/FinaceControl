@extends('layouts.user_type.auth')

@section('content')
@push('head')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/pt.js"></script>
@endpush

<style>
body,
.container-fluid {
    background: #f8fafc;
}

.dashboard-header {
    font-size: 2rem;
    font-weight: 900;
    color: #4f46e5;
    letter-spacing: 2px;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    background: linear-gradient(90deg, #e0e7ff 60%, #f8fafc 100%);
    border-radius: 1rem;
    box-shadow: 0 2px 8px rgba(80, 80, 180, 0.09);
    padding: 1.2rem 2rem 1.2rem 1.5rem;
}

.dashboard-header-left {
    display: flex;
    align-items: center;
    gap: 1.2rem;
}

.dashboard-header-title {
    font-size: 2rem;
    font-weight: 900;
    color: #4f46e5;
    letter-spacing: 2px;
    display: flex;
    align-items: center;
    gap: 0.7rem;
}

.dashboard-header-subtitle {
    font-size: 1.1rem;
    color: #64748b;
    font-weight: 500;
    margin-left: 0.5rem;
}

.dashboard-header-action {
    background: linear-gradient(90deg, #6366f1 60%, #818cf8 100%);
    color: #fff;
    border: none;
    border-radius: 0.7rem;
    font-size: 1rem;
    font-weight: 700;
    padding: 0.6rem 1.4rem;
    box-shadow: 0 2px 8px rgba(80, 80, 180, 0.09);
    transition: background 0.2s, box-shadow 0.2s;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    cursor: pointer;
}

.dashboard-header-action:hover {
    background: linear-gradient(90deg, #818cf8 60%, #6366f1 100%);
    box-shadow: 0 4px 16px rgba(80, 80, 180, 0.13);
}

.dashboard-row-flex {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
    align-items: stretch;
    margin-bottom: 1rem;
}

.dashboard-calendar-flat {
    background: linear-gradient(135deg, #f1f5f9 60%, #e0e7ff 100%);
    border-radius: 1rem;
    box-shadow: 0 2px 8px rgba(80, 80, 180, 0.09);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: flex-start;
    width: auto;
    min-width: unset;
    max-width: unset;
    height: auto;
    padding: 1.2rem 0.7rem 1.2rem 0.7rem;
}

.dashboard-calendar-flat #dashboardCalendar {
    width: 100%;
    min-width: 260px;
    max-width: 340px;
}

.dashboard-calendar-flat .calendar-label {
    font-size: 1rem;
    font-weight: 700;
    color: #6366f1;
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.dashboard-summary-card {
    background: linear-gradient(135deg, #f8fafc 60%, #e0e7ff 100%);
    border-radius: 0.8rem;
    box-shadow: 0 2px 8px rgba(80, 80, 180, 0.07);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 0.7rem 0.7rem 0.6rem 0.7rem;
    flex-direction: column;
    flex: 0 1 130px;
    margin: 0;
    transition: box-shadow 0.2s, transform 0.2s;
}

.dashboard-summary-card:hover {
    box-shadow: 0 4px 16px rgba(80, 80, 180, 0.13);
    transform: translateY(-2px) scale(1.03);
}

.dashboard-summary-icon {
    font-size: 2rem;
    margin-bottom: 0.2rem;
    display: block;
    filter: drop-shadow(0 1px 2px #e0e7ff);
}

.dashboard-summary-label {
    font-size: 0.95rem;
    color: #6366f1;
    font-weight: 700;
    letter-spacing: 1px;
    margin-bottom: 0.1rem;
    text-transform: uppercase;
}

.dashboard-summary-value {
    font-size: 1.2rem;
    font-weight: 700;
}

.dashboard-summary-row {
    display: flex;
    flex-direction: row;
    gap: 0.7rem;
    align-items: stretch;
    justify-content: center;
    width: 100%;
    margin-bottom: 0.7rem;
}

.dashboard-lastmonth-card {
    background: linear-gradient(135deg, #e0e7ff 60%, #f8fafc 100%);
    border-radius: 1rem;
    box-shadow: 0 2px 8px rgba(56, 189, 248, 0.10);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 1.2rem 1rem;
    min-width: 180px;
    min-height: 110px;
    flex: 1 1 0;
    margin: 0;
}

.dashboard-lastmonth-badge {
    font-size: 1rem;
    padding: 0.3rem 1.2rem;
    border-radius: 1rem;
    background: linear-gradient(90deg, #38bdf8 60%, #818cf8 100%);
    color: #fff;
    box-shadow: 0 2px 8px rgba(56, 189, 248, 0.12);
    margin-bottom: 0.5rem;
    display: inline-block;
}

.dashboard-lastmonth-values {
    display: flex;
    gap: 1.2rem;
    justify-content: center;
}

.dashboard-lastmonth-value {
    text-align: center;
    min-width: 60px;
}

.dashboard-lastmonth-label {
    font-size: 0.85rem;
    color: #888;
    margin-bottom: 0.1rem;
}

.dashboard-lastmonth-amount {
    font-size: 1.05rem;
    font-weight: 700;
}

.dashboard-graph-card {
    background: linear-gradient(135deg, #f8fafc 60%, #e0e7ff 100%);
    border-radius: 1rem;
    box-shadow: 0 2px 8px rgba(80, 80, 180, 0.07);
    padding: 1rem 0.5rem 0.5rem 0.5rem;
    min-width: 420px;
    max-width: 100%;
    min-height: 110px;
    flex: 4 1 600px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

.dashboard-section-title {
    font-size: 0.95rem;
    color: #6366f1;
    font-weight: 700;
    letter-spacing: 1px;
    margin-bottom: 0.1rem;
    text-transform: uppercase;
}

@media (max-width: 1400px) {
    .dashboard-row-flex {
        flex-wrap: wrap;
        gap: 0.7rem;
    }
    .dashboard-calendar-flat,
    .dashboard-summary-card,
    .dashboard-lastmonth-card,
    .dashboard-graph-card {
        min-width: 160px;
    }
}

@media (max-width: 991px) {
    .dashboard-row-flex {
        flex-direction: column;
        gap: 0.7rem;
    }
    .dashboard-calendar-flat,
    .dashboard-summary-card,
    .dashboard-lastmonth-card,
    .dashboard-graph-card {
        min-width: 100%;
    }
}
</style>
<style>
.day-details-area-title {
    font-size: 1.3rem;
    font-weight: 700;
    color: #4f46e5;
    letter-spacing: 1px;
    margin-bottom: 1.2rem;
    display: flex;
    align-items: center;
    gap: 0.7rem;
    background: linear-gradient(90deg, #e0e7ff 60%, #f8fafc 100%);
    border-radius: 0.7rem;
    box-shadow: 0 2px 8px rgba(80, 80, 180, 0.09);
    padding: 0.7rem 1.2rem;
}
.day-details-card {
    background: linear-gradient(135deg, #f8fafc 60%, #e0e7ff 100%);
    border-radius: 1rem;
    box-shadow: 0 4px 24px rgba(80,80,180,0.13), 0 1.5px 4px rgba(80,80,180,0.07);
    padding: 1.2rem 1.3rem 1.2rem 1.3rem;
    margin-bottom: 1.2rem;
    transition: box-shadow 0.25s, transform 0.22s, background 0.25s;
    border-left: 6px solid #e0e7ff;
    position: relative;
    overflow: visible;
}
.day-details-card:hover {
    box-shadow: 0 8px 32px rgba(80,80,180,0.18), 0 2px 8px rgba(80,80,180,0.13);
    transform: translateY(-4px) scale(1.035);
    background: linear-gradient(135deg, #e0e7ff 60%, #f8fafc 100%);
}
.day-details-card .card-action-icon {
    position: absolute;
    top: 1.1rem;
    right: 1.1rem;
    font-size: 1.1rem;
    color: #bdbdbd;
    opacity: 0.7;
    transition: color 0.2s, opacity 0.2s;
    cursor: pointer;
}
.day-details-card:hover .card-action-icon {
    color: #6366f1;
    opacity: 1;
}
.day-details-card .category-title {
    font-size: 1.1rem;
    font-weight: 700;
    color: inherit;
    letter-spacing: 0.5px;
    margin-bottom: 0.1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}
.day-details-card .category-icon {
    font-size: 2.1em;
    margin-right: 0.5rem;
    filter: drop-shadow(0 1px 2px #e0e7ff);
}
.day-details-card .fs-5 {
    font-size: 1.18rem !important;
}
.day-details-card .text-muted {
    margin-top: 0.2rem;
}
</style>

<style>
.calendar-dot {
    display: inline-block;
    width: 8px;
    height: 8px;
    border-radius: 50%;
    margin: 0 1px;
    vertical-align: middle;
}
.calendar-dot-receita { background: #22c55e; border: 1px solid #16a34a; }
.calendar-dot-despesa { background: #ef4444; border: 1px solid #b91c1c; }
.calendar-dot-invoice { background: #2563eb; border: 1px solid #1e40af; }
</style>

<div class="container-fluid px-2">
    <div class="dashboard-header mb-2">
        <div class="dashboard-header-left">
            <span class="dashboard-header-title">
                <i class="fa-solid fa-gauge-high"></i> Dashboard Financeiro
            </span>
            <span class="dashboard-header-subtitle">
                <i class="fa-regular fa-calendar-days"></i> Visão geral do caixa
            </span>
        </div>
        <button class="dashboard-header-action" onclick="window.location.href='{{ route('cashbook.create') }}'">
            <i class="fa-solid fa-plus"></i> Nova Transação
        </button>
    </div>

    <div class="dashboard-row-flex flex-wrap flex-md-nowrap">
        <div class="dashboard-calendar-flat shadow-lg border-0 mb-2 me-md-3 p-2"
            style="transition: box-shadow 0.2s; width: auto; min-width: unset; max-width: unset;">
            <div class="calendar-label mb-1">
                <i class="fa-regular fa-calendar-days"></i> Calendário
            </div>
            <div id="dashboardCalendar"></div>
        </div>

        <div class="flex-grow-1 ">
            <div class="dashboard-summary-row">
                <div class="dashboard-summary-card shadow-sm border-0 position-relative overflow-hidden"
                    style="cursor:pointer;">
                    <span class="dashboard-summary-icon text-success"><i class="bi bi-arrow-up"></i></span>
                    <div class="dashboard-summary-label">Receitas</div>
                    <div class="dashboard-summary-value text-success">
                        R$ {{ number_format($totalReceitas ?? 0, 2, ',', '.') }}
                    </div>
                </div>
                <div class="dashboard-summary-card shadow-sm border-0 position-relative overflow-hidden"
                    style="cursor:pointer;">
                    <span class="dashboard-summary-icon text-danger"><i class="bi bi-arrow-down"></i></span>
                    <div class="dashboard-summary-label">Despesas</div>
                    <div class="dashboard-summary-value text-danger">
                        R$ {{ number_format($totalDespesas ?? 0, 2, ',', '.') }}
                    </div>
                </div>
                <div class="dashboard-summary-card shadow-sm border-0 position-relative overflow-hidden"
                    style="cursor:pointer;">
                    <span class="dashboard-summary-icon text-secondary"><i class="bi bi-bar-chart-line"></i></span>
                    <div class="dashboard-summary-label">Saldo Geral</div>
                    <div class="dashboard-summary-value {{ ($saldoTotal ?? 0) >= 0 ? 'text-success' : 'text-danger' }}">
                        R$ {{ number_format($saldoTotal ?? 0, 2, ',', '.') }}
                    </div>
                </div>
            </div>
            <div class="dashboard-lastmonth-card shadow-lg border-0 "
                style=" padding: 0.7rem 0.7rem 0.7rem 0.7rem; transition: box-shadow 0.2s;">
                <span class="dashboard-lastmonth-badge mb-2" style="font-size:0.95rem; padding:0.2rem 1rem;">
                    <i class="bi bi-calendar2-week me-1"></i>
                    <span id="nomeUltimoMes">{{ $nomeUltimoMes }}</span>
                </span>
                <div
                    class="dashboard-lastmonth-values mt-1 d-flex flex-column flex-sm-row gap-2 justify-content-center">
                    <div class="dashboard-lastmonth-value">
                        <div class="dashboard-lastmonth-label">Receitas</div>
                        <div id="receitaUltimoMes" class="dashboard-lastmonth-amount text-success"
                            style="font-size:0.98rem;">
                            R$ {{ number_format($receitaUltimoMes ?? 0, 2, ',', '.') }}
                        </div>
                    </div>
                    <div class="dashboard-lastmonth-value">
                        <div class="dashboard-lastmonth-label">Despesas</div>
                        <div id="despesaUltimoMes" class="dashboard-lastmonth-amount text-danger"
                            style="font-size:0.98rem;">
                            R$ {{ number_format($despesaUltimoMes ?? 0, 2, ',', '.') }}
                        </div>
                    </div>
                    <div class="dashboard-lastmonth-value">
                        <div class="dashboard-lastmonth-label">Saldo</div>
                        <div id="saldoUltimoMes"
                            class="dashboard-lastmonth-amount {{ ($saldoUltimoMes ?? 0) >= 0 ? 'text-success' : 'text-danger' }}"
                            style="font-size:0.98rem;">
                            R$ {{ number_format($saldoUltimoMes ?? 0, 2, ',', '.') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="dashboard-graph-card">
            <div class="dashboard-section-title mb-1">
                <i class="bi bi-bar-chart-fill me-2"></i>Receitas x Despesas
                <form method="GET" class="d-inline-block float-end" id="formAno" onsubmit="return false;"
                    style="float:right;">
                    <select name="ano" id="anoSelect" class="form-select form-select-sm"
                        style="width:auto;display:inline-block;">
                        @for($y = date('Y'); $y >= (date('Y')-5); $y--)
                        <option value="{{ $y }}" @if($ano==$y) selected @endif>{{ $y }}</option>
                        @endfor
                    </select>
                </form>
            </div>
            <div style="width:100%;">
                <canvas id="cashbookChart" height="70"></canvas>
            </div>
            <div class="card dashboard-card h-100 mt-2">
                <div class="card-header pb-0 pt-1 d-flex justify-content-between align-items-center">
                    <span class="dashboard-section-title"><i class="bi bi-calendar3-week me-2"></i>Diário
                        Invoices</span>
                    <div>
                        <select id="mesInvoicesSelect" class="form-select form-select-sm d-inline-block"
                            style="width:auto;">
                            @for($m = 1; $m <= 12; $m++)
                                <option value="{{ $m }}" @if($mesInvoices==$m) selected @endif>
                                    {{ \Carbon\Carbon::create(null, $m, 1)->translatedFormat('F') }}
                                </option>
                            @endfor
                        </select>
                        <select id="anoInvoicesSelect" class="form-select form-select-sm d-inline-block"
                            style="width:auto;">
                            @for($y = date('Y'); $y >= (date('Y')-5); $y--)
                                <option value="{{ $y }}" @if($anoInvoices==$y) selected @endif>{{ $y }}</option>
                            @endfor
                        </select>
                    </div>
                </div>
                <div class="card-body p-1">
                    <canvas id="invoicesDiarioChart" height="60"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="row ">
      <div class="col-md-6">
        <div id="dayDetailsContainer" class="mt-3"></div>
      </div>
    </div>

</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/pt.js"></script>
<script>
// Inicializa variáveis globais para os marcadores do calendário
let cashbookDays = {!! json_encode($cashbookDays) !!};
let invoiceDays = {!! json_encode($invoiceDays) !!};

// Função para atualizar os marcadores do calendário
function updateCalendarMarkers(fp) {
    const currentMonth = fp.currentMonth + 1; // Flatpickr: 0 = Jan
    const currentYear = fp.currentYear;
    fetch(`{{ route('dashboard.cashbook.calendarMarkers') }}?mes=${currentMonth}&ano=${currentYear}`)
        .then(res => res.json())
        .then(data => {
            cashbookDays = data.cashbookDays;
            invoiceDays = data.invoiceDays;
            fp.redraw();
        });
}

var calendar = flatpickr("#dashboardCalendar", {
    inline: true,
    locale: "pt",
    defaultDate: "today",
    disableMobile: true,
    onDayCreate: function(dObj, dStr, fp, dayElem) {
        const date = dayElem.dateObj;
        if (!date) return;
        const yyyy = date.getFullYear();
        const mm = (date.getMonth() + 1).toString().padStart(2, '0');
        const dd = date.getDate().toString().padStart(2, '0');
        const dateStr = `${yyyy}-${mm}-${dd}`;
        let dots = '';
        if (cashbookDays[dateStr]) {
            if (cashbookDays[dateStr].receita) {
                dots += '<span class="calendar-dot calendar-dot-receita" title="Receita"></span>';
            }
            if (cashbookDays[dateStr].despesa) {
                dots += '<span class="calendar-dot calendar-dot-despesa" title="Despesa"></span>';
            }
        }
        if (invoiceDays.includes(dateStr)) {
            dots += '<span class="calendar-dot calendar-dot-invoice" title="Invoice"></span>';
        }
        if (dots) {
            const dotWrapper = document.createElement('div');
            dotWrapper.innerHTML = dots;
            dotWrapper.style.marginTop = '2px';
            dotWrapper.style.textAlign = 'center';
            dayElem.appendChild(dotWrapper);
        }
    },
    onMonthChange: function(selectedDates, dateStr, fp) {
        updateCalendarMarkers(fp);
    },
    onYearChange: function(selectedDates, dateStr, fp) {
        updateCalendarMarkers(fp);
    },
    onChange: function(selectedDates, dateStr, fp) {
        if (selectedDates.length > 0) {
            const date = selectedDates[0];
            const yyyy = date.getFullYear();
            const mm = (date.getMonth() + 1).toString().padStart(2, '0');
            const dd = date.getDate().toString().padStart(2, '0');
            const dateStrApi = `${yyyy}-${mm}-${dd}`;
            fetch(`{{ route('dashboard.cashbook.dayDetails') }}?date=${dateStrApi}`)
                .then(res => res.json())
                .then(data => {
                    let html = '';
                    html += `<div class='mb-3 day-details-area-title'><i class='bi bi-calendar-event-fill'></i> <span>Detalhes do dia</span> <span class='badge bg-primary ms-2'>${dd}/${mm}/${yyyy}</span></div>`;
                    // Receitas
                    html += `<div class="day-details-group day-details-group-receita mb-4">
                        <div class="fw-bold mb-2 text-success"><i class="bi bi-arrow-up-circle"></i> Receitas</div>`;
                    if (data.receitas && data.receitas.length > 0) {
                        html += `<div class="row g-3">`;
                        data.receitas.forEach(function(r) {
                            html += `<div class="col-md-6">
                                <div class="day-details-card" style="border-left: 6px solid ${r.category_color || '#22c55e'};">
                                    <span class="card-action-icon"><i class="fa fa-pen"></i></span>
                                    <div class="d-flex align-items-center mb-2 category-title">`;
                            if (r.category_icon) html += `<span class="category-icon" style="color:${r.category_color || '#22c55e'}"><i class="${r.category_icon}"></i></span>`;
                            html += `<span class="fw-bold" style="color:${r.category_color || '#22c55e'}">${r.category || 'Sem categoria'}</span>`;
                            html += `</div>
                                    <div class="fs-5 fw-bold text-success">R$ ${parseFloat(r.value).toLocaleString('pt-BR', {minimumFractionDigits: 2})}</div>
                                    <div class="text-muted small">${r.description || ''}</div>
                                </div>
                            </div>`;
                        });
                        html += `</div>`;
                    } else {
                        html += '<div class="text-muted small">Nenhuma receita</div>';
                    }
                    html += `</div>`;
                    // Despesas
                    html += `<div class="day-details-group day-details-group-despesa mb-4">
                        <div class="fw-bold mb-2 text-danger"><i class="bi bi-arrow-down-circle"></i> Despesas</div>`;
                    if (data.despesas && data.despesas.length > 0) {
                        html += `<div class="row g-3">`;
                        data.despesas.forEach(function(d) {
                            html += `<div class="col-md-6">
                                <div class="day-details-card" style="border-left: 6px solid ${d.category_color || '#ef4444'};">
                                    <span class="card-action-icon"><i class="fa fa-pen"></i></span>
                                    <div class="d-flex align-items-center mb-2 category-title">`;
                            if (d.category_icon) html += `<span class="category-icon" style="color:${d.category_color || '#ef4444'}"><i class="${d.category_icon}"></i></span>`;
                            html += `<span class="fw-bold" style="color:${d.category_color || '#ef4444'}">${d.category || 'Sem categoria'}</span>`;
                            html += `</div>
                                    <div class="fs-5 fw-bold text-danger">R$ ${parseFloat(d.value).toLocaleString('pt-BR', {minimumFractionDigits: 2})}</div>
                                    <div class="text-muted small">${d.description || ''}</div>
                                </div>
                            </div>`;
                        });
                        html += `</div>`;
                    } else {
                        html += '<div class="text-muted small">Nenhuma despesa</div>';
                    }
                    html += `</div>`;
                    // Invoices
                    html += `<div class="day-details-group day-details-group-invoice mb-4">
                        <div class="fw-bold mb-2 text-primary"><i class="bi bi-credit-card"></i> Invoices</div>`;
                    if (data.invoices && data.invoices.length > 0) {
                        html += `<div class="row g-3">`;
                        data.invoices.forEach(function(i) {
                            html += `<div class="col-md-6">
                                <div class="day-details-card" style="border-left: 6px solid ${i.category_color || '#2563eb'};">
                                    <span class="card-action-icon"><i class="fa fa-pen"></i></span>
                                    <div class="d-flex align-items-center mb-2 category-title">`;
                            if (i.category_icon) html += `<span class="category-icon" style="color:${i.category_color || '#2563eb'}"><i class="${i.category_icon}"></i></span>`;
                            html += `<span class="fw-bold" style="color:${i.category_color || '#2563eb'}">${i.category || 'Sem categoria'}</span>`;
                            html += `</div>
                                    <div class="fs-5 fw-bold text-primary">R$ ${parseFloat(i.value).toLocaleString('pt-BR', {minimumFractionDigits: 2})}</div>
                                    <div class="text-muted small">${i.description || ''}</div>
                                </div>
                            </div>`;
                        });
                        html += `</div>`;
                    } else {
                        html += '<div class="text-muted small">Nenhum invoice</div>';
                    }
                    html += `</div>`;
                    document.getElementById('dayDetailsContainer').innerHTML = html;
                })
                .catch(() => {
                    document.getElementById('dayDetailsContainer').innerHTML = '<div class="alert alert-danger">Erro ao buscar detalhes do dia.</div>';
                });
        }
    }
});
// Atualiza marcadores ao carregar a página
updateCalendarMarkers(calendar);

// Ao carregar a página, já mostra os detalhes do dia atual
window.addEventListener('DOMContentLoaded', function() {
    const today = new Date();
    const yyyy = today.getFullYear();
    const mm = (today.getMonth() + 1).toString().padStart(2, '0');
    const dd = today.getDate().toString().padStart(2, '0');
    const dateStrApi = `${yyyy}-${mm}-${dd}`;
    // Simula seleção do dia atual
    fetch(`{{ route('dashboard.cashbook.dayDetails') }}?date=${dateStrApi}`)
        .then(res => res.json())
        .then(data => {
            let html = '';
            html += `<div class='mb-3 day-details-area-title'><i class='bi bi-calendar-event-fill'></i> <span>Detalhes do dia</span> <span class='badge bg-primary ms-2'>${dd}/${mm}/${yyyy}</span></div>`;
            // Receitas
            html += `<div class="day-details-group day-details-group-receita mb-4">
                <div class="fw-bold mb-2 text-success"><i class="bi bi-arrow-up-circle"></i> Receitas</div>`;
            if (data.receitas && data.receitas.length > 0) {
                html += `<div class="row g-3">`;
                data.receitas.forEach(function(r) {
                    html += `<div class="col-md-6">
                        <div class="day-details-card" style="border-left: 6px solid ${r.category_color || '#22c55e'};">
                            <span class="card-action-icon"><i class="fa fa-pen"></i></span>
                            <div class="d-flex align-items-center mb-2 category-title">`;
                    if (r.category_icon) html += `<span class="category-icon" style="color:${r.category_color || '#22c55e'}"><i class="${r.category_icon}"></i></span>`;
                    html += `<span class="fw-bold" style="color:${r.category_color || '#22c55e'}">${r.category || 'Sem categoria'}</span>`;
                    html += `</div>
                            <div class="fs-5 fw-bold text-success">R$ ${parseFloat(r.value).toLocaleString('pt-BR', {minimumFractionDigits: 2})}</div>
                            <div class="text-muted small">${r.description || ''}</div>
                        </div>
                    </div>`;
                });
                html += `</div>`;
            } else {
                html += '<div class="text-muted small">Nenhuma receita</div>';
            }
            html += `</div>`;
            // Despesas
            html += `<div class="day-details-group day-details-group-despesa mb-4">
                <div class="fw-bold mb-2 text-danger"><i class="bi bi-arrow-down-circle"></i> Despesas</div>`;
            if (data.despesas && data.despesas.length > 0) {
                html += `<div class="row g-3">`;
                data.despesas.forEach(function(d) {
                    html += `<div class="col-md-6">
                        <div class="day-details-card" style="border-left: 6px solid ${d.category_color || '#ef4444'};">
                            <span class="card-action-icon"><i class="fa fa-pen"></i></span>
                            <div class="d-flex align-items-center mb-2 category-title">`;
                    if (d.category_icon) html += `<span class="category-icon" style="color:${d.category_color || '#ef4444'}"><i class="${d.category_icon}"></i></span>`;
                    html += `<span class="fw-bold" style="color:${d.category_color || '#ef4444'}">${d.category || 'Sem categoria'}</span>`;
                    html += `</div>
                            <div class="fs-5 fw-bold text-danger">R$ ${parseFloat(d.value).toLocaleString('pt-BR', {minimumFractionDigits: 2})}</div>
                            <div class="text-muted small">${d.description || ''}</div>
                        </div>
                    </div>`;
                });
                html += `</div>`;
            } else {
                html += '<div class="text-muted small">Nenhuma despesa</div>';
            }
            html += `</div>`;
            // Invoices
            html += `<div class="day-details-group day-details-group-invoice mb-4">
                <div class="fw-bold mb-2 text-primary"><i class="bi bi-credit-card"></i> Invoices</div>`;
            if (data.invoices && data.invoices.length > 0) {
                html += `<div class="row g-3">`;
                data.invoices.forEach(function(i) {
                    html += `<div class="col-md-6">
                        <div class="day-details-card" style="border-left: 6px solid ${i.category_color || '#2563eb'};">
                            <span class="card-action-icon"><i class="fa fa-pen"></i></span>
                            <div class="d-flex align-items-center mb-2 category-title">`;
                    if (i.category_icon) html += `<span class="category-icon" style="color:${i.category_color || '#2563eb'}"><i class="${i.category_icon}"></i></span>`;
                    html += `<span class="fw-bold" style="color:${i.category_color || '#2563eb'}">${i.category || 'Sem categoria'}</span>`;
                    html += `</div>
                            <div class="fs-5 fw-bold text-primary">R$ ${parseFloat(i.value).toLocaleString('pt-BR', {minimumFractionDigits: 2})}</div>
                            <div class="text-muted small">${i.description || ''}</div>
                        </div>
                    </div>`;
                });
                html += `</div>`;
            } else {
                html += '<div class="text-muted small">Nenhum invoice</div>';
            }
            html += `</div>`;
            document.getElementById('dayDetailsContainer').innerHTML = html;
        })
        .catch(() => {
            document.getElementById('dayDetailsContainer').innerHTML = '<div class="alert alert-danger">Erro ao buscar detalhes do dia.</div>';
        });
});
</script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const meses = {!! json_encode(array_values($meses)) !!};
let cashbookChart;

function renderChart(receitas, despesas) {
    const ctx = document.getElementById('cashbookChart').getContext('2d');
    if (cashbookChart) cashbookChart.destroy();
    cashbookChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: meses,
            datasets: [
                {
                    label: 'Receitas',
                    backgroundColor: 'rgba(40, 167, 69, 0.7)',
                    borderColor: 'rgba(40, 167, 69, 1)',
                    borderWidth: 1,
                    data: receitas
                },
                {
                    label: 'Despesas',
                    backgroundColor: 'rgba(220, 53, 69, 0.7)',
                    borderColor: 'rgba(220, 53, 69, 1)',
                    borderWidth: 1,
                    data: despesas
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'top' }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'R$ ' + value.toLocaleString('pt-BR', {minimumFractionDigits: 2});
                        }
                    }
                }
            }
        }
    });
}

renderChart({!! json_encode($dadosReceita) !!}, {!! json_encode($dadosDespesa) !!});

document.getElementById('anoSelect').addEventListener('change', function() {
    const ano = this.value;
    fetch("{{ route('dashboard.cashbookChartData') }}?ano=" + ano)
        .then(res => res.json())
        .then(function(data) {
            renderChart(data.dadosReceita, data.dadosDespesa);
            document.getElementById('receitaUltimoMes').innerHTML =
                'R$ ' + (data.receitaUltimoMes).toLocaleString('pt-BR', {minimumFractionDigits: 2});
            document.getElementById('despesaUltimoMes').innerHTML =
                'R$ ' + (data.despesaUltimoMes).toLocaleString('pt-BR', {minimumFractionDigits: 2});
            document.getElementById('saldoUltimoMes').innerHTML =
                'R$ ' + (data.saldoUltimoMes).toLocaleString('pt-BR', {minimumFractionDigits: 2});
            document.getElementById('saldoUltimoMes').className =
                'dashboard-lastmonth-amount ' + (data.saldoUltimoMes >= 0 ? 'text-success' : 'text-danger');
            document.getElementById('nomeUltimoMes').innerText = data.nomeUltimoMes;
        });
});

let invoicesDiarioChart;
function renderInvoicesDiarioChart(labels, values) {
    const ctx = document.getElementById('invoicesDiarioChart').getContext('2d');
    if (invoicesDiarioChart) invoicesDiarioChart.destroy();
    invoicesDiarioChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Saídas (Invoices)',
                data: values,
                backgroundColor: 'rgba(220, 53, 69, 0.7)'
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'R$ ' + value.toLocaleString('pt-BR', {minimumFractionDigits: 2});
                        }
                    }
                }
            }
        }
    });
}

renderInvoicesDiarioChart({!! json_encode($diasInvoices) !!}, {!! json_encode($valoresInvoices) !!});

document.getElementById('mesInvoicesSelect').addEventListener('change', updateInvoicesDiarioChart);
document.getElementById('anoInvoicesSelect').addEventListener('change', updateInvoicesDiarioChart);

function updateInvoicesDiarioChart() {
    const mes = document.getElementById('mesInvoicesSelect').value;
    const ano = document.getElementById('anoInvoicesSelect').value;
    fetch("{{ route('dashboard.invoicesDailyChartData') }}?mes=" + mes + "&ano=" + ano)
        .then(res => {
            if (!res.ok) throw new Error('Erro ao buscar dados');
            return res.json();
        })
        .then(function(data) {
            renderInvoicesDiarioChart(data.diasInvoices, data.valoresInvoices);
        })
        .catch(function(err) {
            alert('Erro ao atualizar gráfico Diário Invoices');
        });
}
</script>
@endpush
@endsection