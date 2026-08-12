<!-- RIBBON -->
<?php echo $this->Element('admin/breadcrumb'); ?>
<!-- END RIBBON -->

<div id="content">
    <?php echo $this->Form->msg($this->Session->flash()); ?>
    <p>&nbsp;</p>
    <h4 style="border-bottom: 1px dotted #d3d3d3; padding-bottom: 5px;" class="titulo_pagina">
        Dashboards BI - Business Inteligence
    </h4>
    <a class="voltar_bi"> Voltar para lista </a>
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
                font-size: 24px;
            }
            .subtitulo_kpi {
                font-size: 12px;
            }

            .result_kpi {
                font-size: 14px;
                margin-top: 4px;
            }

            .titulo_gerencial {
                border-bottom:1px solid #999; 
                font-weight:bold;
                font-weight:bold;
                padding-bottom:6px;
            }

            .voltar_bi{
                font-size: 12px;
                cursor:pointer;
                display:none;
                float:right;
                margin-top:-25px;
                margin-right:12px;
            }
        </style>
        <?php


            $kpi_class = 'col-xs-12 col-sm-6 col-md-4 col-lg-4 kpi';
            $kpi2_class = 'col-xs-12 col-sm-6 col-md-4 col-lg-4 kpi2 ';
            
            $kpi_tit_class = 'col-xs-12 col-sm-12 col-md-12 col-lg-12 titulo_kpi';
            $kpi_subtit_class = 'col-xs-12 col-sm-12 col-md-12 col-lg-12 subtitulo_kpi';
            $kpi_res_class = 'col-xs-12 col-sm-12 col-md-12 col-lg-12 result_kpi';
  
        ?>

        <script>
            $(document).ready(function(){
                
                $('.titulo_kpi').click(function(){
                    rel = $(this).attr('rel');
                    rel_titulo = $(this).attr('rel_titulo');
                    rel_subtitulo = $(this).attr('rel_subtitulo');
                    rel_link = '<iframe src="'+$(this).attr('rel_link')+'" width="100%" height="600" border="0" style="border:none;" />';
                    $('.oculta_bi_all').hide();
                    
                    
                    setTimeout(() => {
                        $('.exibir_lista').hide();
                        $('.exibir_bi_'+rel).show();
                        $('.exibir_bi_'+rel).show();
                        $('.titulo_pagina').hide();
                        $('html').attr('style','overflow-x: unset !important;');

                        if(rel_subtitulo != ''){
                            //$('.titulo_pagina').html('Dashboards BI - Business Inteligence - '+rel_titulo+' <span style="font-size:12px;>">('+rel_subtitulo+') </span>');
                            $('.breadcrumb li:last').html(rel_titulo+' <span style="font-size:12px;>">('+rel_subtitulo+') </span>');
                        }else{
                            //$('.titulo_pagina').html('Dashboards BI - Business Inteligence - '+rel_titulo);
                            $('.breadcrumb li:last').html(rel_titulo);
                        }
                        $('.voltar_bi').show();

                        var parentHeight = $(parent).height() - 140;
                        var iframe2 = rel_link.replace('height="600"', 'height="'+parentHeight+'"'); //var alturaDesejada = parentHeight * 1; // 100% é o mesmo que 1
                       
                        setTimeout(function(){
                            $('.src_iframe').html(iframe2);
                        },1500);
                        
                        
                    }, 300);
                })

                $('.voltar_bi').click(function(){
                    $('.exibir_lista').fadeIn();
                    $('.oculta_bi_all').hide();
                    $('.voltar_bi').hide();
                    $('.titulo_pagina').show();
                    $('.titulo_pagina').html('Dashboards BI - Business Inteligence');
                    $('.breadcrumb li:last').html('Todos');
                    $('.src_iframe').html('');
                    $('html').attr('style','');
                })
            })
            
        </script>
            <div class="rows" style="text-align:center;">
                 <div class="rows exibir_lista" style="display:;" > 
                     <?php
                        $animate = 'result_kpi_animate';
                         
                        foreach($list as $k => $v){
                            echo '	<div class="' . $kpi_class . '"   >
                                        <div class="well well-lg ">
                                            <p class="' . $kpi_tit_class . '" rel="'.$k.'" rel_titulo="'.$v['titulo'].'" rel_subtitulo="'.$v['subtitulo'].'" rel_link="'.$v['link'].'">
                                                <a style="cursor:pointer;">'.$v['titulo'].'</a>
                                            </p>
                                            <p class="' . $kpi_subtit_class . '">
                                                '.$v['subtitulo'].'
                                            </p>
                                        </div>
                                    </div>';
                                    #echo $this->Html->link('Acessar',Router::url('/admin/bi/'.$k,true));
                        }
                    ?>
                </div>

                <?php 
                    foreach($list as $k => $v){ 
                        echo '<div class="rows src_iframe oculta_bi_all exibir_bi_'.$k.'"  style="display:none; float:left; width:100%;"> </div>';
                    }
                ?>
                

            </div>

    </div>

 
    
    
    
</div>


<script type="text/javascript">

    // ENTRAR FINALIZADO
    $(document).ready(function(){
        setTimeout(function(){
            $("body").removeClass("minified");
            $('.minifyme').click();
        },1000);
        
    })
    
</script>