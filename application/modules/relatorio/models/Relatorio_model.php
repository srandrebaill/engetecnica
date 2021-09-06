<?php 
require_once(__DIR__."/Relatorio_model_base.php");
class Relatorio_model extends Relatorio_model_base {

  public function __construct() {
      parent::__construct();
  }

  private function extract_data($tipo, $data){
    $extracted_data = [];
    foreach($this->relatorios[$tipo]['filtros'] as $filtro){
      $extracted_data[$filtro] = $data[$filtro];
    }

    if (isset($extracted_data['periodo']) && ($extracted_data['periodo']['tipo'] == 'outro')) {
      $extracted_data['periodo']['inicio'] = "{$extracted_data['periodo']['inicio']} 00:00:00";
      $extracted_data['periodo']['fim'] = "{$extracted_data['periodo']['fim']} 23:59:59";
    }
    return $extracted_data;
  }

	public function funcionario($data=null, $tipo=null) {
    $data = $this->extract_data('funcionario', $data);
    $inicio = $data['periodo']['inicio'];
    $fim = $data['periodo']['fim'];

    $relatorio = null;
    if ($tipo && $tipo == 'arquivo') {
      $relatorio = $this->db->from('funcionario fnc')->select('fnc.*');
    } else {

      $relatorio = $this->db
              ->from('funcionario fnc')
              ->select('COUNT(fnc.id_funcionario) as total');
      
      $select = "select COUNT(situacao) FROM funcionario WHERE (situacao = '0'";
      $select2 = "select COUNT(situacao) FROM funcionario WHERE (situacao = '1'";

      if ($data['id_empresa']) {
        $select .= " and id_empresa = fnc.id_empresa";
        $select2 .= " and id_empresa = fnc.id_empresa";
      }
    
      if ($data['id_obra']) {
        $select .= " and id_obra = fnc.id_obra";
        $select2 .= " and id_obra = fnc.id_obra";
      }

      if ($inicio && $fim) {
        $select .= " and (data_criacao >= '$inicio' and data_criacao <= '$fim')";
        $select2 .= " and (data_criacao >= '$inicio' and data_criacao <= '$fim')";
      }

      $select .= ")";
      $select2 .= ")";
      $relatorio
              ->select("($select) as ativos")
              ->select("($select2) as inativos");
    }

    $relatorio
    ->select('emp.id_empresa, emp.razao_social as empresa')
    ->join('empresa emp', 'fnc.id_empresa = emp.id_empresa','left');

    if ($data['id_empresa']) {
      $relatorio->where("fnc.id_empresa = {$data['id_empresa']}");
    }

    $relatorio
      ->select('ob.id_obra, ob.codigo_obra as obra, ob.endereco')
      ->join('obra ob', 'fnc.id_obra = ob.id_obra', 'left');

    if ($data['id_obra']) {
      $relatorio->where("fnc.id_obra = {$data['id_obra']}");
    }

    if ($inicio && $fim) {
      $relatorio->where("fnc.data_criacao >= '$inicio'")
                 ->where("fnc.data_criacao <= '$fim'");
    }
    
    if ($tipo && $tipo == 'arquivo') {
      return $relatorio->group_by('fnc.id_funcionario')->get()->result();
    }
    return $relatorio->get()->row();
  }

  public function empresa($data=null, $tipo=null) {
    $data = $this->extract_data('empresa', $data);
    $inicio = $data['periodo']['inicio'];
    $fim = $data['periodo']['fim'];

    $relatorio = null;
    if ($tipo && $tipo == 'arquivo') {
      $relatorio = $this->db->from('empresa emp')->select('emp.*');
    } else {
      $relatorio = $this->db
              ->from('empresa emp')
              ->select('COUNT(emp.id_empresa) as total');

      $select = "select COUNT(situacao) FROM funcionario WHERE (situacao = '0'";
      $select2 = "select COUNT(situacao) FROM funcionario WHERE (situacao = '1'";

      $inicio = $data['periodo']['inicio'];
      $fim = $data['periodo']['fim'];
      if ($inicio && $fim) {
        $select .= " and (data_criacao >= '$inicio' and data_criacao <= '$fim')";
        $select2 .= " and (data_criacao >= '$inicio' and data_criacao <= '$fim')";
      }
      $select .= ")";
      $select2 .= ")";

      $relatorio
              ->select("($select) as ativos")
              ->select("($select2) as inativos");
    }

    if ($inicio && $fim) {
      $relatorio->where("emp.data_criacao >= '$inicio'")
                 ->where("emp.data_criacao <= '$fim'");
    }

    if ($tipo && $tipo == 'arquivo') { 
      return $relatorio->group_by('emp.id_empresa')->get()->result();
    }
    return $relatorio->get()->row();
  }

