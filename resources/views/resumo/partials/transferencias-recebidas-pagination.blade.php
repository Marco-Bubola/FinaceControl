@if ($recebidas->hasPages())
    @php
        $current = $recebidas->currentPage();
        $last = $recebidas->lastPage();
        $start = max(1, $current - 2);
        $end = min($last, $current + 2);
    @endphp
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-2">
        <small class="text-muted mb-2 mb-md-0">
            Exibindo {{ $recebidas->firstItem() }} a {{ $recebidas->lastItem() }} de {{ $recebidas->total() }} transferências recebidas
        </small>
        <nav>
            <ul class="pagination pagination-sm mb-0">
                {{-- First Page --}}
                <li class="page-item {{ $current == 1 ? 'disabled' : '' }}">
                    <a class="page-link"
                       href="{{ $current == 1 ? '#' : route('clientes.transferencias.recebidas.ajax', ['cliente' => $clienteId, 'page' => 1]) }}"
                       tabindex="-1"
                       aria-label="Primeira">
                        &laquo;
                    </a>
                </li>
                {{-- Dots before --}}
                @if ($start > 1)
                    <li class="page-item disabled"><span class="page-link">...</span></li>
                @endif
                {{-- Page Numbers --}}
                @for ($page = $start; $page <= $end; $page++)
                    @if ($page == $current)
                        <li class="page-item active"><span class="page-link">{{ $page }}</span></li>
                    @else
                        <li class="page-item">
                            <a class="page-link"
                               href="{{ route('clientes.transferencias.recebidas.ajax', ['cliente' => $clienteId, 'page' => $page]) }}">{{ $page }}</a>
                        </li>
                    @endif
                @endfor
                {{-- Dots after --}}
                @if ($end < $last)
                    <li class="page-item disabled"><span class="page-link">...</span></li>
                @endif
                {{-- Last Page --}}
                <li class="page-item {{ $current == $last ? 'disabled' : '' }}">
                    <a class="page-link"
                       href="{{ $current == $last ? '#' : route('clientes.transferencias.recebidas.ajax', ['cliente' => $clienteId, 'page' => $last]) }}"
                       aria-label="Última">
                        &raquo;
                    </a>
                </li>
            </ul>
        </nav>
    </div>
@endif
