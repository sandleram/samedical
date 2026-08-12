<!-- RIBBON -->
<?php echo $this->Element('admin/breadcrumb'); ?>
<!-- END RIBBON -->

<div id="content">
    <?php echo $this->Form->msg($this->Session->flash()); ?>
    <p>&nbsp;</p>
    <h4 style="border-bottom: 1px dotted #d3d3d3; padding-bottom: 5px;">
        <?php 
        if($logoGE != ''){
            echo $this->Html->image($logoGE, array("alt" => "", "title" => "", "style"=>"width:101px !important; margin-top: -9px; padding-left: 0; float:right;", "url" => "/admin"));
        }
        ?>
    
        Sistemas de Gerenciamento Médico
    </h4>
    <div class="row" style="margin-top:20px;">
        <style>
           

            .kpi .well{
                border-radius: 8px;
                height:130px;
                -webkit-box-shadow: 0px 4px 9px 0px #999999;
                box-shadow: 0px 4px 9px 0px #999999;
            }
            .kpi2 .well{
                border-radius: 8px;
                height:150px;
                -webkit-box-shadow: 0px 4px 9px 0px #999999;
                box-shadow: 0px 4px 9px 0px #999999;
            }

            .titulo_kpi {
                font-size: 14px;
            }

            .result_kpi {
                font-size: 40px;
                margin-top: 4px;
            }

            .titulo_gerencial {
                border-bottom:1px solid #999; 
                font-weight:bold;
                font-weight:bold;
                padding-bottom:6px;
            }
        </style>
        <?php

            $kpi_class = 'col-xs-12 col-sm-6 col-md-4 col-lg-4 kpi ';
            $kpi2_class = 'col-xs-12 col-sm-6 col-md-4 col-lg-4 kpi2 ';
            
            $kpi_tit_class = 'col-xs-12 col-sm-12 col-md-12 col-lg-12 titulo_kpi';
            $kpi_res_class = 'col-xs-12 col-sm-12 col-md-12 col-lg-12 result_kpi';

            /* CRIAÇÃO PARA KPI COM DOIS TITULOS */
            $kpi_tit2_class = 'col-xs-2 col-sm-2 col-md-2 col-lg-2 titulo2_kpi';
            $kpi_res2_class = 'col-xs-8 col-sm-8 col-md-8 col-lg-8 result2_kp';
            
            
        ?>
            <div class="rows" style="margin-top:20px; text-align:center;">
                <div class="rows" > 
                    <?php
                    if(isset($row['charts']['kpi']) && $row['charts']['kpi'] >= 0){
                        foreach ($row['charts']['kpi'] as $kkpi => $kpi) {
                            $animate = '';
                            if(in_array($kkpi,['total_beneficiarios'])){
                                $animate = 'result_kpi_animate';
                            }

                            echo '	<div class="' . $kpi_class . '">
                                        <div class="well well-lg ">
                                            <p class="' . $kpi_tit_class . '">
                                                ' . $kpi['titulo'] . '
                                            </p>
                                            <p class="' . $kpi_res_class . ' '.$animate.'" rel_result = "' . $kpi['valor'] . '">';

                            if($kpi['url'] != ''){
                                echo $this->Html->link($kpi['valor'],$kpi['url']);
                            }else{
                                echo $kpi['valor'];
                            }
                            echo '			</p>
                                        </div>
                                    </div>';
                        }
                    }
                    ?>
                </div>

                <div class="rows">
                    <?php
                        if(isset($row['charts']['kpi_gerencial'])){
                            echo '<h2 class="titulo_gerencial" >Gerencial</h2>';
                            foreach ($row['charts']['kpi_gerencial'] as $kkpi => $kpi) {
                                $animate = '';
                                if(in_array($kkpi,['total_beneficiarios'])){
                                    $animate = 'result_kpi_animate';
                                }

                                echo '	<div class="' . $kpi2_class . '">
                                            <div class="well well-lg ">
                                                <p class="' . $kpi_tit_class . '">
                                                    ' . $kpi['titulo'] . '
                                                </p>
                                                <p class="' . $kpi_res_class . ' '.$animate.'" rel_result = "' . $kpi['valor'] . '">';

                                if($kpi['url'] != ''){
                                    echo $this->Html->link($kpi['valor'],$kpi['url']);
                                }else{
                                    echo $kpi['valor'];
                                }
                                echo '			</p>
                                            </div>
                                        </div>';
                            }
                        }
                    ?>
                </div>
            </div>

    </div>

    <div class="row" style="margin-top:20px;">
        
        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12" >
            <div class="jarviswidget " id="wid-id-2" data-widget-editbutton="false">
                <header>
                    <span class="widget-icon"> <i class="fa fa-bar-chart-o"></i> </span>
                    <h2>Importações</h2>
                </header>
                <div>
                    <div class="widget-body no-padding">
                        
                        <div class="table-responsive">
                        
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Cliente</th>
                                        <th>Importação</th>
                                        <th style="text-align:center;">Qtd. Mensais</th>
                                        <th style="text-align:center;">Qtd. Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                        foreach($notificacoes['afastado'] as $cliente_id => $resposta){
                                            if(isset($selectCliente[$cliente_id])){
                                                $sparkline = implode(',',$resposta);
                                                $soma = array_sum($resposta);
                                                echo "<tr>";
                                                echo " <td> {$selectCliente[$cliente_id]} </td>";
                                                echo " <td> Afastados </td>";
                                                echo ' <td style="text-align:center;"> 
                                                        <span class="sparkline txt-color-blue" data-sparkline-type="bar" data-sparkline-width="50px" data-sparkline-barwidth="3" data-sparkline-height="15px">'.$sparkline.'</span>
                                                    </td>';
                                                echo " <td style='text-align:center;'> {$soma} </td>";
                                                echo "</tr>";
                                            }
                                        }

                                        foreach($notificacoes['beneficio_previdenciario'] as $cliente_id => $resposta){
                                            if(isset($selectCliente[$cliente_id])){
                                                $sparkline = implode(',',$resposta);
                                                $soma = array_sum($resposta);
                                                echo "<tr>";
                                                echo " <td> {$selectCliente[$cliente_id]} </td>";
                                                echo " <td> Benefício Previdenciário</td>";
                                                echo ' <td style="text-align:center;"> 
                                                        <span class="sparkline txt-color-blue" data-sparkline-type="bar" data-sparkline-width="50px" data-sparkline-barwidth="3" data-sparkline-height="15px">'.$sparkline.'</span>
                                                    </td>';
                                                echo " <td style='text-align:center;'> {$soma} </td>";
                                                echo "</tr>";
                                            }   
                                        }

                                        foreach($notificacoes['absenteismo'] as $cliente_id => $resposta){
                                            if(isset($selectCliente[$cliente_id])){
                                                $sparkline = implode(',',$resposta);
                                                $soma = array_sum($resposta);
                                                echo "<tr>";
                                                echo " <td> {$selectCliente[$cliente_id]} </td>";
                                                echo " <td> Absenteísmo </td>";
                                                echo ' <td style="text-align:center;"> 
                                                        <span class="sparkline txt-color-blue" data-sparkline-type="bar" data-sparkline-width="50px" data-sparkline-barwidth="3" data-sparkline-height="15px">'.$sparkline.'</span>
                                                    </td>';
                                                echo " <td style='text-align:center;'> {$soma} </td>";
                                                echo "</tr>";
                                            }

                                        }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </article>
        
    </div>
    
    
