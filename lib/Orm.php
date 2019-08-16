<?php

class Orm {
	
	protected static $model;
	protected static $con;
	protected static $nextRow = false;
	protected static $transactionCounter = 0;
	protected static $stmt;
	protected static $persist = true;
	protected static $commitExec = true;
	protected static $returnScopeIdentity = true;
	protected static $scopeIdentity;
	protected static $cols = "";
	protected static $groupCols = "";
	protected static $having = "";
	protected static $join = null;
	protected static $where = "";
	protected static $order;
	protected static $sort;
	protected static $orderBy;
	protected static $pagination = false;
	protected static $page;
	protected static $pageSize;
	
	protected static $sql;
	protected static $sqlCount;
	protected static $totalRecords;
	protected static $commitsQtds = 0;
	protected static $forceInsert = false;
	
	/**
	 * Reseta as propriedades da classe Orm para os valores iniciais
	 * simulando uma instancia, para que um outro model não as herde. 
	 * 
	 * @return Orm
	 */
	public static function instance(){		
		self::$model = new static;
		self::$con = null;
		self::$nextRow = false;
		self::$transactionCounter = 0;
		self::$stmt = null;
		self::$persist = true;
		self::$commitExec = true;
		self::$returnScopeIdentity = true;
		self::$scopeIdentity = null;
		self::$cols = "";
		self::$groupCols = "";
		self::$having = "";
		self::$join = null;
		self::$where = "";
		self::$order = null;
		self::$sort = null;
		self::$orderBy;
		self::$pagination = false;
		self::$page = null;
		self::$pageSize = null;
		
		self::$sql = null;
		self::$sqlCount = null;
		self::$totalRecords = null;
		self::$commitsQtds = 0;
		self::$forceInsert = false;
		
		return new self;
	}
	
	/**
	 * Persistir no BD
	 * @param $action string{find|save} ação
	 */
	private static function exec($action="find") {

		//Iniciar transação.
		if(self::$transactionCounter == 0){
			self::$con->beginTransaction();
			self::$transactionCounter++;
		}
		
		//Executar insert.
		$stmt = self::$con->prepare(self::$sql);
		$stmt->execute();	
		
		/*Se o objeto con tiver a indicação de nextRowset usa a função nextRowSet()
		para executar a proxima linha do sql.*/
		if( self::$con->nextRow ){ $stmt->nextRowSet(); }
		
		//Retornar
		$data=[];
		if($action === "find"){
			
			//Retornar Find
			$data = $stmt->fetchAll(PDO::FETCH_CLASS, get_class(self::$model));
			
		}else if($action === "save"){
			//Retornar Save.
			if(self::$returnScopeIdentity){
				$data = $stmt->fetchAll(PDO::FETCH_OBJ);
				self::$scopeIdentity = $data[0]->scopeIdentity;
			}
		}
		self::$stmt = $stmt;
		self::$totalRecords = is_array($data) ? count($data) : 0;
		self::$model = $data ?? null;
		
		if(self::$commitExec){ self::commit(); self::$commitsQtds++;}
	}
		
	public static function con($con=null, bool $continueTransaction=false){
		
		self::instance();
		
		if($continueTransaction){ self::$transactionCounter = 1; }
		
		self::$con = $con == null ? self::$model->getCon() : $con; 
		return new self;
	}
	
	public static function getTransactionCounter(){
		return self::$transactionCounter;
	}
	
	/**
	 * Força update no model com pk com valor preenchido.
	 * Usado em casos onde o id precisa ser inserido.
	 * @return Orm
	 */
	public static function forceInsert(){
		self::$forceInsert = true;
		return new self;
	}
	
	/**
	 * Adiciona uma where sql.
	 * @param string $where
	 */
	public static function where($where) {
		
		//self::instance();
		
		self::$where .= $where ?? "";
		return new self;
	}
	
