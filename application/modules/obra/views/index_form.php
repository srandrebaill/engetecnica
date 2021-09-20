<!-- MAIN CONTENT-->
<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="overview-wrap">
                        <h2 class="title-1"></h2>
                        <?php $id = isset($detalhes) ? "#".$detalhes->id_obra : '';?>
                        <a href="<?php echo base_url("obra$id"); ?>">
                        <button class="au-btn au-btn-icon au-btn--blue">
                        <i class="zmdi zmdi-arrow-left"></i>todos</button></a>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <h2 class="title-1 m-b-25">Obras</h2>

                    <div class="card">
                        <?php if(isset($detalhes) && isset($detalhes->id_obra)){?>
                            <div class="card-header">Editar Obra</div>
                        <?php }?>

                         <?php if(isset($detalhes) && !isset($detalhes->id_obra)) {?>
                            <div class="card-header">Nova Obra</div>
                         <?php } ?>
                        <div class="card-body">

                            <form action="<?php echo base_url('obra/salvar'); ?>" method="post" enctype="multipart/form-data">

                                <?php if(isset($detalhes) && isset($detalhes->id_obra)){?>
                                <input type="hidden" name="id_obra" id="id_obra" value="<?php echo $detalhes->id_obra; ?>">
                                <?php } ?>

                                <div class="row form-group">
                                    <div class="col col-md-4">
                                        <label for="codigo_obra" class="form-control-label">Código da Obra</label>
                                    </div>
                                    <div class="col-12 col-md-8">
                                        <input required="required" type="text" id="codigo_obra" name="codigo_obra" placeholder="Código da Obra" class="form-control" value="<?php if(isset($detalhes) && isset($detalhes->codigo_obra)){ echo $detalhes->codigo_obra; } ?>">
                                    </div>
                                </div>

                                <div class="row form-group">
                                    <div class="col col-md-4">
                                        <label for="razao_social" class=" form-control-label">Empresa Responsável</label>
                                    </div>
                                    <div class="col-12 col-md-8">
                                        <select required="required" class="form-control" id="id_empresa" name="id_empresa">
                                            <option value="">Selecione a Empresa Responsável</option>
                                            <?php foreach($empresas as $empresa){ ?>
                                                <option 
                                                    <?php echo (isset($detalhes) && isset($detalhes->id_empresa)) && ($empresa->id_empresa == $detalhes->id_empresa) ? "selected" : "";?>  
                                                    value="<?php echo $empresa->id_empresa;?>"
                                                >
                                                    <?php echo $empresa->razao_social; ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>

                                <?php $this->view("endereco_contato/endereco_form_fields"); ?>
                               

                                <div class="row form-group">
                                    <div class="col col-md-4">
                                        <label for="responsavel" class=" form-control-label">Técnico Responsável</label>
                                    </div>
                                    <div class="col-12 col-md-8">
                                        <input type="text" id="responsavel" name="responsavel" placeholder="Responsável" class="form-control" value="<?php if(isset($detalhes) && isset($detalhes->responsavel)){ echo $detalhes->responsavel; } ?>">
                                    </div>
                                </div>

                                <?php $this->view("endereco_contato/contato_form_fields", ['prefix' => 'responsavel']); ?>

                                <div class="row form-group">
                                    <div class="col col-md-2">
                                        <label for="observacao" class=" form-control-label">Observações</label>
                                    </div>
                                    <div class="col-12 col-md-10">
                                        <textarea name="observacao" id="observacao" rows="9" placeholder="Observações..." class="form-control"><?php if(isset($detalhes) && isset($detalhes->observacao)){ echo $detalhes->observacao; } ?></textarea>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <div class="col col-md-2">
                                        <label for="situacao" class=" form-control-label">Situação</label>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <select name="situacao" id="situacao" class="form-control">
                                            <option value="1" <?php if(isset($detalhes) && isset($detalhes->situacao) && $detalhes->situacao==1){ echo "selected='selected'"; } ?>>Inativo</option>
                                            <option value="0" <?php if(isset($detalhes) && isset($detalhes->situacao) && $detalhes->situacao==0){ echo "selected='selected'"; } ?>>Ativo</option>
                                        </select>
                                    </div>

                                    <div class="col col-md-2">
                                        <label for="obra_base" class=" form-control-label">Obra Base</label>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <select name="obra_base" id="obra_base" class="form-control">
                                            <option value="0" <?php if(isset($detalhes) && isset($detalhes->obra_base) && $detalhes->obra_base==0){ echo "selected='selected'"; } ?>>Não</option>
                                            <option value="1" <?php if(isset($detalhes) && isset($detalhes->obra_base) && $detalhes->obra_base==1){ echo "selected='selected'"; } ?>>Sim</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <hr>
                                <div class="pull-left">
                                    <button class="btn btn-primary">                                                    
                                        <i class="fa fa-send "></i>&nbsp;
                                        <span id="submit-form">Salvar</span>
                                    </button>
                                    <a href="<?php echo base_url('obra');?>">
                                    <button class="btn btn-info" type="button">                                                    
                                        <i class="fa fa-remove "></i>&nbsp;
                                        <span id="cancelar-form">Cancelar</span>
                                    </button>                                                
                                    </a>
                                </div>
                            </form>

                        </div>
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
