<?php
    
/**
 *
 *
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.View.Layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 */

$cakeDescription = __d('cake_dev', Configure::read('variaveis.project'));
$prefix = 'admin/';
?>

        <!DOCTYPE html>
        <html>
        <head>
                <?php echo $this->Html->charset(); ?>
                <title>
                    <?php echo $cakeDescription ?>:
                    <?php echo $title_for_layout; ?>
                </title>

                <?php
                    echo $this->Html->meta('icon');
                    echo $this->Html->meta('viewport',null,array('name'=>'viewport','content'=>'width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no'));

                    #BASIC CSS
                    echo $this->Html->css($prefix.'bootstrap.min.css');
                    echo $this->Html->css($prefix.'font-awesome.min.css');

                    #SmartAdmin Styles : Please note (smartadmin-production.css) was created using LESS variables
                    echo $this->Html->css($prefix.'smartadmin-production.css');
                    echo $this->Html->css($prefix.'smartadmin-skins.css');
                    echo $this->Html->css($prefix.'plugin/tag-it/jquery.tagit.css');
                    echo $this->Html->css($prefix.'plugin/tag-it/tagit.ui-zendesk.css');
                    echo $this->Html->css($prefix.'plugin/uploadfile/uploadfile.min.css');
                    
                    
                    
//                    echo $this->Html->css('demo.css');

                    #GOOGLE FONT
                    echo $this->Html->css($prefix.'font-google.css');

                    echo $this->fetch('meta');
                    echo $this->fetch('css');
                    
                    #PACE LOADER - turn this on if you want ajax loading to show (caution: uses lots of memory on iDevices
                    echo $this->Html->script($prefix.'plugin/pace/pace.min.js',array('data-pace-options'=>"{ \"restartOnRequestAfter\": true }"));
                    echo $this->Html->script($prefix.'jquery.min.js');
                ?>
        </head>
	<!--<body class=" fixed-header fixed-ribbon fixed-navigation smart-style-3">-->
	<body class=" fixed-header fixed-ribbon smart-style-3">
        <!-- possible classes: minified, fixed-ribbon, fixed-header, fixed-width, fixed-navigation-->
                <style type="text/css">
                
                </style>
            <div id="boxes">
                <div id="dialog2" class="window"></div>
                <div id="mask"></div>
                
                
                <?php 
                
                    #$cache = array('cache'=>true);#DESCOMENTAR
                    $cache = array();#COMENTAR
//                    echo $this->element('admin/header',array(),$cache); 
//                    echo $this->element('admin/menu',array(),$cache); 
                    echo '<div id="" role="">';
                    echo    $this->fetch('content');
//                    echo  $this->element('sql_dump');
                    echo '</div>';
//                    echo $this->element('admin/profile_menu',array(),array('cache'=>false));
//                    echo "<script data-pace-options='{ \"restartOnRequestAfter\": true }' src=\"plugin/pace/pace.min.js\"></script>";
                    
                    echo "  <script>  
                                if (!window.jQuery) { documento.write('".str_replace('</script>','<\/script>',$this->Html->script($prefix.'libs/jquery-2.0.2.min.js'))."');}
                            </script>";
                    echo $this->Html->script($prefix.'jquery-ui.min.js');
                    echo "  <script>  
                                if (!window.jQuery.ui) { documento.write('".str_replace('</script>','<\/script>',$this->Html->script($prefix.'libs/jquery-ui-1.10.3.min.js'))."');}
                            </script>";

                    /**
                     * <!-- JS TOUCH : include this plugin for mobile drag / drop touch events
                            <script src="js/plugin/jquery-touch/jquery.ui.touch-punch.min.js"></script> -->
                     */
                    echo $this->Html->script($prefix.'jquery.maskedinput.js');#MÁSCARA DE CAMPOS JS
//                    echo $this->Html->script('plugin/bootstrap-tags/bootstrap-tagsinput.min.js');#tags
                    echo $this->Html->script($prefix.'plugin/tag-it/tag-it.js');#tags
                    echo $this->Html->script($prefix.'plugin/ckeditor/ckeditor.js');#editor texto
                    echo $this->Html->script($prefix.'plugin/superbox/superbox.min.js');#editor texto
                    echo $this->Html->script($prefix.'plugin/uploadfile/jquery.form.js');#editor texto
                    echo $this->Html->script($prefix.'plugin/uploadfile/uploadfile.min.js');#editor texto
                    echo $this->Html->script($prefix.'ajax_init.js');#AJAX JS