	/**
	 * Adiciona ao where uma condição.
	 * @param string $property - campo a ser filtrado.
	 * @param string $value - valor a ser encontrado.
	 */
	public static function filterBy($property, $value) {
		
		//self::instance();
		
		$m = self::$model;
		
		//Definir campo para filtro
		//$condition = $m->getConfig()->$property->coluna;
		
		//Definir campo para filtro acrescentando tabela
		if(strpos($property,".") == 0){
			$column = $m->getConfig()->$property->coluna;
			$tbl = $m->getTable();
			//$condition = $column.".".$tbl;
		}
		
		//Definir where sql se houver valor.
		if ($value != null) {
			$type = $m->getConfig()->$property->tipo;
			$signal = is_array($value) ? "in" : "=";
			$validVal = $m->addQuotesForSql($type, $value);
			
			$whrVal = is_array($value) ? "({$validVal})" : $validVal;
			
			self::$where .= " and {$tbl}.{$column} {$signal} {$whrVal}";
		}
		
		return new self;
	}
	
	/**
	 * Adiciona ao where uma condição between.
	 * @param string $property - campo a ser filtrado.
	 * @param string $value - valor a ser encontrado.
	 */
	public static function filterBetween($property, $initial, $final) {
		
		//self::instance();
		
		$m = self::$model;
		
		//Definir campo para filtro
		//$condition = $m->getConfig()->$property->coluna;
		
		//Definir campo para filtro acrescentando tabela
		if(strpos($property,".") == 0){
			$column = $m->getConfig()->$property->coluna;
			$tbl = $m->getTable();
			//$condition = $column.".".$tbl;
		}
		
		//Definir where sql se houver valor.
		if ($initial != null && $final != null) {
			$type = $m->getConfig()->$property->tipo;
			$validInitial = $m->addQuotesForSql($type, $initial);
			$validFinal = $m->addQuotesForSql($type, $final);
			
			self::$where .= " and {$tbl}.{$column} between {$validInitial} and {$validFinal}";
		}
		
		return new self;
	}

	/**
	 * Adiciona ao where uma condição executando cast
	 * @param string $property - campo a ser filtrado.
	 * @param string $value - valor a ser encontrado.
	 */
	public static function filterByWithCast($property, $value, $cast) {
		
		//self::instance();
		
		$m = self::$model;
		
		//Definir campo para filtro
		//$condition = $m->getConfig()->$property->coluna;
		
		//Definir campo para filtro acrescentando tabela
		if(strpos($property,".") == 0){
			$column = $m->getConfig()->$property->coluna;
			$tbl = $m->getTable();
			//$condition = $column.".".$tbl;
		}
		
		//Definir where sql se houver valor.
		if ($value != null) {
			$type = $m->getConfig()->$property->tipo;
			$signal = is_array($value) ? "in" : "=";
			$validVal = $m->addQuotesForSql($type, $value);
			
			$whrVal = is_array($value) ? "({$validVal})" : $validVal;
			
			self::$where .= " and CAST({$tbl}.{$column} AS {$cast}) {$signal} {$whrVal}";
		}
		
		return new self;
	}

	/**
	 * Adiciona ao where uma condição executando convert
	 * @param string $property - campo a ser filtrado.
	 * @param string $value - valor a ser encontrado.
	 */
	public static function filterByWithConvert($property, $value, $convert) {
		
		//self::instance();
		
		$m = self::$model;
		
		//Definir campo para filtro
		//$condition = $m->getConfig()->$property->coluna;
		
		//Definir campo para filtro acrescentando tabela
		if(strpos($property,".") == 0){
			$column = $m->getConfig()->$property->coluna;
			$tbl = $m->getTable();
			//$condition = $column.".".$tbl;
		}
		
		//Definir where sql se houver valor.
		if ($value != null) {
			$type = $m->getConfig()->$property->tipo;
			$signal = is_array($value) ? "in" : "=";
			$validVal = $m->addQuotesForSql($type, $value);
			
			$whrVal = is_array($value) ? "({$validVal})" : $validVal;
			
			self::$where .= " and CONVERT({$convert}, {$tbl}.{$column}) {$signal} {$whrVal}";
		}
		
		return new self;
	}
	
