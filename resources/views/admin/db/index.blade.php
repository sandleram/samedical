@extends('layouts.admin')

@section('title', $title ?? 'DB')

@section('breadcrumb')
    <li>DB</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <h1 class="page-title txt-color-blueDark">
                <i class="fa fa-database fa-fw"></i> Utilitário DB
            </h1>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12">
            <div class="well">
                <p>
                    Acesso administrativo ao banco via ferramenta externa (phpMyAdmin / cliente SQL).
                    Credenciais <strong>não</strong> são exibidas nesta tela (diferente do legado).
                </p>

                @if ($phpmyadminUrl !== '')
                    <p>
                        Link:
                        <a href="{{ $phpmyadminUrl }}" target="_blank" rel="noopener noreferrer">
                            {{ $phpmyadminUrl }}
                        </a>
                    </p>
                @else
                    <p class="text-muted">
                        Configure <code>SAMED_PHPMYADMIN_URL</code> no <code>.env</code> para exibir o link.
                    </p>
                @endif

                <p class="text-muted" style="margin-top: 12px;">
                    CRUD / lista BI espelhada do legado <code>DbController</code> (cópia incompleta de BI)
                    ficou deferred — use o módulo BI quando portado.
                </p>
            </div>
        </div>
    </div>
@endsection
