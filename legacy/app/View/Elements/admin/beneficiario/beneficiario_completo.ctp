<div class="well no-padding smart-form client-form " >
    
    <fieldset>
        <h4 style="margin-top:0; margin-bottom:20px; border-bottom: 1px dotted #d3d3d3; font-weight:bold;   "> Cadastro </h4>
        <div class="row">
            <section class="col col-6" >
                <label class="label"><strong>ID:</strong> <?php echo $row[$TABLE]['id']; ?></label>
            </section>
        </div>
        <section>
            <label class="Bold"><strong>Nome: </strong></label>
            <label> <?php echo $row[$TABLE]['nome']; ?></label>
        </section>
        <section>
            <label class="Bold"><strong>E-mail: </strong></label>
            <label> <?php echo $row[$TABLE]['email']; ?></label>
        </section>
        <section>
            <label class="Bold"><strong>Situação: </strong></label>
            <label> <?php echo $sitBenef = ''; 
            
            if(isset($row[$TABLE]['situacao']) && $row[$TABLE]['situacao'] != ''){
                $style_benef ="border-radius:6px; color:white;background-color:red; padding:2px 4px;";
                if($row['Beneficiario']['situacao'] == 'Ativo'){
                    $style_benef="border-radius:6px; color:white;background-color:green; padding:2px 4px;";
                }
                $sitBenef = ' <span style="'.$style_benef.'">'.@$row['Beneficiario']['situacao'].'</span><br>';
            }
            echo $sitBenef;
            ?></label>
        </section>


        <h4 style="border-bottom:1px dotted #d3d3d3;margin-top:30px; margin-bottom:10px; font-weight: bold;">Informações do Beneficio</h4>
        <section>
            <label class="Bold"><strong>Benefício: </strong></label>
            <label> <?php echo $row[$TABLE]['beneficio']; ?></label>
        </section>
        <section>
            <label class="Bold"><strong>Valor (R$): </strong></label>
            <label> <?php echo $row[$TABLE]['valor']; ?></label>
        </section>


        <h4 style="border-bottom:1px dotted #d3d3d3;margin-top:30px; margin-bottom:10px; font-weight: bold;">Dados Pessoais</h4>
        <div class="row">
            <section class="col col-4">
                <label class="Bold"><strong>CPF: </strong></label>
                <label> <?php echo $row[$TABLE]['cpf']; ?></label>
            </section>
            <section class="col col-4">
                <label class="Bold"><strong>RG: </strong></label>
                <label> <?php echo $row[$TABLE]['rg']; ?></label>
            </section> 
            <section class="col col-4">
                <label class="Bold"><strong>Sexo: </strong></label>
                <label> <?php echo $sexoArr[$row[$TABLE]['sexo']]; ?></label>
            </section>
        </div>
        <div class="row">
            <section class="col col-4">
                <label class="Bold"><strong>Estado Civil: </strong></label>
                <label> <?php echo $estadoCivilArr[$row[$TABLE]['estado_civil']]; ?></label>
            </section>
            <section class="col col-4">
                <label class="Bold"><strong>Data de Nascimento : </strong></label>
                <label> <?php echo $this->DateTime->dbToView($row[$TABLE]['data_nascimento']); ?></label>
            </section> 
        </div>
        <div class="row">
            <section class="col col-4">
                <label class="Bold"><strong>Peso: </strong></label>
                <label> <?php echo $row[$TABLE]['peso']; ?></label>
            </section>
            <section class="col col-4">
                <label class="Bold"><strong>Altura: </strong></label>
                <label> <?php echo $row[$TABLE]['altura']; ?></label>
            </section>
        </div>



        <h4 style="border-bottom:1px dotted #d3d3d3;margin-top:30px; margin-bottom:10px; font-weight: bold;">Endereço</h4>
        <div class="row">
            <section class="col col-4">
                <label class="Bold"><strong>Endereço Completo: </strong></label>
                <label> <?php echo $row[$TABLE]['endereco']; ?></label>
            </section> 
            <section class="col col-2">
                <label class="Bold"><strong>Bairro: </strong></label>
                <label> <?php echo $row[$TABLE]['bairro']; ?></label>
            </section> 
            <section class="col col-2">
                <label class="Bold"><strong>Cidade: </strong></label>
                <label> <?php echo $row[$TABLE]['cidade']; ?></label>
            </section> 
            <section class="col col-1">
                <label class="Bold"><strong>Estado: </strong></label>
                <label> <?php echo $row[$TABLE]['estado']; ?></label>
            </section> 
            <section class="col col-2">
                <label class="Bold"><strong>CEP: </strong></label>
                <label> <?php echo $row[$TABLE]['cep']; ?></label>
            </section> 
        </div>



        <h4 style="border-bottom:1px dotted #d3d3d3;margin-top:30px; margin-bottom:10px; font-weight: bold;">Dados Bancários</h4>
        <div class="row">
            <section class="col col-2">
                <label class="Bold"><strong>Agência: </strong></label>
                <label> <?php echo $row[$TABLE]['agencia']; ?></label>
            </section> 
            <section class="col col-2">
                <label class="Bold"><strong>Conta: </strong></label>
                <label> <?php echo $row[$TABLE]['conta']; ?></label>
            </section> 
            <section class="col col-2">
                <label class="Bold"><strong>Tipo de Conta: </strong></label>
                <label> <?php echo $row[$TABLE]['tipo_de_conta']; ?></label>
            </section> 
        </div>




        <h4 style="border-bottom:1px dotted #d3d3d3;margin-top:30px; margin-bottom:10px; font-weight: bold;">Informações Profissionais</h4>
        <div class="row">
            <section class="col col-6">
                <label class="Bold"><strong>Profissão: </strong></label>
                <label> <?php echo $row[$TABLE]['profissao']; ?></label>
            </section> 
            <section class="col col-6">
                <label class="Bold"><strong>Ocupação: </strong></label>
                <label> <?php echo $row[$TABLE]['ocupacao']; ?></label>
            </section> 
        </div>
        <section>
            <label class="Bold"><strong>Pessoa Politicamente Exposta? </strong></label>
            <label> <?php echo $row[$TABLE]['pessoa_politicamente_exposta']; ?></label>
        </section> 
        <section>
            <label class="Bold"><strong>Realiza alguma atividade perigosa na profissao? </strong></label>
            <label> <?php echo $row[$TABLE]['realiza_alguma_atividade_perigosa_na_profissao']; ?></label>
        </section> 
        <section>
            <label class="Bold"><strong>Possui deficiência? </strong></label>
            <label> <?php echo $row[$TABLE]['possui_deficiencia']; ?></label>
        </section> 
        

        <h4 style="border-bottom:1px dotted #d3d3d3;margin-top:30px; margin-bottom:10px; font-weight: bold;">Contatos</h4>
        <section >
            <label class="Bold"><strong>Telefone Principal: </strong></label>
            <label> 
                <?php echo (!isset($row[$TABLE]['telefone_tipo']) && $row[$TABLE]['telefone_tipo'] != '')?  $telTipoArr[$row[$TABLE]['telefone_tipo']] : ''; ?>: 
                <?php echo $row[$TABLE]['telefone']; ?>
            </label>
        </section>
        <?php 
            $id_tel = 1;
            while ($id_tel < 10):
                if(isset($row[$TABLE]["telefone{$id_tel}"]) && 
                $row[$TABLE]["telefone{$id_tel}"] != ''
            )  :
        ?>
                <section >
                    <label class="Bold"><strong>Telefone <?php echo $id_tel; ?>: </strong></label>
                    <label> 
                        <?php 
                            if(isset($telTipoArr[$row[$TABLE]["telefone{$id_tel}_tipo"]]) && $row[$TABLE]["telefone{$id_tel}_tipo"] != ''){
                                echo $telTipoArr[$row[$TABLE]["telefone{$id_tel}_tipo"]].': ' ; 
                            }
                        ?>
                        <?php echo $row[$TABLE]['telefone'.$id_tel]; ?>
                    </label>
                </section>
        <?php 
                endif;
            $id_tel++;
            endwhile;
        ?>




        <h4 style="border-bottom:1px dotted #d3d3d3;margin-top:30px; margin-bottom:10px; font-weight: bold;">Beneficiários</h4>
        <?php 
            $id_ben = 1;
            while ($id_ben < 5):
                if(isset($row[$TABLE]["beneficiario{$id_ben}"]) && 
                $row[$TABLE]["parentesco{$id_ben}"] != ''
            )  :
        ?>
        <div class="row">

            <section class="col col-6">
                <label class="Bold"><strong>Beneficiario <?php echo $id_ben; ?>: </strong></label>
                <label> 
                    <?php echo $row[$TABLE]['beneficiario'.$id_ben]; ?>
                </label>
            </section>
            <section class="col col-6">
                <label class="Bold"><strong>Parentesco <?php echo $id_ben; ?>: </strong></label>
                <label> 
                    <?php echo $row[$TABLE]['parentesco'.$id_ben]; ?>
                </label>
            </section>
        </div>
        <?php 
                endif;
            $id_ben++;
            endwhile;
        ?>


        <section style="margin-top:30px;">
            <label class="Bold"><strong>Observações: </strong></label>
            <br>
            <label> 
                <?php echo $row[$TABLE]['observacao']; ?>
            </label>
        </section>

        
        
        <hr style="margin-top:40px; margin-bottom: 10px;">
        <?php if(isset($row[$TABLE]['id']) && $row[$TABLE]['id'] != ''):?>
            <?php if(isset($row['UsuarioAtualizacao']['nome'])):?>
                <section>
                    <label>
                        <strong>Criado por:</strong> <i><?php echo $row['UsuarioCriador']['nome']; ?></i> <strong>data:</strong> <?php echo $this->DateTime->dbToView($row[$TABLE]['data_atualizacao']);?>
                    </label>
                </section>
            <?php endif; ?>
            <?php if(isset($row['UsuarioAtualizacao']['nome'])):?>
                <section>
                    <label>
                        <strong>Atualizado por:</strong> <i><?php echo $row['UsuarioAtualizacao']['nome']; ?></i> <strong>data:</strong> <?php echo $this->DateTime->dbToView($row[$TABLE]['data_atualizacao']);?>
                    </label>
                </section>
            <?php endif; ?>
        <?php endif;?>
        <div class="row">
            <section class="col col-4">
                <label class="Bold"><strong>Status: </strong></label>
                <label> <?php echo $this->Funcoes->status($row[$TABLE]['status']); ?></label>
            </section>
        </div>
    </fieldset>
    
    
</div>