<?php echo $this->Element('admin/breadcrumb');?>
<div id="content">
    <section id="widget-grid" class="">
        
        <div class="row">
            <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="jarviswidget jarviswidget-color-blue" id="wid-id-11" data-widget-colorbutton="false" data-widget-editbutton="false"  data-widget-deletebutton="false" data-widget-togglebutton="false">
                    <header>
                        <span class="widget-icon"> <i class="fa fa-list"></i> </span>
                        <h2>Todos <?php echo $this->Funcoes->titulos($this->params['controller'],true);?></h2>
                    </header>
                    
                    <!--BEGIN - FILTRO -->
                    <div class="row">
                         <?php
                            $retorno = $this->Form->msg($this->Session->flash());
                        ?>
                           
                    </div>
                    <!--END - FILTRO -->

                    <!--BEGIN - LISTA-->
                    <div>
                        <div class="widget-body ">
                            <?php
                                echo $this->Funcoes->menus('geral',$permissao);
                            ?>
                            <div class="row">
                                <?php echo $retorno;?>
                            </div>
                        </div>
                    </div>
                <!--END - LISTA-->
                
                </div>
            </article>
        </div>
    </section>

</div>