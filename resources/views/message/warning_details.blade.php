<style>
    #warningDetailsModal .modal-header {
        background: linear-gradient(90deg, #fffbe6 80%, #fef9c3 100%);
        border-bottom: 1px solid #ffe58f;
    }
    #warningDetailsModal .modal-title {
        color: #b68400;
        font-weight: bold;
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 1.25em;
    }
    #warningDetailsModal .modal-title .icon-warning {
        color: #f59e42;
        font-size: 2em;
        background: #fff7ed;
        border-radius: 50%;
        padding: 6px 10px 6px 8px;
        box-shadow: 0 2px 8px #f59e4222;
    }
    #warningDetailsModal .modal-body {
        max-height: 350px;
        overflow-y: auto;
        background: #fffdfa;
        padding-bottom: 0.5rem;
    }
    #warningDetailsModal ul {
        padding-left: 1.2em;
    }
    #warningDetailsModal li {
        margin-bottom: 0.4em;
        font-size: 1.07em;
        display: flex;
        align-items: center;
        gap: 7px;
    }
    #warningDetailsModal .text-success {
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 6px;
        color: #22c55e;
    }
    #warningDetailsModal .text-warning {
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 6px;
        color: #f59e42;
    }
    #warningDetailsModal .icon-success {
        color: #22c55e;
        font-size: 1.2em;
        background: #e7fbe9;
        border-radius: 50%;
        padding: 2px 6px;
    }
    #warningDetailsModal .icon-duplicate {
        color: #f59e42;
        font-size: 1.2em;
        background: #fff7ed;
        border-radius: 50%;
        padding: 2px 6px;
    }
    #warningDetailsModal .modal-footer {
        background: #fffbe6;
        border-top: 1px solid #ffe58f;
    }
    #warningDetailsModal .btn-warning {
        background: #fef08a;
        color: #b68400;
        border: none;
        font-weight: 600;
        border-radius: 20px;
        padding: 6px 24px;
        transition: background 0.2s;
    }
    #warningDetailsModal .btn-warning:hover {
        background: #fde047;
        color: #a16207;
    }
    /* Barra de rolagem customizada */
    #warningDetailsModal .modal-body::-webkit-scrollbar {
        width: 8px;
        background: #fffbe6;
    }
    #warningDetailsModal .modal-body::-webkit-scrollbar-thumb {
        background: #ffe58f;
        border-radius: 6px;
    }
</style>

<div class="modal fade" id="warningDetailsModal" tabindex="-1" aria-labelledby="warningDetailsModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="warningDetailsModalLabel">
            <span class="icon-warning"><i class="fas fa-exclamation-triangle"></i></span>
            Detalhes das Transações Importadas
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
      </div>
      <div class="modal-body">
        <h6 class="mb-2 text-success"><span class="icon-success"><i class="fas fa-check-circle"></i></span>Transações inseridas:</h6>
        @if(!empty($inserted))
            <ul>
                @foreach($inserted as $item)
                    <li>
                        <span class="icon-success"><i class="fas fa-check"></i></span>
                        <strong>{{ $item['date'] }}</strong> | R$ {{ number_format($item['value'], 2, ',', '.') }} | {{ $item['description'] }}
                    </li>
                @endforeach
            </ul>
        @else
            <p class="text-muted">Nenhuma transação foi inserida.</p>
        @endif

        <hr>
        <h6 class="mb-2 text-warning"><span class="icon-duplicate"><i class="fas fa-clone"></i></span>Transações não inseridas (já existiam):</h6>
        @if(!empty($duplicated))
            <ul>
                @foreach($duplicated as $item)
                    <li>
                        <span class="icon-duplicate"><i class="fas fa-exclamation"></i></span>
                        <strong>{{ $item['date'] }}</strong> | R$ {{ number_format($item['value'], 2, ',', '.') }} | {{ $item['description'] }}
                    </li>
                @endforeach
            </ul>
        @else
            <p class="text-muted">Nenhuma transação duplicada encontrada.</p>
        @endif
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-warning" data-bs-dismiss="modal">Fechar</button>
      </div>
    </div>
  </div>
</div>

<script>
    // Remove o alerta de aviso ao clicar em "Fechar"
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.alert-warning .btn-close-warning').forEach(function(btn) {
            btn.addEventListener('click', function() {
                this.closest('.alert-warning').remove();
            });
        });
    });
</script>
