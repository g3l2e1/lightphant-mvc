<?php

/*class ArrayValue implements JsonSerializable {

	public function __construct(array $array) {
		$this->array = $array;
	}

	public function jsonSerialize() {
		return $this->array;
	}

}*/

function isAssoc($arr) {
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
					$objectIn[$i] = object_map($encode, $value[$i]);
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
 * Encodifica uma array de objetos ou objeto para json
 * @param stdClass $obj - objeto ou array de objeto
 * @return string json
 */
function json_encode_object($obj) {

	$retorno=[];

	//Tratamento de array.
	if (is_array($obj)) {
		for ($i = 0; $i < count($obj); $i++) {
			if (is_array($obj[$i])) {
				$retorno[] = json_encode_object($obj[$i]);
			}
		}
	//Tratamento de objeto
	}else if(is_object($obj)){
		if(get_class($obj) == 'stdClass'){
			$retorno[] = $obj;
		}
	//Tratamento simples.
	}else{
		$retorno[] = $obj;
	}

	$json = json_encode($retorno);

	return str_replace(array("\"{", "}\"", "\\"), array("{", "}", ""), $json);
}

/**
 * Função para encodificar & (ê comercial) e ' (aspas simples) para ser decoficad
 * após passar pelo métogo $_GET não confundindo com seus limitadores de parâmetros.
 * @param string $str
 * @return string
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
 * @param string $str
 * @return string
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


/**
 * Mesmo que d() + die()
 * Essa função d() vem do plugin kint similar ao VardUmper do Synphony
 * @param mixed $expression
 * The variable you want to dump.
 */
function dd($expression){
	d($expression);
	die();
}

function array_map_recursive($fn, $arr) {
	$rarr = array();
	foreach ($arr as $k => $v) {
		$rarr[$k] = is_array($v)
			? array_map_recursive($fn, $v)
			: $fn($v); // or call_user_func($fn, $v)
	}
	return $rarr;
}

function remover_acentos($string){
	$acentos = array( "/(á|à|ã|â|ä)/","/(Á|À|Ã|Â|Ä)/","/(é|è|ê|ë)/","/(É|È|Ê|Ë)/",
					  "/(í|ì|î|ï)/","/(Í|Ì|Î|Ï)/","/(ó|ò|õ|ô|ö)/","/(Ó|Ò|Õ|Ô|Ö)/",
					  "/(ú|ù|û|ü)/","/(Ú|Ù|Û|Ü)/","/(ñ)/","/(Ñ)/","/(ç)/","/(Ç)/");
	$substitutos = explode(" ","a A e E i I o O u U n N c C");

	return preg_replace($acentos, $substitutos, $string);
}

/**
 * Retorna true se um dos elementos da array Neddle for encontrado
 * @param array $neddle - array com elementos a serem encontrados
 * @param array $haystack - array a ser procurado
 */
function array_match($neddle, $haystack){
	$return = count(array_intersect($neddle, $haystack)) > 0 ? true : false;
	return $return;
}

/**
 * Função implode com callback.
 * @param string $separator
 * @param array $array
 * @param callback $callback
 * @return string
 */
function implodeCallback($callback, $separator, $array) {
	$returnValue = '';
	foreach($array as $value) {
			$returnValue .= $callback($value) . $separator;
	}
	return substr($returnValue, 0, strlen($returnValue) - 2);
}

/**
 * Extrai um texto entre duas palavras
 * @param string $content
 * @param string $start
 * @param string $end
 * @return mixed|string
 */
function str_between(string $content, string $start,string $end){
	$r = explode($start, $content);
	if (isset($r[1])){
		$r = explode($end, $r[1]);
		return $r[0];
	}
	return '';
}

if (!function_exists('dd2')) {
	function dd2(){
		echo '<pre>';
		array_map(function($x) { var_dump($x); }, func_get_args());
		die;
	}
}

function arrayOrderBy(array &$arr, $order = null) {
	if (is_null($order)) {
		return $arr;
	}
	$orders = explode(',', $order);
	usort($arr, function($a, $b) use($orders) {
		$result = array();
		foreach ($orders as $value) {
			list($field, $sort) = array_map('trim', explode(' ', trim($value)));
			if (!(isset($a[$field]) && isset($b[$field]))) {
				continue;
			}
			if (strcasecmp($sort, 'desc') === 0) {
				$tmp = $a;
				$a = $b;
				$b = $tmp;
			}
			if (is_numeric($a[$field]) && is_numeric($b[$field]) ) {
				$result[] = $a[$field] - $b[$field];
			} else {
				$result[] = strcmp($a[$field], $b[$field]);
			}
		}
		return implode('', $result);
	});
		return $arr;
}
