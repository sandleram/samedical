<?php
    if(isset($paginas['Banner']) && count($paginas['Banner']) > 0):
?>
<div class="medium-8 columns" style="margin-top:40px;">
    <ul class="bxslider">
        <?php
            foreach ($paginas['Banner'] as $vBanner):
                echo '<li>';#<li class="black">
//                echo '<h3 class="banner-ofertas">Promoção <small> > '.$vBannerDest['titulo'].'</small></h3>';
                echo '<h3 class="banner-ofertas"><small>'.$vBanner['titulo'].'</small></h3>';
                echo $this->Html->image("uploads/banner/" . $vBanner['imagem'], array("alt" => $vBanner['titulo'], "title" => $vBanner['descricao'], "url"=>$vBanner['link']));
                
                echo '</li>';
            endforeach;
        ?>
    </ul>
</div>

<?php
    endif;
?>