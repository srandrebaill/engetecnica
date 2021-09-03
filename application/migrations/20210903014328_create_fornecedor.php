<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_Fornecedor extends CI_Migration {
	private $table = 'fornecedor';

    //Upgrade migration
	public function up(){
		if (!$this->db->table_exists($this->table)) {
			$this->dbforge
			->add_field('id_fornecedor int(10) NOT NULL AUTO_INCREMENT PRIMARY KEY')
			->add_field('razao_social varchar(255) NOT NULL')
			->add_field('nome_fantasia varchar(255) NOT NULL')
			->add_field('cnpj varchar(100) NOT NULL')
			->add_field('inscricao_estadual varchar(30) NOT NULL')
			->add_field('inscricao_municipal varchar(30) NOT NULL')
			->add_field('endereco varchar(255) NOT NULL')
			->add_field('endereco_numero varchar(30) NOT NULL')
			->add_field('endereco_complemento varchar(255) NOT NULL')
			->add_field('endereco_bairro varchar(255) NOT NULL')
			->add_field('endereco_cep varchar(15) NOT NULL')
			->add_field('endereco_cidade varchar(255) NOT NULL')
			->add_field('endereco_estado int(10) NOT NULL DEFAULT 0')
			->add_field('responsavel varchar(255) NOT NULL')
			->add_field('responsavel_telefone varchar(50) NOT NULL')
			->add_field('responsavel_celular varchar(50) NOT NULL')
			->add_field('responsavel_email varchar(255) NOT NULL')
			->add_field('observacao text NOT NULL')
			->add_field('data_criacao timestamp NOT NULL DEFAULT current_timestamp()')
			->add_field('data_atualizacao timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()')
			->add_field("situacao enum('0','1') NOT NULL DEFAULT '0' COMMENT '0=Ativo, 1=Inativo'")
			->create_table($this->table);
		}
	}
	
    //Downgrade migration
	public function down(){
		if ($this->db->table_exists($this->table)) {
			$this->dbforge->drop_table($this->table);
		}
	}
}