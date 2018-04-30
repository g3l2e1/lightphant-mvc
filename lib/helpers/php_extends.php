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

/**
 * Aplica mascara a uma string
 * Ex: mask('60340000', '##.###-###')
 * Retornará 60.340-000
 * @param string $val
 * @param string $mask 
 * @return string
 */
function mask(string $val, string $mask) {
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

/**
 * Remove todos os espaços em branco de uma string
 * @param string $string
 * @return string
 */
function trimAll($string = false) {
    if ($string) {
        return str_replace(array(" ", "\n", "\t", "\r", "\0", "\x0B"), array("", "", "", "", "", ""), $string);
    }
}

/**
 * Retorna todas as primeiras letras de cada nome em maiúscula e o restante
 * em minúscula incluindo preposições junções de nomes.
 * @param string $nome
 * @return string
 */
function formatNomePessoal(string $nome){
    $minusculas = mb_strtolower($nome);
    $primeiraMaiuscula = ucwords($minusculas);
    
    $search = array(' De ', ' Da ', ' Do ', ' E ', ' Du ');
    $replace = array(' de ', ' da ', ' do ', ' e ', ' du ');
    $nomeFormatado = str_replace($search, $replace, $primeiraMaiuscula);
    
    return $nomeFormatado;
}

/**
 * Codifica todos os valores de um objeto
 * @param string $encode
 * @param StdClass $obj
 * @return StdClass
 */
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

/**
 * Gera json a partir de um objeto
 * @param StdClass $obj
 * @return mixed
 */
function json_encode_object($obj) {
    
    $retorno;

    if (is_array($obj)) {
        for ($i = 0; $i < count($obj); $i++) {
            if (is_array($obj[$i])) {
                $retorno[] = json_encode_object($obj[$i]);
            }else {
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
 * @param String $str
 * @return String
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
 * @param String $str
 * @return String
 */
function specialDecode($str){
    $find = array("_ecomercial_","_aspassimples_");
    $replace = array("&","'");    
    $decStr = str_replace($find, $replace, $str);
    return $decStr; 
}



/**
 * Remove espaços em branco de um array.
 * @param array|string|int $input
 * @return string|array
 */
function trim_array($input){
    if(!is_array($input)){
        return trim($input);
    }else{
        return array_map('trim_array',$input);
    }            
}

/**
 * Converte um arquivo csv em array
 * @param string $filename
 * @param string $delimiter
 * @return array[]
 */
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

/**
 * Converte um arquivo csv para array de objetos
 * @param string $filename
 * @param string $delimiter
 * @return StdClass[]
 */
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


