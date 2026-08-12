<?php
/**
 *
 *
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.View.Layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 */
$cakeDescription = __d('cake_dev', Configure::read('variaveis.project'));
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
        echo $this->Html->meta('viewport', null, array('name' => 'viewport', 'content' => 'width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no'));

#BASIC CSS
        echo $this->Html->css('bootstrap.min.css');
        echo $this->Html->css('font-awesome.min.css');

#SmartAdmin Styles : Please note (smartadmin-production.css) was created using LESS variables
        echo $this->Html->css('smartadmin-production.css');
        echo $this->Html->css('smartadmin-skins.css');
        echo $this->Html->css('demo.css');
        if ($this->params['action'] == 'lock'):
            echo $this->Html->css('lockscreen.css');
        endif;

#GOOGLE FONT
        echo $this->Html->css('font-google.css');

        echo $this->fetch('meta');
        echo $this->fetch('css');
        ?>
        
        <style>
    .error-text-2 {
        text-align: center;
        font-size: 700%;
        font-weight: bold;
        font-weight: 100;
        color: #333;
        line-height: 1;
        letter-spacing: -.05em;
        background-image: -webkit-linear-gradient(92deg,#333,#ed1c24);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    .particle {
        position: absolute;
        top: 50%;
        left: 50%;
        width: 1rem;
        height: 1rem;
        border-radius: 100%;
        background-color: #ed1c24;
        background-image: -webkit-linear-gradient(rgba(0,0,0,0),rgba(0,0,0,.3) 75%,rgba(0,0,0,0));
        box-shadow: inset 0 0 1px 1px rgba(0,0,0,.25);
    }
    .particle--a {
        -webkit-animation: particle-a 1.4s infinite linear;
        -moz-animation: particle-a 1.4s infinite linear;
        -o-animation: particle-a 1.4s infinite linear;
        animation: particle-a 1.4s infinite linear;
    }
    .particle--b {
        -webkit-animation: particle-b 1.3s infinite linear;
        -moz-animation: particle-b 1.3s infinite linear;
        -o-animation: particle-b 1.3s infinite linear;
        animation: particle-b 1.3s infinite linear;
        background-color: #00A300;
    }
    .particle--c {
        -webkit-animation: particle-c 1.5s infinite linear;
        -moz-animation: particle-c 1.5s infinite linear;
        -o-animation: particle-c 1.5s infinite linear;
        animation: particle-c 1.5s infinite linear;
        background-color: #57889C;
    }@-webkit-keyframes particle-a {
        0% {
            -webkit-transform: translate3D(-3rem,-3rem,0);
            z-index: 1;
            -webkit-animation-timing-function: ease-in-out;
        } 25% {
            width: 1.5rem;
            height: 1.5rem;
        }

        50% {
            -webkit-transform: translate3D(4rem, 3rem, 0);
            opacity: 1;
            z-index: 1;
            -webkit-animation-timing-function: ease-in-out;
        }

        55% {
            z-index: -1;
        }

        75% {
            width: .75rem;
            height: .75rem;
            opacity: .5;
        }

        100% {
            -webkit-transform: translate3D(-3rem,-3rem,0);
            z-index: -1;
        }
    }

    @-moz-keyframes particle-a {
        0% {
            -moz-transform: translate3D(-3rem,-3rem,0);
            z-index: 1;
            -moz-animation-timing-function: ease-in-out;
        }

        25% {
            width: 1.5rem;
            height: 1.5rem;
        }

        50% {
            -moz-transform: translate3D(4rem, 3rem, 0);
            opacity: 1;
            z-index: 1;
            -moz-animation-timing-function: ease-in-out;
        }

        55% {
            z-index: -1;
        }

        75% {
            width: .75rem;
            height: .75rem;
            opacity: .5;
        }

        100% {
            -moz-transform: translate3D(-3rem,-3rem,0);
            z-index: -1;
        }
    }

    @-o-keyframes particle-a {
        0% {
            -o-transform: translate3D(-3rem,-3rem,0);
            z-index: 1;
            -o-animation-timing-function: ease-in-out;
        }

        25% {
            width: 1.5rem;
            height: 1.5rem;
        }

        50% {
            -o-transform: translate3D(4rem, 3rem, 0);
            opacity: 1;
            z-index: 1;
            -o-animation-timing-function: ease-in-out;
        }

        55% {
            z-index: -1;
        }

        75% {
            width: .75rem;
            height: .75rem;
            opacity: .5;
        }

        100% {
            -o-transform: translate3D(-3rem,-3rem,0);
            z-index: -1;
        }
    }

    @keyframes particle-a {
        0% {
            transform: translate3D(-3rem,-3rem,0);
            z-index: 1;
            animation-timing-function: ease-in-out;
        }

        25% {
            width: 1.5rem;
            height: 1.5rem;
        }

        50% {
            transform: translate3D(4rem, 3rem, 0);
            opacity: 1;
            z-index: 1;
            animation-timing-function: ease-in-out;
        }

        55% {
            z-index: -1;
        }

        75% {
            width: .75rem;
            height: .75rem;
            opacity: .5;
        }

        100% {
            transform: translate3D(-3rem,-3rem,0);
            z-index: -1;
        }
    }

    @-webkit-keyframes particle-b {
        0% {
            -webkit-transform: translate3D(3rem,-3rem,0);
            z-index: 1;
            -webkit-animation-timing-function: ease-in-out;
        }

        25% {
            width: 1.5rem;
            height: 1.5rem;
        }

        50% {
            -webkit-transform: translate3D(-3rem, 3.5rem, 0);
            opacity: 1;
            z-index: 1;
            -webkit-animation-timing-function: ease-in-out;
        }

        55% {
            z-index: -1;
        }

        75% {
            width: .5rem;
            height: .5rem;
            opacity: .5;
        }

        100% {
            -webkit-transform: translate3D(3rem,-3rem,0);
            z-index: -1;
        }
    }

    @-moz-keyframes particle-b {
        0% {
            -moz-transform: translate3D(3rem,-3rem,0);
            z-index: 1;
            -moz-animation-timing-function: ease-in-out;
        }

        25% {
            width: 1.5rem;
            height: 1.5rem;
        }

        50% {
            -moz-transform: translate3D(-3rem, 3.5rem, 0);
            opacity: 1;
            z-index: 1;
            -moz-animation-timing-function: ease-in-out;
        }

        55% {
            z-index: -1;
        }

        75% {
            width: .5rem;
            height: .5rem;
            opacity: .5;
        }

        100% {
            -moz-transform: translate3D(3rem,-3rem,0);
            z-index: -1;
        }
    }

    @-o-keyframes particle-b {
        0% {
            -o-transform: translate3D(3rem,-3rem,0);
            z-index: 1;
            -o-animation-timing-function: ease-in-out;
        }

        25% {
            width: 1.5rem;
            height: 1.5rem;
        }

        50% {
            -o-transform: translate3D(-3rem, 3.5rem, 0);
            opacity: 1;
            z-index: 1;
            -o-animation-timing-function: ease-in-out;
        }

        55% {
            z-index: -1;
        }

        75% {
            width: .5rem;
            height: .5rem;
            opacity: .5;
        }

        100% {
            -o-transform: translate3D(3rem,-3rem,0);
            z-index: -1;
        }
    }

    @keyframes particle-b {
        0% {
            transform: translate3D(3rem,-3rem,0);
            z-index: 1;
            animation-timing-function: ease-in-out;
        }

        25% {
            width: 1.5rem;
            height: 1.5rem;
        }

        50% {
            transform: translate3D(-3rem, 3.5rem, 0);
            opacity: 1;
            z-index: 1;
            animation-timing-function: ease-in-out;
        }

        55% {
            z-index: -1;
        }

        75% {
            width: .5rem;
            height: .5rem;
            opacity: .5;
        }

        100% {
            transform: translate3D(3rem,-3rem,0);
            z-index: -1;
        }
    }

    @-webkit-keyframes particle-c {
        0% {
            -webkit-transform: translate3D(-1rem,-3rem,0);
            z-index: 1;
            -webkit-animation-timing-function: ease-in-out;
        }

        25% {
            width: 1.3rem;
            height: 1.3rem;
        }

        50% {
            -webkit-transform: translate3D(2rem, 2.5rem, 0);
            opacity: 1;
            z-index: 1;
            -webkit-animation-timing-function: ease-in-out;
        }

        55% {
            z-index: -1;
        }

        75% {
            width: .5rem;
            height: .5rem;
            opacity: .5;
        }

        100% {
            -webkit-transform: translate3D(-1rem,-3rem,0);
            z-index: -1;
        }
    }

    @-moz-keyframes particle-c {
        0% {
            -moz-transform: translate3D(-1rem,-3rem,0);
            z-index: 1;
            -moz-animation-timing-function: ease-in-out;
        }

        25% {
            width: 1.3rem;
            height: 1.3rem;
        }

        50% {
            -moz-transform: translate3D(2rem, 2.5rem, 0);
            opacity: 1;
            z-index: 1;
            -moz-animation-timing-function: ease-in-out;
        }

        55% {
            z-index: -1;
        }

        75% {
            width: .5rem;
            height: .5rem;
            opacity: .5;
        }

        100% {
            -moz-transform: translate3D(-1rem,-3rem,0);
            z-index: -1;
        }
    }

    @-o-keyframes particle-c {
        0% {
            -o-transform: translate3D(-1rem,-3rem,0);
            z-index: 1;
            -o-animation-timing-function: ease-in-out;
        }

        25% {
            width: 1.3rem;
            height: 1.3rem;
        }

        50% {
            -o-transform: translate3D(2rem, 2.5rem, 0);
            opacity: 1;
            z-index: 1;
            -o-animation-timing-function: ease-in-out;
        }

        55% {
            z-index: -1;
        }

        75% {
            width: .5rem;
            height: .5rem;
            opacity: .5;
        }

        100% {
            -o-transform: translate3D(-1rem,-3rem,0);
            z-index: -1;
        }
    }

    @keyframes particle-c {
        0% {
            transform: translate3D(-1rem,-3rem,0);
            z-index: 1;
            animation-timing-function: ease-in-out;
        }

        25% {
            width: 1.3rem;
            height: 1.3rem;
        }

        50% {
            transform: translate3D(2rem, 2.5rem, 0);
            opacity: 1;
            z-index: 1;
            animation-timing-function: ease-in-out;
        }

        55% {
            z-index: -1;
        }

        75% {
            width: .5rem;
            height: .5rem;
            opacity: .5;
        }

        100% {
            transform: translate3D(-1rem,-3rem,0);
            z-index: -1;
        }
    }
</style>

<!--[if IE 9]>
<style>
.error-text {
        color: #333 !important;
}
.particle {
        display:none;
}
</style>
<![endif]-->
        
    </head>

    <?php
    $class = '';
    if ($this->params['action'] != 'lock'):
        $class = 'class="animated fadeInDown"';
    endif;
    ?>


    <body id="login" <?php echo $class; ?>>
        <?php
            echo $this->fetch('content');
        ?>



<?php
    echo $this->Html->script('plugin/pace/pace.min.js'); #PACE LOADER

    echo $this->Html->script('jquery.min.js');
    echo "  <script>  
                if (!window.jQuery) { documento.write('" . str_replace('</script>', '<\/script>', $this->Html->script('libs/jquery-2.0.2.min.js')) . "');}
            </script>";

    echo $this->Html->script('jquery-ui.min.js');
    echo "  <script>  
                if (!window.jQuery.ui) { documento.write('" . str_replace('</script>', '<\/script>', $this->Html->script('libs/jquery-ui-1.10.3.min.js')) . "');}
            </script>";



    echo $this->fetch('script');
?>


    </body>
</html>
