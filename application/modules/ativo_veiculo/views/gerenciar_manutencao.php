<!-- MAIN CONTENT-->
<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="overview-wrap">
                        <h2 class="title-1"></h2>
                        <a href="<?php echo base_url('ativo_veiculo/gerenciar/manutencao/adicionar/'.$id_ativo_veiculo); ?>">
                        <button class="au-btn au-btn-icon au-btn--blue">
                        <i class="zmdi zmdi-plus"></i>Adicionar</button></a>
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
                                    <th width="7%">Veículo</th>
                                    <th>Placa</th>
                                    <th>Fornecedor</th>
                                    <th>Serviço</th>
                                    <th>KM</th>
                                    <th>Custo</th>
                                    <th>Data</th>
                                    <th>Comprovante</th>

                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    foreach($lista as $valor){ 
                                ?>
                                <tr>
                                    <td><?php echo $valor->veiculo; ?></td>
                                    <td><?php echo $valor->veiculo_placa; ?></td>
                                    <td><?php echo $valor->id_fornecedor; ?></td>
                                    <td><?php echo $valor->id_ativo_configuracao; ?></td>
                                    <td><?php echo $valor->veiculo_km_atual; ?></td>
                                    <td>R$ <?php echo number_format($valor->veiculo_custo, 2, ',', '.'); ?></td>
                                    <td><?php echo date("d/m/Y", strtotime($valor->data)); ?></td>
                                    <td>
                                        <?php if($valor->ordem_de_servico){ ?>
                                        <a href="<?php echo base_url('ativo_veiculo/gerenciar/manutencao/comprovante/'.$valor->ordem_de_servico); ?>">
                                            <button id="btnGroupDrop1" type="button" class="btn btn-danger btn-sm">
                                                Baixar Comprovante
                                            </button>
                                        </a>                           
                                        <?php } ?>
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