  public function obra($data=null, $tipo=null) {
    $data = $this->extract_data('obra', $data);
    $inicio = $data['periodo']['inicio'];
    $fim = $data['periodo']['fim'];

    $relatorio = null;
    if ($tipo && $tipo == 'arquivo') {
      $relatorio = $this->db->from('obra ob')->select('ob.*');
    } else {
      $relatorio = $this->db
              ->from('obra ob')
              ->select('COUNT(ob.id_obra) as total');

      $select = "select COUNT(situacao) FROM obra WHERE (situacao = '0'";
      $select2 = "select COUNT(situacao) FROM obra WHERE (situacao = '1'";

      if ($data['id_empresa']) {
        $select .= " and id_empresa = ob.id_empresa";
        $select2 .= " and id_empresa = ob.id_empresa";
      }
    
      if ($inicio && $fim) {
        $select .= " and (data_criacao >= '$inicio' and data_criacao <= '$fim')";
        $select2 .= " and (data_criacao >= '$inicio' and data_criacao <= '$fim')";
      }
      $select .= ")";
      $select2 .= ")";
      $relatorio
          ->select("($select) as ativos")
          ->select("($select2) as inativos");
    }

    $relatorio
        ->select('emp.id_empresa, emp.razao_social as empresa')
        ->join('empresa emp', 'ob.id_empresa = emp.id_empresa');

    if ($data['id_empresa']) {
      $relatorio->where("ob.id_empresa = {$data['id_empresa']}");
    }

    if ($inicio && $fim) {
      $relatorio->where("ob.data_criacao >= '$inicio'")
                 ->where("ob.data_criacao <= '$fim'")
                 ->group_by('ob.id_obra');
    }
    
    if ($tipo && $tipo == 'arquivo') { 
      return $relatorio->get()->result();
    }
    return $relatorio->get()->row();
  }

  public function ferramentas_disponiveis_na_obra($data=null, $tipo=null) {
    $data = $this->extract_data('ferramentas_disponiveis_na_obra', $data);
    $relatorio = null;

    if ($tipo && $tipo == 'arquivo') {
      if ($data['id_obra']) {
        $obra = $this->obra_model->get_obra($data['id_obra']);
        $obra->grupos = [];
        $obra->grupos = $this->ativo_externo_model->get_grupos($obra->id_obra);
        
        return [$obra];
      }

      $obras = $this->obra_model->get_obras();
      foreach($obras as $obra){
        $grupos = $this->ativo_externo_model->get_grupos($obra->id_obra);

        if ($data['valor_total'] === "true") {
          $obra->total_obra = 0;
          foreach($grupos as $grupo){ 
            $grupo->total_grupo = 0;
            foreach($grupo->ativos as $ativo){ 
              $grupo->total_grupo += floatval($ativo->valor);
            } 
            $obra->total_obra += floatval($grupo->total_grupo);
          }
        }

        $obra->grupos = $grupos;
      }

      $obras_data = [
        'obras' => $obras,
        'show_valor_total' => $data['valor_total'] === "true"
      ];

      return $obras_data;
    } else {
      $relatorio = $this->db
      ->from('ativo_externo atv')
      ->select('COUNT(atv.id_ativo_externo) as total');

      //'Em Estoque', 'Liberado' ,'Em Transito', 'Em Operação', 'Fora de Operação', 'Com Defeito', 'Total'
      $select = "select COUNT(id_ativo_externo) FROM ativo_externo WHERE (situacao = 12";
      $select2 = "select COUNT(id_ativo_externo) FROM ativo_externo WHERE (situacao = 2";
      $select3 = "select COUNT(id_ativo_externo) FROM ativo_externo WHERE (situacao = 3";
      $select4 = "select COUNT(id_ativo_externo) FROM ativo_externo WHERE (situacao = 5";
      $select5 = "select COUNT(id_ativo_externo) FROM ativo_externo WHERE (situacao = 8";
      $select6 = "select COUNT(id_ativo_externo) FROM ativo_externo WHERE (situacao = 10";

      if ($data['id_obra']) {
        $select .= " and id_obra = atv.id_obra";
        $select2 .= " and id_obra = atv.id_obra";
        $select3 .= " and id_obra = atv.id_obra";
        $select4 .= " and id_obra = atv.id_obra";
        $select5 .= " and id_obra = atv.id_obra";
        $select6 .= " and id_obra = atv.id_obra";
      }

      $select .= ")";
      $select2 .= ")";
      $select3 .= ")";
      $select4 .= ")";
      $select5 .= ")";
      $select6 .= ")";

      $relatorio
          ->select("($select) as em_estoque")
          ->select("($select2) as liberado")
          ->select("($select3) as em_transito")
          ->select("($select4) as em_operacao")
          ->select("($select5) as fora_de_operacao")
          ->select("($select6) as com_defeito");
    }

    $relatorio
      ->select('ob.id_obra, ob.codigo_obra as obra, ob.endereco')
      ->join('obra ob', 'atv.id_obra = ob.id_obra', 'left');

    if ($data['id_obra']) {
        $relatorio->where("atv.id_obra = {$data['id_obra']}");
    }

    if ($tipo && $tipo == 'arquivo') { 
      return $relatorio->get()->result();
    }
    return $relatorio->get()->row();
  }

