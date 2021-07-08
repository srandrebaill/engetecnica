<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

/* load the MX_Loader class */
require APPPATH . "third_party/MX/Loader.php";

class MY_Loader extends MX_Loader {
    public function get_situacao($status=null){
    	switch($status){
    		case 0:
    		$status = "ATIVO";
    		break;

    		case 1:
    		$status = "INATIVO";
    		break;
    	}

    	return $status;
    }

    public function get_obra_base($status=null){
    	switch($status){
    		case 0:
    		$status = "NÃO";
    		break;

    		case 1:
    		$status = "SIM";
    		break;
    	}

    	return $status;
    }


    public function get_situacao_transporte($status=null){

        switch($status){

            case 1:
            $status = "Pendente";
            break;

            case 2:
            $status = "Em Andamento";
            break;

            case 3:
            $status = "Entregue";
            break;

            case 4:
            $status = "Concluído";
            break;

        }

        return $status;
    }   
}
