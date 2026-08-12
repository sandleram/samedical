<!-- RIBBON -->
<div id="ribbon">
    <!--<span class="ribbon-button-alignment"> <span id="refresh" class="btn btn-ribbon" data-title="refresh"  rel="tooltip" data-placement="bottom" data-original-title="<i class='text-warning fa fa-warning'></i> Aviso! Isso irá redefinir todas as suas configurações do widget." data-html="true"><i class="fa fa-refresh"></i></span> </span>-->
    <ol class="breadcrumb">
        <li><i class="fa-fw fa fa-home"></i>Dashboard</li>
        <li></i>Relatorios</li>
    </ol>
</div>
<!-- END RIBBON -->
<style>
    .table_graph tr td {
        line-height: 60px !important;

    }
    .table_graph2 tr td {
        line-height: 35px !important;
    }
</style>




<div id="content">
    <?php echo $this->Form->msg($this->Session->flash()); ?>


    <div class="widget-body">
        <h2>Relatórios Disponíves</h2>
        <p>Todos Relatórios  </p>

        <div class="table-responsive">
            
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>RELATÓRIO GERAL</th>
                        <th width="200">Acesso</th>
                    </tr>
                </thead>
                <tbody>
                    <?php  
                        if((isset($permissoes['Relatorio/beneficiarios']['permissao']) && $permissoes['Relatorio/beneficiarios']['permissao'] > 0) || $perfil_id == $perfil_root){ ?>
                        <tr>
                            <td>Beneficiarios</td>
                            <td><?php echo $this->Html->link('Acessar','beneficiarios');?></td>
                        </tr>
                    <?php }
                      if((isset($permissoes['Relatorio/afastados']['permissao']) && $permissoes['Relatorio/afastados']['permissao'] > 0) || $perfil_id == $perfil_root){ ?>
                        <tr>
                            <td>Afastados</td>
                            <td><?php echo $this->Html->link('Acessar','afastados');?></td>
                        </tr>
                    <?php }
                        if((isset($permissoes['Relatorio/beneficio_previdenciario']['permissao']) && $permissoes['Relatorio/beneficio_previdenciario']['permissao'] > 0) || $perfil_id == $perfil_root){ ?>
                        <!-- <tr>
                            <td>Beneficio Previdenciário</td>
                            <td>< ?php echo $this->Html->link('Acessar','beneficio_previdenciario');?></td>
                        </tr> -->
                    
                    <?php }  
                        if((isset($permissoes['Relatorio/atendimentos_pendentes']['permissao']) && $permissoes['Relatorio/atendimentos_pendentes']['permissao'] > 0) || $perfil_id == $perfil_root){ ?>
                        <tr>
                            <td>Atendimentos Pendentes</td>
                            <td><?php echo $this->Html->link('Acessar','atendimentos_pendentes');?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            
            
            <?php if($perfil_id == $perfil_root):?>
            <table class="table table-bordered" style="display:none;">
                <thead>
                    <tr>
                        <th>RELATÓRIOS OPERACIONAIS</th>
                        <th width="200">Acesso</th>
                    </tr>
                </thead>
                <tbody> 
                    <tr>
                        <td>Gerencial</td>
                        <td><?php echo $this->Html->link('Acessar','gerencial');?></td>
                    </tr>
                    <tr>
                        <td>Fatura e Sinistro</td>
                        <td><?php echo $this->Html->link('Acessar','exportacao');?></td>
                    </tr>
                    <tr>
                        <td>Movimentação de Beneficiários</td>
                        <td><?php echo $this->Html->link('Acessar','movimentacao_beneficiario');?></td>
                    </tr>
                    <tr>
                        <td>Movimentação de Sinistro</td>
                        <td><?php echo $this->Html->link('Acessar','movimentacao_sinistro');?></td>
                    </tr>
                    <tr>
                        <td>Movimentação de Fatura</td>
                        <td><?php echo $this->Html->link('Acessar','movimentacao_fatura');?></td>
                    </tr>
                </tbody>
            </table>
            <?php endif;?>
        </div>
    </div>
</div>