  public function ferramentas_em_estoque($data=null, $tipo=null){
    $data = $this->extract_data('ferramentas_em_estoque', $data);
    $relatorio = null;

    if ($tipo && $tipo == 'arquivo') {
      if ($data['id_obra']) {
        $obra = $this->obra_model->get_obra($data['id_obra']);
        $obra->grupos = $this->ativo_externo_model->get_grupos($obra->id_obra);
        return [$obra];
      }

      $obras = $this->obra_model->get_obras();
      foreach($obras as $obra){
        $obra->grupos = $this->ativo_externo_model->get_grupos($obra->id_obra, null, 12);
      }
      return $obras;
    } else {
      $relatorio = $this->db
      ->from('ativo_externo atv')
      ->select('COUNT(atv.id_ativo_externo) as total, atv.id_obra')
      ->where("atv.situacao = 12");

      if ($data['id_obra']) {
          $relatorio
          ->select('ob.id_obra, ob.codigo_obra as nome, ob.endereco as endereco')
          ->join('obra ob', 'atv.id_obra = ob.id_obra')
          ->where("atv.id_obra = {$data['id_obra']}");
      } else {
        $relatorio
          ->select("ob.id_obra, ob.codigo_obra as nome, ob.endereco as endereco")
          ->join('obra ob', 'atv.id_obra = ob.id_obra')
          ->where("atv.id_obra = ob.id_obra");
      }

      $obras = $relatorio->group_by('atv.id_obra')->get()->result();
      $relatorio = [
        'total' => 0,
      ];

      foreach ($obras as $key => $obra) {
        $relatorio[str_replace([' ', '-'], ['_', ''] ,strtolower($obra->nome))] = (int) $obra->total;
        $relatorio['total'] += (int) $obra->total;
      }
      return (object) $relatorio;
    }
  }

  public function equipamentos_em_estoque($data=null, $tipo=null){
    $data = $this->extract_data('equipamentos_em_estoque', $data);
    $relatorio = null;

    if ($tipo && $tipo == 'arquivo') {
      if ($data['id_obra']) {
        $obra = $this->obra_model->get_obra($data['id_obra']);
        $obra->equipamentos = $this->ativo_interno_model->get_lista($obra->id_obra, 0);
        return [$obra];
      }

      $obras = $this->obra_model->get_obras();
      foreach($obras as $obra){
        $obra->equipamentos = $this->ativo_interno_model->get_lista($obra->id_obra, 0);
      }
      return $obras;

    } else {
      $relatorio = $this->db
        ->from('ativo_interno atv')
        ->select('COUNT(atv.id_ativo_interno) as total, atv.id_obra')
        ->where("atv.situacao = 0")
        ->select('ob.id_obra, ob.codigo_obra as nome, ob.endereco as endereco')
        ->join('obra ob', 'atv.id_obra = ob.id_obra');

      if ($data['id_obra']) {
          $relatorio->where("atv.id_obra = {$data['id_obra']}");
      } else {
        $relatorio->where("atv.id_obra = ob.id_obra");
      }
      $relatorio->group_by('atv.id_obra');

      if ($tipo && $tipo == 'arquivo') {
        return $relatorio->get()->result();
      }

      $obras = $relatorio->get()->result();
      $relatorio = [
        'total' => 0,
      ];

      foreach ($obras as $key => $obra) {
        $relatorio[str_replace([' ', '-'], ['_', ''] ,strtolower($obra->nome))] = (int) $obra->total;
        $relatorio['total'] += (int) $obra->total;
      }

      return (object) $relatorio;
    }
  }

