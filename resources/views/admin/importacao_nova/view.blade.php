@extends('layouts.admin')

@section('title', 'Importação Nova')

@section('breadcrumb')
    <li><a href="{{ route('admin.importacao_nova.index') }}">Importação Nova</a></li>
    <li>#{{ $row->id }}</li>
@endsection

@section('content')
<div class="row">
    <div class="col-xs-12">
        <div class="well no-padding smart-form client-form">
            <header>Visualização de Importação Nova</header>
            <fieldset>
                <div class="row">
                    <section class="col col-12">
                        <label class="Bold"><strong>ID: </strong></label>
                        <label>{{ $row->id }}</label>
                    </section>
                </div>
                <div class="row">
                    <section class="col col-12">
                        <label class="Bold"><strong>Arquivo: </strong></label>
                        <label>{{ $row->nome_arquivo }} ({{ $row->arquivo_importado }})</label>
                    </section>
                </div>
                <div class="row">
                    <section class="col col-12">
                        <label class="Bold"><strong>Status: </strong></label>
                        <label class="progresso_status">{{ $statusProcessoArr[$row->status_processo] ?? $row->status_processo }}</label>
                    </section>
                </div>
                <div class="row">
                    <section class="col col-12">
                        <label class="Bold"><strong>Linhas Processadas: </strong></label>
                        <label class="progresso">{{ $row->linhas_processadas }} de {{ $row->linhas_totais }}</label>
                    </section>
                </div>
                <div class="row">
                    <section class="col col-12">
                        <label class="Bold"><strong>Atualizado em: </strong></label>
                        <label class="progresso_data">{{ optional($row->data_atualizacao)->format('d/m/Y H:i:s') ?: '—' }}</label>
                    </section>
                </div>
                <div class="row">
                    <section class="col col-12">
                        <label class="Bold"><strong>Reprocessar: </strong></label>
                        <label>
                            <a href="{{ route('admin.importacao_nova.processar_arquivo', $row->id) }}">Gerar Reprocessamento</a>
                        </label>
                    </section>
                </div>
            </fieldset>
            <footer>
                <a href="{{ route('admin.importacao_nova.index') }}" class="btn btn-default">Voltar</a>
            </footer>
        </div>
    </div>
</div>

@push('scripts')
<script>
    setInterval(function () {
        fetch(@json(route('admin.importacao_nova.status', $row->id)))
            .then(function (r) { return r.json(); })
            .then(function (data) {
                var map = {
                    0: 'Aguardando processamento',
                    1: 'Importação em processamento',
                    2: 'Importação finalizada com sucesso',
                    3: 'Importação finalizada com erros',
                    4: 'Erro'
                };
                $('.progresso_status').text(map[data.status_processo] || data.status_processo);
                $('.progresso').text(data.linhas_processadas + ' de ' + data.linhas_totais);
                if (data.data_atualizacao) {
                    $('.progresso_data').text(data.data_atualizacao);
                }
            })
            .catch(function () {});
    }, 5000);
</script>
@endpush
@endsection
