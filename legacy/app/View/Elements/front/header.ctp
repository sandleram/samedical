<?php
    $this->start('header');
        echo $this->element('front/header/top');
        echo $this->element('front/header/fast_search');
        echo $this->element('front/header/menu');
    $this->end();
    
    echo '<header>';
    echo $this->fetch('header');
    echo '</header>';
