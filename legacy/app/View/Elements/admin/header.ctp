<!-- possible classes: minified, fixed-ribbon, fixed-header, fixed-width-->

<!-- HEADER -->
<header id="header">

    <div id="logo-group">
        <span id="logo">
            <?php
            $localhost = explode(':', $_SERVER['HTTP_HOST']);
            //            if(in_array($localhost[0],array('localhost'))){
            //                echo $this->Html->image("http://www.victorysolutions.com.br/v4/img/vs/logo_victory.jpg", array("alt" => "ICS ", "title" => "ICS", "style"=>"width:130px !important;", "url" => "/admin"));
            //            }else{
            if ($logoGE != '') {
                echo $this->Html->image($logoGE, array("alt" => " ", "title" => "", "style" => "width:101px !important;     margin-top: -9px; padding-left: 0;", "url" => "/admin"));
            } else {
                // echo $this->Html->image("logo-med-branco.png", array("alt" => "SA Med ", "title" => "SA Med ", "style"=>"width:101px !important;     margin-top: -9px; padding-left: 0;", "url" => "/admin"));
                echo $this->Html->image("logo_samed_pp.png", array("alt" => "SA Med ", "title" => "SA Med ", "style" => "width:134px !important;margin-top: -5px; padding-left: 0;", "url" => "/admin"));
            }

            ?>

        </span>



        <span id="activity" class="activity-dropdown" style="display:none;">
            <i class="fa fa-user"></i>
            <!--<b class="badge"> 21 </b>-->
        </span>

        <!-- AJAX-DROPDOWN : control this dropdown height, look and feel from the LESS variable file -->
        <div class="ajax-dropdown">

            <!-- the ID links are fetched via AJAX to the ajax container "ajax-notifications" -->
            <div class="btn-group btn-group-justified" data-toggle="buttons">
                <label class="btn btn-default" style="display:none;">
                    <input type="radio" name="activity" id="<?php echo Router::url('/ajax/notify/mail.php', true); ?>">
                    Msgs (14)
                </label>
                <label class="btn btn-default">
                    <input type="radio" name="activity" id="<?php echo Router::url('/ajax/notify/notifications.php', true); ?>">
                    Notificações (3)
                </label>
                <label class="btn btn-default" style="display:none;">
                    <input type="radio" name="activity" id="<?php echo Router::url('/ajax/notify/tasks.php', true); ?>">
                    Tarefas (4)
                </label>
            </div>

            <!-- notification content -->
            <div class="ajax-notifications custom-scroll">

                <div class="alert alert-transparent">
                    <h4>Clique em um botão para mostrar mensagens aqui</h4>
                    Esta mensagem página em branco ajuda a proteger a sua privacidade, ou você pode mostrar a primeira mensagem aqui automaticamente.
                </div>''

                <i class="fa fa-lock fa-4x fa-border"></i>

            </div>
            <!-- end notification content -->

            <!-- footer: refresh area -->
            <span> Última atualização em: <?php echo date('d/m/Y H:m'); ?>
                <button type="button" data-loading-text="<i class='fa fa-refresh fa-spin'></i> Loading..." class="btn btn-xs btn-default pull-right">
                    <i class="fa fa-refresh"></i>
                </button> </span>
            <!-- end footer -->
        </div>
        <!-- END AJAX-DROPDOWN -->
    </div>







    <!-- pulled right: nav area -->
    <div class="pull-right">

        <div id="logout" class="btn-header transparent pull-right">
            <span>
                <?php echo $this->Html->link_('', '/usuario/logout', array('title' => 'Logout'), false, '<i class="fa fa-sign-out"></i>'); ?>
            </span>
        </div>


        <!-- end logout button -->

        <!-- collapse menu button -->
        <div id="hide-menu" class="btn-header pull-right">
            <span> <a href="javascript:void(0);" title="Collapse Menu"><i class="fa fa-reorder"></i></a> </span>
        </div>
        <!-- end collapse menu -->

        <!-- search mobile button (this is hidden till mobile view port) -->
        <div id="search-mobile" class="btn-header transparent pull-right">
            <span> <a href="javascript:void(0)" title="Search"><i class="fa fa-search"></i></a> </span>
        </div>
        <!-- end search mobile button -->

        <!-- input: search field -->
        <form action="admin" class="header-search pull-right" method="GET" style="display:none;">
            <input type="text" placeholder="Buscar" id="search-fld" nam="busca">
            <button type="submit">
                <i class="fa fa-search"></i>
            </button>
            <a href="javascript:void(0);" id="cancel-search-js" title="Cancelar Busca"><i class="fa fa-times"></i></a>
        </form>
        <!-- end input: search field -->


    </div>
    <!-- end pulled right: nav area -->

</header>
<!-- END HEADER -->