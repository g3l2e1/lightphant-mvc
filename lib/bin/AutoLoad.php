<?php

/**
 * Classe para carregamento automático de uma classe instânciada pela função
 * require_once.
 */
class AutoLoad {

    public function __construct($classe) {
        
    try{
        $estrutura = ESTRUTURA;
        $requireFile = null;

        for ($i = 0; $i < count($estrutura); $i++) {
            if (file_exists($estrutura[$i] . $classe . '.php')) {
                $requireFile = $estrutura[$i] . $classe . '.php';
            }
        }

        if ($requireFile == null) {

            throw new Exception("A classe <b><i>{$classe}</i></b> instânciada não foi encontrada pelo autoload"); die();
            /*print "
                <style>
                    body { background-color: DodgerBlue; }
                    section {
                        font-family:\"Source Sans pro\", sans-serif; margin: 0 auto; width: 85%;
                        color: #fff;
                    }
                    h1 { margin: 40px 0px 20px 0px; font-size: 80px; }
                    h3 { margin: 0px 0px 15px 0px; font-size: 40px; }
                    p { margin: 0px; font-size: 18px; }
                    a { color: Aquamarine; }
                </style>

                <section>
                    <h1>:( </h1>
                    <h3>Erro!</h3>
                    <p>
                        <i>AutoLoad:</i><br/>
                        A classe <b><i>{$classe}</i></b> instânciada não existe ou não foi encontrada pelo sistema.
                    </p>
                    <br/>
                    <a href='/'>Voltar para pagina Inicial.</a>
                </section>
            ";die();*/
        } else {
            require_once( $requireFile );
        }

    }catch($t){
        
    }

}

function __autoload($classe){
    $autoload = new Autoload($classe);
}