//                    echo $this->Html->script($prefix.'ajax_all.js');#AJAX JS
                    echo $this->Html->script($prefix.'bootstrap/bootstrap.min.js');#BOOTSTRAP JS
                    echo $this->Html->script($prefix.'notification/SmartNotification.min.js');#CUSTOM NOTIFICATION
                    echo $this->Html->script($prefix.'smartwidgets/jarvis.widget.min.js');#JARVIS WIDGETS
                    echo $this->Html->script($prefix.'plugin/easy-pie-chart/jquery.easy-pie-chart.min.js');#EASY PIE CHARTS
                    echo $this->Html->script($prefix.'plugin/sparkline/jquery.sparkline.min.js');#SPARKLINES
                    echo $this->Html->script($prefix.'plugin/jquery-validate/jquery.validate.min.js');#JQUERY VALIDATE
                    echo $this->Html->script($prefix.'plugin/masked-input/jquery.maskedinput.min.js');#JQUERY MASKED INPUT
                    echo $this->Html->script($prefix.'plugin/masked-money/jquery.maskMoney.js');#JQUERY MASKED MONEY INPUT
                    echo $this->Html->script($prefix.'plugin/select2/select2.min.js');#JQUERY SELECT2 INPUT
                    echo $this->Html->script($prefix.'plugin/bootstrap-slider/bootstrap-slider.min.js');#JQUERY UI + Bootstrap Slider
                    echo $this->Html->script($prefix.'plugin/msie-fix/jquery.mb.browser.min.js');#browser msie issue fix
                    echo $this->Html->script($prefix.'plugin/fastclick/fastclick.js');#FastClick: For mobile devices
                    echo '<!--[if IE 7]>';
                    echo '<h1>Seu navegador está desatualizado, por favor atualize acesando www.microsoft.com/download</h1>';
                    echo '<![endif]-->';
//                    echo $this->Html->script('demo.js');#Demo purpose only
                    echo $this->Html->script($prefix.'app.js');#MAIN APP JS FILE

                    #PAGE RELATED PLUGIN(S)
                    #Flot Chart Plugin: Flot Engine, Flot Resizer, Flot Tooltip
                    echo $this->Html->script($prefix.'plugin/flot/jquery.flot.cust.js');
                    echo $this->Html->script($prefix.'plugin/flot/jquery.flot.resize.js');
                    echo $this->Html->script($prefix.'plugin/flot/jquery.flot.tooltip.js');
                    
                    echo $this->Html->script($prefix.'plugin/morris/raphael.2.1.0.min.js');
                    echo $this->Html->script($prefix.'plugin/morris/morris.min.js');
              
                    
//                    <script src="js/plugin/morris/raphael.2.1.0.min.js"></script>
//                    <script src="js/plugin/morris/morris.min.js"></script>
                    
