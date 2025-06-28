@extends('layouts.user_type.auth')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-6">
            <div class="card shadow-lg border-0" style="border-radius: 18px;">
                <div class="card-body p-5">
                    <h2 class="fw-bold mb-4 text-warning text-center"><i class="fas fa-piggy-bank me-2"></i>Novo Cofrinho</h2>
                    <div class="modal fade" id="createCofrinhoModal" tabindex="-1" aria-labelledby="createCofrinhoModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <form id="createCofrinhoForm" method="POST" action="{{ route('cofrinho.store') }}">
                                @csrf
                                <div class="modal-content">
                                    <div class="modal-header bg-warning bg-gradient">
                                        <h5 class="modal-title fw-bold" id="createCofrinhoModalLabel"><i class="fas fa-piggy-bank me-2"></i>Novo Cofrinho</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label for="nome" class="form-label fw-semibold">Nome do Cofrinho</label>
                                            <input type="text" class="form-control form-control-lg rounded-pill" id="nome" name="nome" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="meta_valor" class="form-label fw-semibold">Meta (R$)</label>
                                            <input type="number" class="form-control form-control-lg rounded-pill" id="meta_valor" name="meta_valor" min="0" step="0.01" required>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                        <button type="submit" class="btn btn-warning fw-bold">Salvar</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 