</div>

<?php
#BEGIN - NOTIFICAÇÃO DE MANUTENÇÃO DO SERVIDOR
if ($exibeNotificacao) {
    echo '<div id="dialog-message" title="Warning System">
                <p >Notificamos através desta mensagem, que será realizado uma manutenção no servidor.</p>
                <p>A manutenção está programada para ocorrer no dia 17/07/2026, iniciando às 22h00min e com previsão de término às 02h00min do dia 18/07/2026.</p>
                <p>Durante este processo, o sistema ficará indisponível por cerca de 05 minutos e por algumas vezes, poderá apresentar instabilidade.</p>

                <div class="hr hr-12 hr-double"></div>
            </div>';
}
#END - NOTIFICAÇÃO DE MANUTENÇÃO DO SERVIDOR
?>


<script>
		
		// DO NOT REMOVE : GLOBAL FUNCTIONS!
		
		$(document).ready(function() {
		
			/*
			 * CONVERT DIALOG TITLE TO HTML
			 * REF: http://stackoverflow.com/questions/14488774/using-html-in-a-dialogs-title-in-jquery-ui-1-10
			 */
			$.widget("ui.dialog", $.extend({}, $.ui.dialog.prototype, {
				_title : function(title) {
					if (!this.options.title) {
						title.html("&#160;");
					} else {
						title.html(this.options.title);
					}
				}
			}));
		
		
			$("#dialog-message").dialog({
				autoOpen : true,
				modal : true,
                width: 500,
				title : "<div class='widget-header' ><h4 style='color: #ff2600;'><i class='icon-ok'></i>Aviso do Sistema</h4></div>",
				buttons : [ {
					html : "<i class='fa fa-check'></i>&nbsp; OK",
					"class" : "btn btn-primary",
					click : function() {
						$(this).dialog("close");
					}
				}]
		
			});
		

		})

		</script>

