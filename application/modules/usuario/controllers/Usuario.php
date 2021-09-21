<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Description of site
 *
 * @author Messias Dias | https://github.com/messiasdias
 */
class usuario  extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('usuario_model');

        # Login
        if($this->session->userdata('logado')==null){
            echo redirect(base_url('login')); 
        } 
        # Fecha Login 
        $this->load->model('obra/obra_model');       
    }

    function index($subitem=null) {
        if ($this->user->nivel != 1) {
            echo redirect(base_url(""));
            return;
        }

        $data['lista'] = $this->usuario_model->get_lista();
    	$subitem = ($subitem==null ? 'index' : $subitem);
        $this->get_template($subitem, $data);
    }

    function adicionar(){
        if ($this->user->nivel != 1) {
            echo redirect(base_url(""));
            return;
        }

        $data['form_type'] = "adicionar";
        $data['is_self'] = false;
        $data['detalhes'] =  (object) [
            'nivel' => null,
            'empresas' => $this->get_empresas(),
            'obras' => $this->obra_model->get_obras(),
            'niveis' => $this->get_niveis()
        ];
        $data['upload_max_filesize'] = ini_get('upload_max_filesize');
    	$this->get_template('index_form', $data);
    }

    function editar($id_usuario=null){
        $usuario = $this->usuario_model->get_usuario($id_usuario);
        $data = null;
        if ($usuario) {
            $data['form_type'] = "editar";
            $data['is_self'] = $usuario->id_usuario == $this->user->id_usuario;
            $data['detalhes'] = $usuario;
            $data['detalhes']->empresas = $this->get_empresas();
            $data['detalhes']->obras = $this->obra_model->get_obras();
            $data['detalhes']->niveis = $this->get_niveis();
        }
        $data['upload_max_filesize'] = ini_get('upload_max_filesize');
        $this->get_template('index_form', $data);
    }

    function salvar(){
        $data['id_usuario'] = $this->input->post('id_usuario');
        $usuario = $this->usuario_model->get_usuario($data['id_usuario']);

        $data['usuario'] = $this->input->post('usuario');
        $data['nome'] = $this->input->post('nome');
        $data['email'] = $this->input->post('email');
     
        if ($usuario && $this->user->id_usuario != $usuario->id_usuario || !$usuario && $this->user->nivel == 1) {
            $data['situacao'] = $this->input->post('situacao');
            $data['nivel'] = $this->input->post('nivel');
            $data['id_empresa'] = $this->input->post('id_empresa');
            $data['id_obra'] = $this->input->post('id_obra');
        }

        $senha = strlen($this->input->post('senha')) > 0 ? $this->input->post('senha') : null;
        $confirmar_senha = strlen($this->input->post('confirmar_senha')) > 0 ? $this->input->post('confirmar_senha') : null;
        $data['senha'] = $this->usuario_model->verificaSenha($senha, $confirmar_senha);

        if (($senha && $confirmar_senha) && $data['senha'] == null) {
            $this->session->set_flashdata('msg_erro', "As senhas fornecidas não conferem!");

            if($data['id_usuario'] == null){
                echo redirect(base_url("usuario/adicionar"));
            } else {
                echo redirect(base_url("usuario/editar/{$data['id_usuario']}"));          
            }
            return;
        } 

        if ($data['id_usuario'] == null && $data['senha'] == null) {
            $this->session->set_flashdata('msg_erro', "Deve fornecer uma senha e confirmar!");
            echo redirect(base_url("usuario/adicionar"));
            return;
        } 

        if ($data['id_usuario'] && !$data['senha']) {
            $usuario = $this->usuario_model->get_usuario($data['id_usuario'], true);
            if (isset($usuario->senha)) {
                $data['senha'] = $usuario->senha;
            }
        }

        if ($this->usuario_model->exists_usuario($data['usuario'], $data['id_usuario'])) {
            $this->session->set_flashdata('msg_erro', "Nome de usuário já existe na base de dados!");
            $this->redirect($data);
            return;
        }

        if ($this->usuario_model->exists_email($data['email'], $data['id_usuario'])) {
            $this->session->set_flashdata('msg_erro', "Já existe um usuário cadastrado com o email especificado na base de dados!");
            $this->redirect($data);
            return;
        }

        if ($_FILES['avatar']) {
            $data['avatar'] = ($_FILES['avatar'] ? $this->upload_arquivo('avatar') : '');
            if (!$data['avatar'] || $data['avatar'] == '') {
                $this->session->set_flashdata('msg_erro', "O tamanho da imagem deve ser menor ou igual a ".ini_get('upload_max_filesize'));
                return $this->redirect($data);
            }

            if (isset($usuario->avatar)) {
                $path = __DIR__."/../../../../assets/uploads/avatar";
                $file = "$path/{$usuario->avatar}";
                if (file_exists($file)) {
                    unlink($file);
                }
            }
        }

        $this->usuario_model->salvar_formulario($data);
        if($data['id_usuario'] == null){
            $this->session->set_flashdata('msg_success', "Novo registro inserido com sucesso!");
        } else {
            $this->session->set_flashdata('msg_success', "Registro atualizado com sucesso!");            
        }

        if ($data['id_usuario'] != null && $this->user->id_usuario == $data['id_usuario']) {
            echo redirect(base_url(""));
            return;
        }
        echo redirect(base_url("usuario"));
    }

    function deletar($id=null){
        if ($this->user->nivel != 1) {
            echo redirect(base_url(""));
            return;
        }

        $this->db->where('id_usuario', $id);
        return $this->db->delete('usuario');
    }

    function solicitar_confirmacao_email($id_usuario){
        if ($this->user->nivel != 1) {
            echo redirect(base_url(""));
            return;
        }

        return $this->json(['success' => $this->usuario_model->solicitar_confirmacao_email($id_usuario)]);
    }

    private function redirect($data) {
        if ($data['id_usuario'] != null && $this->user->id_usuario == $data['id_usuario']) {
            echo redirect(base_url("usuario/editar/{$data['id_usuario']}")); 
            return;
        }

        if($data['id_usuario'] == null){
            echo redirect(base_url("usuario/adicionar"));
        } else {
            echo redirect(base_url("usuario/editar/{$data['id_usuario']}"));          
        }
    }
}

/* End of file Site.php */
/* Location: ./application/modules/site/controllers/site.php */