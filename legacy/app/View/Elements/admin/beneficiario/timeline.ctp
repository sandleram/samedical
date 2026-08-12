<?php
if ($tipoPerfil == 1) {
?>
    <style>
        .smart-timeline-icon {
            left: 96px !important;
        }

        .smart-timeline-time {
            width: 90px !important;
        }

        .smart-timeline-content {
            margin-left: 135px !important;
        }

        .smart-timeline-list:after {
            left: 111px !important;
        }
    </style>
<?php  } ?>
<div class="well well-sm" style="margin:-10px; border:none;">
    <h4 style="margin-top:0; margin-bottom:20px; border-bottom: 1px dotted #d3d3d3;"> Histórico de Atendimentos</h4>
    <!-- Timeline Content -->
    <div class="smart-timeline">

        <?php
        echo $this->Form->msg($this->Session->flash());
        $avatar = 'female.png';
        if ($row['Beneficiario']['sexo'] == 'Masculino') {
            $avatar = 'male.png';
        }
        if (isset($row['listTimeline']) && count($row['listTimeline']) > 0) {
            #krumo($row['listTimeline']);
            echo '<ul class="smart-timeline-list" style="margin-top:10px;">';
            foreach ($row['listTimeline'] as  $listTimelineAll) {
                if ($listTimelineAll['status_atendimento'] == 3  && $tipoPerfil == 1) {
                    continue;
                }
                $bkcolor = '';

                if ($listTimelineAll['status_atendimento'] == 3) {
                    $dataAtual = date('Y-m-d H:i:s');
                    $datamais2 = date('Y-m-d H:i:s', strtotime('+2 days', strtotime(date('Y-m-d H:i:s'))));

                    if (strtotime($dataAtual) > strtotime($listTimelineAll['datahora_agendamento'])) {
                        $bkcolor = 'background-image: linear-gradient(to left, #ffa2a2, #fff0f0,#ffffff);';
                    } elseif (strtotime($datamais2) > strtotime($listTimelineAll['datahora_agendamento'])) {
                        $bkcolor = 'background-image: linear-gradient(to left, #edef5e, #feffdb,#ffffff);   ';
                    }
                }
                #$bkcolor = 'background-image: linear-gradient(to left,  #fff, #f3f3d8,#f3f3d8)';
                $bordercolor = '';

                if ($listTimelineAll['status_atendimento'] == 2) {
                    $bkcolor = 'background-image: linear-gradient(to right,  #fff, #d8f3dc)';
                    $bordercolor = 'border: 1px solid #2bad45 !important; border-radius:5px;';
                }

                echo '<li style="margin-top:5px;' . $bordercolor . ' ' . $bkcolor . '">'; //  box-shadow: 0px 5px 0px 0px rgb(235 235 235 / 35%); 
                echo '<div class="smart-timeline-icon" >
                                ' . $this->Html->image('avatars/' . $avatar, array('style' => 'border: 1px solid #c7c7c7;')) . '
                             </div>';
                echo '<div class="smart-timeline-time">
                                <small>' . $this->Funcoes->data_hora_recado($listTimelineAll['data_cadastro']) . '</small><br/>
                                <span class="note" style="font-size:8px;">(' . $this->Funcoes->dateToView($listTimelineAll['data_cadastro']) . ')</span>
                              </div>';


                $forma_atendimento = 'via ' . $formaAtendimentoArr[$listTimelineAll['forma_atendimento']];
                if ($listTimelineAll['forma_atendimento'] == 1) {
                    $forma_atendimento = ' Presencialmente';
                }

                $status_ = '';

                if ($listTimelineAll['status_atendimento'] == 0) {
                    $status_ = '<span class="text-info"><b>Status: </b></span>
                                            <img src="' . Router::url('/img/sys/1397068785_user-male-2-delete.png', true) . '" style="width:15px;" alt="Sem Contato">
                                            <span class="note">' . $statusAtendimentoArr[$listTimelineAll['status_atendimento']] . ' ' . $forma_atendimento . '  (' . $this->Funcoes->dateToView($listTimelineAll['data_conclusao'], true) . '<i> por ' . $listTimelineAll['usuario_nome'] . '</i>)
                                        </span>';
                } elseif ($listTimelineAll['status_atendimento'] == 1) {
                    $status_ = '<span class="text-info"><b>Status: </b></span>
                                            <img src="' . Router::url('/img/sys/Interview.jpg', true) . '" style="width:15px;" alt="Deixou Recado">
                                            <span class="note">' . $statusAtendimentoArr[$listTimelineAll['status_atendimento']] . ' ' . $forma_atendimento . '  (' . $this->Funcoes->dateToView($listTimelineAll['data_conclusao'], true) . '<i> por ' . $listTimelineAll['usuario_nome'] . '</i>)
                                        </span>';
                } elseif ($listTimelineAll['status_atendimento'] == 2) {
                    $status_ = '<span class="text-info"><b>Status: </b></span>
                                            <img src="' . Router::url('/img/icons/sucess.png', true) . '" style="width:15px;" alt="Concluído">
                                            <span class="note">' . $statusAtendimentoArr[$listTimelineAll['status_atendimento']] . ' ' . $forma_atendimento . '  (' . $this->Funcoes->dateToView($listTimelineAll['data_conclusao'], true) . '<i> por ' . $listTimelineAll['usuario_nome'] . '</i>)
                                        </span>';
                } elseif ($listTimelineAll['status_atendimento'] == 3) {
                    $status_ = '<span class="text-info"><b>Status: </b></span>
                                            <img src="' . Router::url('/img/icons/clock.png', true) . '" style="width:15px;" alt="Aguardando Atendimento">
                                            <span class="note">' . $statusAtendimentoArr[$listTimelineAll['status_atendimento']] . ' </i>
                                        </span>';
                }
                $at_horas = ($listTimelineAll['at_horas'] < 10) ? '0' . $listTimelineAll['at_horas'] : $listTimelineAll['at_horas'];
                $at_minutos = ($listTimelineAll['at_minutos'] < 10) ? '0' . $listTimelineAll['at_minutos'] : $listTimelineAll['at_minutos'];

                $tipo_atendimento = '<b>Atendimento :: ' . $tipoAtendimentoArr[$listTimelineAll['tipo_atendimento']] . '</b>';
                $responsavel = '';
                if ($listTimelineAll['status_atendimento'] == 3) {
                    $tipo_atendimento = '<b>Atendimento Agendado </b>';
                    $responsavel = '<p><b class="text-info">Responsável: </b> ' . $listTimelineAll['usuario_nome'] . '</p>';
                }
                $agendamento_usuario = '';
                if ($listTimelineAll['usuario_agendamento_nome'] != '') {
                    $agendamento_usuario = '<p><b class="text-info">Agendado por: </b> ' . $listTimelineAll['usuario_agendamento_nome'] . '</p>';
                }

                $descricao_agendamento = '';
                if ($listTimelineAll['descricao_agendamento'] != '') {
                    $descricao_agendamento = '<p> 
                                            <h5 style="font-size: 15px; border-bottom: 1px dotted rgb(211, 211, 211); "> 
                                                Descritivo do Agendamento
                                            </h5>
                                            ' . $listTimelineAll['descricao_agendamento'] . '
                                        </p>';
                }
                $datahora_agendamento = '';
                if ($listTimelineAll['datahora_agendamento'] != '' && $listTimelineAll['status_atendimento']  == 3) {
                    $dataAtual = date('Y-m-d H:i:s');
                    $datamais2 = date('Y-m-d H:i:s', strtotime('+2 days', strtotime(date('Y-m-d H:i:s'))));

                    $style_ag = '';
                    $style_ag = 'background-color:green; color:white; padding: 2px 4px';
                    if (strtotime($dataAtual) > strtotime($listTimelineAll['datahora_agendamento'])) {
                        $style_ag = 'background-color:red; color:white; padding: 2px 4px;';
                    } elseif (strtotime($datamais2) > strtotime($listTimelineAll['datahora_agendamento'])) {
                        $style_ag = 'background-color:yellow; padding: 2px 4px';
                    }

                    $datahora_agendamento = '   <p><b class="text-info">Data do agendamento: </b>
                                                            <span style="' . $style_ag . '">' . $this->DateTime->dbToView($listTimelineAll['datahora_agendamento']) . '</span>
                                                        </p>';
                    #$datahora_agendamento = '<br><p><b class="text-info">Data do agendamento: </b> '.$listTimelineAll['datahora_agendamento'].'</p>';
                }







                echo '  <div class="smart-timeline-content" style="padding-bottom: 20px; " >
                                    <p style="float:right; margin-right:8px; ">
                                    ' . $listTimelineAll['btn'] . '
                                    </p>
                                    <p class="text-info">
                                        ' . $tipo_atendimento . '
                                    </p>
                                    ' . $responsavel . '
                                    ' . $agendamento_usuario . '
                                    ' . $datahora_agendamento . '
                                    <p>
                                        ' . $status_ . '
                                    </p>';
                


                #AGENDAMENTO (diferente de atendimento)
                if (in_array($listTimelineAll['status_atendimento'], [0, 1, 2]) && $tipoPerfil != 1) {
                    echo '      <p style="margin-top:8px;">
                                            <span class="text-info"><b>Tempo do Atendimento: </b></span>
                                            <span class="note ">' . $at_horas . ':' . $at_minutos . 'm</span>
                                        </p>
                                        ';
                    if (isset($listTimelineAll['anexo']) && !empty($listTimelineAll['anexo'])) {
                        echo ' <p style="margin-top:8px;">
                                    <span class="text-info"><b>Anexo: </b></span>';
                        $url_file = 'files/uploads/atendimento/' . $listTimelineAll['anexo'];
                        $file_disabled = '';
                        if (file_exists($url_file)) {
                            echo '<a href="' . Router::url('/' . $url_file) . '" target="_blank" ">
                                                <img src="' . Router::url('/img/icons/attach.png', true) . '" width="20"/>
                                                ' . $listTimelineAll['anexo'] . '
                                                </a>';
                        } else {
                            echo '<a href="javascript:void(0);">Sem Anexo</a>';
                        }

                        echo '</p>';
                    }
                    if (isset($listTimelineAll['blob_id']) && !empty($listTimelineAll['blob_id'])  && $this->Session->read('Auth.Usuario.perfil_id') == 1) {
                        echo ' <p style="margin-top:8px;">
                                    <span class="text-info"><b>Anexo Blob: </b></span>';
                        $url_file = Router::url(array('controller' => 'blob', 'action' => 'download', md5($listTimelineAll['blob_id']), 'admin' => true));

                        echo '<a href="' . $url_file . '" target="_blank" ">
                                            <img src="' . Router::url('/img/icons/attach.png', true) . '" width="20"/>
                                            ' . $listTimelineAll['anexo'] . '
                                            </a>';


                        echo '</p>';
                    }

                    if (isset($listTimelineAll['cid']) && !empty($listTimelineAll['cid'])) {
                        echo '      <p style="margin-top:5px;">
                                                <span class="bg-color-red txt-color-white" style="padding:0 2px ;">
                                                <b>CID:</b></span> 
                                                <span class="note txt-color-red" style="background-color: rgb(249, 242, 244); text-decoration: underline; --darkreader-inline-bgcolor:#2b141a;" data-darkreader-inline-bgcolor="">' . $listTimelineAll['cid'] . '</span>
                                            </p>';
                    }


                    echo $descricao_agendamento;
                    echo '     <p> 
                                            <h5 style="font-size: 15px; margin-top:20px; border-bottom: 1px dotted rgb(211, 211, 211); "> 
                                                Descritivo do Atendimento
                                            </h5>
                                            ' . $listTimelineAll['descricao'] . '
                                        </p>
                                        
                                    </div> ';
                }else{
                    echo $descricao_agendamento;
                }




                echo '</li>';
            }
            echo '</ul>';
        } else {

            echo '<ul class="smart-timeline-list" style="margin-top:10px;">
                            <div class="smart-timeline-icon" >
                                ' . $this->Html->image('avatars/' . $avatar, array('style' => 'border: 1px solid #c7c7c7;')) . '
                            </div>
                            <div class="smart-timeline-time">
                                <small>' . $this->Funcoes->data_hora_recado(date('Y-m-d H:i:s')) . '</small><br/>
                                <span class="note" style="font-size:8px;">(' . $this->Funcoes->dateToView(date('Y-m-d H:i:s')) . ')</span>
                            </div>
                            <div class="smart-timeline-content" style="padding: 20px 0;" >
                                <p> 
                                    <i> Sem nenhuma atualização! </i>
                                </p>
                            </div> 
                        </ul>';
        }
        ?>
    </div>
    <!-- END Timeline Content -->

</div>