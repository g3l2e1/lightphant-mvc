<?php

/**
 * Função para carregamento automático de uma classe instânciada pela função
 * require_once.
 */
function __autoload($classe){
	try{
		$estrutura = ESTRUTURA;
		$requireFile = null;
		
		for ($i = 0; $i < count($estrutura); $i++) {
			if (file_exists($estrutura[$i] . $classe . '.php')) {
				$requireFile = $estrutura[$i] . $classe . '.php';
			}
		}
		
		if ($requireFile == null) {
			
			throw new Exception("A classe <b><i>{$classe}</i></b> instânciada não foi encontrada pelo autoload"); die();
			
		} else {
			
			require_once( $requireFile );
		}
		
	}catch(Throwable $t){
		$error = new LightphantError($t, $this);
	}
}


