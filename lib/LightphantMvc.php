<?php
/**
 * Description of LightphantMvc
 *
 * @author gleyson
 */
class LightphantMvc {   
  protected $url;
  protected $cache;
  protected $controller;
  protected $action;
  protected $params;

  public function __construct(){
    $this->setDebug();
     
    $sys = new SysMvc(urldecode($_SERVER['REQUEST_URI'] ?? false));
    
    $this->url = $sys->url;
    $this->controller = $sys->controller;
    $this->action = $sys->action;
    $this->params = $sys->params;
    
    $this->cache = new SysMvc(urldecode($_SERVER['HTTP_REFERER'] ?? false));
  }

  /**
   * Define modo debug retornado ao usuário analise do erro 
   * com base na constante LF_DEBUG em /lib/config/defines.php
   */
  private function setDebug() {
    if (!LP_DEBUG) {
      error_reporting ( 0 );
      ini_set ( 'display_errors', 0 );
    }
  }
  
  /**
   * Executa a função de um determinado controller com base na url.
   * Exemplo: localhost/meusite/index/boasvindas/ executará a função boasvindas() da class/controller index.
   */
  public function trumpet(){
    try {    
    	//Verificar se existe rota e retornar controller e action equivalente
      $rota = new Rotas($this->controller, $this->action);
      $controller = $rota->getRotaClass();
      $sysAction = $rota->getRotaMetodo();
      
      //Redirecionar para XDEBUG
      if ($this->controller == 'XDEBUG_SESSION_START') {
        App::redirect(URL_BASE.'IndexController/initial?'.$controller.'='.$sysAction); die ();
      }
      
      //Comparar existência de arquivos
      $sysController = substr($controller,-10) == 'Controller' ? $controller : $controller."Controller";
             
      $mvc = new $sysController();
      $mvc->$sysAction();

    } catch (Throwable $t) {
      $error = new LightphantError($t, new Controller());
    }
  }
 
  public function getUrl(){
    return $this->url;
  }
 
  public function getCache(){
    return $this->cache;
  }
 
  public function getController(){
    return $this->controller;
  }
 
  public function getAction(){
    return $this->action;
  }
 
  public function getParams(){
    return $this->params;
  }
}