  public function veiculos_disponiveis($data=null, $tipo = null){
    $data = $this->extract_data('veiculos_disponiveis', $data);

    if ($tipo && $tipo == 'arquivo') {
      $relatorio = $this->db ->from('ativo_veiculo atv');
      if ($data['tipo_veiculo'] && $data['tipo_veiculo'] !== 'todos') {
          $relatorio->where("tipo_veiculo = {$data['tipo_veiculo']}");
      }
      return  $relatorio->where("situacao = '0'")->get()->result();
    }

    $relatorio = $this->db
    ->from('ativo_veiculo atv')
    ->select('COUNT(atv.id_ativo_veiculo) as total');

    if ($data['tipo_veiculo'] && $data['tipo_veiculo'] !== 'todos') {
        $select = "select COUNT(id_ativo_veiculo) FROM ativo_veiculo WHERE (tipo_veiculo = '{$data['tipo_veiculo']}' and situacao = '0')";
        $relatorio->select("($select) as '{$data['tipo_veiculo']}'");
    } else {
      $select = "select COUNT(id_ativo_veiculo) FROM ativo_veiculo WHERE (tipo_veiculo = 'carro' and situacao = '0')";
      $select2 = "select COUNT(id_ativo_veiculo) FROM ativo_veiculo WHERE (tipo_veiculo = 'moto' and situacao = '0')";
      $select3 = "select COUNT(id_ativo_veiculo) FROM ativo_veiculo WHERE (tipo_veiculo = 'caminhao' and situacao = '0')";
      $relatorio->select("($select) as carro")
                ->select("($select2) as moto")
                ->select("($select3) as caminhao");
    }
    return $relatorio->where("atv.situacao = '0'")->get()->row();
  }

  public function veiculos_depreciacao($data=null){
    $data = $this->extract_data('veiculos_depreciacao', $data);

    $relatorio = $this->db
        ->from('ativo_veiculo_depreciacao vdp')
        ->join('ativo_veiculo atv', 'vdp.id_ativo_veiculo = atv.id_ativo_veiculo');
    
    $inicio = $data['periodo']['inicio'];
    $fim = $data['periodo']['fim'];

    if ($inicio && $fim) {
      $relatorio->where("vdp.veiculo_data >= '$inicio'")
                 ->where("vdp.veiculo_data <= '$fim'");
    }
    return $relatorio->get()->result();
  }

  public function veiculos_abastecimentos($data=null){
    $veiculos_abastecimentos = $this->custos_veiculos_abastecimentos($this->extract_data('veiculos_abastecimentos', $data), 'arquivo');

    return (object) [
        'abastecimentos' => $veiculos_abastecimentos->lista,
        'total' => $veiculos_abastecimentos->total
    ];
  }

  public function custos_ferramentas($data, $tipo=null){
    $ferramentas = null;
    $ferramentas_total = null;
    $inicio = $data['periodo']['inicio'];
    $fim = $data['periodo']['fim'];

    if ($tipo && $tipo == 'arquivo') {
      //Ferramentas
      $ferramentas = $this->db->from('ativo_externo ate');
      if ($inicio && $fim) {
        $ferramentas->where("ate.data_inclusao >= '$inicio'")
                  ->where("ate.data_inclusao <= '$fim'");
      }

      if ($data['id_obra']) {
        $ferramentas->where("ate.id_obra = {$data['id_obra']}");
      }
      $ferramentas = $ferramentas->get()->result();
    }

    //Ferramentas total
    $this->db->reset_query();
    $ferramentas_total = $this->db
          ->from('ativo_externo ates')
          ->select("SUM(ates.valor) as valor");

    if ($inicio && $fim) {
      $ferramentas_total->where("ates.data_inclusao >= '$inicio'")
                  ->where("ates.data_inclusao <= '$fim'");
    }

    if ($data['id_obra']) {
      $ferramentas_total->where("ates.id_obra = {$data['id_obra']}");
    }
    $ferramentas_total = $ferramentas_total->get()->row();

    
    if ($tipo && $tipo == 'arquivo') {
      return (object) [
        'lista' =>  $ferramentas,
        'total' => $this->formata_moeda($ferramentas_total->valor),
      ];
    }

    return (object) [
      'lista' =>  $ferramentas,
      'total' => $this->formata_moeda($ferramentas_total->valor, true),
    ];
  }