//                    echo $this->Html->script($prefix.'plugin/flot/jquery.flot.cust.js');
//                    echo $this->Html->script($prefix.'plugin/flot/jquery.flot.resize.js');
//                    echo $this->Html->script($prefix.'plugin/flot/jquery.flot.fillbetween.js');
//                    echo $this->Html->script($prefix.'plugin/flot/jquery.flot.orderBar.js');
//                    echo $this->Html->script($prefix.'plugin/flot/jquery.flot.pie.js');
//                    echo $this->Html->script($prefix.'plugin/flot/jquery.flot.tooltip.js');
                    

                    #Vector Maps Plugin: Vectormap engine, Vectormap language
                    echo $this->Html->script($prefix.'plugin/vectormap/jquery-jvectormap-1.2.2.min.js');
                    echo $this->Html->script($prefix.'plugin/vectormap/jquery-jvectormap-world-mill-en.js');

                    #Full Calendar
                    echo $this->Html->script($prefix.'plugin/fullcalendar/jquery.fullcalendar.min.js');
                    
                    #progress
                    echo $this->Html->script($prefix.'plugin/bootstrap-progressbar/bootstrap-progressbar.js');
                    
                    
                    echo '<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>';
                    
                    echo $this->fetch('script');
            
        $controller = $this->params['controller'];
        $action     = $this->params['action'];
        
        #ATUALIZAÇÃO DASHBOARD
       
        ?>
                
            <script  type="text/javascript">
                
               /*CHAMA GRÁFICO DASHBOARD */
                setInterval(reload_page, 30000);
                function reload_page(){
//                    console.log('atualização dashboard2');
                    window.location = location.href;
                }
            </script>
            <script type="text/javascript">
                /* chart colors default */
                var $chrt_border_color = "#efefef";
                var $chrt_grid_color = "#DDD"
                var $chrt_main = "#E24913";
                /* red       */
                var $chrt_second = "#6595b4";
                /* blue      */
                var $chrt_third = "#FF9F01";
                /* orange    */
                var $chrt_fourth = "#7e9d3a";
                /* green     */
                var $chrt_fifth = "#BD362F";
                /* dark red  */
                var $chrt_mono = "#000";

                $(document).ready(function() {
                // DO NOT REMOVE : GLOBAL FUNCTIONS!
                                pageSetUp();

                                /* sales chart */

                                if ($("#saleschart").length) {
                                        var d = [[1196463600000, 0], [1196550000000, 0], [1196636400000, 0], [1196722800000, 77], [1196809200000, 3636], [1196895600000, 3575], [1196982000000, 2736], [1197068400000, 1086], [1197154800000, 676], [1197241200000, 1205], [1197327600000, 906], [1197414000000, 710], [1197500400000, 639], [1197586800000, 540], [1197673200000, 435], [1197759600000, 301], [1197846000000, 575], [1197932400000, 481], [1198018800000, 591], [1198105200000, 608], [1198191600000, 459], [1198278000000, 234], [1198364400000, 1352], [1198450800000, 686], [1198537200000, 279], [1198623600000, 449], [1198710000000, 468], [1198796400000, 392], [1198882800000, 282], [1198969200000, 208], [1199055600000, 229], [1199142000000, 177], [1199228400000, 374], [1199314800000, 436], [1199401200000, 404], [1199487600000, 253], [1199574000000, 218], [1199660400000, 476], [1199746800000, 462], [1199833200000, 500], [1199919600000, 700], [1200006000000, 750], [1200092400000, 600], [1200178800000, 500], [1200265200000, 900], [1200351600000, 930], [1200438000000, 1200], [1200524400000, 980], [1200610800000, 950], [1200697200000, 900], [1200783600000, 1000], [1200870000000, 1050], [1200956400000, 1150], [1201042800000, 1100], [1201129200000, 1200], [1201215600000, 1300], [1201302000000, 1700], [1201388400000, 1450], [1201474800000, 1500], [1201561200000, 546], [1201647600000, 614], [1201734000000, 954], [1201820400000, 1700], [1201906800000, 1800], [1201993200000, 1900], [1202079600000, 2000], [1202166000000, 2100], [1202252400000, 2200], [1202338800000, 2300], [1202425200000, 2400], [1202511600000, 2550], [1202598000000, 2600], [1202684400000, 2500], [1202770800000, 2700], [1202857200000, 2750], [1202943600000, 2800], [1203030000000, 3245], [1203116400000, 3345], [1203202800000, 3000], [1203289200000, 3200], [1203375600000, 3300], [1203462000000, 3400], [1203548400000, 3600], [1203634800000, 3700], [1203721200000, 3800], [1203807600000, 4000], [1203894000000, 4500]];

                                        for (var i = 0; i < d.length; ++i)
                                                d[i][0] += 60 * 60 * 1000;

                                        function weekendAreas(axes) {
                                                var markings = [];
                                                var d = new Date(axes.xaxis.min);
                                                // go to the first Saturday
                                                d.setUTCDate(d.getUTCDate() - ((d.getUTCDay() + 1) % 7))
                                                d.setUTCSeconds(0);
                                                d.setUTCMinutes(0);
                                                d.setUTCHours(0);
                                                var i = d.getTime();
                                                do {
                                                        // when we don't set yaxis, the rectangle automatically
                                                        // extends to infinity upwards and downwards
                                                        markings.push({
                                                                xaxis : {
                                                                        from : i,
                                                                        to : i + 2 * 24 * 60 * 60 * 1000
                                                                }
                                                        });
                                                        i += 7 * 24 * 60 * 60 * 1000;
                                                } while (i < axes.xaxis.max);

                                                return markings;
                                        }

                                        var options = {
                                                xaxis : {
                                                        mode : "time",
                                                        tickLength : 5
                                                },
                                                series : {
                                                        lines : {
                                                                show : true,
                                                                lineWidth : 1,
                                                                fill : true,
                                                                fillColor : {
                                                                        colors : [{
                                                                                opacity : 0.1
                                                                        }, {
                                                                                opacity : 0.15
                                                                        }]
                                                                }
                                                        },
                                                        //points: { show: true },
                                                        shadowSize : 0
                                                },
                                                selection : {
                                                        mode : "x"
                                                },
                                                grid : {
                                                        hoverable : true,
                                                        clickable : true,
                                                        tickColor : $chrt_border_color,
                                                        borderWidth : 0,
                                                        borderColor : $chrt_border_color,
                                                },
                                                tooltip : true,
                                                tooltipOpts : {
                                                        content : "Usuários Novos <b>%x</b> - <span>%y</span>",
                                                        dateFormat : "%y-%0m-%0d",
                                                        defaultTheme : false
                                                },
                                                colors : [$chrt_second],

                                        };

                                        var plot = $.plot($("#saleschart"), [d], options);
                                };

                                /* end sales chart */
                                
                                // donut
				if ($('#donut-graph').length) {
					Morris.Donut({
						element : 'donut-graph',
						data : [{
							value : 70,
							label : 'Aguardando Contato'
						}, {
							value : 15,
							label : 'Reagendamento'
						}, {
							value : 10,
							label : 'Documentação Pendente'
						}, {
							value : 5,
							label : 'Matriculados'
						}],
						formatter : function(x) {
							return x + "%"
						}
					});
				}

//https://morrisjs.github.io/morris.js/lines.html
//                                Morris.Line({
                                Morris.Area({
				element : 'sales-graph',
                                data: [<?php echo $dadosCampanha;?>],
//				data : [{
//					period : '2010 Q1',
//					iphone : 2666,
//					ipad : null,
//					itouch : 2647
//				}, {
//					period : '2010 Q2',
//					iphone : 2778,
//					ipad : 2294,
//					itouch : 2441
//				}, {
//					period : '2010 Q3',
//					iphone : 4912,
//					ipad : 1969,
//					itouch : 2501
//				}, {
//					period : '2010 Q4',
//					iphone : 3767,
//					ipad : 3597,
//					itouch : 5689
//				}, {
//					period : '2011 Q1',
//					iphone : 6810,
//					ipad : 1914,
//					itouch : 2293
//				}, {
//					period : '2011 Q2',
//					iphone : 5670,
//					ipad : 4293,
//					itouch : 1881
//				}, {
//					period : '2011 Q3',
//					iphone : 4820,
//					ipad : 3795,
//					itouch : 1588
//				}, {
//					period : '2011 Q4',
//					iphone : 15073,
//					ipad : 5967,
//					itouch : 5175
//				}, {
//					period : '2012 Q1',
//					iphone : 10687,
//					ipad : 4460,
//					itouch : 2028
//				}, {
//					period : '2012 -',
//					iphone : 8432,
//					ipad : 5713,
//					itouch : 1791
//				},],
                                
				xkey : 'period',
				ykeys : [<?php echo $ykeys;?>],
				labels : [<?php echo $labels;?>],
                                xLabelFormat: function(d) { return (d.getMonth()+1)+'/'+d.getFullYear(); },
//                                xLabelFormat: function(d) { return d.getDate()+'/'+(d.getMonth()+1)+'/'+d.getFullYear(); },
				pointSize : 2,
				hideHover : 'auto',
//                                dateFormat: function (d) {return d.getDate()+'/'+(d.getMonth()+1)+'/'+d.getFullYear();},

			});
                                
//                                if ($('#sales-graph').length) {
//
//					Morris.Area({
//						element : 'sales-graph',
//						data : [{
//							period : '2010 Q1',
//							iphone : 2666,
//							ipad : null,
//						}, {
//							period : '2010 Q2',
//							iphone : 2778,
//							ipad : 2294,
//						}, {
//							period : '2010 Q3',
//							iphone : 4912,
//							ipad : 1969,
//						}, {
//							period : '2010 Q4',
//							iphone : 3767,
//							ipad : 3597,
//						}, {
//							period : '2011 Q1',
//							iphone : 6810,
//							ipad : 1914,
//						}, {
//							period : '2011 Q2',
//							iphone : 5670,
//							ipad : 10000,
//						}, {
//							period : '2011 Q3',
//							iphone : 4820,
//							ipad : 3795,
//						}, {
//							period : '2011 Q4',
//							iphone : 15073,
//							ipad : 5967,
//						}, {
//							period : '2012 Q1',
//							iphone : 10687,
//							ipad : 4460,
//						}, {
//							period : '2012 Q2',
//							iphone : 8432,
//							ipad : 5713,
//						}],
//						xkey : 'period',
//						ykeys : ['iphone', 'ipad'],
//						labels : ['Movimento Cidadania', 'Outros'],
//						pointSize : 2,
//						hideHover : 'auto'
//					});
//
//				}
                                
                                

                });

                /* end flot charts */
                
                
                
                
                
                
            </script>
            <script type="text/javascript" src="https://www.google.com/jsapi"></script>
            <script type="text/javascript">
              google.load("visualization", "1", {packages:["corechart"]});
              google.setOnLoadCallback(drawChart);
              function drawChart() {
                var data_controller = <?php echo $all_leads_detalhado;?>;
//                console.log(data_controller);
                
                var data = google.visualization.arrayToDataTable([
                  ['Task', 'Hours per Day'],
                  ['Sem Interesse',  data_controller['total_leads']['sem_interesse']],
                  ['Potencial',      data_controller['total_leads']['potencial']]
                ]);

                var options = {
                  title: 'Total de Leads'
                };

                var chart = new google.visualization.PieChart(document.getElementById('piechart'));
                chart.draw(data, options);
                
                
                
                var data2 = google.visualization.arrayToDataTable([
                  ['Task', 'Hours per Day'],
                  ['Compareceu',        data_controller['vestibular_agendado']['compareceu']],
                  ['Aprovado',          data_controller['vestibular_agendado']['aprovado']],
                  ['Reprovado',         data_controller['vestibular_agendado']['reprovado']],
                  ['Não compareceu',    data_controller['vestibular_agendado']['nao_compareceu']],
                  ['Pontencial',        data_controller['vestibular_agendado']['potencial']]
                ]);

                var options2 = {
                  title: 'Vestibular Agendado'
                };
                                
                var chart2 = new google.visualization.PieChart(document.getElementById('piechart2'));
                chart2.draw(data2, options2);
                
                
                 var data3 = google.visualization.arrayToDataTable([
                  ['Task', 'Hours per Day'],
                  ['Entregues',     data_controller['documentos_entregues']['entregue']],
                  ['Pendente',      data_controller['documentos_entregues']['pendente']],
                  ['Pontencial',    data_controller['documentos_entregues']['potencial']]
                ]);

                var options3 = {
                  title: 'Documentos Entregues'
                };
                var chart3 = new google.visualization.PieChart(document.getElementById('piechart3'));
                chart3.draw(data3, options3);
                
                
                
                 var data4 = google.visualization.arrayToDataTable([
                  ['Task', 'Hours per Day'],
                  ['Contrato Assinado',     data_controller['matriculados']['matriculado']],
                  ['Pendente Assinatura',   data_controller['matriculados']['pendente']],
                  ['Pontencial',            data_controller['matriculados']['potencial']]
                ]);

                var options4 = {
                  title: 'Matriculados'
                };
                var chart4 = new google.visualization.PieChart(document.getElementById('piechart4'));
                chart4.draw(data4, options4);
                
                
                
              }
            </script>
            <script type="text/javascript">
                google.charts.load("current", {packages:['corechart']});
                google.charts.setOnLoadCallback(drawChart);
                function drawChart() {
                    var data = google.visualization.arrayToDataTable([
                      ["Element", "Leads", { role: "style" } ],
//                      ["Copper", 8.94, "#b87333"],
//                      ["Silver", 10.49, "silver"],
//                      ["Gold", 19.30, "gold"],
//                      ["Platinum", 21.45, "color: #e5e4e2"],
                        <?php echo $leads_x_campanha;?>
                    ]);

                    var view = new google.visualization.DataView(data);
                    view.setColumns([0, 1,
                                     { calc: "stringify",
                                       sourceColumn: 1,
                                       type: "string",
                                       role: "annotation" },
                                     2]);

                    var options = {
                      title: "Leads Inclusos nos últimos 3 meses",
                      width: 500,
                      height: 250,
                      bar: {groupWidth: "95%"},
                      legend: { position: "none" },
                    };
                    var chart = new google.visualization.ColumnChart(document.getElementById("chart-google"));
                    chart.draw(view, options);
                }
            </script>
            </div>
	</body>
</html>