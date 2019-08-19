<?php

class ModelConfig {
	public $pk;
	public $tipo;
	public $coluna;
	public $fk;
	public $preservar = false;

	public function __construct($tipo, $coluna, $pk=false, $fk=null){
		$this->pk = $pk;
		$this->tipo = $tipo;
		$this->coluna = $coluna;
		$this->fk = $fk;
	}
}