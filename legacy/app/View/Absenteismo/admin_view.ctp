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
                        <section class="col col-6" style="margin-bottom: -30px;">
                            <label class="label" style="text-align: right;">
                                <strong>Criado por:</strong> <i><?php echo $row['UsuarioCriador']['nome']; ?></i><br>
                            </label>
                        </section>
                    </div>
                    
                    <section>
                        <label class="Bold"><strong>Empresa: </strong></label>
                        <label> <?php echo $row[$TABLE]['nome']; ?></label>
                    </section>
                    
                    <section>
                        <label class="Bold"><strong>Razão Social: </strong></label>
                        <label> <?php echo $row[$TABLE]['razao_social']; ?></label>
                    </section>
                    
                    <section>
                        <label class="Bold"><strong>Nome Fantasia: </strong></label>
                        <label> <?php echo $row[$TABLE]['nome_fantasia']; ?></label>
                    </section>
                    
                    <section>
                        <label class="Bold"><strong>CNPJ: </strong></label>
                        <label> <?php echo $this->Funcoes->formata_cnpj($row[$TABLE]['cnpj']); ?></label>
                    </section>
                    
                    <section>
                        <label class="Bold"><strong>Inscrição Estadual: </strong></label>
                        <label> <?php echo $row[$TABLE]['inscricao_estadual']; ?></label>
                    </section>
                    
                    <section>
                        <label class="Bold"><strong>Inscrição Municipal: </strong></label>
                        <label> <?php echo $row[$TABLE]['inscricao_municipal']; ?></label>
                    </section>
                    
                    <section>
                        <label class="Bold"><strong>Número Funcionários: </strong></label>
                        <label> <?php echo $row[$TABLE]['numero_funcionarios']; ?></label>
                    </section>
                    
                    <section>
                        <label class="Bold"><strong>Descrição: </strong></label>
                        <label> <?php echo $row[$TABLE]['descricao']; ?></label>
                    </section>
                    
                                       
                    <section>
                        <label class="Bold"><strong>Porte: </strong></label>
                        <label> <?php echo $porteArr[$row[$TABLE]['porte']]; ?></label>
                    </section>
                    
                    <section>
                        <label class="Bold"><strong>Faturamento: </strong></label>
                        <label> <?php echo $faturamentoArr[$row[$TABLE]['faturamento']]; ?></label>
                    </section>
               
                    
                    
                    <section>
                        <label class="Bold"><strong>Localização: </strong></label>
                        <label> <?php 
                                    if(isset($row[$TABLE]['endereco']) && $row[$TABLE]['endereco'] != ''):
                                        echo $row[$TABLE]['endereco'].', '. $row[$TABLE]['numero'].' '. $row[$TABLE]['complemento'].' - '.$row[$TABLE]['bairro'].' - '. $row[$TABLE]['cidade'].' - '. $row[$TABLE]['estado'].' - '. $row[$TABLE]['cep'];
                                    endif;
                                 ?>
                        </label>    
                    </section>
                    
                    
                    
                    <section>
                        <label class="Bold"><strong>Telefone: </strong></label>
                        <label> <?php echo $row[$TABLE]['telefone']; ?></label>
                    </section>
                    
                    <section>
                        <label class="Bold"><strong>Email: </strong></label>
                        <label> <?php echo $row[$TABLE]['email']; ?></label>
                    </section>
                    
                    <section>
                        <label class="Bold"><strong>Site: </strong></label>
                        <label> <?php echo $row[$TABLE]['site']; ?></label>
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
                            <label class="Bold"><strong>Status: </strong></label>
                            <label> <?php echo $this->Funcoes->status($row[$TABLE]['status']); ?></label>
                        </section>
                    </div>
                </fieldset>
                
                
            </div>

        </div>
    </div>
</div>