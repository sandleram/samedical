<table class="table">
    <tr>
        <td > 
            <div class='pagination '>
                <?php
                echo $this->Paginator->counter(array(
                    'format' => __('Exibindo registros do {:start} ao {:end} - Total de {:count}')
                ));
//                echo $this->Paginator->counter(array(
//                    'format' => __('Página {:page} de {:pages}, Exibindo {:current} registros do total de {:count}, Iniciando por {:start}, finalizado por {:end}')
//                ));
                ?>	
            </div>
        </td>
        <td>        
            <div class="text-right">
                <ul class="pagination pagination-sm">
                    <?php
                    echo $this->Paginator->prev('<< ', array('tag' => 'li', 'class2' => ''), null, array('tag' => 'li', 'class' => 'hidden', 'disabledTag' => 'a'));
                    echo $this->Paginator->numbers(array(
                        'class2' => '',
                        'separator' => '',
                        'currentClass' => 'active',
                        'currentTag' => 'a',
                        'tag' => 'li'
                    ));
                    echo $this->Paginator->next(' >>', array('tag' => 'li', 'class2' => ''), null, array('tag' => 'li', 'class' => 'hidden', 'disabledTag' => 'a'));
                    ?>
                </ul>
            </div>
        </td>
    </tr>
</table>