	/**
	 * Adiciona ao where uma condição de maior que.
	 * @param string $property - campo a ser filtrado.
	 * @param string $value - valor a ser encontrado.
	 */
	public static function filterByGreater($property, $value) {
		
		//self::instance();
		
		$m = self::$model;
		
		//Definir campo para filtro
		//$condition = $m->getConfig()->$property->coluna;
		
		//Definir campo para filtro acrescentando tabela
		if(strpos($property,".") == 0){
			$column = $m->getConfig()->$property->coluna;
			$tbl = $m->getTable();
			//$condition = $column.".".$tbl;
		}
		
		//Definir where sql se houver valor.
		if ($value != null) {
			$type = $m->getConfig()->$property->tipo;
			$validVal = $m->addQuotesForSql($type, $value);
			
			$whrVal = is_array($value) ? "({$validVal})" : $validVal;
			
			self::$where .= " and {$tbl}.{$column} > {$whrVal}";
		}
		
		return new self;
	}
	
	/**
	 * Adiciona ao where uma condição de menor que.
	 * @param string $property - campo a ser filtrado.
	 * @param string $value - valor a ser encontrado.
	 */
	public static function filterByLess($property, $value) {
		
		//self::instance();
		
		$m = self::$model;
		
		//Definir campo para filtro
		//$condition = $m->getConfig()->$property->coluna;
		
		//Definir campo para filtro acrescentando tabela
		if(strpos($property,".") == 0){
			$column = $m->getConfig()->$property->coluna;
			$tbl = $m->getTable();
			//$condition = $column.".".$tbl;
		}
		
		//Definir where sql se houver valor.
		if ($value != null) {
			$type = $m->getConfig()->$property->tipo;
			$validVal = $m->addQuotesForSql($type, $value);
			
			$whrVal = is_array($value) ? "({$validVal})" : $validVal;
			
			self::$where .= " and {$tbl}.{$column} < {$whrVal}";
		}
		
		return new self;
	}
	
	/**
	 * Adiciona ao where uma condição de diferente de.
	 * @param string $property - campo a ser filtrado.
	 * @param string $value - valor a ser encontrado.
	 */
	public static function filterByDifferent($property, $value) {
		
		//self::instance();
		
		$m = self::$model;
		
		//Definir campo para filtro
		//$condition = $m->getConfig()->$property->coluna;
		
		//Definir campo para filtro acrescentando tabela
		if(strpos($property,".") == 0){
			$column = $m->getConfig()->$property->coluna;
			$tbl = $m->getTable();
			//$condition = $column.".".$tbl;
		}
		
		//Definir where sql se houver valor.
		if ($value != null) {
			$type = $m->getConfig()->$property->tipo;
			$validVal = $m->addQuotesForSql($type, $value);
			
			$whrVal = is_array($value) ? "({$validVal})" : $validVal;
			$sign = is_array($value) ? "not in" : "<>";
			
			self::$where .= " and {$tbl}.{$column} {$sign} {$whrVal}";
		}
		
		return new self;
	}
	
	/**
	 * Adiciona ao where uma condição de maior que.
	 * @param string $property - campo a ser filtrado.
	 * @param string $value - valor a ser encontrado.
	 */
	public static function filterByNotNull($property) {
		
		//self::instance();
		
		$m = self::$model;
		$column = $m->getConfig()->$property->coluna;
		$tbl = $m->getTable();
		
		//Definir where sql se houver valor.
		self::$where .= " and {$tbl}.{$column} is not null";
		
		return new self;
	}
	
	public static function paginate(int $page=null, int $pageSize=null) {
		$page = $page ?? 1;
		$pageSize = $pageSize ?? 50;
		self::$pagination = true;
		self::$page = $page;
		self::$pageSize = $pageSize;
		
		return new self;
	}
	
