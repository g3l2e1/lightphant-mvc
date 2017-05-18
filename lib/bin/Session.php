<?php

class Session {

    public function __construct() {
        try {
            session_start();
            $idUser = session_id();
        } catch (Exception $e) {
            print "Erro: " . $e->getMessage();
        }
    }

}

?>