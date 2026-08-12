<div class="well well-light well-sm no-margin no-padding">
    <div class="row">

        <div class="col-sm-12">
        <div id="myCarousel" class="carousel fade profile-carousel">
            <div class="air air-top-left padding-10">
                <h4 class="txt-color-black font-md">
                    <?php 
                    if($row['Beneficiario']['data_nascimento'] != ''){
                        $dataArr = explode('-',$row['Beneficiario']['data_nascimento']);
                        $timestamp = mktime(1, 00, 00, $dataArr[1], $dataArr[2], $dataArr[0]);


                        echo date('M',$timestamp).' '.$dataArr[2].', '.$dataArr[0].' <span class="note" style="color:black">('.
                                $this->Funcoes->idade($row['Beneficiario']['data_nascimento']).' anos )</span>' ;
                    }
                    ?>
                </h4>
            </div>

            <div class="carousel-inner">
                <!-- Slide 1 -->
                <div class="item active">
                    <?php echo $this->Html->image('/img/demo/s6.jpg');?>
                </div>

            </div>
        </div>
        </div>

        <div class="col-sm-12">

        <div class="row">

            <div class="col-sm-4  profile-pic">
                <?php #echo $this->Html->image($row['img_avatar'],array('width'=>80,'height'=>80));?>

                <?php 
                
                $avatar = 'female.png';
                if($row['Beneficiario']['sexo'] == 'Masculino'){
                    $avatar = 'male.png';
                }
                
                echo $this->Html->image('/img/avatars/'.$avatar,array('width'=>80,'height'=>80));?>
            </div>
            <div class="col-sm-8">
                <h1><?php echo $row['Beneficiario']['first_name'];?> 
                <br>
                <small style="margin-left:20px;"><?php echo $row['Beneficiario']['last_name'];?> </small></h1>
            </div>
            
            <div class="col-sm-12" style="padding: 10px 15px 0 30px;">
                <p class="text-muted">
                   <b >Nome Social:</b> <?php echo ($row['Beneficiario']['nome_social'] == '')? 'Não preenchido!' : '<span style="color:red;">'.$row['Beneficiario']['nome_social'].'</span>'; ?>
                </p>
                <p class="text-muted">
                   <b>CPF:</b> <?php echo $this->Funcoes->formata_cpf($row['Beneficiario']['cpf']); ?>
                </p>
                <p class="text-muted">
                   <b>PIS:</b> <?php echo $row['Beneficiario']['pis']; ?>
                </p>
                <p class="text-muted">
                   <b>Cliente:</b> <?php echo $row['Cliente']['nome']; ?>
                </p>
                <p class="text-muted">
                   <b>Empresa:</b> 
                   <ul class="note" style="margin-left:20px;">
                        <li>
                            <b>Nome:</b> <?php echo $row['Empresa']['nome']; ?>     
                        </li>
                        <li>
                            <b>CNPJ:</b> <?php echo $this->Funcoes->formata_cnpj($row['Empresa']['cnpj']); ?>
                        </li>
                   </ul>
                </p>
              
                
                <h5 style="border-bottom: 1px dotted #d3d3d3;">Observação </h5>
                <p class="text-muted">
                    <?php echo $row['Beneficiario']['observacao']; ?>
                </p>

             
            
            <h5 style="border-bottom: 1px dotted #d3d3d3; margin-top:20px;" > Situações </h5>
                <p class="text-muted">
                <?php 
                    
                    $sitBenef = 'Beneficiário:-<br>';
                   
                    if(isset($row['Beneficiario']['situacao']) && $row['Beneficiario']['situacao'] != ''){
                        $style_benef ="border-radius:6px; color:white;background-color:red; padding:2px 4px;";
                        if($row['Beneficiario']['situacao'] == 'Ativo'){
                            $style_benef="border-radius:6px; color:white;background-color:green; padding:2px 4px;";
                        }

                        $sitBenef = '<b>Beneficiário:</b> <span style="'.$style_benef.'">'.@$row['Beneficiario']['situacao'].'</span><br>';
                    }
                    
                    $situacaoAfList = array(''=>' - ','A'=>'Afastado','RT'=>'Retorno ao Trabalho');
                    $sitAf = 'Afastado:-<br>';
                    
                    if(count($row['listAfastado']) > 0){
                        $sitAf = '<b>Afastado:</b> '.@$situacaoAfList[$row['listAfastado'][0]['situacao']].'<br>';
                    }

                    $situacaoBPList = array(''=>' - ','A'=>'Ativo','C'=>'Cessado','Suspenso'=>'Suspenso','AN'=>'Em Análise' );
                    $sitBP = 'Benefício Previdenciário: -<br>';
                    if(count($row['listBeneficioPrevidenciario']) > 0){
                        $sitBP = '<b>Benefício Previdenciário:</b> '.@$situacaoBPList[$row['listBeneficioPrevidenciario'][0]['situacao']].'<br>';
                    }
                    
                    $situacaoAbList = array(''=>' - ','0'=>'Inativo','1'=>'Ativo');
                    $sitAb = 'Absenteísmo: -<br>';
                    if(count($row['listAbsenteismo']) > 0){
                        $sitAb = '<b>Absenteísmo:</b> '.@$situacaoAbList[$row['listAbsenteismo'][0]['situacao']].'<br>';
                    }

                    echo $sitBenef;
                    echo $sitAf;
                    echo $sitBP;
                    echo $sitAb;
                ?>
                </p>
            


            <h5 style="border-bottom: 1px dotted #d3d3d3; margin-top:20px;" > Benefício Previdenciário </h5>
                <p class="text-muted">
                <?php 
                    if(count($row['listBeneficioPrevidenciario']) > 0){
                        echo 'Situacao: '.$row['listBeneficioPrevidenciario'][0]['situacao'].'<br>';
                        echo 'Data Próxima Perícia: '.$this->Funcoes->dateToView($row['listBeneficioPrevidenciario'][0]['data_proxima_pericia']).'<br>';
                        echo 'Data Cessação: '.$this->Funcoes->dateToView($row['listBeneficioPrevidenciario'][0]['data_cessacao']);
                    }
                ?>
                </p>

            <h5 style="border-bottom: 1px dotted #d3d3d3; margin-top:20px;" > Contatos </h5>
                <ul class="list-unstyled">
