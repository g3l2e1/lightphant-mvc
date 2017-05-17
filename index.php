<?php

header('Content-Type: text/html; charset=UTF-8', true);

//Caminhos dos arquivos
//require_once( 'lib/config/defines.php' );

//Definicões do usuário.
//require_once( 'lib/config/Definicoes.php' );

//Autoload
//require_once( LF_SYSTEM . 'AutoLoad.php' );

//Funções extras para o php.
//require_once( LF_HELPERS . 'php_extends.php' );

//Criar Sessão Inicial gravando banco.
$sessao = new Session();

//Iniciar Lightphant MVC
$mvc = new LightphantMvc();
$mvc->trumpet();



?>