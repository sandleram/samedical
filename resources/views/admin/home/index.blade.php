@extends('layouts.admin')

@section('title', 'Home')

@section('content')
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <h1 class="page-title txt-color-blueDark">
            <i class="fa fa-home fa-fw"></i> Home
        </h1>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <div class="well">
            <h3>Bem-vindo, {{ auth()->user()->nome }}</h3>
            <p>SAMED V2 — fundação Laravel. Use o menu para acessar o piloto de Beneficiários.</p>
            <p>
                <a class="btn btn-primary" href="{{ route('admin.beneficiarios.index') }}">
                    <i class="fa fa-users"></i> Beneficiários
                </a>
            </p>
        </div>
    </div>
</div>
@endsection
