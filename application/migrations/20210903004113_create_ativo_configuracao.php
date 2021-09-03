<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_Ativo_Configuracao extends CI_Migration {
	private $table = 'ativo_configuracao';

    //Upgrade migration
	public function up(){
		if (!$this->db->table_exists($this->table)) {
			$this->dbforge
			->add_field('id_ativo_configuracao int(10) NOT NULL AUTO_INCREMENT PRIMARY KEY')
			->add_field('id_ativo_configuracao_vinculo int(10) NOT NULL DEFAULT 0')
			->add_field('titulo varchar(255) NOT NULL')
			->add_field('slug varchar(255) NULL')
			->add_field("situacao enum('0','1') NOT NULL DEFAULT '0' COMMENT '0=Ativo,1=Inativo'")
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