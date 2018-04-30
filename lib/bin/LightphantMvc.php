<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of LightphantMvc
 *
 * @author gleyson
 */
class LightphantMvc {
   
    protected $url;
    protected $cache;
    protected $controller='IndexController';
    protected $action='initial';
    protected $params;

    public function __construct(){

    }

    public function trumpet(){

    	$Controller = $this->controller;
    	$action = $this->action;
    	
        //Executar a classe controller e método
        $mvc = new $Controller();
        $mvc->$action();
    }
}
