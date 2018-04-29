<?php

final class LightphantError extends Exception{

    public function __construct(Throwable $t, Controller $controller){

        if(strpos($t->getMessage(), "SQLSTATE") !== false){

			if(DEBUG_MODE == 0){
                $cache = $controller->get_cache();
                $strParams = count($cache->_params) > 0 ? "/".implode("/", $cache->_params) : '';
                App::redirectWithError(
                    URL_BASE.$cache->_controller."/".$cache->_action.$strParams,
                    "PDOErro: ".$pdo->getMessage()
                ); die();
            }else{
                $this->errorView('asind-debug-error', $pdo, $controller);
            }

		}else{
			if(DEBUG_MODE == 0){
                $cache = $controller->get_cache();
                $strParams = count($cache->_params) > 0 ? "/".implode("/", $cache->_params) : '';
                App::redirectWithError(
                    URL_BASE.$cache->_controller."/".$cache->_action.$strParams,
                    "Erro: ".$t->getMessage()
                ); die();	
            }else{
                $this->errorView('asind-debug-error', $t, $controller );
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