	public static function orderBy(int $order, $sort) {
		if($order != null && $sort != null){
			self::$order = $order;
			self::$sort = $sort == 'asc' ? 'asc' : 'desc';
			self::$orderBy = $order." ".$sort;
		}
		return new self;
	}
	
	private static function makeJoin($value){
		
		$m = self::$model;
		$tabelaAtual = $m->getTable();
		
		$valueExplode = explode(".", $value);
		$property = $valueExplode[0];
		$fkProperty = $valueExplode[1];
		$colunaAtual = $m->getConfig()->$property->coluna;
		
		$fk = $m->getConfig()->$property->fk;
		$fkExplode = explode(".", $fk);
		$fkModel = $fkExplode[0];
		$fkPropertyJoin = $fkExplode[1];
		
		$model = new $fkModel();
		$fkTable = $model->getTable();
		$fkColumn = $model->getConfig()->$fkPropertyJoin->coluna;
		
		$join = "INNER JOIN ".$fkTable." ON ".$fkTable.".".$fkColumn." = ".$tabelaAtual.".".$colunaAtual;
		$leftJoin = "LEFT JOIN ".$fkTable." ON ".$fkTable.".".$fkColumn." = ".$tabelaAtual.".".$colunaAtual;
		
		return ['fkModel'=>$fkModel, 'fkProperty'=>$fkProperty, 
				'fkPropertyJoin'=>$fkPropertyJoin, 'fkTable'=> $fkTable, 
				'fkColumn'=>$fkColumn, 'innerJoin'=>$join, 'leftJoin'=>$leftJoin];
	}
	
	public static function maxProperties(array $properties, $alias=null, $fk=false){
		$cols = "";
		$m = self::$model;
		foreach ($properties as $value) {
			//Verificar se o valor é estrangeiro
			if(strpos($value,'.')){
				$fk = self::makeJoin($value);
				$fModel = new $fk['fkModel']();
				$fkValue = $fk['fkProperty'];
				$fkTable = $fk['fkTable'];
				self::$join[] = $fk['innerJoin'];
				
				$coluna = $fkTable.".".$fModel->getConfig()->$fkValue->coluna;
				$alias = $alias ?? "max".ucwords(self::aliasFromFk($fkValue));
				$cols .= "MAX(".$coluna.") as ".$alias.","; 
			}else{
				$coluna = $m->getConfig()->$value->coluna;
				$alias = $alias ?? "max".ucwords($value);
				$cols .= "MAX(".$coluna.") as ".$alias.",";
			}
		}
		self::$model = $m;
		self::$cols .= $cols;
		return new self;
	}
	
	public function minProperties(array $properties, $alias=null){
		$cols = "";
		$m = self::$model;
		foreach ($properties as $value) {
			if(strpos($value,'.')){
				$fk = self::makeJoin($value);
				$fModel = new $fk['fkModel']();
				$fkValue = $fk['fkProperty'];
				$fkTable = $fk['fkTable'];
				self::$join[] = $fk['innerJoin'];
				
				$coluna = $fkTable.".".$fModel->getConfig()->$fkValue->coluna;
				$alias = $alias ?? "min".ucwords(self::aliasFromFk($fkValue));
				$cols .= "MIN(".$coluna.") as ".$alias.",";
			}else{
				$coluna = $m->getConfig()->$value->coluna;
				$alias = $alias ?? "min".ucwords($value);
				$cols .= "MIN(".$coluna.") as ".$alias.",";
			}
		}
		self::$model = $m;
		self::$cols .= $cols;
		return new self;
	}
	
