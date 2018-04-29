<?php

class Redirecionar{

    static function prepareUrl($url, $msg){
    	$redirect = $url."/msg/".$msg;
    	$ex = explode("/", $redirect);
    	
    	$methodExist = method_exists(@$ex[1], @$ex[2]);
    	$finalUrl = count($ex) % 2 == 0 && !$methodExist ? $url."/inicial/msg/".$msg : $redirect;
    	return $finalUrl;
    }
    
    /**
     * Redireciona para um url específico sem o uso do header.
     * 
     * O redirecionamento por PHP, utiliza o comando header, isto faz com que o header atual seja substituído e
     * de certa forma, retirando as especificações do usuário como: charset, timeout etc.
     *  
     * Alguns serviços de hospedagem bloqueiam a utilização do header, portanto, para evitar problemas futuros, 
     * foi utilizado o redirecionamento por script. 
     * 
     * O redirecionamento por Html, também funciona, porém é mais lento, uma vez que ao printar se escreve em Html
     * depois é feita a leitura do php e por fim dos scripts. A partir de então, a meta tag por ter refresh= 0, passa
     * a redirecionar a página. Já no caso do script, após ser escrito e lido o php ele é executado.
     * 
     * @param $url - url a ser redirecionada.
     */
    static function url($url){

        //Redirecionamento por script.
        print "<script>location.href='".$url."'</script>";	
    }
    
    /**
     * Redirecionamento com 
     */
    static function erro($url, $msg){
    	$finalUrl = self::prepareUrl($url, $msg);
    	print "<script>location.href='".$finalUrl."#msg-erro'</script>";
    }
    
    static function aviso($url, $msg){ 
    	$finalUrl = self::prepareUrl($url, $msg);
    	print "<script>location.href='".$finalUrl."#msg-warning'</script>";
    }
    
    static function info($url, $msg){
    	$finalUrl = self::prepareUrl($url, $msg);
    	print "<script>location.href='".$finalUrl."#msg-info'</script>";
    }
    
    static function sucesso($url, $msg){
    	$finalUrl = self::prepareUrl($url, $msg);
    	print "<script>location.href='".$finalUrl."#msg-success'</script>";
    }

    
    /**
     * Redireciona o App para o url acessado anteriormente.
     */
    static function urlAnterior(){
        print "<script>window.history.back(-1)</script>"; 	
    }	
    
    /**
     * Redireciona o App para o url acessado anteriormente.
     */
    static function recarregar(){
        print "<script>location.reload()</script>"; 	
    }
	
}

?>