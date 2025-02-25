@extends('layouts.app')

@section('content')
    <h1>Lista de Bancos</h1>
    <a href="{{ route('banks.create') }}" class="btn btn-primary">Adicionar Banco</a>

    <table class="table mt-3">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Descrição</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach($banks as $bank)
                <tr>
                    <td>{{ $bank->id_bank }}</td>
                    <td>{{ $bank->name }}</td>
                    <td>{{ $bank->description }}</td>
                    <td>
                        <a href="{{ route('banks.edit', $bank->id_bank) }}" class="btn btn-warning">Editar</a>
                        <form action="{{ route('banks.destroy', $bank->id_bank) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Deletar</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
