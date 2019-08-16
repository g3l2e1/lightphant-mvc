<?php

/** 
 * @author gleyson
 * 
 */
class IndexController extends Controller {
	
	public function __construct() {
		parent::__construct ();
	}
	
	public function initial(){		
		$params = [];		
		$this->view('lightphant-index', $params);		
	}
}

