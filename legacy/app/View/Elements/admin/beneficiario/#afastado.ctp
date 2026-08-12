<section id="widget-grid" class="" >
    <div class="rows">
        <h4 style="margin-bottom:20px; border-bottom: 1px dotted #d3d3d3;"> Lista de importações de afastamento</h4>
        <div class="table-responsive" style="overflow-y: auto;">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th width="100">#ID <span class="note">(#Importação) </span></th>
                        <th>Data Inicio Afastamento</th>
                        <th>Data Fim Afastamento</th>
                        <th>CID</th>
                        <th>Tipo de Afastamento</th>
                        <th>Assistência Médica</th>
                        <th>Plano de Assistência Médica</th>
                        <th>Data da Importação</th>
                        <!-- <th>Ações</th> -->
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        if(count($row['ListAfastado'])>0){
                            foreach($row['ListAfastado'] as  $af){
                                echo "<tr>";
                                echo " <td>#{$af['id']} (<a href='".Router::url(array('controller'=>'importacao',$af['importacao_id']) ,true)."'>#{$af['importacao_id']}</a>) </td>";
                                echo " <td> {$this->Funcoes->dateToView(@$af['data_inicio_afastamento'])} </td>";
                                echo " <td> {$af['data_fim_afastamento']} </td>";
                                echo " <td> {$af['cid']} </td>";
                                echo " <td> {$af['tipo_afastamento']} </td>";
                                echo " <td> {$af['assistencia_medica']} </td>";
                                echo " <td> {$af['plano_assistencia_medica']} </td>";
                                echo " <td> {$this->Funcoes->dateToView($af['data_cadastro'])} </td>";
                                #echo " <td> {$af['btn']}</td>"; 
                                echo "</tr>";
                            }
                        }
                        
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</section>