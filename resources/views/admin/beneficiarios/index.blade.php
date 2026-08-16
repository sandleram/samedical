@extends('layouts.admin')

@section('title', 'Beneficiários')

@section('content')
<div class="row">
    <div class="col-xs-12">
        <h1 class="page-title txt-color-blueDark">
            <i class="fa fa-users fa-fw"></i> Beneficiários
        </h1>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <div class="well">
            <form method="GET" action="{{ route('admin.beneficiarios.index') }}" class="form-inline" style="margin-bottom:15px;">
                <div class="form-group">
                    <input type="text" name="q" value="{{ $search }}" class="form-control" placeholder="Nome, CPF ou matrícula">
                </div>
                <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
            </form>

            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>CPF</th>
                        <th>Matrícula</th>
                        <th>Cliente</th>
                        <th>Empresa</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse ($beneficiarios as $row)
                        <tr>
                            <td>{{ $row->id }}</td>
                            <td>{{ $row->nome }}</td>
                            <td>{{ $row->cpf }}</td>
                            <td>{{ $row->cod_matricula }}</td>
                            <td>{{ $row->cliente->nome ?? '-' }}</td>
                            <td>{{ $row->empresa->nome ?? '-' }}</td>
                            <td>
                                <a href="{{ route('admin.beneficiarios.show', $row->id) }}" class="btn btn-xs btn-default">
                                    <i class="fa fa-eye"></i> Ver
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">Nenhum beneficiário encontrado.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            {{ $beneficiarios->links() }}
        </div>
    </div>
</div>
@endsection