<!--                                                <li>
                    <span class="txt-color-darken">
                        <p class="text-muted"><i class="fa fa-phone"></i>&nbsp;&nbsp;
                        Elegibilidade: <?php #echo ($row['Gestante']['elegibilidade'] == 'T' ? 'Titular' : 'Dependente');?>
                    </span>
                    </li>-->
                    <?php 
                    if(!empty($row['Beneficiario']['telefone'])){
                        echo '<li><p class="text-muted"><i class="fa fa-phone"></i>&nbsp;&nbsp;';
                        echo 'Principal: ';
                        echo $row['Beneficiario']['telefone'];
                        echo '</li>';
                    }


                    for($i=1;$i<=9;$i++){
                        if(!empty($row['Beneficiario']['telefone'.$i])){
                            echo '<li><p class="text-muted"><i class="fa fa-phone"></i>&nbsp;&nbsp;';
                            if(!empty($row['Beneficiario']['telefone'.$i.'_tipo'])){
                                echo $row['Beneficiario']['telefone'.$i.'_tipo'].': ';
                            }
                            echo $row['Beneficiario']['telefone'.$i];

                            echo '</li>';
                        }
                    }

                    ?>
                    <li>
                    <?php 
                        if($row['Beneficiario']['email'] != ''){
                            echo '<p class="text-muted">';
                            echo '<i class="fa fa-envelope"></i>&nbsp;&nbsp;'.$row['Beneficiario']['email'];
                            echo '</p>';
                            echo '<p class="text-muted">';
                            echo '<a href="mailto:'.$row['Beneficiario']['email'].'" class="btn btn-default btn-xs"> <i class="fa fa-envelope-o"></i> Enviar E-mail</a>';
                            echo '</p>';
                        }
                    ?>
                    </li>
                </ul>

            <h5 style="border-bottom: 1px dotted #d3d3d3; margin-top:20px;">Informações do Beneficio</h5>
                
                <p class="text-muted">
                    <b>Beneficio: </b>
                    <?php echo $row[$TABLE]['beneficio']; ?>
                </p>
                <p class="text-muted">
                    <b>Valor (R$): </b>
                    <?php echo $row[$TABLE]['valor']; ?>
                </p>
                    

                



            <h5 style="border-bottom: 1px dotted #d3d3d3; margin-top:20px;">Info Geral</h5>
                <!-- <p class="text-muted">
                    <b>CPF: </b>
                    < ?php echo $row[$TABLE]['cpf']; ?>
                </p>
                <p class="text-muted">
                    <b>RG: </b>
                    < ?php echo $row[$TABLE]['rg']; ?>
                </p> 
                <p class="text-muted">
                    <b>Sexo: </b>
                    < ?php echo $row[$TABLE]['sexo']; ?>
                </p>
                <p class="text-muted">
                    <b>Estado Civil: </b>
                    < ?php echo $row[$TABLE]['estado_civil']; ?>
                </p> -->
                <p class="text-muted">
                    <b>Peso: </b>
                    <?php echo $row[$TABLE]['peso']; ?>
                </p>
                <p class="text-muted">
                    <b>Altura: </b>
                    <?php echo $row[$TABLE]['altura']; ?>
                </p>
                
                

                <!-- <h5 style="border-bottom: 1px dotted #d3d3d3; margin-top:20px;">Endereço</h5>
                <p class="text-muted">
                    <b>Endereço: </b>
                    < ?php echo $row[$TABLE]['endereco']; ?>
                </p>
                <p class="text-muted">
                    <b>Bairro: </b>
                    < ?php echo $row[$TABLE]['bairro']; ?>
                </p>
                <p class="text-muted">
                    <b>Cidade: </b>
                    < ?php echo $row[$TABLE]['cidade']; ?>
                </p>
                <p class="text-muted">
                    <b>Estado: </b>
                    < ?php echo $row[$TABLE]['estado']; ?>
                </p>
                <p class="text-muted">
                    <b>CEP: </b>
                    < ?php echo $row[$TABLE]['cep']; ?>
                </p> -->


                <!-- <h5 style="border-bottom: 1px dotted #d3d3d3; margin-top:20px;">Dados Bancários</h5>
                <p class="text-muted">
                    <b>Agência: </b>
                    < ?php echo $row[$TABLE]['agencia']; ?>
                </p>
                <p class="text-muted">
                    <b>Conta: </b>
                    < ?php echo $row[$TABLE]['conta']; ?>
                </p>
                <p class="text-muted">
                    <b>Tipo de Conta: </b>
                    < ?php echo $row[$TABLE]['tipo_de_conta']; ?>
                </p> -->


                <!-- <h5 style="border-bottom: 1px dotted #d3d3d3; margin-top:20px;">Informações Profissionais</h5>
                <p class="text-muted">
                    <b>Profissão: </b>
                    < ?php echo $row[$TABLE]['profissao']; ?>
                </p>
                <p class="text-muted">
                    <b>Ocupação: </b>
                    < ?php echo $row[$TABLE]['ocupacao']; ?>
                </p>
                <p class="text-muted">
                    <b>Pessoa Politicamente Exposta? </b>
                    < ?php echo $row[$TABLE]['pessoa_politicamente_exposta']; ?>
                </p>
                <p class="text-muted">
                    <b>Realiza alguma atividade perigosa na profissao? </b>
                    < ?php echo $row[$TABLE]['realiza_alguma_atividade_perigosa_na_profissao']; ?>
                </p>
                <p class="text-muted">
                    <b>Possui deficiência física? </b>
                    < ?php echo $row[$TABLE]['conta']; ?>
                </p> -->

                <!-- <h5 style="border-bottom: 1px dotted #d3d3d3; margin-top:20px;">Beneficiários</h5> -->
                <?php 
                    // $id_ben = 1;
                    // while ($id_ben < 5){
                    //     if(isset($row[$TABLE]["beneficiario{$id_ben}"]) && $row[$TABLE]["parentesco{$id_ben}"] != ''){
                    //         echo '<p class="text-muted">';
                    //         echo '<b>Beneficiário </b>'.$row[$TABLE]['beneficiario'.$id_ben].'<br>';
                    //         echo '<b>Parentesco </b>'.$row[$TABLE]['parentesco'.$id_ben];
                    //         echo '';
                    //         echo '</p>';
                    //     } 
                    //     $id_ben++;
                    // }
                ?>

                <h5 style="border-bottom: 1px dotted #d3d3d3; margin-top:20px;">Status</h5>
                <?php 
                    if(isset($row['UsuarioCriador']['nome'])){
                        echo '<p class="text-muted">';
                        echo '<b>Criado por: </b>'.$row['UsuarioCriador']['nome'];
                        echo '</p>';
                    }
                    
                ?>
                <?php 
                    if(isset($row['UsuarioAtualizacao']['nome'])){
                        echo '<p class="text-muted">';
                        echo '<b>Atualizado por: </b>'.$row['UsuarioAtualizacao']['nome'];
                        echo '</p>';
                    }
                    
                ?>
                    
                
                <p class="text-muted">
                    <b>Status: </b>
                    <?php echo $this->Funcoes->status($row[$TABLE]['status']); ?>
                </p>
                        

            </div>

        </div>

        </div>

    </div>
    <!-- end row -->

</div>