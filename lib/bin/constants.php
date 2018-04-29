<?php

/*
 * Arquivo com as definições usadas no LightPhantMvc.
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


/**
 * Caminho para pasta app do sistema.
 */
define('APP', 'app/');

/**
 * Caminho para pasta com controllers do sistema.
 */
define('CONTROLLER', APP.'controllers/');

/**
 * Caminho para pasta com dtos do sistema.
 */
define('DTOS', APP.'dtos/');

/**
 * Caminho para pasta com dtos do sistema.
 */
define('ENUMS', APP.'enums/');

/**
 * Caminho para pasta com filters do sistema.
 */
define('FILTERS', APP.'filters/');

/**
 * Caminho para pasta com helpers do sistema.
 */
define('HELPERS', APP.'helpers/');

/**
 * Caminho para pasta com models do sistema.
 */
define('MODELS', APP.'models/');

/**
 * Caminho para pasta com dtos do sistema.
 */
define('RENDERERS', APP.'renderers/');

/**
 * Caminho para pasta com dtos do sistema.
 */
define('SERVICES', APP.'services/');

/**
 * Caminho para pasta com dtos do sistema.
 */
define('VIEWS', APP.'views/');

/**
 * Caminho para pasta com dtos do sistema.
 */
define('VIEWS_PLUGINS', VIEWS.'plugins/');

/**
 * Caminho para pasta com arquivos do sistema.
 */
define('LIB', 'lib/');

/**
 * Caminho para pasta com bin do sistema.
 */
define('LP_BIN', LIB.'bin/');

/**
 * Caminho para pasta com config do sistema.
 */
define('LP_CONFIG', LIB.'config/');

/**
 * Caminho para pasta com helpers do sistema.
 */
define('LP_HELPERS', LIB.'helpers/');

/**
 * Caminho para pasta com bin do sistema.
 */
define('DIR_SOCKETS', LP_HELPERS.'fakesockets/');

