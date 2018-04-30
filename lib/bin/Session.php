<?php

class Session {

    public function __construct() {
        
    	try {
    		
            session_start();
            $idUser = session_id();
            
        } catch (Throwable $t) {
        	$error = new LightphantError($t, $this);
        }
    }

}