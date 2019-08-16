<?php

class Model extends Orm{

  public function __construct($data=null) {
    //Preencher objeto 
    if(!empty($data)){ $this->fill($data); }
    
    //Definir model
    parent::$model = $this; 
    
    //Definir conexão
    parent::$con = $this->getCon();
  }

  /**
   * Retorna o nome da classe do Model.
   */
  public function getClasse() {
    return get_class($this);
  }

  /**
   * Retornar as variáveis do Model.
   */
  public function getVariaveis() {
    return get_class_vars(get_class($this));
  }

  /**
   * Preenche o objeto Model com um array quando o índice do array for igual
   * ao nome da variável usando métodos set do objeto Model.
   * @param $obj - array.
   */
  public function fill($data){      
    if(@$data[0] != null){
      for ($i = 0; $i < count($data); $i++) {
        foreach ($data[$i] as $key => $value) {
          $setFunction = "set".ucwords($key);
          if (method_exists($this, $setFunction)) {
            $this->$setFunction($value);
          }
        }
      }
    }else{ 
      foreach ($data as $key => $value) {                
        $setFunction = "set".ucwords($key);                
        if (method_exists($this, $setFunction)) {
          $this->$setFunction($value);
        }
      }
    }      
  }

  /**
   * Transforma o objeto de variáveis protected ou private em pública 
   * para varredura com for ou foreah sem usar o método get.
   */
  public function iTerator() {      
    $iterator = (object) array(); 
    foreach ($this as $key => $value) {
      if (is_array($value) && @$value[0] != null) {
        for ($i = 0; $i < count($value); $i++) {
          $iterator->$key = array($value[$i]->iTerator());
        }
      } else {
        $iterator->$key = $value;
      }
    }
    return $iterator;
  }
    
  /**
   * Transforma o objeto em array
   */
  public function toArray() {  	
  	return (array) $this;
  }

  /**
   * Transformar as variáveis protected e private em pública para varredura
   * ao mesmo tempo que adiciona aspas nas variáveis do tipo string (varchar, char...).
   */
  public function iTeratorForSql() {
    $iterator = (object) array();
    foreach ($this as $key => $value) {
      if (is_array($value) && is_object($value[0])) {
        for ($i = 0; $i < count($value); $i++) {
          $iterator->$key = array($value[$i]->iTerator());
        }
      } else {
        $valorParaSql = null;
        
        $config = $this->getConfig();
        $type = $config->$key->tipo;

        switch ($type) {
          case 'string': $valorParaSql = "'" . str_replace("'", "''", $value) . "'"; break;
          case 'varchar': $valorParaSql = "'" . str_replace("'", "''", $value) . "'"; break;
          case 'text': $valorParaSql = "'" . str_replace("'", "''", $value) . "'"; break;
          case 'date': $valorParaSql = "'" . $value . "'"; break;
          case 'datetime2': $valorParaSql = "'" . $value . "'"; break;
          case 'datetime': $valorParaSql = "'" . $value . "'"; break;
          case 'nchar': $valorParaSql = "'" . str_replace("'", "''", $value) . "'"; break;
          case 'nvarchar': $valorParaSql = "'" . str_replace("'", "''", $value) . "'"; break;
          case 'ntext': $valorParaSql = "'" . str_replace("'", "''", $value) . "'"; break;
          default: $valorParaSql = $value; break;
        }

        if ($value === 0) {
          $iterator->$key = 0;
        } else {
          $iterator->$key = ($valorParaSql != "'null'" && $valorParaSql != "''" && $valorParaSql != "" ? $valorParaSql : 'null');
        }
      }
    }
    return $iterator;
  }

  /**
   * Retorna o objeto Model no formato json.
   */
  public function toJson() {
    $json = array();
    foreach ($this as $key => $value) {
      $json[$key] = $value;
    }
    return json_encode(array_map('htmlentities', $json));
  }
    
  /**
   * Retorna a propriedade da classe que é PK no banco.
   * @return array
   */
  public function getPkProperty(){
    $pk = [];
    foreach ($this->getConfig() as $key => $value) {
      if($value->pk ?? false){ $pk[] = $key; }
    }
    return $pk;
  }
    
  /**
   * Retorna a coluna que é PK no banco.
   * @return array
   */
  public function getPkColumn(){
  	$pk = [];
  	foreach ($this->getConfig() as $value) {
  		if($value->pk ?? false){ $pk[] = $value->coluna; }
  	}
  	return $pk;
  }
    
  public function getPkValues(){
    $pkValue = [];
    foreach ($this as $key => $value) {
      $pk = $this->getConfig()->$key->pk ?? false;
      $notNullPkValue = $value == 'null' || empty($value) ? false : $value;
      if( $pk && $notNullPkValue ){ $pkValue[] = $value; }
    }
    return $pkValue;
  }
    
  public function getColumnsForFind(){
    $cols = [];
    foreach ($this->getConfig() as $key => $value) {
      $cols[] = $this->getTable().".".$value->coluna." as ".$key ?? null;
    }
    return $cols;
  }
    
  public function getColumnsForSave(){
    $cols = [];
    foreach ($this->getConfig() as $value) {
      $pkColumn = $value->pk ?? false;
      if(!$pkColumn){
        $cols[] = $value->coluna ?? null;
      }
    }
    return $cols;
  }
    
  public function getValuesForSave(){
    $vals = [];
    foreach ($this as $key => $value) {
      $pkColumn = $this->getConfig()->$key->pk ?? false;
      $columnType = $this->getConfig()->$key->tipo ?? null;
      if(!$pkColumn && $columnType != null){
        $vals[] = $this->addQuotesForSql($columnType, $value);
      }
    }
    return $vals;
  }
    
  protected function addQuotesForSql($type, $value){
    if($value === 'null' || $value === null){
      return 'null';
    }else if(is_array($value)){
      $strQuotedValue = [];
      for ($i = 0; $i < count($value); $i++) {
        $strQuotedValue[] = $this->addQuotesForSql($type, $value[$i]);
      }
      return implode(",",$strQuotedValue);
    }else{
      $quotedsTypes = [
        "string","varchar","text","date","datetime","datetime2","nchar",
        "nvarchar","ntext","char"
      ];
      
      $quotedValue = in_array($type, $quotedsTypes)
        ? "'".str_replace("'","''",$value)."'"
        : $value;
          
      return $quotedValue;
    }        
  }
    
  public function printSaveSqlAndDie(){
    parent::$persist = false;
    parent::ormSave();
    parent::printSqlAndDie();
  }
    
  public static function printDestroySqlAndDie(){
    parent::$persist = false;
    parent::destroy();
    parent::printSqlAndDie();
  }
    
  public function save(bool $commit=true){  	
  	parent::$model = $this;
    parent::ormSave($commit);
      
    //Retornar ScopeIdentity
    if(parent::$returnScopeIdentity){
      $pk = $this->getPkProperty();
      $set = "set".ucfirst($pk[0]);
      $this->$set(parent::$scopeIdentity);
    }      
  }
    
}