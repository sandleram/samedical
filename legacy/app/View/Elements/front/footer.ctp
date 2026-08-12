<?php
    $this->start('footer');
        echo $this->element('front/footer/prefooter');
        echo $this->element('front/footer/footer');
    $this->end();
    echo $this->fetch('footer');
    