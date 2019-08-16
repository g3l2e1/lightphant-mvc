<?php
header( 'Access-Control-Allow-Origin: http://localhost:4200' );
header( 'Content-Type: text/html; charset=UTF-8', true );

//Estrutura das pastas
require_once( 'lib/config/config.php' );

//Autoload
require_once( 'lib/Autoload.php' );

//Kint Debug tema aante-light.
require_once( "lib/helpers/kint/build/kint-aante-light.php" );

//Funções extras para o php.
require_once( 'lib/helpers/php_extends.php' );

//Criar sessão inicial
$sessao = new Session();

//Iniciar Lightphant MVC
$mvc = new LightphantMvc();
$mvc->trumpet();