  public function custos_equipamentos($data, $tipo=null){
    $equipamentos =  null;
    $equipamentos_total = null;
    $inicio = $data['periodo']['inicio'];
    $fim = $data['periodo']['fim'];

    if ($tipo && $tipo == 'arquivo') {
      //Equipamentos
      $equipamentos = $this->db->from('ativo_interno ati');
      if ($inicio && $fim) {
        $equipamentos->where("ati.data_inclusao >= '$inicio'")
                  ->where("ati.data_inclusao <= '$fim'");
      }

      if ($data['id_obra']) {
        $equipamentos->where("ati.id_obra = {$data['id_obra']}");
      }
      $equipamentos = $equipamentos->get()->result();
   }

    //Equipamentos total
    $this->db->reset_query();
    $equipamentos_total = $this->db
          ->from('ativo_interno atei')
          ->select("SUM(atei.valor) as valor");

    if ($inicio && $fim) {
      $equipamentos_total->where("atei.data_inclusao >= '$inicio'")
                  ->where("atei.data_inclusao <= '$fim'");
    }

    if ($data['id_obra']) {
      $equipamentos_total->where("atei.id_obra = {$data['id_obra']}");
    }
    $equipamentos_total = $equipamentos_total->get()->row();

    if ($tipo && $tipo == 'arquivo') {
      return (object) [
        'lista' =>  $equipamentos,
        'total' => $this->formata_moeda($equipamentos_total->valor),
      ];
    }

    return (object) [
      'lista' =>  $equipamentos,
      'total' => $this->formata_moeda($equipamentos_total->valor, true),
    ];
  }

  public function custos_equipamentos_manutecoes($data, $tipo=null){
    $equipamentos_manutencao =  null;
    $equipamentos_manutencao_total = null;
    $inicio = $data['periodo']['inicio'];
    $fim = $data['periodo']['fim'];

    if ($tipo && $tipo == 'arquivo') {
      //Equipamentos manuteções
      $equipamentos_manutencao = $this->db->from('ativo_interno_manutencao atm')
                                        ->select('atm.*, atv.*, atm.valor as manutencao_valor, atv.valor as equipamento_valor')
                                        ->join('ativo_interno atv', 'atv.id_ativo_interno = atm.id_ativo_interno');
      if ($inicio && $fim) {
        $equipamentos_manutencao
            ->where("atm.data_retorno >= '$inicio'")
            ->where("atm.data_retorno <= '$fim'");
      }

      if ($data['id_obra']) {
        $equipamentos_manutencao->where("atm.id_obra = {$data['id_obra']}");
      }
      $equipamentos_manutencao = $equipamentos_manutencao
                                      ->group_by('atm.id_manutencao')
                                      ->get()->result();
   }

    //Equipamentos manuteções total
    $this->db->reset_query();
    $equipamentos_manutencao_total = $this->db
          ->from('ativo_interno_manutencao atmc')
          ->select("SUM(atmc.valor) as valor");

    if ($inicio && $fim) {
      $equipamentos_manutencao_total
                  ->where("atmc.data_retorno >= '$inicio'")
                  ->where("atmc.data_retorno <= '$fim'");
    }

    if ($data['id_obra']) {
      $equipamentos_manutencao_total->where("atmc.id_obra = {$data['id_obra']}");
    }
    $equipamentos_manutencao_total = $equipamentos_manutencao_total->get()->row();

    if ($tipo && $tipo == 'arquivo') {
      return (object) [
        'lista' =>  $equipamentos_manutencao,
        'total' => $this->formata_moeda($equipamentos_manutencao_total->valor),
      ];
    }

    return (object) [
      'lista' =>  $equipamentos_manutencao,
      'total' => $this->formata_moeda($equipamentos_manutencao_total->valor, true),
    ];
  }

