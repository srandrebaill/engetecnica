<!-- MAIN CONTENT-->
<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="overview-wrap">
                        <a href="<?php echo base_url('ativo_veiculo/gerenciar/manutencao/adicionar/'.$id_ativo_veiculo); ?>">
                        <button class="au-btn au-btn-icon au-btn--blue">
                        <i class="zmdi zmdi-plus"></i>Adicionar</button></a>
                    </div>
                    <div class="overview-wrap m-t-10">
                        <a href="<?php echo base_url("ativo_veiculo"); ?>">
                        <button class="">
                        <i class="zmdi zmdi-arrow-left"></i>&nbsp;Listar Todos os Veículos</button></a>
                    </div>
                    <div class="overview-wrap m-t-10">
                        <a href="<?php echo base_url("ativo_veiculo/editar/{$id_ativo_veiculo}"); ?>">
                        <button class="">
                        <i class="zmdi zmdi-arrow-left"></i>&nbsp;Editar Veículo</button></a>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <h2 class="title-1 m-b-25">Gerenciar Manutenção</h2>
                    <div class="table-responsive table--no-card m-b-40">
                        <table class="table table-borderless table-striped table-earning">
                            <thead>
                                <tr>
                                    <th width="7%">ID Manutenção</th>
                                    <th width="7%">Veículo</th>
                                    <th>Placa</th>
                                    <th>Fornecedor</th>
                                    <th>Serviço</th>
                                    <th>KM Atual</th>
                                    <th>KM Próxima Revisão</th>
                                    <th>Horas Próxima Revisão</th>
                                    <th>Custo</th>
                                    <th>Data Entrada</th>
                                    <th>Data Saída</th>
                                    <th>Data Vencimento</th>
                                    <th>Gerenciar</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    foreach($lista as $valor){ 
                                ?>
                                <tr>
                                    <td><?php echo $valor->id_ativo_veiculo_manutencao;?></td>
                                    <td><?php echo $valor->veiculo;?></td>
                                    <td><?php echo $valor->veiculo_placa; ?></td>
                                    <td><?php echo $valor->fornecedor; ?></td>
                                    <td><?php echo $valor->servico; ?></td>
                                    <td><?php echo $valor->veiculo_km_atual; ?></td>
                                    <td><?php echo $valor->veiculo_km_proxima_revisao; ?></td>
                                    <td><?php echo $valor->veiculo_hora_proxima_revisao; ?></td>
                                    <td>R$ <?php echo number_format($valor->veiculo_custo, 2, ',', '.'); ?></td>
                                    <td><?php echo $this->formata_data($valor->data_entrada) ; ?></td>
                                    <td><?php echo isset($valor->data_saida) ? $this->formata_data($valor->data_saida) : '-' ; ?></td>
                                    <td><?php echo isset($valor->data_vencimento) ? $this->formata_data($valor->data_vencimento) : '-' ; ?></td>
                                    <td> 
                                        <div class="btn-group" role="group">
                                            <button id="btnGroupGerenciarManutencao" type="button" class="btn btn-secondary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                Gerenciar
                                            </button>
                                            <div class="dropdown-menu" aria-labelledby="btnGroupGerenciarManutencao">
                                                <?php if(!$valor->data_saida){ ?>
                                                    <a class="dropdown-item " href="<?php echo base_url("ativo_veiculo/gerenciar/manutencao/editar/{$valor->id_ativo_veiculo}/{$valor->id_ativo_veiculo_manutencao}");?>">
                                                    <i class="fa fa-edit"></i> Editar
                                                    </a>
                                                    <?php if($valor->ordem_de_servico){ ?>
                                                        <div class="dropdown-divider"></div>
                                                        <a
                                                            class="dropdown-item  confirmar_registro" data-tabela="<?php echo base_url("ativo_veiculo/gerenciar/manutencao/{$valor->id_ativo_veiculo}");?>" 
                                                            href="javascript:void(0)" data-registro=""
                                                            data-acao="Marca como Finalizada"  data-redirect="true"
                                                            data-href="<?php echo base_url("ativo_veiculo/manutencao_saida/{$valor->id_ativo_veiculo}/{$valor->id_ativo_veiculo_manutencao}");?>"
                                                        >
                                                        <i class="fa fa-check"></i> Marca como Finalizada
                                                        </a>
                                                    <?php } ?>
                                                <?php } ?>
                                                <?php if($valor->ordem_de_servico){ ?>
                                                    <div class="dropdown-divider"></div>
                                                    <a class="dropdown-item " target="_blank" href="<?php echo base_url("assets/uploads/ordem_de_servico/{$valor->ordem_de_servico}"); ?>">
                                                    <i class="fa fa-eye"></i> Ver Compovante
                                                    </a> 
                                                    <div class="dropdown-divider"></div>
                                                    <a class="dropdown-item " target="_blank" download href="<?php echo base_url("assets/uploads/ordem_de_servico/{$valor->ordem_de_servico}"); ?>">
                                                    <i class="fa fa-download"></i> Baixar Comprovante
                                                    </a>                           
                                                <?php } ?>

                                                <?php if(!$valor->data_saida){ ?>
                                                    <div class="dropdown-divider"></div>
                                                    <a href="javascript:void(0)" data-href="<?php echo base_url("ativo_veiculo/manutencao_deletar/{$valor->id_ativo_veiculo}/{$valor->id_ativo_veiculo_manutencao}"); ?>" 
                                                        data-registro="<?php echo $valor->id_ativo_veiculo;?>" data-redirect="true"
                                                        data-tabela="ativo_veiculo/gerenciar/manutencao/<?php echo $valor->id_ativo_veiculo; ?>" class="dropdown-item  deletar_registro"><i class="fa fa-trash"></i> Excluir</a>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                               <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="copyright">
                        <p>Copyright © <?php echo date("Y"); ?>. All rights reserved.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END MAIN CONTENT-->
<!-- END PAGE CONTAINER-->
