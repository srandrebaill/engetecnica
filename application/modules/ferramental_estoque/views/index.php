<!-- MAIN CONTENT-->
<style>
    .btn-contagem {
        width: 50px;
        height: 30px;
        font-weight: bold;
    }
    .btn-codigo {
        width: 100%;
        font-weight: bold;
    }
</style>
<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 m-b-40">
                    <div class="overview-wrap">
                        <h2 class="title-1">Estoque de Ferramentas</h2>
                        <a href="<?php echo base_url('ferramental_estoque/adicionar'); ?>">
                        <button class="au-btn au-btn-icon au-btn--blue">
                        <i class="zmdi zmdi-plus"></i>Nova Retirada</button></a>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="table-responsive table--no-d m-b-40">
                        <h3 class="title-1 m-b-25">Retiradas</h3>
                        <table class="table table-borderless table-striped table-earning" id="lista">
                            <thead>
                                <tr>
                                    <th>Retirada ID</th>
                                    <th>Obra</th>
                                    <th>Funcionário</th>
                                    <th>Data</th>
                                    <th>Status</th>
                                    <th>Opções</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($retiradas as $valor){ ?>
                                <tr>
                                    <td>
                                        <a class="" href="<?php echo base_url("ferramental_estoque/detalhes/{$valor->id_retirada}"); ?>">    
                                            <?php echo $valor->id_retirada; ?>
                                        </a>
                                    </td>
                                    <td><?php echo $valor->obra; ?></td>
                                    <td><?php echo $valor->funcionario; ?></td>
                                    <td><?php echo date("d/m/Y H:i", strtotime($valor->data_inclusao)); ?></td>
                                    <td>
                                        <?php $status = $this->status($valor->status); ?>
                                        <span class="badge badge-<?php echo $status['class'];?>"><?php echo $status['texto'];?></span>
                                    </td>
                                    <td>
                                        <a class="btn btn-sm btn-outline-<?php echo $status['class'];?>" href="<?php echo base_url("ferramental_estoque/detalhes/{$valor->id_retirada}"); ?>"><i class="fas fa-list"></i> Detalhes</a>
                                        
                                        <?php if($valor->status == 1) {?>
                                        <a href="<?php echo base_url("ferramental_estoque/editar/{$valor->id_retirada}"); ?>">
                                            <button class="btn btn-sm btn-primary" type="button">                                                    
                                                <i class="fas fa-edit"></i>
                                            </button>   
                                        </a>
                                        <a 
                                            class="confirmar_registro"  data-tabela="<?php echo base_url("ferramental_estoque");?>" 
                                            href="javascript:void(0)" data-registro="<?php echo $valor->id_retirada;?>"
                                            data-acao="Remover Retirada"  data-redirect="true"
                                            data-href="<?php echo base_url("ferramental_estoque/remove_retirada/{$valor->id_retirada}");?>"
                                        >
                                            <button class="btn btn-sm btn-danger" type="button">                                                    
                                                <i class="fas fa-trash"></i>
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
                <div class="col-lg-12">
                    <div class="table-responsive table--no-card m-b-40">
                        <h3 class="title-1 m-b-25">Itens</h3>
                        <table class="table table-borderless table-striped table-earning" id="lista2">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Código</th>
                                    <th>Item</th>
                                    <th>Tipo</th>
                                    <th>Situação</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($estoque as $valor){ ?>
                                <tr>
                                  <td><?php echo $valor->id_ativo_externo; ?></td>
                                    <td>
                                        <button class="badge badge-success">    
                                            <?php echo $valor->codigo; ?>
                                        </button>
                                    </td>
                                    <td><?php echo $valor->nome; ?></td>
                                    <td>
                                        <?php if($valor->tipo == 1) { ?>
                                            <button class="badge badge-primary">Kit</button>
                                          <?php } else { ?>
                                            <button class="badge badge-secondary">Unidade</button>
                                        <?php } ?>
                                    </td>
                                    <td>
                                        <?php $status = $this->status($valor->situacao); ?>
                                        <span class="badge badge-<?php echo $status['class'];?>"><?php echo $status['texto'];?></span>
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