  public function custos_veiculos_manutecoes($data, $tipo=null){
    $veiculos_manutencao =  null;
    $veiculos_manutencao_total = null;
    $inicio = $data['periodo']['inicio'];
    $fim = $data['periodo']['fim'];

    if ($tipo && $tipo == 'arquivo') {
      //Veiculos manuteções
      $veiculos_manutencao = $this->db->from('ativo_veiculo_manutencao atvm')
                                      ->select('atvm.*, atv.*, fn.id_fornecedor, fn.razao_social as fornecedor')
                                      ->join('ativo_veiculo atv', 'atv.id_ativo_veiculo = atvm.id_ativo_veiculo')
                                      ->join('fornecedor fn', 'fn.id_fornecedor = atvm.id_fornecedor');
      if ($inicio && $fim) {
        $veiculos_manutencao
            ->where("atvm.data_saida >= '$inicio'")
            ->where("atvm.data_saida <= '$fim'");
      }

      if ($data['id_obra']) {
        $veiculos_manutencao->where("atvm.id_obra = {$data['id_obra']}");
      }
      $veiculos_manutencao = $veiculos_manutencao
                              ->group_by('atvm.id_ativo_veiculo_manutencao')
                              ->get()->result();
   }

    //Veiculos manuteções total
    $this->db->reset_query();
    $veiculos_manutencao_total = $this->db
          ->from('ativo_veiculo_manutencao atvmc')
          ->select("SUM(atvmc.veiculo_custo) as valor");

    if ($inicio && $fim) {
      $veiculos_manutencao_total
                  ->where("atvmc.data_saida >= '$inicio'")
                  ->where("atvmc.data_saida <= '$fim'");
    }

    if ($data['id_obra']) {
      $veiculos_manutencao_total->where("atvmc.id_obra = {$data['id_obra']}");
    }
    $veiculos_manutencao_total = $veiculos_manutencao_total->get()->row();

    if ($tipo && $tipo == 'arquivo') {
      return (object) [
        'lista' =>  $veiculos_manutencao,
        'total' => $this->formata_moeda($veiculos_manutencao_total->valor),
      ];
    }

    return (object) [
      'lista' =>  $veiculos_manutencao,
      'total' => $this->formata_moeda($veiculos_manutencao_total->valor, true),
    ];
  }

  public function custos_veiculos_abastecimentos($data, $tipo=null){
    $inicio = $data['periodo']['inicio'];
    $fim = $data['periodo']['fim'];

    //Veiculos abastecimentos
    $veiculos_abastecimento = $this->db->from('ativo_veiculo_quilometragem km')
                                        ->select('km.*, atv.*')
                                        ->join('ativo_veiculo atv', 'atv.id_ativo_veiculo = km.id_ativo_veiculo');
    if ($inicio && $fim) {
      $veiculos_abastecimento
          ->where("km.data >= '$inicio'")
          ->where("km.data <= '$fim'");
    }

    if (isset($data['veiculo_placa'])) {
      $veiculos_abastecimento->like("veiculo_placa", $data['veiculo_placa']);
    }

    $veiculos_abastecimento = $veiculos_abastecimento->get()->result();
  
    $valor = 0;
    if (count($veiculos_abastecimento) > 0){
      foreach($veiculos_abastecimento as $ab => $abastecimento) {
        $litros = (float) $abastecimento->veiculo_litros;
        $custo = (float) $abastecimento->veiculo_custo;
        $veiculos_abastecimento[$ab]->veiculo_custo_total = ($litros * $custo);
        $valor += $veiculos_abastecimento[$ab]->veiculo_custo_total;
      }
    }
    return (object) [
      'lista' =>  $veiculos_abastecimento,
      'total' => $this->formata_moeda($valor, ($tipo != 'arquivo')),
    ];
  }

