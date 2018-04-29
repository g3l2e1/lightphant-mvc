<?php

/*
 * Classe com as definições usadas no LightPhantMvc.
 * ************************************************************************** */
/*
 * Para começar a usar o framework na mesma estrutura que é baixado é necessário 
 * apenas configurar a define BASE caso esteja trabalhando com um emulador de php: 
 * wamp, xampp, easy_php etc.
 * Se já estiver hospedado a define base é a raiz "/".
 * 
 * Se você deseja modificar a estrutura original do framework, você precisará 
 * modificar para o caminho correto cada define abaixo.
 * 
 * ************************************************************************** */
class Constants {

    /**
     * Caminho base da url
     */
    const URL_BASE = "/";

    /**
     * Ativa modo de deupração desenvolvedor se TRUE;
     */
    const LP_DEBUG = true;

    /**
     * Caminho para diretório raiz
     */
    const DIR_BASE = $_SERVER['DOCUMENT_ROOT'];

    /**
     * Caminho para pasta lib do sistema.
     */
    const LIB = 'lib/';

    /**
     * Caminho para pasta com arquivos de configuração do sistema.
     */
    const CONFIG = 'lib/config/';

    /**
     * Caminho para pasta app do sistema.
     */
    const APP = 'app/';

    /**
     * Caminho para pasta com controllers do sistema.
     */
    const CONTROLLER = APP.'controllers/';

    /**
     * Caminho para pasta com dtos do sistema.
     */
    const DTOS = APP.'dtos/';

    /**
     * Caminho para pasta com dtos do sistema.
     */
    const ENUMS = APP.'enums/';

    /**
     * Caminho para pasta com filters do sistema.
     */
    const FILTERS = APP.'filters/';

    /**
     * Caminho para pasta com helpers do sistema.
     */
    const HELPERS = APP.'helpers/';

    /**
     * Caminho para pasta com models do sistema.
     */
    const MODELS = APP.'models/';

    /**
     * Caminho para pasta com dtos do sistema.
     */
    const RENDERERS = APP.'renderers/';

    /**
     * Caminho para pasta com dtos do sistema.
     */
    const SERVICES = APP.'services/';

    /**
     * Caminho para pasta com dtos do sistema.
     */
    const VIEWS = APP.'views/';

    /**
     * Caminho para pasta com dtos do sistema.
     */
    const VIEWS_PLUGINS = VIEWS.'plugins/';

    /**
     * Caminho para pasta com arquivos do sistema.
     */
    const LIB = 'lib/';

    /**
     * Caminho para pasta com bin do sistema.
     */
    const LP_BIN = LIB.'bin/';

    /**
     * Caminho para pasta com config do sistema.
     */
    const LP_CONFIG = LIB.'config/';

    /**
     * Caminho para pasta com helpers do sistema.
     */
    const LP_HELPERS = LIB.'helpers/';

    /**
     * Caminho para pasta com bin do sistema.
     */
    const DIR_SOCKETS = LP_HELPERS.'fakesockets/';

    /**
     * Estrutura do sistema padrão
     */
    const ESTRUTURA = [
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
    ];

}