<!-- MAIN CONTENT-->
<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="overview-wrap">
                        <h2 class="title-1"></h2>
                        <a href="<?php echo base_url('ferramental_requisicao'); ?>">
                        <button class="au-btn au-btn-icon au-btn--blue">
                        <i class="zmdi zmdi-arrow-left"></i>todos</button></a>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <?php if ($user->nivel == 1) { ?>
                    <h2 class="title-1 m-b-25">Transferência de Ferramentas</h2>
                    <?php } if ($user->nivel == 2) { ?>
                    <h2 class="title-1 m-b-25">Requisição de Ferramentas</h2>
                    <?php }  ?>

                    <div class="card">
                        <div class="card-header">Incluir Requisição</div>
                        <div class="card-body">

                            <form action="<?php echo base_url('ferramental_requisicao/salvar'); ?>" method="post" enctype="multipart/form-data">

                                <?php if(isset($detalhes) && isset($detalhes->id_ferramental_requisicao)){?>
                                <input type="hidden" name="id_ferramental_requisicao" id="id_ferramental_requisicao" value="<?php echo $detalhes->id_ferramental_requisicao; ?>">
                                <?php } ?> 

                                <?php if(isset($obras) && $user->nivel == 1) { ?>
                                <div class="row form-group">
                                    <div class="col col-md-2">
                                        <label for="id_destino" class=" form-control-label">Obra Destino</label>
                                    </div>
                                    <div class="col-12 col-md-8">
                                        <select 
                                            class="form-control" 
                                            name="id_destino" 
                                            id="id_destino"
                                            required="required"
                                        >
                                            <option value="">Nenhuma obra selecionada</option>
                                            <?php foreach ($obras as $obra) {
                                                    if ($obra->id_obra != $user->id_obra) {
                                                ?>
                                                <option 
                                                    <?php echo isset($detalhes->id_obra) && $obra->id_obra == $detalhes->id_obra ? 'selected' : ''?>
                                                    value="<?php echo $obra->id_obra; ?>"
                                                >
                                                    <?php echo "{$obra->id_obra} - {$obra->codigo_obra}"; ?>
                                                </option>
                                            <?php }} ?>
                                        </select>
                                    </div>
                                </div>  
                                <?php } ?> 
                             


                                <div class="row">
                                    <div class="col-md-8"><label for="">Item</label></div>
                                    <div class="col-md-2"><label for="">Quantidade</label></div>
                                    <div class="col-md-2"><label for=""></label></div>
                                </div>
                                <div class="listagem">

                                </div>
                                
                                <hr>

                                <div class="row form-group">                                   
                                    <div class="col-12 col-md-12">
                                        <input type="text" class="form-control" name="observacoes" id="observacoes" placeholder="Alguma observação?">
                                    </div>                                    
                                </div>

                                <hr>
                                <div class="pull-left">
                                    <button class="btn btn-primary">                                                    
                                        <i class="fa fa-send "></i>&nbsp;
                                        <span id="submit-form">Enviar Requisição</span>
                                    </button>
                                    <a href="<?php echo base_url('ferramental_requisicao');?>">
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

<template id="item_lista">
    <div class="row item-lista" style="margin-bottom: 10px;">
        <div class="col-md-8">
            <div class="exchange1">
                <select required="required" name="id_ativo_externo_grupo[]" class="form-control">
                    <option value="">Buscar Item</option>
                    <?php foreach ($grupos as $grupo) { ?>
                        <option value="<?php echo $grupo->id_ativo_externo_grupo; ?>"><?php echo $grupo->nome; ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>

        <div class="col-md-2">
            <input required="required" name="quantidade[]" type="number" placeholder="0" class="form-control quantidade" value="1" min="1" max="100">
        </div>
        <div class="col-md-2" nowrap>
            <p>
                <button type="button" class="btn btn-sm btn-primary add_line"><i class="fa fa-plus"></i></button>
                <button type="button" class="btn btn-sm btn-danger remove_line"><i class="fa fa-minus"></i></button>
            </p>
        </div>
    </div>
</template>

