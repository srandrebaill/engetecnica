<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Description of site
 *
 * @author https://www.roytuts.com
 */
class funcionario  extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('funcionario_model');

        # Login
        if($this->session->userdata('logado')==null){
            echo redirect(base_url('login')); 
        } 
        # Fecha Login        
    }

    function index($subitem=null) {
        $data['lista'] = $this->funcionario_model->get_lista();
    	$subitem = ($subitem==null ? 'index' : $subitem);
        $this->get_template($subitem, $data);
    }

    function adicionar(){
        $data['estados'] = $this->get_estados();
    	$this->get_template('index_form', $data);
    }

    function editar($id_funcionario=null){
        $data['detalhes'] = $this->funcionario_model->get_funcionario($id_funcionario);
        $data['veiculos_cadastrados'] = $this->funcionario_model->get_veiculos_cadastrados($id_funcionario);
        $data['estados'] = $this->get_estados(); 
        $this->get_template('index_form', $data);
    }

    function salvar(){

        $data['id_funcionario'] = !is_null($this->input->post('id_funcionario')) ? $this->input->post('id_funcionario') : '';
        $data['nome'] = $this->input->post('nome');
        $data['rg'] = $this->input->post('rg');
        $data['cpf'] = $this->input->post('cpf');
        $data['data_nascimento'] = $this->input->post('data_nascimento');
        $data['endereco'] = $this->input->post('endereco');
        $data['endereco_numero'] = $this->input->post('endereco_numero');
        $data['endereco_complemento'] = $this->input->post('endereco_complemento');
        $data['endereco_bairro'] = $this->input->post('endereco_bairro');
        $data['endereco_cep'] = $this->input->post('endereco_cep');
        $data['endereco_cidade'] = $this->input->post('endereco_cidade');
        $data['endereco_estado'] = $this->input->post('endereco_estado');
        $data['telefone'] = $this->input->post('telefone');
        $data['celular'] = $this->input->post('celular');
        $data['email'] = $this->input->post('email');
        $data['observacao'] = $this->input->post('observacao');
        $data['situacao'] = $this->input->post('situacao');

        $tratamento = $this->funcionario_model->salvar_formulario($data);

        if($data['id_funcionario']==''){
            $this->session->set_flashdata('msg_retorno', "Novo registro inserido com sucesso!");
        } else {
            $this->session->set_flashdata('msg_retorno', "Registro atualizado com sucesso!");            
        }
        echo redirect(base_url("funcionario"));

    }

    function deletar($id=null){
        $this->db->where('id_funcionario', $id);
        return $this->db->delete('funcionario');
    }

}

/* End of file Site.php */
/* Location: ./application/modules/site/controllers/site.php */