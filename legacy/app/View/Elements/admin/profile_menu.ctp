<!-- SHORTCUT AREA : With large tiles (activated via clicking user name tag)
Note: These tiles are completely responsive,
you can add as many as you like
-->
<?php 
    $menuActive = array('admin'=>'',
                        'admin/usuario/add/'.$_SESSION['Auth']['Usuario']['id'] =>'',
                        'calendarios'=>'',
    );
    $menuActive[$this->params->url] = 'selected';
?>
<div id="shortcut">
        <ul>
                <li>
                    <?php 
                        $title = 'Dashboard';
                        $link = 'admin';
                        $icon = 'fa-coffee';
                        $color = 'bg-color-blueLight';
                        $html = '<span class="iconbox"> <i class="fa '.$icon.' fa-4x"></i> <span> '.$title.' </span> </span>';
                        echo $this->Html->link_($title,'/'.$link, array('title'=>$title, 'class'=>'jarvismetro-tile big-cubes '.$menuActive[$link].' '.$color),false,$html);
                    ?>

                </li>
                <li>
                    <?php 
                        $title = 'Editar Perfil';
                        $link = 'admin/usuario/add/'.$_SESSION['Auth']['Usuario']['id'];
                        $icon = 'fa-user';
                        $color = 'bg-color-pinkDark';
                        $html = '<span class="iconbox"> <i class="fa '.$icon.' fa-4x"></i> <span> '.$title.' </span> </span>';
                        echo $this->Html->link_($title,'/'.$link, array('title'=>$title, 'class'=>'jarvismetro-tile big-cubes '.$menuActive[$link].' '.$color),false,$html);
                    ?>
                </li>
<!--                <li>
                    <?php 
//                        $title = 'Calendário';
//                        $link = 'calendarios';
//                        $icon = 'fa-calendar';
//                        $color = 'bg-color-orangeDark';
//                        $html = '<span class="iconbox"> <i class="fa '.$icon.' fa-4x"></i> <span> '.$title.' </span> </span>';
//                        echo $this->Html->link_($title,'/'.$link, array('title'=>$title, 'class'=>'jarvismetro-tile big-cubes '.$menuActive[$link].' '.$color),false,$html);
                    ?>
                </li>-->
                <?php /*
                    <li>
                            <a href="javascript:void(0);" class="jarvismetro-tile big-cubes  bg-color-purple"> <span class="iconbox"> <i class="fa fa-map-marker fa-4x"></i> <span>Mapas</span> </span> </a>
                    </li>
                    <li>
                            <a href="javascript:void(0);" class="jarvismetro-tile big-cubes  bg-color-blueDark"> <span class="iconbox"> <i class="fa fa-book fa-4x"></i> <span>Processos <span class="label pull-right bg-color-darken">99</span></span> </span> </a>
                    </li>
                    <li>
                            <a href="javascript:void(0);" class="jarvismetro-tile big-cubes  bg-color-greenLight"> <span class="iconbox"> <i class="fa fa-list-alt fa-4x"></i> <span>Currículos </span> </span> </a>
                    </li>
                */?>
                
        </ul>
</div>
<!-- END SHORTCUT AREA -->