  public function centro_de_custo($data=null, $tipo=null){
    $data = $this->extract_data('centro_de_custo', $data);
    $equipamentos =  $this->custos_equipamentos($data, $tipo);
    $equipamentos_manutecoes = $this->custos_equipamentos_manutecoes($data, $tipo);
    $ferramentas =  $this->custos_ferramentas($data, $tipo);
    $veiculos_manutecoes = $this->custos_veiculos_manutecoes($data, $tipo);
    $veiculos_abastecimentos = $this->custos_veiculos_abastecimentos($data, $tipo);

    if ($tipo && $tipo == 'arquivo') {
      return (object) [
        'ferramentas' =>  $ferramentas->lista,
        'ferramentas_total' => $ferramentas->total,
        'equipamentos' =>  $equipamentos->lista,
        'equipamentos_total' => $equipamentos->total,
        'equipamentos_manutecoes' => $equipamentos_manutecoes->lista, 
        'equipamentos_manutecoes_total' => $equipamentos_manutecoes->total, 
        'veiculos_abastecimentos' => $veiculos_abastecimentos->lista, 
        'veiculos_abastecimentos_total' => $veiculos_abastecimentos->total, 
        'veiculos_manutecoes' => $veiculos_manutecoes->lista, 
        'veiculos_manutecoes_total' => $veiculos_manutecoes->total, 
        'total' => $this->formata_moeda(array_sum([
          $ferramentas->total,
          $equipamentos->total
        ]))
      ];
    }

    $relatorio = [
        'ferramentas' =>  $ferramentas->total,
        'equipamentos' =>  $equipamentos->total,
        'equipamentos_manutecoes' => $equipamentos_manutecoes->total,
        'veiculos_abastecimentos' => $veiculos_abastecimentos->total, 
        'veiculos_manutecoes' => $veiculos_manutecoes->total, 
        'total' => $this->formata_moeda(array_sum([
          $ferramentas->total,
          $equipamentos->total,
          $equipamentos_manutecoes->total,
          $veiculos_manutecoes->total,
          $veiculos_abastecimentos->total
        ]), true)
    ];
    return (object) $relatorio;       
  }

  private function get_patrimonio_obra_items($obra, $show_valor_total = true){
    $obra->equipamentos = $this->ativo_interno_model->get_lista($obra->id_obra);
    if ($show_valor_total) {
      $obra->equipamentos_total = 0;
      foreach($obra->equipamentos as $equipamento){
          $obra->equipamentos_total  +=  floatval($equipamento->valor);
      }
    }

    $obra->ferramentas = $this->ativo_externo_model->get_ativos($obra->id_obra);
    if ($show_valor_total) {
      $obra->ferramentas_total = 0;
      foreach($obra->ferramentas as $ferramenta){
        $obra->ferramentas_total  += floatval($ferramenta->valor);
      }
    }
    return $obra;
  }

  public function patrimonio_disponivel($data=null, $tipo=null){
    $data = $this->extract_data('patrimonio_disponivel', $data);

    if ($tipo && $tipo == 'arquivo') {
      $relatorio = $this->db ->from('ativo_veiculo atv');
      if ($data['tipo_veiculo'] && $data['tipo_veiculo'] !== 'todos') {
        $relatorio->where("tipo_veiculo = {$data['tipo_veiculo']}");
      }
      $veiculos = $relatorio->where("situacao = '0'")->get()->result();
      $veiculos_total = array_sum(array_map(function($veiculo) {
        return floatval($veiculo->valor_fipe);
      }, $veiculos));

      $obras = [];
      $show_valor_total = isset($data['valor_total']) && $data['valor_total'] === "true";
      if ($data['id_obra']) {
        $obra = $this->obra_model->get_obra($data['id_obra']);
        $obras[] = $this->get_patrimonio_obra_items($obra, $show_valor_total);
      } else {
        $obras_models = $this->obra_model->get_obras();
        foreach($obras_models as $obra){
          $obras[] = $this->get_patrimonio_obra_items($obra, $show_valor_total);
        }
      }

      return (object) [
        'veiculos' => $veiculos,
        'veiculos_total' => $veiculos_total,
        'obras' => $obras,
        'show_valor_total' => $show_valor_total
      ];
    }
  
    $ativo_interno = $this->db
      ->from('ativo_interno ati')
      ->select('COUNT(ati.id_ativo_interno) as equipamentos')
      ->where('ati.situacao = 0');

    if ($data['id_obra']){
      $ativo_interno->where("ati.id_obra = {$data['id_obra']}");
    }
    $ativos_internos = $ativo_interno->get()->row();

    $ativo_externo = $this->db
      ->from('ativo_externo ate')
      ->select('COUNT(ate.id_ativo_externo) as ferramentas')
      ->where('ate.situacao = 12');

    if ($data['id_obra']){
      $ativo_externo->where("ate.id_obra = {$data['id_obra']}");
    }
    $ativos_externos =  $ativo_externo->get()->row();

    if (!$data['id_obra']){
      $ativos_veiculos = $this->db
        ->from('ativo_veiculo atv')
        ->select('COUNT(atv.id_ativo_veiculo) as veiculos')
        ->where("atv.situacao = '0'")
        ->get()->row();
    }

    return (object) array_merge(
      (array)  $ativos_internos, 
      (array) $ativos_externos,
      !$data['id_obra'] ? (array) $ativos_veiculos : [],
      [
        'total_de_items' => ($ativos_internos->equipamentos + $ativos_externos->ferramentas) + (!$data['id_obra'] ? $ativos_veiculos->veiculos : 0)
      ]
    );
  }


