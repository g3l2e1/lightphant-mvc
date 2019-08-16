<?php

/**
 * Caminho base da url
 */
define('URL_BASE', '/');

/**
 * Ativa/desativar modo de depuração desenvolvedor
 */
define('LP_DEBUG', true);

/**
 * Estruturas do projeto usadas no autload.
 * Se criar uma estrutura nova que tenha objetos a instanciar acrescente aqui.
 */
define('LP_STRUCTURE', serialize([
	'/',
	'app/',
	'app/controllers/',
	'app/daos/',
	'app/dtos/',
	'app/enums/',
	'app/filters/',
	'app/helpers/',
	'app/models/',
	'app/renderers/',
	'app/services/',	
	'app/views/',	
	'lib/',
	'lib/config/',
	'lib/helpers/'
]));

/**
 * Ativa/desativar modo de depuração desenvolvedor
 */
define('LP_ROUTES', serialize([
	'Index/initial' => ''
]));