<div class="btn-group" style="float:left; margin-right: 100px;">
    <?php 
        if (isset($search) && is_array($search)):
            $qtd = 0;
            $filtros = '';
            foreach($search as $key => $value):
                if($value):
                    $html = '<span data-role="remove"></span>';
                    $linkAjax =  array('style'=>'color:white');

                    $filtros .= '<div style="float:left;">
                            <div class="bootstrap-tagsinput no-border" style="float:left;">
                                <span class="tag label label-info">
                                    '.  ucwords(str_replace('_', ' ', $key)).': '.$value.'
                                    '.$this->Html->link_('',array('controller'=> $this->params['controller'],
                                                                  'action'=>'admin_busca_unset',
                                                                  $key) , $linkAjax , false , $html).'
                                </span>
                            </div>
                        </div>';
                    $qtd++;
                endif;
            endforeach;
            if($qtd > 0):
                echo '<div style="float:left;" class="bold">Filtros:</div>';
                echo $filtros;
                echo '<div style="float:left;">
                        <div class="bootstrap-tagsinput no-border" style="float:left;">
                            <span class="tag label label-info">
                                Remover Todos
                                '.$this->Html->link_('',array('controller'=> $this->params['controller'],
                                                              'action'=>'admin_busca_unset',
                                                              'ALL') , $linkAjax , false , $html).'
                            </span>
                        </div>
                    </div>';
            endif;
        endif;
    ?>
</div>