  public function count_ativos_externos($periodo_inicio, $periodo_fim){
    return $this->ativo_externo_model->ativos()
            ->where("data_inclusao >= '$periodo_inicio'")
            ->where("data_inclusao <= '$periodo_fim'")
            ->where("data_descarte IS NULL")
             ->get()->num_rows();
  }

  public function count_ativos_internos($periodo_inicio, $periodo_fim){
    return $this->ativo_interno_model->ativos()
            ->where("data_inclusao >= '$periodo_inicio'")
            ->where("data_inclusao <= '$periodo_fim'")
            ->where("data_descarte IS NULL")
            ->get()->num_rows();
  }

  public function count_ativos_veiculos($periodo_inicio, $periodo_fim){
    return $this->ativo_veiculo_model->ativos()
            ->where("data >= '$periodo_inicio'")
            ->where("data <= '$periodo_fim'")
            ->where("situacao = '0'")
            ->get()->num_rows();
  }

  public function count_colaboradores($periodo_inicio, $periodo_fim){
    return $this->db
            ->from('funcionario')
            ->where("data_criacao >= '$periodo_inicio'")
            ->where("data_criacao <= '$periodo_fim'")
            ->get()->num_rows();
  }

  public function crescimento_empresa(){
    $meses_31_dias = [1,3,5,7,8,10,12];
    $meses_porcentagens = $meses_total = [];

    for($i=0; $i < 13; $i++){
      $inicio = date('Y-m-01 00:00:00', strtotime("-{$i} months"));
      $ultimo_dia = date('t', strtotime($inicio));
      $fim = date("Y-m-{$ultimo_dia} 23:59:59", strtotime("-{$i} months"));

      $mes = (int) date('m', strtotime($inicio));
      $meses_total[$mes] = 0;
      $meses_total[$mes] += (float) $this->count_ativos_externos($inicio, $fim);
      $meses_total[$mes] += (float) $this->count_ativos_internos($inicio, $fim);
      $meses_total[$mes] += (float) $this->count_ativos_veiculos($inicio, $fim);
      $meses_total[$mes] += (float) $this->count_colaboradores($inicio, $fim);

      $v_menor = (float) isset($meses_total[$mes + 1]) ? $meses_total[$mes + 1] : 0;
      $v_maior = (float) $meses_total[$mes];

      //V = ((Vmaior - Vmenor)/Vmenor) * 100
      $meses_porcentagens[$i][0] = $mes;
      if ($v_menor > 0){
         $meses_porcentagens[$i][1] = number_format(((($v_maior + $v_menor)/$v_menor) * 100), 2);
      } else {
        $meses_porcentagens[$i][1] = number_format(((($v_maior - $v_menor) * $v_menor) / 100), 2);
      }
    }

    return array_reverse($meses_porcentagens);
  }

  public function crescimento_empresa_custos(){
    $meses_31_dias = [1,3,5,7,8,10,12];
    $meses_porcentagens = $meses_total = [];

    for($i=12; $i > 0; $i--){
      $inicio = date('Y-m-01 00:00:00', strtotime("-{$i} months"));
      $ultimo_dia = date('t', strtotime($inicio));
      $fim = date("Y-m-{$ultimo_dia} 23:59:59", strtotime("-{$i} months"));
     
      $centro_de_custo = $this->centro_de_custo(
        [
          'periodo' => [
            'tipo' => 'outro',
            'inicio' => $inicio,
            'fim' => $fim
          ],
          'id_obra' => null,
        ],
        'grafico'
      );
      $mes = (int) date('m', strtotime($inicio));
      $meses_total[$mes] = (float) $centro_de_custo->total;

      $v_menor = isset($meses_total[$mes - 1]) ? $meses_total[$mes - 1] : 0;
      $v_maior = $meses_total[$mes];

      //V = ((Vmaior - Vmenor)/Vmenor) * 100
      $valor = $v_maior - $v_menor;
      if ($v_menor > 0){
        $meses_porcentagens[$mes] = number_format((($valor/$v_menor) * 100), 2);
      } else {
        $meses_porcentagens[$mes] = number_format((($valor * $v_menor) / 100), 2);
      }
    }

    return $meses_porcentagens;
  }
}