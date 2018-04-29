<?php

class Config extends Constants {

    /**
     * Caminho base da url
     */
    //const URL_BASE = "/";

    /**
     * Ativa/desativar modo de depuração desenvolvedor
     */
    const LP_DEBUG = false;

    /**
     * Acesso via URL ao diretório de anexos.
     */
    //const URL_ANEXO = $_SERVER['DOCUMENT_ROOT']."anexos/";    

    /**
     * Caminho para diretório raiz
     */
    //const DIR_BASE = $_SERVER['DOCUMENT_ROOT'];

    /**
     * Caminho para pasta com bin do sistema.
     */
    //const DIR_SOCKETS = LP_HELPERS.'fakesockets/';

    /**
     * Personalizar a estrtura do sistema.
     * Caso seja necessário criar uma pasta diferente da estrtura original 
     * do sistema, é necessário que seja indicada nesta constante para o
     * pleno funcionamento do Autoload
     */
    /*const ESTRUTURA = [
        "/",
        "app/",
        "app/controllers/",
        "app/dtos/",
        "app/enums/",
        "app/filters/",
        "app/helpers/",
        "app/models/",
        "app/renderers/",
        "app/services/",
        "app/views/",
        "lib/",
        "lib/bin/",
        "lib/config/",
        "lib/helpers/",
        "lib/helpers/fakesockets/",
        "lib/helpers/kint/"
    ];*/

}