	public function countProperties(array $properties, $alias=null, $mostrarNulos=false){
		$cols = "";
		$m = self::$model;
		foreach ($properties as $value) {
			if(strpos($value,'.')){
				$fk = self::makeJoin($value);
				$fModel = new $fk['fkModel']();
				$fkValue = $fk['fkProperty'];
				$fkTable = $fk['fkTable'];
				self::$join[] = $mostrarNulos ? $fk['leftJoin'] : $fk['innerJoin'];
				
				$coluna = $fkTable.".".$fModel->getConfig()->$fkValue->coluna;
				$alias = $alias ?? "count".ucwords(self::aliasFromFk($fkValue));
				$cols .= "COUNT(".$coluna.") as ".$alias.",";
			}else{
				$coluna = $m->getConfig()->$value->coluna;
				$alias = $alias ?? "count".ucwords($value);
				$cols .= "COUNT(".$coluna.") as ".$alias.",";
			}
		}
		self::$model = $m;
		self::$cols .= $cols;
		return new self;
	}
	
	public static function sumProperties(array $properties, $alias=null, $mostrarNulos=false){
		$cols = "";		
		$m = self::$model;
		foreach ($properties as $value) {
			if(strpos($value,'.')){
				$fk = self::makeJoin($value);
				$fModel = new $fk['fkModel']();
				$fkValue = $fk['fkProperty'];
				$fkTable = $fk['fkTable'];
				self::$join[] = $mostrarNulos ? $fk['leftJoin'] : $fk['innerJoin'];
				
				$coluna = $fkTable.".".$fModel->getConfig()->$fkValue->coluna;
				$alias = $alias ?? "sum".ucwords(self::aliasFromFk($fkValue));
				$cols .= "SUM(".$coluna.") as ".$alias.",";
			}else{
				$coluna = $m->getConfig()->$value->coluna;
				$alias = $alias ?? "sum".ucwords($value);
				$cols .= "SUM(".$coluna.") as ".$alias.",";
			}
		}
		self::$model = $m;
		self::$cols .= $cols;
		return new self;
	}
	
	public static function calc(string $calc){
		$cols = $calc.",";
		self::$cols .= $cols;
		return new self;
	}
	
	public static function groupProperties(array $properties){
		$grp = "";
		$m = self::$model;
		foreach ($properties as $value) {
			if(strpos($value,'.')){
				$fk = self::makeJoin($value);
				$fModel = new $fk['fkModel']();
				$fkValue = $fk['fkProperty'];
				$fkTable = $fk['fkTable'];
				
				$coluna = $fkTable.".".$fModel->getConfig()->$fkValue->coluna;
				$grp .= $coluna.",";
			}else{
				$coluna = $m->getConfig()->$value->coluna;
				$grp .= $coluna.",";
			}
		}
		self::$model = $m;
		self::$groupCols .= $grp; 
		return new self;
	}
	
	public static function having(string $havingInstruction){
		self::$having .= $havingInstruction;
		return new self;
	}
	
	private static function aliasFromFk($value){
		$search = [
			'.a','.b','.c','.d','.e','.f','.g','.h','.i','.j','.k','.l','.m',
			'.n','.o','.p','.q','.r','.s','.t','.u','.v','.w','.x','.y','.z'				
		];
		$replace = [
			'A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P',
			'Q','R','S','T','U','V','W','X','Y','Z'
		];
		return str_replace($search, $replace, $value);
	}
	
	public static function showProperties(array $properties, $alias=false, $mostrarNulos=false){
		$cols = "";
		$m = self::$model;
		foreach ($properties as $value) {
			if(strpos($value,'.')){
				$fk = self::makeJoin($value);
				$fModel = new $fk['fkModel']();
				$fkValue = $fk['fkProperty'];
				$fkTable = $fk['fkTable'];
				self::$join[] = $mostrarNulos ? $fk['leftJoin'] : $fk['innerJoin'];
				
				$coluna = $fkTable.".".$fModel->getConfig()->$fkValue->coluna;
				$falias = $alias ? $alias : self::aliasFromFk($value);
				$cols .= $coluna." as ".$falias.","; 
			}else{
				$coluna = $m->getConfig()->$value->coluna;
				$falias = $alias ? $alias : $value;
				$cols .= $coluna." as ".$falias.",";
			}
		}
		self::$model = $m;
		self::$cols .= $cols;
		return new self;
	}
	
