<?php

final class LightphantError extends Exception{

	public function __construct(Throwable $t, Controller $controller){

		//Verificar se o erro é do PDOException
		if(strpos($t->getMessage(), "SQLSTATE") !== false){

			//Verificando se o debugmode estÁ ATIVADO
			if(LP_DEBUG == 0){
				
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
			if(LP_DEBUG == 0){
				
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
		if( file_exists( "app/views/{$pagina}.phtml" ) ){
			require_once( "app/views/{$pagina}.phtml" );				
		}
	}
}