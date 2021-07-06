<!-- MAIN CONTENT-->
<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="overview-wrap">
                        <h2 class="title-1"></h2>
                        <a href="<?php echo base_url('empresa/adicionar'); ?>">
                        <button class="au-btn au-btn-icon au-btn--blue">
                        <i class="zmdi zmdi-plus"></i>Adicionar</button></a>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <h2 class="title-1 m-b-25">Empresas</h2>
                    <div class="table-responsive table--no-card m-b-40">
                        <table class="table table-borderless table-striped table-earning">
                            <thead>
                                <tr>
                                    <th width="7%">Id</th>
                                    <th>Razão Social</th>
                                    <th>Responsável</th>
                                    <th>E-mail</th>
                                    <th>Celular</th>
                                    <th>Situação</th>
                                    <th class="text-right">Opções</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($lista as $valor){ ?>
                                <tr>
                                    <td><?php echo $valor->id_empresa; ?></td>
                                    <td><?php echo $valor->razao_social; ?></td>
                                    <td><?php echo $valor->responsavel; ?></td>
                                    <td><?php echo $valor->responsavel_email; ?></td>
                                    <td><?php echo $valor->responsavel_celular; ?></td>
                                    <td><?php echo $this->get_situacao($valor->situacao); ?></td>
                                    <td class="text-right">
                                        <a href="<?php echo base_url('empresa'); ?>/editar/<?php echo $valor->id_empresa; ?>"><i class="fas fa-edit"></i></a>
                                        <?php if($valor->id_empresa>1){ ?>
                                        <a href="javascript:void(0)" data-href="<?php echo base_url('empresa'); ?>/deletar/<?php echo $valor->id_empresa; ?>" data-registro="<?php echo $valor->id_empresa;?>" data-tabela="empresa" class="deletar_registro"><i class="fas fa-remove"></i></a>
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