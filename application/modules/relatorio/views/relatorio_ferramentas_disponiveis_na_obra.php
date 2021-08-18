<page>
<style media="all"><?php echo $css;?></style>
<header>
    <img src="<?php echo $header;?>">
</header>

  <h1>Ferramentas Diponíveis na Obra (Em uso ou não)</h1>
  <p>Relatório de Ferramentas diponíveis, gerado em <?php echo date('d/m/Y H:i:s', strtotime('now')); ?>.</p>
  
  <?php foreach($relatorio as $i => $obra) { ?>
  <h2><?php echo $obra->codigo_obra;?></h2>
  <table class="tabela">
      <thead>
          <tr>
            <th>ID Grupo</th>
            <th>Grupo Nome</th>
            <!--<th>Endereço</th>-->
            <th>Em estoque</th>
            <th>Liberado</th>
            <th>Em Trânsito</th>
            <th>Em Operação</th>
            <th>Fora de Operação</th>
            <th>Com Defeito</th>
            <th>Total</th>
          </tr>
      </thead>
      <tbody>
          <?php foreach($obra->grupos as $j => $grupo) { ?>
          <tr>
            <td><?php echo $grupo->id_ativo_externo_grupo; ?></td>
            <td><?php echo $grupo->nome; ?></td>
            <!--<td><?php echo $grupo->endereco;?> </td>-->
            <td><?php echo $grupo->estoque;?> </td>
            <td><?php echo $grupo->liberado; ?> </td>
            <td><?php echo $grupo->transito; ?> </td>
            <td><?php echo $grupo->emoperacao; ?> </td>
            <td><?php echo $grupo->foradeoperacao; ?> </td>
            <td><?php echo $grupo->comdefeito; ?> </td>
            <td><?php echo $grupo->total;?> </td>
          </tr>
        <?php } ?>
      </tbody>
  </table>
  <?php } ?>


<footer>
  <img src="<?php echo $footer; ?>"><br>
  <small><b>ENGETÉCNICA ENGENHARIA E CONSTRUÇÃO LTDA</b>, Rua João Bettega, n.1160, Portão, Curitiba-PR | Fone: (41) 4040-4676</small>
</footer>
</page>