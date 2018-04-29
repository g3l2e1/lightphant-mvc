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
final class LightphantMvc {
   
    private $url;
    private $cache;
    private $controller='IndexController';
    private $action='inicial';
    private $params;

    public function __construct(){

    }

    public function trumpet(){

        //Executar a classe controller e método
        $mvc = new $this->controller();
        $mvc->$this->action();
    }
}
