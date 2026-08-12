<section id="widget-grid" class="" >
    <div class="rows">
        <h4 style="margin-bottom:20px; border-bottom: 1px dotted #d3d3d3;"> Lista de importações de benefício previdenciário</h4>
        <div class="table-responsive" style="overflow-y: auto;">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th width="100">#ID <span class="note">(#Importação) </span></th>
                        <th>Data Próxima Perícia</th>
                        <th>Nº Requerimento</th>
                        <th>NB</th>
                        <th>ID Espécie</th>
                        <th>Espécie</th>
                        <th>Situação</th>
                        <th>Data de Entrada do Requerimento</th>
                        <th>Data Início</th>
                        <th>Data Despacho</th>
                        <th>Data da Perícia</th>
                        <th>Conclusão da Perícia</th>
                        <th>Data Limite</th>
                        <th>Data Indeferimento</th>
                        <th>Data Cessação</th>
                        <th>Nexo Técnico</th>
                        <th>Data da Importação</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        foreach($row['listBeneficioPrevidenciario'] as $kbp => $bp){
                            echo "<tr>";
                            echo " <td>{$bp['id']} ";
                            if($bp['importacao_id'] != ''){
                                echo "(<a href='".Router::url(array('controller'=>'importacao',$bp['importacao_id']) ,true)."'>#{$bp['importacao_id']}</a>)";
                            }else{
                                echo "(Manual)";
                            }

                            $situacao = '-';
                            $situacaoList = array(''=>' - ','A'=>'Ativo','C'=>'Cessado','Suspenso'=>'Suspenso','AN'=>'Em Análise' );
                            if(isset($situacaoList[$bp['situacao']])){
                                $situacao = $situacaoList[$bp['situacao']];
                            }

                            echo " </td>";
                            echo " <td> {$this->Funcoes->dateToView(@$bp['data_proxima_pericia'])} </td>";
                            echo " <td> {$bp['num_requerimento']} </td>";
                            echo " <td> {$bp['nb']} </td>";
                            echo " <td> {$bp['especie_bp_id']} </td>";
                            echo " <td> {$bp['especie']} </td>";
                            echo " <td> {$situacao} </td>";
                            echo " <td> {$this->Funcoes->dateToView(@$bp['data_entrada_requerimento'])} </td>";
                            echo " <td> {$this->Funcoes->dateToView(@$bp['data_inicio'])} </td>";
                            echo " <td> {$this->Funcoes->dateToView(@$bp['data_despacho'])} </td>";
                            echo " <td> {$this->Funcoes->dateToView(@$bp['data_realizacao_pericia'])} </td>";
                            echo " <td> {$bp['conclusao_pericia_medica']} </td>";
                            echo " <td> {$this->Funcoes->dateToView(@$bp['data_limite'])} </td>";
                            echo " <td> {$this->Funcoes->dateToView(@$bp['data_indeferimento'])} </td>";
                            echo " <td> {$this->Funcoes->dateToView(@$bp['data_cessacao'])} </td>";
                            echo " <td> {$bp['nexo_tecnico']} </td>";
                            echo " <td> {$this->Funcoes->dateToView(@$bp['data_cadastro'])} </td>";
                            echo " <td> {$bp['btn']}</td>";
                            echo "</tr>";
                        }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</section>