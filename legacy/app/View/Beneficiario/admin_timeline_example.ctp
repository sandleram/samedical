<?php echo $this->Element('admin/breadcrumb'); ?>

<div id="content">



    <div class="row">
            
        <div class="col-sm-12">
              <?php # krumo($row); ?>         

              <div class="well well-sm">

                 <div class="row">

                    <div class="col-sm-12 col-md-12 col-lg-3">
                       <div class="well well-light well-sm no-margin no-padding">

                          <div class="row">       

                             <div class="col-sm-12">
                                <div id="myCarousel" class="carousel fade profile-carousel">
                                   <div class="air air-top-left padding-10">
                                      <h4 class="txt-color-white font-md">
                                         <?php 
                                            if($row['DadoPessoal']['data_nascimento'] != ''){
                                               $dataArr = explode('-',$row['DadoPessoal']['data_nascimento']);

                                               $timestamp = mktime(1, 00, 00, $dataArr[1], $dataArr[2], $dataArr[0]);


                                               echo date('M',$timestamp).' '.$dataArr[2].', '.$dataArr[0].' <span class="note" style="color:white">('.
                                                     $this->Funcoes->idade($row['DadoPessoal']['data_nascimento']).' anos )</span>' ;
                                            }
                                         ?>
                                      </h4>
                                   </div>

                                   <div class="carousel-inner">
                                      <!-- Slide 1 -->
                                      <div class="item active">
                                         <?php echo $this->Html->image('/img/demo/s1.jpg');?>
                                      </div>

                                   </div>
                                </div>
                             </div>

                             <div class="col-sm-12">

                                <div class="row">

                                   <div class="col-sm-4  profile-pic">
                                    <?php echo $this->Html->image($row['img_avatar'],array('width'=>80,'height'=>80));?>
                                      <?php #echo $this->Html->image('/img/avatars/pregnancy.jpg',array('width'=>80,'height'=>80));?>
                                   </div>
                                   <div class="col-sm-8">
                                      <h1><?php echo $row['VhGestante']['first_name'];?> 
                                      <br>
                                      <small style="margin-left:20px;"><?php echo $row['VhGestante']['last_name'];?> </small></h1>
                                   </div>
                                    
                                   <div class="col-sm-12" style="padding: 10px 15px 0 30px;">
                                    <h5 style="border-bottom: 1px dotted #d3d3d3;">Etapa da Gestação </h5>
                                    <?php 
                                       echo  $this->Funcoes->grupo_trimestral($row['VhGestante']['data_ultima_menstruacao']).'<br>';
                                    ?>


                                    <h5 style="border-bottom: 1px dotted #d3d3d3; margin-top:20px;">Tempo para o Parto <span class="note">(tempo provável) </span></h5>
                                    <?php 
                                       
                                       echo $this->Funcoes->dias_para_parto($row['VhGestante']['data_ultima_menstruacao']);
                                    
                                    ?> 
                                
                                    <h5 style="border-bottom: 1px dotted #d3d3d3;margin-top:20px;"> Empresa </h5>
                                       <ul class="list-unstyled" style="font-size:12px;">
                                          <?php 
                                             if(isset($row['DadoPessoal']['EmpresaBeneficiario'][0]['Empresa']['nome'])){
                                                echo '<li>'.$this->Funcoes->normalizaTexto($row['DadoPessoal']['EmpresaBeneficiario'][0]['Empresa']['nome']).'</li>';
                                             }
                                          ?>
                                       </ul>


                                    <h5 style="border-bottom: 1px dotted #d3d3d3; margin-top:20px;"> Planos </h5>
                                       <ul class="list-unstyled" style="font-size:12px;">

                                          <?php 

                                             if(count($row['DadoPessoal']['DadoPlano'])>0){
                                                foreach($row['DadoPessoal']['DadoPlano'] as $DP){
                                                   $planoArr = explode(' - ',$DP['Plano']['nome']);
                                                   $plano = $DP['Plano']['nome'];
                                                   if(count($plano)> 0){
                                                      unset($planoArr[0]);
                                                      $plano = implode(' - ',$planoArr);
                                                      $plano = $this->Funcoes->normalizaTexto($plano);
                                                   }
                                                   $br = '<br>';
                                                   $carteira = $DP['carteira'];
                                                   $operadora = $this->Funcoes->normalizaTexto($DP['Beneficio']['Operadora']['nome']);
                                                   $produto = $this->Funcoes->normalizaTexto($DP['Beneficio']['Produto']['nome']);
 

                                                   echo '<li> ';
                                                   echo $operadora.$br;
                                                   echo 'Plano: '.$plano.$br;
                                                   echo 'Carteirinha: '.$carteira.$br;

                                                   echo '</li>';
                                                }
                                             }

                                          ?>

                                       </ul>



                                    <h5 style="border-bottom: 1px dotted #d3d3d3; margin-top:20px;" > Contatos </h5>
                                       <ul class="list-unstyled">
