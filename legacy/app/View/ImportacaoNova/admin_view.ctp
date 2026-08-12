<?php echo $this->Element('admin/breadcrumb'); ?>

<div id="content">
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="well no-padding smart-form client-form ">
                <?php
                echo $this->Form->msg($this->Session->flash());
                echo $this->Funcoes->menus('geral', $permissao);
                ?>

                <header>
                    Visualização de <?php echo $this->Funcoes->titulos($this->params['controller']); ?>
                </header>
                <fieldset>
                    <div class="row">
                        <section class="col col-12">
                            <label class="Bold"><strong>ID: </strong></label>
                            <label> <?php echo $row[$TABLE]['id']; ?></label>
                        </section>

                    </div>

                    <div class="row">
                        <section class="col col-12">
                            <label class="Bold"><strong>Status: </strong></label>
                            <label class="progresso_status"> </label><br />
                        </section>
                    </div>
                    <div class="row">
                        <section class="col col-12">
                            <label class="Bold"><strong>Linhas Processadas: </strong></label>
                            <label class="progresso"> </label><br />
                        </section>
                    </div>
                    <div class="row">
                        <section class="col col-12">
                            <label class="Bold"><strong>Atualizado em: </strong></label>
                            <label class="progresso_data"> </label><br />
                        </section>
                    </div>
                    <div class="row">
                        <section class="col col-12">
                            <label class="Bold"><strong>Reprocessar: </strong></label>
                            <label class=""><?php echo $this->Html->link('Gerar Reprocessamento', array('action' => 'admin_processar_arquivo', $row[$TABLE]['id'])); ?> </label><br />
                        </section>
                    </div>


                </fieldset>


            </div>

        </div>
    </div>
</div>
<script>
    setInterval(() => {
        fetch('<?php echo Router::url('/importacao_nova/status/' . $row[$TABLE]['id']); ?>')
            .then(r => r.json())
            .then(data => {


                let statusText = '';
                if (data.status_processo == 0) {
                    statusText = 'Aguardando processamento';
                } else if (data.status_processo == 1) {
                    statusText = 'Importação em processamento';
                } else if (data.status_processo == 2) {
                    statusText = 'Importação finalizada com sucesso';
                } else if (data.status_processo == 3) {
                    statusText = 'Importação finalizada com erros';
                }

                // statusText = '< ?php print($this->Funcoes->status_processo(' + data.status_processo + ')) ?>';
                // console.log(statusText);

                $('.progresso_status').text(statusText);
                $('.progresso').text(data.linhas_processadas + ' de ' + data.linhas_totais);
                $('.progresso_data').text(data.data_atualizacao);
            });
    }, 3000);
</script>