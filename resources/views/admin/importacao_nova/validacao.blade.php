@extends('layouts.admin')

@section('title', 'Validação de Importação Nova')

@section('content')
<h4 style="border-bottom: 1px dotted #d3d3d3; padding-bottom: 5px;">Validação de Importação Nova</h4>

@if (count($rows) > 0)
    <table class="table table-hover">
        <thead>
        <tr><th>QTD</th><th>Linha</th><th>Descrição</th></tr>
        </thead>
        <tbody>
        @foreach ($rows as $k => $v)
            <tr>
                <td>#{{ $k + 1 }}</td>
                <td>{{ ($v['linha'] ?? '') !== '' ? (($v['linha'] ?? 0) + 1) : 'Validação Geral' }}</td>
                <td>{{ is_array($v['descricao'] ?? null) ? implode(' ', $v['descricao']) : ($v['descricao'] ?? '') }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@else
    <p class="text-muted">Nenhuma validação na sessão.</p>
@endif

<p><a href="{{ route('admin.importacao_nova.add') }}" class="btn btn-success">Voltar para Re-importar</a></p>
@endsection
