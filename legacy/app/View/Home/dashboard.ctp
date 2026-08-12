
<!-- END RIBBON -->
<style>
    .table_graph tr td {
        line-height: 60px !important;

    }
    .table_graph2 tr td {
        line-height: 35px !important;
    }
</style>


<div id="content" style="padding-top: 10px; ">
    <?php echo $this->Form->msg($this->Session->flash()); ?>
    <div class="row">
        <article class="col-xs-12d col-sm-12 col-md-6 col-lg-6" >
            <div class="jarviswidget" id="wid-id-1" >
                <header>
                    <span class="widget-icon"> <i class="fa fa-bar-chart-o"></i> </span>
                    <h2>Leads  
                        <?php
                        if (date('m') <= 6) {
                            echo date('Y') . '|2';
                        } else {
                            echo (date('Y') + 1) . '|1';
                        }
                        ?>
                    </h2>
                </header>
                <div>
                    <div style="float:left;margin-top: 10px;">
                        <?php echo $this->Html->image('piramide.jpg', array('width' => '300')); ?>
                    </div>
                    <div style="float:left; width: 460px;  padding-top: 10px;" class="content_graph1">
                        <table class="table table_graph">
                            <?php $colspan = 3; ?>
                            <tr>
                                <th colspan="<?php echo $colspan + 1; ?>" style="text-align: center;">RESUMO GERAL</th>
                            </tr>
                            <tr>
                                <th>DESCRIÇÃO</th>
                                <th colspan="2" style="padding-left: 30px;">QUANTIDADE</th>
                            </tr>
                            <tr >
                                <td><b>Total de Leads</b></td>
                                <td colspan="<?php echo $colspan; ?>" style="padding-left: 30px;"><?php echo $all_total_leads; ?></td>
                            </tr>
                            <tr >
                                <td ><b>Vestibular Agendado</b>  </td>
                                <td align="center">
                                    <strong><u>Total</u></strong><br/>
                                    <?php echo $all_vestibular_agendado; ?>
                                </td>
                                <td align="center">
                                    <strong><u>Pago</u></strong><br/>
                                    <?php echo $all_vestibular_agendado_pg; ?>
                                </td>
                                <td align="center">
                                    <strong><u>Não Pago</u></strong><br/>
                                    <?php echo $all_vestibular_agendado_npg; ?>
                                </td>
                            </tr>
                            <tr >
                                <td ><b>Vestibular</b></td>
                                <td align="center" >
                                    <strong><u>Aprovados</u></strong><br/>
                                    <?php echo $all_vestibular_aprovado; ?>
                                </td>
                                <td align="center">
                                    <strong><u>Reprovados</u></strong><br/>
                                    <?php echo $all_vestibular_reprovado; ?>
                                </td>
                                <td align="center">
                                    <strong><u>Não Compareceu</u></strong><br/>
                                    <?php echo $all_vestibular_naocompareceu; ?>
                                </td>
                            </tr>

                            <tr>
                                <td><b>Documentos Entregues</b></td>
                                <td colspan="<?php echo $colspan; ?>" style="padding-left: 30px;"><?php echo $all_documentos_entregue; ?></td>
                            </tr>
                            <tr>
                                <td><b>Matriculados</b></td>
                                <td colspan="<?php echo $colspan; ?>" style="padding-left: 30px;"><?php echo $all_matriculados; ?></td>
                            </tr>
                        </table>
                    </div>

                </div>

            </div>
        </article>



        
        <article class="col-xs-12 col-sm-12 col-md-6 col-lg-6" style="display:none;">

            <!-- Widget ID (each widget will need unique ID)-->
            <div class="jarviswidget" id="wid-id-6" data-widget-editbutton="false">

                <header>
                    <span class="widget-icon"> <i class="fa fa-bar-chart-o"></i> </span>
                    <h2>Leads Detalhado 
                        <?php
                        if (date('m') <= 6) {
                            echo date('Y') . '|2';
                        } else {
                            echo (date('Y') + 1) . '|1';
                        }
                        ?>
                    </h2>

                </header>

                <!-- widget div-->
                <div>

                    <div id="piechart" style="width: 390px; height: 210px; float:left;"></div>
                    <div id="piechart2" style="width: 390px; height: 210px; float:left;"></div>
                    <div id="piechart3" style="width: 390px; height: 210px; float:left;"></div>
                    <div id="piechart4" style="width: 390px; height: 210px; float:left;"></div>

                </div>
                <!-- end widget div -->

            </div>
            <!-- end widget -->

        </article>
        
        
        
        <article class="col-xs-12 col-sm-12 col-md-6 col-lg-6" >
            <div class="jarviswidget" id="wid-id-21" data-widget-editbutton="false" >
                <header>
                    <span class="widget-icon"> <i class="fa fa-bar-chart-o"></i> </span>
                    <h2>Leads x Campanhas <?php
                        if (date('m') <= 6) {
                            echo date('Y') . '|2';
                        } else {
                            echo (date('Y') + 1) . '|1';
                        }
                        ?>
                    </h2>
                </header>

                <div>
                    <div class="jarviswidget-editbox">
                         This area used as dropdown edit box 
                        <input type="text">
                    </div>
                    <div class="widget-body no-padding">
                        <!--<div id="sales-graph" class="chart no-padding"></div>-->
                        <div id="chart-google" class="chart no-padding" style="height:320px;"></div>
                    </div>
                </div>
            </div>
        </article>
        
        
        
        <article class="col-xs-12d col-sm-12 col-md-12 col-lg-12" style="display:none;">
            <div class="jarviswidget" id="wid-id-1" >
                <header>
                    <span class="widget-icon"> <i class="fa fa-bar-chart-o"></i> </span>
                    <h2>Cursos
                        Detalhado | Resumo <?php
                        if (date('m') <= 6) {
                            echo date('Y') . '|2';
                        } else {
                            echo (date('Y') + 1) . '|1';
                        }
                        ?>

                    </h2>
                </header>
                <div>

                    <div style="float:left; width: 100%; padding-top: 10px;" class="content_graph1">
                        <table class="table table_graph2">
                            <tr style="border-bottom: #000 2px solid;border-top: #000 2px solid; font-weight: bold; ">
                                <th>Curso:</th>
                                <th style="text-align:center;">Total de Leads</th>
                                <th style="text-align:center;">
                            <table style="margin-left:20%;">
                                <tr > 
                                    <th colspan="3" style="text-align:center;">Vestibular Agendado</th>
                                </tr>
                                <tr>
                                    <td style="text-align:center; width: 60px;" >Total</td>
                                    <td style="text-align:center;width: 60px;">Pago</td>
                                    <td style="text-align:center;width:60px;">Não Pago</td>
                                </tr>
                            </table>

                            </th>
                            <th style="text-align:center; ">
                            <table style="margin-left:10%;">
                                <tr > 
                                    <th colspan="3" style="text-align:center;">Vestibular</th>
                                </tr>
                                <tr>
                                    <td style="text-align:center; width: 80px;" >Aprovado</td>
                                    <td style="text-align:center;width: 80px;">Reprovado</td>
                                    <td style="text-align:center;width:110px;">Não Compareceu</td>
                                </tr>
                            </table>
                            </th>
                            <th style="text-align:center;">Documentos Entregues</th>
                            <th style="text-align:center;">Matrícula</th>
                            <th style="text-align:center; border-left:1px #000 solid; background-color:#d3d3d3; width: 140px">Total de Vagas</th>
                            <th style="text-align:center; background-color:#d3d3d3; width: 140px">Vagas Disponíveis</th>
                            </tr>
                            <?php
                            $count_linha = 1;
                            foreach ($all_cursos_detalhado as $curso_nome => $curso_detalhado):
                                $color_line = '#FFFFFF';
                                $color_line2 = '#d3d3d3';
                                if ($count_linha % 2 == 0) {
                                    $color_line = '#f8f8f8';
                                    $color_line2 = '#c3c3c3';
                                }

                                echo '<tr style="background-color:' . $color_line . ';">';
                                echo '<td>' . $curso_nome . '</td>';
                                echo '<td style="text-align:center;"> ' . (isset($curso_detalhado['total_leads']) ? $curso_detalhado['total_leads'] : 0) . '</td>';
                                echo '<td style="text-align:center;"> ';
                                echo '  <table style="margin-left:20%;">
                                                    <tr>
                                                        <td style="text-align:center;width: 60px;">' . (isset($curso_detalhado['vestibular_agendado']) ? $curso_detalhado['vestibular_agendado'] : '0') . '</td>
                                                        <td style="text-align:center;width: 60px;">' . (isset($curso_detalhado['vestibular_agendado_pg']) ? $curso_detalhado['vestibular_agendado_pg'] : '0') . '</td>
                                                        <td style="text-align:center;width: 60px;">' . (isset($curso_detalhado['vestibular_agendado_npg']) ? $curso_detalhado['vestibular_agendado_npg'] : '0') . '</td>
                                                    </tr>
                                                </table>';
                                echo '</td>';

                                echo '<td style="text-align:center;"> ';
                                echo '  <table style="margin-left:10%;">
                                                    <tr>
                                                        <td style="text-align:center;width: 80px;">' . (isset($curso_detalhado['vestibular_aprovado']) ? $curso_detalhado['vestibular_aprovado'] : '0') . '</td>
                                                        <td style="text-align:center;width: 80px;">' . (isset($curso_detalhado['vestibular_reprovado']) ? $curso_detalhado['vestibular_reprovado'] : '0') . '</td>
                                                        <td style="text-align:center;width: 110px;">' . (isset($curso_detalhado['vestibular_naocompareceu']) ? $curso_detalhado['vestibular_naocompareceu'] : '0') . '</td>
                                                    </tr>
                                                </table>';
                                echo '</td>';

                                echo '<td style="text-align:center;"> ' . (isset($curso_detalhado['documentos_entregue']) ? $curso_detalhado['documentos_entregue'] : 0) . '</td>';
                                echo '<td style="text-align:center;"> ' . (isset($curso_detalhado['matriculados']) ? $curso_detalhado['matriculados'] : 0) . '</td>';
                                echo '<td style="text-align:center; border-left:1px #000 solid; background-color:' . $color_line2 . ';"> ' . (isset($curso_detalhado['total_vagas']) ? $this->Funcoes->valor_negativo($curso_detalhado['total_vagas']) : 0) . '</td>';
                                echo '<td style="text-align:center; background-color:' . $color_line2 . ';"> ';
                                $total_vagas_disponiveis = (isset($curso_detalhado['total_vagas']) ? $curso_detalhado['total_vagas'] : 0);
                                if (isset($curso_detalhado['matriculados'])):
                                    if (isset($curso_detalhado['total_vagas'])):
                                        $total_vagas_disponiveis = $curso_detalhado['total_vagas'] - $curso_detalhado['matriculados'];
                                    endif;
                                endif;

                                echo $this->Funcoes->valor_negativo($total_vagas_disponiveis);

