<section id="widget-grid" class="" >
    <div class="rows">
        <h4 style="margin-bottom:20px; border-bottom: 1px dotted #d3d3d3;"> Lista de importações de absenteísmo</h4>
        <div class="table-responsive" style="overflow-y: auto;">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th width="100">#ID <span class="note">(#Importação) </span></th>
                        <th>Matrícula</th>
                        <th>Documento</th>
                        <th>Motivo</th>
                        <th>Hospital / Clínica</th>
                        <!-- <th>Nome Colaborador</th> -->
                        <th>Data Saída</th>
                        <th>Qtde Dias</th>
                        <th>Hora Saída</th>
                        <th>Hora Retorno</th>
                        <th>CID</th>
                        <th>Especialidade</th>
                        <th>Emissor</th>
                        <th>Profissional</th>
                        <th>Num. CRM</th>
                        <th>Tipo Absenteismo</th>
                        <th>Situação</th>
                        <th>Data Cadastro</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        foreach($row['listAbsenteismo'] as $kab => $ab){
                            $situacao = '-';
                            $situacaoList = array(''=>' - ','0'=>'Inativo','1'=>'Ativo');
                            if(isset($situacaoList[$ab['situacao']])){
                                $situacao = $situacaoList[$ab['situacao']];
                            }
                            
                            echo "<tr>";
                            echo " <td>{$ab['id']} ";
                            if($ab['importacao_id'] != ''){
                                echo "(<a href='".Router::url(array('controller'=>'importacao',@$ab['importacao_id']) ,true)."'>#{$ab['importacao_id']}</a>)";
                            }else{
                                echo "(Manual)";
                            }                               
                            echo " </td>";
                            
                            echo " <td> {$ab['matricula']} </td>";
                            echo " <td> {$ab['documento_id']} </td>";
                            echo " <td> {$ab['motivo_id']} </td>";
                            echo " <td> {$ab['hospital_clinica']} </td>";
                            // echo " <td> {$ab['nome_colaborador']} </td>";
                            echo " <td> {$this->Funcoes->dateToView(@$ab['data_saida'])} </td>";
                            echo " <td> {$ab['qtde_dias_atestado']} </td>";
                            echo " <td> {$ab['hora_saida']} </td>";
                            echo " <td> {$ab['hora_retorno']} </td>";
                            echo " <td> {$ab['cid']} </td>";
                            echo " <td> {$ab['especialidade_id']} </td>";
                            echo " <td> {$ab['emissor_id']} </td>";
                            echo " <td> {$ab['profissional']} </td>";
                            echo " <td> {$ab['num_crm']} </td>";
                            echo " <td> {$ab['tipo_absenteismo_id']} </td>";
                            echo " <td> {$situacao} </td>";
                            echo " <td> {$this->Funcoes->dateToView(@$ab['data_cadastro'])} </td>";
                            echo " <td> {$ab['btn']}</td>";
                            echo "</tr>";
                        }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</section>