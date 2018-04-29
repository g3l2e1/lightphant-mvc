<?php

/**
 * Caminho base da url
 */
define('URL_BASE', "/");

/**
 * Ativa/desativar modo de depuração desenvolvedor
 */
define('LP_DEBUG', false);

/**
 * Acesso via URL ao diretório de anexos.
 */
//define('URL_ANEXO', $_SERVER['DOCUMENT_ROOT']."anexos/");    

/**
 * Caminho para diretório raiz
 */
//define('DIR_BASE', $_SERVER['DOCUMENT_ROOT']);

/**
 * Personalizar a estrtura do sistema.
 * Caso seja necessário criar uma pasta diferente da estrtura original 
 * do sistema, é necessário que seja indicada nesta constante para o
 * pleno funcionamento do Autoload
 */
define('ESTRUTURA', [
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
]);