	/**
	 * 
	 * @param int $id pk da tabela a ser procurado
	 * @param bool $commit - comitar 
	 * @return Orm
	 */
	public static function find($id=null, bool $commit=true){
			
		self::$commitExec = $commit;
			
		$m = self::$model;
		$pk = $m->getPkProperty(); 
		$pk2 = $pk[0] ?? null;
		$pkColumn = $m->getConfig()->$pk2->coluna;
		$cols = strlen(trimAll(self::$cols)) > 0 
			? substr(self::$cols,0,-1) 
			: implode(",",$m->getColumnsForFind());
		$groupCols = strlen(trimAll(self::$groupCols)) > 0
			? substr(self::$groupCols,0,-1) 
			: false;
		$havingInstruction = strlen(trimAll(self::$having)) > 0 ? self::$having : false;
		$tbl = $m->getTable();
		$join = implode(" ",array_unique(is_array(self::$join) ? self::$join : ['']));
		
		//Construir Sql.
		$sql = new QueryBuilder();
		$sql->colunas($cols);
		$sql->tabela($tbl);
		$sql->join($join);
		$sql->paginar(self::$page, self::$pageSize);
		$sql->orderBy(self::$order, self::$sort);
		//Achar por id/pk
		if($id != null){
			$signal = is_array($id) ? "in" : "=";
			$idList = is_array($id) ? "(".implode(", ",$id).")" : $id;
			/*for ($i = 0; $i < count($pk); $i++) {
				$sql->where(" and {$tbl}.{$pk[$i]} {$signal} {$idList}");
			}   */
			$sql->where(" and {$tbl}.{$pkColumn} {$signal} {$idList}");
		}
		//Achar por filtros
		$sql->where(self::$where);
		if($groupCols){ $sql->groupBy($groupCols); }
		if($havingInstruction){ $sql->having($havingInstruction); }
		$sql->listar();
		self::$sql = $sql->getSql();
		
		self::$con->query = $sql->getSql();
		
		//Persistir.
		if(self::$persist){ self::exec(); }
		
		return new self;
	}
	
	public static function ormSave(bool $commit=true){
		
		self::$commitExec = $commit;
		
		$m = is_array(self::$model) ? self::$model[0] : self::$model;
		$cols = implode(", ",$m->getColumnsForSave());
		$vals = implode("▀ ",$m->getValuesForSave());
		$tbl = $m->getTable();
		//$pk = $m->getPkProperty();
		$pkCol = $m->getPkColumn();
		$pkVals = $m->getPkValues();
		$forceInsert = self::$forceInsert;
		
		//Construir Sql.
		$sql = new QueryBuilder();
		$sql->colunas($cols);
		$sql->valores($vals);
		$sql->tabela($tbl);
		if(count($pkVals)==0 || $forceInsert){
			
			if($forceInsert){ $sql->criarForcandoInsert($pkCol, $pkVals); } else { $sql->criar(); }
			
			//Modificar sql para retornar o id após a inserção.
			if (self::$returnScopeIdentity) {
				self::$sql = "SET NOCOUNT ON ".$sql->getSql()." select scope_identity() as scopeIdentity";
			}else{
				self::$sql = $sql->getSql();
			}			
		}else{			
			for ($i = 0; $i < count($pkCol); $i++) {
				$sql->where(" {$pkCol[$i]} = {$pkVals[$i]}");
			}
			$sql->editar();
			self::$sql = $sql->getSql();
			//Não retornar o scopeIdentity para persistir em update.
			self::$returnScopeIdentity = false;
		}
		
		self::$con->query = $sql->getSql();
		
		//Persistir.
		if(self::$persist){ self::exec("save"); }
		
		return new self;
	}
	
