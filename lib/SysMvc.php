<?php

/**
 * modificado em: 2017-08-15
 */
class SysMvc {
	
	public $url;
	public $controller;
	public $action;
	public $params;
	public $cache;
	
	public function __construct(string $uri) {
		
		$this->setUrl($uri);
		$this->setControllerAndAction();
		$this->setParams();
	}
	
	/**
	 * Definir url com base na REQUEST_URI
	 */
	private function setUrl($uri) {
		
		$this->url = $uri;
	}
	
	/**
	 * Define o controller para ser instanciado.
	 */
	private function setControllerAndAction() {
		
		//Extrair caminho da URI
		$uriEndereco = $this->url ? parse_url($this->url, PHP_URL_PATH) : null;
		
		//Transformar URI em array
		$explodeUriEndereco = explode("/", $uriEndereco);
		
		//Definir controller e action
		$this->controller = !empty($explodeUriEndereco[1]) ? $explodeUriEndereco[1] : "Index";
		$this->action = !empty($explodeUriEndereco[2]) ? $explodeUriEndereco[2] : "initial";
	}
	
	/**
	 * Transforma a url em uma array.
	 * Exemplo: localhost/meusite/indice1/valor1/indice2/valor2 = Array(indice1 => valor1, indice2 => valor2);
	 */
	private function setParams() {
		
		//Extrair caminho da URI
		$uriEndereco = $this->url ? parse_url($this->url, PHP_URL_PATH) : null;
		
		//Transformar URI em array
		$explodeUriEndereco = explode("/", $uriEndereco);
		
		//Varrer URI para definir parametros passados com "/"
		$explodePathParams = [];
		for ($i = 3; $i < count($explodeUriEndereco); $i++) {
			$explodePathParams[] = $explodeUriEndereco[$i];
		}
		
		//Transformar impares em chaves e pares em valores
		$pathParams = array();
		foreach (array_chunk($explodePathParams, 2) as $pair) {			
			list($key, $value) = $pair+[null,null]; // fix list() issue
			if(strpos($key,"[]")){
				$pathParams[str_replace("[]","",$key)][] = $value;
			}else{
				$pathParams[$key] = $value;
			}			
		}
		
		//Extrair parâmetros da query da URI
		$uriParametros = $this->url ? parse_url($this->url, PHP_URL_QUERY) : null;		
		
		//Transformar parametros em array
		$queryParams=[];
		parse_str($uriParametros, $queryParams);
		//Pegar parâmetros se passados via POST
		$queryParams = @$SERVER ['REQUEST_METHOD'] == 'POST' ? $POST : $queryParams;
		
		//Mesclar parametros de caminho com parâmetros da query
		$this->params = array_merge($pathParams, $queryParams);
	}
}

?>