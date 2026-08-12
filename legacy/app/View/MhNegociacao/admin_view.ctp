<?php echo $this->Element('admin/breadcrumb');?>

<div id="content">
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="well no-padding smart-form client-form ">
                <?php
                    echo $this->Form->msg($this->Session->flash());
                     echo $this->Funcoes->menus('geral',$permissao);
                ?>

                <header>
                    Visualização de <?php echo $this->Funcoes->titulos($this->params['controller']); ?>
                </header>
                <fieldset>
                    <div class="row">
                        <section class="col col-6">
                            <label class="Bold"><strong>ID: </strong></label>
                            <label> <?php echo $row[$TABLE]['id']; ?></label>
                        </section>
                    </div>
                  
                    <section>
                        <label class="Bold"><strong>Cliente: </strong></label>
                        <label> <?php echo $row[$TABLE]['nome']; ?></label>
                    </section>
                    
                  
                    <div class="row">
                       <section class="col col-4">
                            <label class="Bold"><strong>Data de Cadastro: </strong></label>
                            <label> <?php echo $this->DateTime->dbToView($row[$TABLE]['data_cadastro']); ?></label>
                        </section>
                    </div>
                    
                     <div class="row">
                       <section class="col col-4">
                            <label class="Bold"><strong>Data de Atualização: </strong></label>
                            <label> <?php echo $this->DateTime->dbToView($row[$TABLE]['data_atualizacao']); ?></label>
                        </section>
                    </div>
                    <div class="row">
                       <section class="col col-4">
                            <label class="Bold"><strong>Data de Cancelamento: </strong></label>
                            <label> <?php echo $this->DateTime->dbToView($row[$TABLE]['data_cancelamento']); ?></label>
                        </section>
                    </div>
                    <div class="row">
                        <section class="col col-4">
                            <label class="Bold"><strong>Status: </strong></label>
                            <label> <?php echo $this->Funcoes->status($row[$TABLE]['status']); ?></label>
                        </section>
                    </div>
                </fieldset>
                
                
            </div>

        </div>
    </div>
</div>