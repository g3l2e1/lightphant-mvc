<?php

/** 
 * Controller principal.
 * Todo controller deve estendê-lo. * 
 */
class Controller extends LightphantMvc {
	
	private $userException;

	/** 
	 * Controller principal.
	 * Todo controller deve estendê-lo. * 
	 */
	public function __construct() {
		parent::__construct ();
	}
	

	protected function view(string $pagina, $params = null) {
		
		try {
			
			if (is_array($params) && count($params) > 0) {
				
				//variáveis com prefixo.
				extract($params, EXTR_PREFIX_SAME, 'wxdd');
			}
			
			//Incluir pagina			
			require_once( "app/views/{$pagina}.phtml" );
			
		} catch (Throwable $t) {
			$error = new LightphantError($t, $this);
		}
	}
 
	public function getUserException(){
		return $this->userException;
	}
 
	public function setUserException($userException){
		$this->userException = $userException;
	}
}