<!--                                                <li>
                                            <span class="txt-color-darken">
                                               <p class="text-muted"><i class="fa fa-phone"></i>&nbsp;&nbsp;
                                               Elegibilidade: <?php #echo ($row['Gestante']['elegibilidade'] == 'T' ? 'Titular' : 'Dependente');?>
                                            </span>
                                         </li>-->
                                         <?php 
                                             if(count($row['DadoPessoal']['DadoTelefone']) > 0){
                                                 echo '<li>';
                                                 foreach($row['DadoPessoal']['DadoTelefone'] as $dadoTel){
                                                    if(count($dadoTel['Telefone'])> 0){
                                                        echo '<p class="text-muted" style="font-size:12px;"><i class="fa fa-phone"></i>&nbsp;&nbsp;';
                                                         echo '<span class="txt-color-darken"> ';
                                                         echo $dadoTel['Telefone']['tipo'].': ';
                                                         echo '('.$dadoTel['Telefone']['ddd'].') ';
                                                         echo $dadoTel['Telefone']['numero'];
                                                         if($dadoTel['Telefone']['ramal'] != ''){
                                                            echo 'Ramal: '.$dadoTel['Telefone']['ramal'];
                                                         }
                                                         echo '</span>';
                                                         echo '</p>';
                                                    }
                                                 }
                                                 echo '</li>';
                                             }
                                         ?>
                                         <li>
                                            <?php 
                                               if($row['DadoPessoal']['UsuarioVS']['email'] != ''){
                                                  echo '<p class="text-muted">';
                                                  echo '<i class="fa fa-envelope"></i>&nbsp;&nbsp;'.$row['DadoPessoal']['UsuarioVS']['email'];
                                                  echo '</p>';
                                                  echo '<p class="text-muted">';
                                                  echo '<a href="mailto:'.$row['DadoPessoal']['UsuarioVS']['email'].'" class="btn btn-default btn-xs"> <i class="fa fa-envelope-o"></i> Enviar E-mail</a>';
                                                  echo '</p>';
                                               }
                                            ?>
                                         </li>
                                      </ul>
                                   </div>

                                </div>

                             </div>

                          </div>
                          <!-- end row -->

                       </div>

                    </div>
                     <style type="text/css">
                         .smart-timeline-list li {
                             padding: 10px 0 !important;
                         }
                         .smart-timeline-icon>img {
                             border: 1px solid #ffbdcf;
                         }
                     </style>
                     
                     <div class="col-xs-12 col-sm-12 col-md-12 col-lg-9">

                         <div class="well well-sm">
                             <!-- Timeline Content -->
                             <div class="smart-timeline">
                                 
                                 
                                    <?php 
                                      #($row);
                                      #exit;
                                        foreach($row['chatList'] as $chatGrupo =>  $chatListAll){ 
                                            echo '<div class="timeline-seperator text-center"> 
                                                    <span style="background-color: #ff00002b; color: black; z-index:9999; font-size:12px;">'.$chatGrupo.'</span></div>';
                                            echo '<ul class="smart-timeline-list" style="margin-top:10px;">';
                                            foreach($chatListAll as $chatList){ 
                                              #krumo($chatList);
                                              #exit;

                                              $styleli = ' style=""';
                                              $stylediv = ' style="width:100%;"';
                                              $transb = false;
                                              if(count($chatList['transbordo_retorno']) > 0){
                                                $stylediv = ' style="width:50%; display: inline-block; border-right:1px solid #d3d3d3;"';
                                                $transb = true;
                                                $styleli = ' style="background-color:#ff00000f; border: 1px solid #fdd2d2; border-radius: 6px;"';   
                                              }
                                              

                                    ?>
                                                <li <?php echo $styleli;?>>
                                                    <div class="smart-timeline-icon">
                                                        <?php echo $this->Html->image($row['img_avatar']);?>
                                                    </div>
                                                    <div class="smart-timeline-time">
                                                        <small><?php echo $this->Funcoes->data_hora_recado($chatList['data_cadastro']);?></small><br/>
                                                        <span class='note' style='font-size:8px;'>(<?php echo $this->Funcoes->dateToView($chatList['data_cadastro']);?>)</span>
                                                    </div>
                                                    <div class="smart-timeline-content"  <?php echo ($transb == true)? 'style="padding-bottom: 20px;"' :'' ; ?> >
                                                      <p>  
                                                        <div <?php echo $stylediv;?>>
                                                          <p><?php echo $chatList['pergunta'];?></p>
                                                          <p><?php echo $chatList['resposta'];?></p>
                                                        </div>
                                                        <?php  if($transb === true){ ?> 
                                                          <div style="width:50%; float:right; display: inline-block; padding-left: 10px;">
                                                            <b>Transbordo</b><br>
                                                            <?php 
                                                              $quebra = '<br>';
                                                              foreach($chatList['transbordo_retorno'] as $transbRet){


                                                                $linkTransb = Router::url('/admin/transbordo/view/'.$transbRet['transbordo_id'],true);
                                                                
                                                                #echo '<a href="'.$linkTransb.'" target="_blank">#'.$transbRet['transbordo_id'].'</a> ';
                                                                echo '#'.$transbRet['transbordo_id'].' ';
                                                                echo $transbRet['tipo_comparacao'].' '.$transbRet['resposta'].$quebra;

                                                                if($transbRet['status'] == 3){
                                                                  echo '<p style="padding-left:16px;"><a class="btn btn-danger btn-xs" href="'.Router::url(array('controller'=>'transbordo_retorno','action'=>'admin_add',$transbRet['id'])).'">Gravar Atendimento</a></p>';


                                                                 
                                                                }else if($transbRet['status'] == 4) {
                                                                  echo '<p style="padding-left:16px;"><a class="btn btn-success btn-xs" href="'.Router::url(array('controller'=>'transbordo_retorno','action'=>'admin_view',$transbRet['id'])).'">Atendimento Feito</a></p>';
                                                                }else{
                                                                  echo 'Transbordo Desativado.';
                                                                }
                                                               }
                                                            ?>
                                                          </div>  
                                                        <?php } ?>  

                                                      </p>
                                                    </div>
                                                </li>
                                        <?php 
                                            }
                                            echo '</ul>';
                                        } 
                                        ?>
                             </div>
                             <!-- END Timeline Content -->
                             
                         </div>

                     </div>
                    
                 </div>
              </div>
        </div>
     </div>
</div>
 
<?php #echo $this->Html->image('/img/avatars/female.png');?>
<?php #echo $row[$TABLE]['nome']; ?> 