//                                        if(isset($curso_detalhado['vagas_disponiveis'])):
//                                            echo $this->Funcoes->valor_negativo($curso_detalhado['vagas_disponiveis']);                                        
//                                        else:
//                                            echo (isset($curso_detalhado['total_vagas'])? $this->Funcoes->valor_negativo($curso_detalhado['total_vagas']) : 0);
//                                        endif;
                                echo '</td>';
                                echo '<tr>';
                                $count_linha++;
                            endforeach;
                            ?>

                        </table>
                    </div>

                </div>

            </div>

        </article>

        <!--        <article class="col-xs-12d col-sm-12 col-md-6 col-lg-6">
                    <div class="jarviswidget" id="wid-id-1" >
                        <header>
                            <span class="widget-icon"> <i class="fa fa-bar-chart-o"></i> </span>
                            <h2>Alunos</h2>
                        </header>
                        <div>
                          
                            <div style="float:left; width: 100%; padding-top: 10px;" class="content_graph1">
                                <table class="table table_graph2">
                                    <tr>
                                        <th colspan="6" style="text-align: center;">Detalhado | Resumo mês <?php echo date('m/Y'); ?></th>
                                    </tr>
                                    <tr style="border-top: #000 2px solid; ">
                                        <th>Total de Leads:</th>
                                        <th style="background-color: yellow; color:black;">Caixa Postal</th>
                                        <th style="background-color: red; color:white;">Sem Interesse</th>
                                        <th colspan="3" style="background-color: blue; color:white;">Potencial</th>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td>5</td>
                                        <td>5</td>
                                        <td colspan="3">5</td>
                                    </tr>
                                    
                                    <tr style="border-top: #000 2px solid; ">
                                        <th>Vestibular Agendado:</th>
                                        <th>Compareceu:</th>
                                        <th style="background-color: green; color:white;">Aprovado</th>
                                        <th style="background-color: red; color:white;">Reprovado</th>
                                        <th style="background-color: yellow;">Não compareceu</th>
                                        <th style="background-color: blue; color:white;">Pontencial</th>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td>18</td>
                                        <td>12</td>
                                        <td>6</td>
                                        <td>2</td>
                                        <td>8</td>
                                    </tr>
                                    
                                    <tr style="border-top: #000 2px solid; ">
                                        <th>Documentos Entregues:</th>
                                        <th style="background-color: green; color:white;">Entregues</th>
                                        <th style="background-color: yellow;">Pendente</th>
                                        <th colspan="3" style="background-color: blue; color:white;">Pontencial</th>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td>10</td>
                                        <td>2</td>
                                        <td colspan="3">2</td>
                                    </tr>
                                    
                                    <tr style="border-top: #000 2px solid; ">
                                        <th>Matriculados:</th>
                                        <th style="background-color: green; color:white;">Contrato Assinado</th>
                                        <th style="background-color: yellow;">Pendente Assinatura</th>
                                        <th colspan="3" style="background-color: blue; color:white;">Pontencial</th>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td>2</td>
                                        <td>8</td>
                                        <td colspan="3">8</td>
                                    </tr>
                                  
                                    
                                </table>
                            </div>
                                
                        </div>
                        
                        
                    </div>
                    
                </article>-->




        <!--        <article class="col-xs-12d col-sm-12 col-md-6 col-lg-6">
                    <div class="jarviswidget" id="wid-id-1" >
                        <header>
                            <span class="widget-icon"> <i class="fa fa-bar-chart-o"></i> </span>
                            <h2>Alunos</h2>
                        </header>
                        <div>
                            
                            <div style="float:left; width: 250px; padding-top: 10px;" class="content_graph1">
                                <table class="table table_graph">
                                    <tr>
                                        <th colspan="2" style="text-align: center;">Vestibular Agendado</th>
                                    </tr>
                                    <tr>
                                        <th>Não Compareceu</th>
                                        <th>Compareceu</th>
                                    </tr>
                                    <tr >
                                        <td>10</td>
                                        <td>10</td>
                                       
                                    </tr>
                                    
                                </table>
                            </div>
                            <div style="float:left; width: 250px; padding-top: 10px; margin-left:20px;" class="content_graph1">
                                <table class="table table_graph">
                                    <tr>
                                        <th colspan="2" style="text-align: center;">Documento Entregues</th>
                                    </tr>
                                    <tr>
                                        <th>Não Compareceu</th>
                                        <th>Compareceu</th>
                                    </tr>
                                    <tr >
                                        <td>10</td>
                                        <td>10</td>
                                       
                                    </tr>
                                    
                                </table>
                            </div>
                                
                        </div>
                        
                        
                    </div>
                    
                </article>-->






        <!--        <article class="col-xs-12d col-sm-12 col-md-12 col-lg-12">
                    <div class="jarviswidget" id="wid-id-1" >
                        <header>
                            <span class="widget-icon"> <i class="fa fa-bar-chart-o"></i> </span>
                            <h2>Novos alunos</h2>
                        </header>
                        <div>
                            <div class="jarviswidget-editbox">
                            </div>
                            <div class="widget-body no-padding">
                                <div id="saleschart" class="chart"></div>
                            </div>
                        </div>
                    </div>
                    
                </article>-->

        <!--        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12" >
        
                     Widget ID (each widget will need unique ID)
                    <div class="jarviswidget" id="wid-id-0" data-widget-editbutton="false" >
                         widget options:
                        usage: <div class="jarviswidget" id="wid-id-0" data-widget-editbutton="false">
        
                        data-widget-colorbutton="false"
                        data-widget-editbutton="false"
                        data-widget-togglebutton="false"
                        data-widget-deletebutton="false"
                        data-widget-fullscreenbutton="false"
                        data-widget-custombutton="false"
                        data-widget-collapsed="true"
                        data-widget-sortable="false"
        
                        
                        <header>
                            <span class="widget-icon"> <i class="fa fa-bar-chart-o"></i> </span>
                            <h2>Leads x Campanhas <?php
        if (date('m') <= 6) {
            echo date('Y') . '|2';
        } else {
            echo (date('Y') + 1) . '|1';
        }
        ?>
                            </h2>
        
                        </header>
        
                         widget div
                        <div>
        
                             widget edit box 
                            <div class="jarviswidget-editbox">
                                 This area used as dropdown edit box 
                                <input type="text">
                            </div>
                             end widget edit box 
        
                             widget content 
                            <div class="widget-body no-padding">
        
                                <div id="sales-graph" class="chart no-padding"></div>
        
                            </div>
                             end widget content 
        
                        </div>
                         end widget div 
        
                    </div>
                     end widget 
        
                </article>-->
        <!-- WIDGET END -->


        <!--        <article class="col-xs-6d col-sm-6 col-md-6 col-lg-6">
                    <div class="jarviswidget" id="wid-id-2" data-widget-editbutton="false">
                        <header>
                            <span class="widget-icon"> <i class="fa fa-bar-chart-o"></i> </span>
                            <h2>Status de Alunos (<?php // echo date('m/Y'); ?>)</h2>
                        </header>
                        <div>
                            <div class="jarviswidget-editbox">
                            </div>
                            <div class="widget-body no-padding">
                                <div id="donut-graph" class="chart no-padding"></div>
                            </div>
                        </div>
                    </div>
                    
                </article>-->
    </div>
</div>