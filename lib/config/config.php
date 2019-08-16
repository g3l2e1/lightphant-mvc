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
 * Ativa/desativar modo de depuração desenvolvedor
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