<?php
header('Content-Type: text/html; charset=UTF-8', true);

//Constantes do usuário - define();
require_once( 'lib/config/constants.php' );

//Estrutura das pastas
require_once( 'lib/config/FolderStructure.php' );

//Autoload
require_once( 'lib/bin/AutoLoad.php' );

//Funções extras para o php.
require_once( 'lib/helpers/php_extends.php' );

//Criar Sessão Inicial gravando banco.
$sessao = new Session();

//Iniciar Lightphant MVC
$mvc = new LightphantMvc();
$mvc->trumpet();



?>