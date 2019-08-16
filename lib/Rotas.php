<?php

/*
 * Classe para aplicar rotas para classe - alias.
 */
class Rotas {

  private $rotas = [];

  private $rota;
  private $rotaClasse;
  private $rotaMetodo;

  public function __construct(string $controller, string $action){
    $this->rotas = unserialize(LP_ROUTES);
    $this->rotas['LightphantController/initial'] = 'lightphant-welcome';
    $this->rotas['LightphantController/builders'] = 'lightphant-builder';

    $this->setRota($controller, $action);
  }

  private function setRota(string $controller, string $action){
  	$_matchRota = array_search($controller."/".$action, $this->rotas);
  	$matchRota = $_matchRota ? $_matchRota : array_search($controller, $this->rotas);
  	$this->rota =  $matchRota ? $matchRota : $controller."/".$action;

  	$rota = explode("/", $this->rota);
  	$this->rotaClasse = $rota[0];
  	$this->rotaMetodo = $rota[1];
  }

  public function getRota(){
  	return $this->rota;
  }

  public function getRotaClass(){
  	return $this->rotaClasse;
  }

  public function getRotaMetodo(){
  	return $this->rotaMetodo;
  }

}