	public static function destroy($id=null, bool $commit=true){
		
		self::$commitExec = $commit;
		
		$m = is_array(self::$model) ? self::$model[0] : self::$model;
		$pk = $m->getPkProperty();
		$pk2 = $pk[0] ?? null;
		$pkColumn = $m->getConfig()->$pk2->coluna;
		$tbl = $m->getTable();
		
		//Construir Sql.
		$sql = new QueryBuilder();
		$sql->tabela($tbl);
		
		$signal = is_array($id) ? "in" : "=";
		$idList = is_array($id) ? "(".implode(", ",$id).")" : $id;
		/*for ($i = 0; $i < count($pk); $i++) {
			$sql->where(" {$pk[$i]} {$signal} {$idList}");
		} */
		//excluir por where
		$sql->where(self::$where);
		//excluir por id
		if(!empty($idList)){
			$sql->where(" and {$tbl}.{$pkColumn} {$signal} {$idList}");
		}
		
		if(strlen(trimAll($sql->getWhere())) < 5){
			throw new Exception('Ação de delete sem where impedida!');
		}
		$sql->excluir();
		self::$sql = $sql->getSql();  
		
		//Não retornar o scopeIdentity para persistir em delete.
		self::$returnScopeIdentity = false;
		
		self::$con->query = $sql->getSql();
		
		//Persistir.
		if(self::$persist){ self::exec('destroy'); }
		
		return new self;
	}
	
	public static function persist($persist=true){
		self::$persist = $persist;
		return new self;
	}
	
	/**
	 * Imprime em tela para depuração o sql vigente interrompendo todos os processos. die();
	 */
	public static function printSqlAndDie(){
		print SqlFormatter::format(self::$sql); die();
		return new self;
	}
	
	/**
	 * Imprime em tela para depuração o sql vigente;
	 */
	public static function printSql(){
		print SqlFormatter::format(self::$sql);
		return new self;
	}
	
	/**
	 * resgata o sql vigente;
	 */
	public static function getSql(){
		return self::$sql;
	}
	
	/**
	 * Persiste/Comita uma transação iniciada $con->beginTransaction()
	 */
	public static function commit() {		
		self::$con->commit();
		self::$transactionCounter = 0;
		return new self;		
	}
	
	public static function getPdo(){
		return self::$con;
	}
	
	/**
	 * Retorna o total de registros
	 * @return int
	 */
	public static function getTotalRecords(){
		return self::$totalRecords;
	}
	
	/**
	 * Retorna o objeto persistido.
	 * @return object class
	 */
	public static function get(){
		return self::$model;
	}
	
	/**
	 * Retorna uma array como todas os valores de uma propriedade.
	 * @param string $property
	 * @return array
	 */
	public static function getAllProperties(string $property){
		
		$model = self::$model;
		$getMethod = "get".ucfirst($property);
		
		$arrProps = [];
		for ($i = 0; $i < count($model); $i++) {
			$arrProps[] = $model[$i]->$getMethod();
		}
		
		return $arrProps;
	}
	
	/**
	 * Retorna o objeto persistido.
	 * @return object class
	 */
	public static function getIterated(){
		
		$m = self::$model;
		
		if(is_array($m)){
			$iterated = [];
					
			for ($i = 0; $i < count($m); $i++) {
				$iterated[] = $m[$i]->iTerator();
			}
		}else{
			$iterated[] = $m->iTerator();
		}
		
		return $iterated;
	}
	
	/**
	 * Retorna o objeto em json. Se o nível for menor que 0 
	 * o json retorna o primeiro objeto sem a camada de array.
	 * @param int $nivel -1 or n
	 * @return string json
	 */
	public static function getJson(int $nivel=null){
		
		$m = (array) self::getIterated();
		$json = $nivel < 0 ? $m[0] : $m;
		
		return json_encode($json, JSON_NUMERIC_CHECK);
	}
}
