<?php

final class LightphantError extends Exception{

	public function __construct(Throwable $t, Controller $controller){

		//Verificar se o erro é do PDOException
		if(strpos($t->getMessage(), "SQLSTATE") !== false){

			//Verificando se o debugmode estÁ ATIVADO
			if(DEBUG_MODE == 0){
				
				$cache = $controller->getCache();
				$strParams = count($cache->params) > 0 ? "/".implode("/", $cache->params) : '';
				
				Redirect::error(
					URL_BASE.$cache->controller."/".$cache->action.$strParams,
					"PDOErro: ".$pdo->getMessage()
				); die();
				
			}else{
				$this->errorView('debug-error', $pdo, $controller);
			}

		//Se o erro é Exception
		}else{
			//Verificando se o debugmode estÁ ATIVADO
			if(DEBUG_MODE == 0){
				
				$cache = $controller->getCache();
				$strParams = count($cache->params) > 0 ? "/".implode("/", $cache->params) : '';
				
				Redirect::error(
					URL_BASE.$cache->controller."/".$cache->action.$strParams,
					"Erro: ".$t->getMessage()
				); die();	
				
			}else{
				$this->errorView('debug-error', $t, $controller );
			}
		}
	}

	private function errorView($pagina, $t, $controller){  
		
		if( file_exists( URL_BASE.VIEWS.$pagina.'.phtml') ){
			require_once( URL_BASE.VIEWS.$pagina.'.phtml' );				
		}else{
			require_once( VIEWS.$pagina.'.phtml' );	  
		}
	}
}