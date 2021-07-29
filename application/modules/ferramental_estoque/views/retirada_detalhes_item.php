<!-- MAIN CONTENT-->
<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="overview-wrap">
                        <h2 class="title-1"></h2>
                        <a href="<?php echo base_url("ferramental_estoque/detalhes/{$retirada->id_retirada}"); ?>">
                        <button class="au-btn au-btn-icon au-btn--blue">
                        <i class="zmdi zmdi-arrow-left"></i>Voltar a Retirada</button></a>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <form action="<?php echo base_url('ferramental_retirada/liberar_retirada'); ?>" method="post" enctype="multipart/form-data"> 
                        <h2 class="title-1 m-b-25">Detalhes Item da Retirada</h2>

                        <div class="card">

                            <input type="hidden" name="id_retirada" value="<?php echo $retirada->id_retirada; ?>">
                            <input type="hidden" name="id_obra" value="<?php echo $retirada->id_obra; ?>">
                            <div class="card-body">

                                <!-- Detalhes da Retirada -->
                                <table class="table table-responsiv table-borderless table-striped table-earning" id="lista">
                                    <thead>
                                        <tr class="active">
                                          <th>Item ID</th>
                                          <th>Item</th>
                                          <th>Quantidade</th>
                                          <th>Status</th>
                                          <?php if ($retirada->status == 1) { ?>
                                          <th>Remover</th>
                                          <?php } ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                      <?php foreach($items as $item){ ?>
                                      <tr>
                                        <td><?php echo $item->id_retirada_item; ?></td>
                                        <td><?php echo $item->nome; ?></td>
                                        <td><?php echo $item->quantidade; ?></td>
                                        <td>
                                            <?php $status = $this->get_requisicao_status($status_lista, $item->status)?>
                                            <span class="badge badge-<?php echo $status['class'];?>"><?php echo $status['texto'];?></span>
                                        </td>
                                        <?php if ($retirada->status == 1) { ?>
                                        <td>
                                        <a 
                                            class="confirmar_registro"  data-tabela="<?php echo base_url("ferramental_estoque/detalhes/{$retirada->id_retirada}");?>" 
                                            href="javascript:void(0)" data-registro="<?php echo $item->id_retirada_item;?>"
                                            data-acao="Remover Item"  data-redirect="true"
                                            data-href="<?php echo base_url("ferramental_estoque/remove_item/{$item->id_retirada_item}");?>"
                                        >
                                            <button class="btn btn-sm btn-danger" type="button">                                                    
                                            <i class="fas fa-trash"></i>
                                            </button>                                                
                                        </a>
                                        
                                        </td>
                                        <?php } ?>
                                      </tr>
                                      <?php } ?>
                                    </tbody>
                                </table>
                                <hr>


                                <table class="table table-responsive table-borderless table-striped table-earning"  id="lista2">
                                    <thead>
                                        <tr class="active">
                                            <th scope="col" width="40%">Ativo ID</th>
                                            <th scope="col" width="40%">Código</th>
                                            <th scope="col" width="40%">Nome</th>
                                            <th scope="col">Data da Retirada</th>
                                            <th scope="col">Data da Entrega</th>
                                            <th scope="col">Situação</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($ativos as $ativo){ ?>
                                        <tr>
                                            <td><?php echo $ativo->id_ativo_externo; ?></td>
                                            <td><?php echo $ativo->codigo; ?></td>
                                            <td><?php echo $ativo->nome; ?></td>
                                            <td><?php echo isset($ativo->data_retirada) ? date("d/m/Y H:i", strtotime($ativo->data_retirada)) : '-'; ?></td>
                                            <td><?php echo isset($ativo->data_devolucao) ? date("d/m/Y H:i", strtotime($ativo->data_devolucao)) : '-'; ?></td>
                                            <td>
                                                <?php $status = $this->get_requisicao_status($status_lista, $ativo->status)?>
                                                <span class="badge badge-<?php echo $status['class'];?>"><?php echo $status['texto'];?></span>
                                            
                                                <?php if($retirada->status == 1) {?>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </form>
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