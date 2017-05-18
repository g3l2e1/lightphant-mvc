<?php

/**
 * Classe de Definicoes.
 */
class FolderStructure {

    protected $estrutura;
    
    public function __construct(){
        
        $this->estrutura = array(
            "/",
            "app/",
            "app/controllers/",
            "app/helpers/",
            "app/renderers/",
            "app/filters/",
            "app/services/",
            "app/models/",
            "app/views/",
            "app/daos/",
            "lib/",
            "lib/config/",
            "lib/controllers/",
            "lib/example/",
            "lib/helpers/",
            "lib/models/",
            "lib/system/",
            "lib/views/",
            "lib/views/errors"
        );
    }
    
    public function getPastas(){
        return $this->estrutura;
    }
}

?>