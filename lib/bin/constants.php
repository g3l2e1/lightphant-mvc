<?php

/*
 * Arquivo de definições usadas no LightPhantMvc.
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
 * Caminho base do site.
 * 
 * Usado em caso de desenvolvimento com <b>wamp</b> ou <b>xampp</b> 
 * quando o site está compartilhando o espaço da pasta www com vários 
 * outros sites em desenvolvimento.
 * 
 * Caso esteja hospedado a base deve estar definida como "".
 */
$host = gethostbyaddr($_SERVER['REMOTE_ADDR']);




/**
 * Caminho inicial da página. "/" para págins com aliás.
 */
define('URL_BASE', "/");

/**
 * Caminho para socket.
 */
define('DIR_SOCKET', str_replace(array('//'), array('/'), "{$_SERVER['DOCUMENT_ROOT']}/lib/sockets/"));

/**
 * Caminho para anexos.
 */
define('DIR_ANEXO', "/media/intrasul/www/layouts/");

/**
 * Acesso via URL ao diretório de anexos.
 */
define('URL_ANEXO', "//intrasul/layouts/");

/**
 * Ativa modo de deupração desenvolvedor se TRUE;
 */
define('LF_DEBUG',TRUE);

/**
 * Caminho para diretório raiz
 */define('DIR_BASE', $_SERVER['DOCUMENT_ROOT']);

/**
 * Caminho para pasta com arquivos de configuração do sistema.
 */
define('CONFIG', 'lib/config/');

/**
 * Caminho para pasta lib do sistema.
 */
define('LIB', 'lib/');

/**
 * Caminho para pasta app do sistema.
 */
define('APP', 'app/');

/**
 * Caminho para pasta com controllers do sistema.
 */
define('LF_CONTROLLERS', LIB . 'controllers/');

/**
 * Caminho para pasta com models do sistema.
 */
define('LF_MODELS', LIB . 'models/');

/**
 * Caminho para pasta com arquivos de sistema.
 */
define('LF_SYSTEM', LIB . 'system/');

/**
 * Caminho para pasta com helpers do sistema.
 */
define('LF_HELPERS', LIB . 'helpers/');

/**
 * Caminho para pasta com helpers do sistema.
 */
define('LF_SOCKETS', LIB . 'sockets/');

/**
 * Caminho para pasta com as views do sistema.
 */
define('LF_VIEWS', LIB . 'views/');

/**
 * Caminho para pasta com as classes de erro do sistema.
 */
define('LF_ERRORS', LIB . 'views/errors/');

/**
 * Caminho para pasta com as webfiles de erro.
 */
define('EXAMPLE', LIB . 'example/');

/**
 * Caminho para pasta com as views da aplicação.
 */
define('MODELS', APP . 'models/');

/**
 * Caminho para pasta com as views da aplicação.
 */
define('VIEWS', APP . 'views/');

/**
 * Caminho para pasta com os controllers da aplicação.
 */
define('CONTROLLERS', APP . 'controllers/');

/**
 * Caminho para pasta com os helpers da aplicação.
 */
define('HELPERS', APP . 'helpers/');

/**
 * Caminho para pasta com os plugins da aplicação.
 */
define('PLUGINS', VIEWS . 'plugins/');


define('GPVendas', 'GPVendas_Desenvolvimento');



?>