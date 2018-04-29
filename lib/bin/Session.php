<?php

class Session {

    public function __construct() {
        try {
            session_start();
            $idUser = session_id();
        } catch (Exception $t) {
            print "Erro: " . $t->getMessage(); die();
        }
    }

}