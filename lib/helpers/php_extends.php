<?php

/*
 * Retorna true se a array tiver apenas índices (keys), sequenciais (numéricos).
 * Ex: is_assoc(array('a', 'b', 'c')); // false
 *     is_assoc(array("0" => 'a', "1" => 'b', "2" => 'c')); // false
 *     is_assoc(array("1" => 'a', "0" => 'b', "2" => 'c')); // true
 *     is_assoc(array("a" => 'a', "b" => 'b', "c" => 'c')); // true
 */
function is_assoc($arr) {
    return array_keys($arr) !== range(0, count($arr) - 1);
}

function mask($val, $mask) {
    $maskared = '';
    $k = 0;
    for ($i = 0; $i <= strlen($mask) - 1; $i++) {
        if ($mask[$i] == '#') {
            if (isset($val[$k]))
                $maskared .= $val[$k++];
        }else {
            if (isset($mask[$i]))
                $maskared .= $mask[$i];
        }
    }
    return $maskared;
}

function trimAll($string = false) {
    if ($string) {
        return str_replace(array(" ", "\n", "\t", "\r", "\0", "\x0B"), array("", "", "", "", "", ""), $string);
    }
}

function formatNomePessoal($nome){
    $minusculas = mb_strtolower($nome);
    $primeiraMaiuscula = ucwords($minusculas);
    
    $search = array(' De ', ' Da ', ' Do ', ' E ', ' Du ');
    $replace = array(' de ', ' da ', ' do ', ' e ', ' du ');
    $nomeFormatado = str_replace($search, $replace, $primeiraMaiuscula);
    
    return $nomeFormatado;
}

function object_map($encode, &$obj) {
    if (is_object($obj)) {
        $objeto = (object) array();

        foreach ($obj as $key => $value) {
            if (is_string($value)) {                
                //Detectar encodificação.
                if( $encode == 'utf8_encode' && mb_detect_encoding($value) != 'UTF-8' ){
                    $objeto->$key = utf8_encode($value);
                }else if( $encode == 'utf8_decode' ){
                    $objeto->$key = utf8_decode($value);
                }else if( $encode == 'htmlentities' ){
                    $objeto->$key = htmlentities($value);
                }else{
                    $objeto->$key =$value;
                }
            } else if (is_array($value)) {
                $objectIn = array();
                for ($i = 0; $i < count($value); $i++) {
                    $objectIn[$i] = $this->object_map($encode, $value[$i]);
                }
                $objeto->$key = $objectIn;
            } else if (is_null($value)) {
                $objeto->$key = null;
            }
        }
    }

    return $objeto;
}

function json_encode_object($obj) {
    
    $retorno;

    if (is_array($obj)) {
        for ($i = 0; $i < count($obj); $i++) {
            if (is_array($obj[$i])) {
                $retorno[] = json_encode_object($obj[$i]);
            }
            /* else if(is_object($obj[$i]))
              {
              foreach ($obj[$i] as $key => $value)
              {
              $retorno[] = json_encode(array($key => $value));
              }
              } */ else {
                $retorno[] = json_encode($obj[$i]);
            }
        }
    }

    $json = json_encode($retorno);

    return str_replace(array("\"{", "}\"", "\\"), array("{", "}", ""), $json);
}

/**
 * Função para encodificar & (ê comercial) e ' (aspas simples) para ser decoficad
 * após passar pelo métogo $_GET não confundindo com seus limitadores de parâmetros.
 * @param type $str
 * @return type
 */
function specialEncode($str){
    $find = array("&","'");
    $replace = array("_ecomercial_","_aspassimples_");
    $encStr = str_replace($find, $replace, $str);
    return $encStr; 
}

/**
 * Função para decodificar & (ê comercial) e ' (aspas simples) para ser decoficad
 * após passar pelo métogo $_GET não confundindo com seus limitadores de parâmetros.
 * @param type $str
 * @return type
 */
function specialDecode($str){
    $find = array("_ecomercial_","_aspassimples_");
    $replace = array("&","'");    
    $decStr = str_replace($find, $replace, $str);
    return $decStr; 
}

function csv_to_array($filename='', $delimiter=','){
    
    if(!file_exists($filename) || !is_readable($filename))
        return FALSE;

    $header = NULL;
    $data = array();
    if (($handle = fopen($filename, 'r')) !== FALSE)
    {
        while (($row = fgetcsv($handle, 1000, $delimiter)) !== FALSE)
        {
            if(!$header)
                $header = $row;
            else
                $data[] = array_combine($header, $row);
        }
        fclose($handle);
    }
    return $data;
}

function trim_array($input){
    if(!is_array($input)){
        return trim($input);
    }else{
        return array_map('trim_array',$input);
    }            
}

function csv_to_object_array($filename='', $delimiter=','){
        
    if(!file_exists($filename) || !is_readable($filename))
        return FALSE;

    $header = NULL;
    $data = array();
    if (($handle = fopen($filename, 'r')) !== FALSE){
                
        while (($row = fgetcsv($handle, 1000, $delimiter)) !== FALSE){
            if(!$header){
                $header = trim_array($row); 
            }else{
                $data[] = (object) array_combine($header, trim_array($row));
            }
        }
        fclose($handle);
    }
    return $data;
}

?>


