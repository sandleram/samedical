<?php

App::uses('AppHelper', 'View/Helper');

class LinkHelper extends AppHelper {

    public $helpers = array('Html');

    /**
     * #VIEW
     * <?php echo $this->Link->buttonFilter('Status',$statuses,'statuses_id', $LINK_AJAX);?>
     * 
     * #CONTROLLER
     * #FILTRO STATUS
        $statuses_id = $this->params['named']['statuses_id'];
        $conditions = array();
        if(is_numeric($statuses_id)){
            $conditions = array('statuses_id'=>$statuses_id);
        }
        
        $this->paginate = array(
            'conditions'=>$conditions,
            'limit' => 5,
            'order' => array('id' => 'ASC')
        );
     */
    public function buttonFilter($title, array $infArr, $parans, $linkAjax = false) {
        $styleLi = 'style="background-color:#3276b1; color:#FFF;"';
        $styleArr = array('style' => 'color:#FFF;');
        $paransId = '';
        if (isset($this->params['named'][$parans])):
            $paransId = $this->params['named'][$parans];
        endif;

        $buttonFilter = '<div class="btn-group" style="float:right; margin-bottom: 10px; margin-left: 3px;">
                            <button class="btn btn-primary btn-sm dropdown-toggle " data-toggle="dropdown">
                                Status <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-right">';
        foreach ($infArr as $k => $v):
            if ($paransId == '' || $paransId != $k):
                $buttonFilter .= '<li>' . $this->Html->link($v, array('statuses_id' => $k, '?' => array('layout' => 'ajax')), $linkAjax) . '</li>';
            else:
                $buttonFilter .= '<li ' . $styleLi . '>' . $this->Html->link($v, array('statuses_id' => $k, '?' => array('layout' => 'ajax')), array_merge($linkAjax, $styleArr)) . '</li>';
            endif;
        endforeach;

        $buttonFilter .= '  </ul>
                        </div>';
        
        
        return $buttonFilter;
        /*direct view
         * 
         * 
         * <!--                        <div class="btn-group" style="float:right; margin-bottom: 10px; margin-left: 3px;">
                            <button class="btn btn-primary btn-sm dropdown-toggle " data-toggle="dropdown">
                                Status <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-right">
                                <?php
//                                $statusNamed = '';
//                                $styleLi = 'style="background-color:#3276b1; color:#FFF;"';
//                                $styleArr = array('style' => 'color:#FFF;');
//                                if (isset($this->params['named']['statuses_id'])):
//                                    $statusNamed = $this->params['named']['statuses_id'];
//                                endif;
//                                foreach ($statuses as $idStatus => $status):
//                                    if ($statusNamed == '' || $statusNamed != $idStatus):
//                                        echo '<li>' . $this->Html->link($status, array('statuses_id' => $idStatus, '?' => array('layout' => 'ajax')), $LINK_AJAX) . '</li>';
//                                    else:
//                                        echo '<li ' . $styleLi . '>' . $this->Html->link($status, array('statuses_id' => $idStatus, '?' => array('layout' => 'ajax')), array_merge($LINK_AJAX, $styleArr)) . '</li>';
//                                    endif;
//                                endforeach;
                                ?>
                            </ul>
                        </div>-->
         * 
         */
    }

    public function makeEdit($title, $url) {
        // Use the HTML helper to output
        // formatted data:

        $link = $this->Html->link($title, $url, array('class' => 'edit'));

        return '<div class="editOuter">' . $link . '</div>';
    }

}

?>