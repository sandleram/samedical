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
                        <section class="col col-4">
                            <label class="Bold"><strong>ID: </strong></label>
                            <label> <?php echo $row[$TABLE]['id']; ?></label>
                        </section>
                        <section class="col col-6">
                            <label class="label" style="text-align: right;"><strong>Criado por:</strong> <i><?php echo $row['UsuarioCriador']['nome']; ?></i></label>
                        </section>
                    </div>
                    <div class="row">
                        
                    </div>
                    <div class="row">
                        <section class="col col-2">
                            <label> 
                                <?php
                                    
                                    if(isset($row[$TABLE]['imagem']) &&  $row[$TABLE]['imagem'] != ''){
                                        if(file_exists('img/uploads/'.$this->params['controller'].'/'.$row[$TABLE]['imagem'])){
                                            echo $this->Html->image('uploads/'.$this->params['controller'].'/'.$row[$TABLE]['imagem'], array('width'=>'36', 'rel'=>Router::url('/img/uploads/'.$this->params['controller'].'/').$row[$TABLE]['imagem'],'class'=>'link_image')); 
                                        }
                                    }
                                   
                                ?>
                            </label>
                        </section>
                        <section class="col col-2">
                            <label class="Bold"><strong>Cliente: </strong></label>
                            <label> <?php echo $row['GrupoEmpresarial']['nome']; ?></label>
                        </section>
                        <section class="col col-3">
                            <label class="Bold"><strong>Perfil: </strong></label>
                            <label> <?php echo $row['Perfil']['nome']; ?></label>
                        </section>
                    </div>
                    <div class="row">
                        <section class="col col-3">
                            <label class="Bold"><strong>Apelido: </strong></label>
                            <label> <?php echo $row[$TABLE]['apelido']; ?></label>
                        </section>
                        <section class="col col-3">
                            <label class="Bold"><strong>Nome: </strong></label>
                            <label> <?php echo $row[$TABLE]['nome']; ?></label>
                        </section>
                        <section class="col col-3">
                            <label class="Bold"><strong>Usuário: </strong></label>
                            <label> <?php echo $row[$TABLE]['usuario']; ?></label>
                        </section>
                        <section class="col col-3">
                            <label class="Bold"><strong>Email: </strong></label>
                            <label> <?php echo $row[$TABLE]['email']; ?></label>
                        </section>
                    </div>
                    
                    
                    <header style="margin-top:20px; margin-bottom: 15px;">
                        DADOS PESSOAIS
                    </header>
                    <div class="row">
                        <section class="col col-2">
                            <label class="Bold"><strong>Sexo: </strong></label>
                            <label> <?php echo $row[$TABLE]['sexo']; ?></label>
                        </section>
                        <section class="col col-2">
                            <label class="Bold"><strong>Rg: </strong></label>
                            <label> <?php echo $row[$TABLE]['rg']; ?></label>
                        </section>
                        <section class="col col-2">
                            <label class="Bold"><strong>Cpf: </strong></label>
                            <label> <?php echo $row[$TABLE]['cpf']; ?></label>
                        </section>
                    </div>
                    <div class="row">
                        <section class="col col-2">
                            <label class="Bold"><strong>Data Nascimento: </strong></label>
                            <label> <?php echo $this->DateTime->dbToView($row[$TABLE]['data_nascimento']); ?></label>
                        </section>
                        
                    </div>
                    
                    
                    
                    
                    <header style="margin-top:20px; margin-bottom: 15px;">
                        CONTATOS
                    </header>
                    <div class="row">
                        <section class="col col-3">
                            <label class="Bold"><strong>Telefone 1: </strong></label>
                            <label> <?php echo $row[$TABLE]['tel1_tipo'].': '.$row[$TABLE]['tel1']; ?></label>
                        </section>
                        <section class="col col-3">
                            <label class="Bold"><strong>Telefone 2: </strong></label>
                            <label> <?php echo $row[$TABLE]['tel2_tipo'].': '.$row[$TABLE]['tel2']; ?></label>
                        </section>
                        <section class="col col-3">
                            <label class="Bold"><strong>Telefone 3: </strong></label>
                            <label> <?php echo $row[$TABLE]['tel3_tipo'].': '.$row[$TABLE]['tel3']; ?></label>
                        </section>
                    </div>
                    
                    
                    
                    
                    
                    <div class="row" style="margin-top:50px; margin-bottom:50px; ">
                        <section class="col col-12">
                            <label class="Bold label"><strong>Observações: </strong></label>
                            <label ><?php echo $row[$TABLE]['observacao']; ?></label>
                        </section>
                    </div>
                    
                    
                    
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