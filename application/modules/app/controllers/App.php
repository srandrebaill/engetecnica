<?php
(defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Description of site
 *
 * @author Messias Dias | https://github.com/messiasdias
 */
class App extends MY_Controller {

    protected $path, $erro_enviroment;

    function __construct() {
        parent::__construct(false);
        $this->load->model('relatorio/relatorio_model');
        $this->load->model('relatorio/notificacoes_model');
        $this->load->helper('download');
        $this->path = __DIR__."/../../../../assets/exports";
        $this->erro_enviroment = "Atenção! Essa ação tem uso restrito ao modo 'Desenvolvimento' (development), há riscos ao ser executada
        em modo produção (production).";
    }

    public function automacoes($type = "day") {
      $status = [];
      switch ($type) {
        case "day":
          $status = [
            'limpar_exports' => $this->db_export_clear(),
            'limpar_uploads' => $this->relatorio_model->limpar_uploads(),
            'informe_vencimentos' => $this->relatorio_model->enviar_informe_vencimentos(),
            'informe_retiradas_pendentes' => $this->relatorio_model->enviar_informe_retiradas_pendentes(),
          ];
        break;

        case "test":
          $status = [
            'informe_retiradas_pendentes' => $this->relatorio_model->enviar_informe_retiradas_pendentes("now", true),
            'informe_vencimentos' => $this->relatorio_model->enviar_informe_vencimentos(30, true),
          ];
        break;
      }

      $this->json($status);
    }
  
    public function test_email(){
      $return = $this->erro_enviroment;
      if (getenv('CI_ENV') == 'development') {
        $top = $this->load->view('relatorio/email_top', [
          'ilustration' => "welcome", 
          "assunto" => "Test email", 
          "styles" => $this->notificacoes_model->getEmailStyles()
        ], true);
        $email = "<h1> Teste email</h1> <p>Essa é uma mensagem de teste, caso esteja lendo isso, significa que tudo está funcionando como o esperado.</p>";
        $footer = $this->load->view('relatorio/email_footer', null, true);
        $html = $top.$email.$footer;
        $return = $this->notificacoes_model->enviar_email("Test Email", $html, $this->config->item("notifications_address"));
      }
      $this->json(['success' => $return]);
    }
  
    public function test_push(){
      $return = $this->erro_enviroment;
      if (getenv('CI_ENV') == 'development') {
        $return = $this->notificacoes_model->enviar_push("Test Push", "Test Push Notications ok!", [
          "filters" => [
              ["field" => "tag", "key" => "nivel", "relation" => "=", "value" => "1"],
              ["operator" => "AND"],
              ["field" => "tag", "key" => "nivel", "relation" => "=", "value" => "2"],
          ],
          "url" => "/"
        ]);
      }
      $this->json($return);
    }
  
    public function export(){
      if (getenv('CI_ENV') == 'development') {
        if ($this->user && $this->user->nivel == 1) {
          $filename = "{$this->path}/". date("Ymdhis") .".json";
      
          $tables = array_map(function($table) {
            return array_values((array) $table)[0];
          }, $this->db->query('show tables')->result());

          $data = [];
          foreach($tables as $table){
            $data[$table] = $this->db->get($table)->result();
          }

          file_put_contents($filename, json_encode($data));
          return force_download($filename, null);
        }
        echo "Ocorreu um erro ao gerar arquivo!";
        return;
      }

      echo $this->erro_enviroment;
    }
  
    private function db_export_clear() {
      if (getenv('CI_ENV') == 'development') {
        foreach(glob("{$this->path}/*.json") as $filename) {
          unlink($filename);
        }
        return true;
      }
      return false;
    }
}