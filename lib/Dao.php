<?php

class Dao {
  protected $sql;
  protected $sqlCount;
  protected $registros;
  protected $obj;
  protected $con;
  protected $transactionCounter = 0;
  protected $paginar = false;
  protected $pagina;
  protected $tamPagina;
  protected $totalRegistros;
  protected $tabela;
  protected $variaveis = false;
  protected $config;
  protected $where = "";
  protected $order;
  protected $orderBy = false;
  protected $groupBy = false;
  protected $sort = ["asc"];
  protected $over;
  protected $fetchClass = false;
  protected $fetchClassModel;
  protected $scopeIdentity = false;
  protected $returnInserted = false;
  protected $returnUpdated = false;
  protected $returnMessage = "";

  public function __construct($obj = false) {
    //Definir objeto do model.
    $this->obj = $obj;

    if ($obj) {
      $this->registros = $obj->iTerator();
      $this->variaveis = $this->obj->getVariaveis();
      $this->tabela = $this->obj->getTabela();
      $this->config = $this->obj->getConfig();
    }
  }
    
  public function setCon($con, bool $continueTransaction=false){
  	$this->con = $con;	
  	$this->transactionCounter = $continueTransaction ? 1 : 0;
  }
    
  public function getPdo(){
  	return $this->con;
  }
    
  /**
   * Prepara o sql para execução injetando a coluna linha com row_number e over
   * para paginação bem como a cláusula where.
   * 
   * OBS: O sql deve trazer _LINHA_ para a linha e _WHERE_ para where com  col-
   * chetes.
   * EXEMPLO: SELECT ID, NOME _LINHA_ FROM Usuario _WHERE_
   * 
   * OBS2: A identificação é feita pela primeira ocorrência das palavras reser-
   * vadas no T_SQL para o CRUD (SELECT, UPDATE, INSERT e DELETE) caso haja al-
   * guma instrução anterior no sql, deve-se colocar a natureza final do   Crud
   * na primeira linha do sql como comentário para contornar o problema.
   * EXEMPLO: 
   * -- UPDATE
   * SELECT @idTableA = ID FROM TABLE_A WHERE SIT = 1
   * UPDATE TABLE_B SET ID_TABLE_A = @idTableA WHERE ...
   * 
   * OBS3: O método também substitui os alias identificados após as. Portanto al-
   * gumas funções do sql para serem preservadas devem esta com AS maiúscula.
   * EXEMPLO: CAST(PEDIDO AS VARCHAR) as pedido.
   * Neste exemplo 'AS' será preservado e 'as pedido' removido das colunas.
   * 
   * @param string $sql
   */
  public function prepareSql(string $sql, string $sqlRetorno=""){  	
  	//Descobrir qual CRUD do sql.
  	$select=[]; $update=[]; $insert=[];
  	preg_match("/^SELECT/",$sql, $select);
  	preg_match("/^UPDATE/",$sql, $update);
  	preg_match("/^INSERT/",$sql, $insert);
  	
  	if($select){
    	$arrCols = $this->getArrayColumnsFromStringQuery($sql);
    	
    	$this->over = $this->paginar == true ? $this->makeOver($arrCols) : "";
    	$linha = $this->paginar == true ? ",\rlinha = ROW_NUMBER() OVER( ORDER BY {$this->over} )" : "";
    	
    	//Injetar sql
    	$orderBy = $this->orderBy == false ? "" : "ORDER BY " . implode(", ", $this->orderBy);
    	$innerOrderBy = $this->orderBy == false ? "" : "ORDER BY " . implode(", ", $this->orderBy);
    	$where = trimAll($this->where) == "" ? "" : "WHERE 1=1 " . $this->where;
    	
    	$sqlInjected = str_replace(["_LINHA_","_WHERE_"], [$linha, $where], $sql);
    	
    	//Select paginado.
    	$sqlPaginado =
      	"SELECT TOP ({$this->tamPagina}) *
  		   FROM ( {$sqlInjected} ) AS paginacao
            WHERE linha > ((({$this->tamPagina} * {$this->pagina}) - {$this->tamPagina}))
           {$orderBy} ";
                 
		  //Definir Sql.
      $this->sql = $this->paginar == true ? $sqlPaginado : $sqlInjected . " " . $innerOrderBy;	
        
      //Definir SqlCount
      $this->sqlCount = $this->paginar == true ? "select count(*) as totalRegistros from ( {$sqlInjected} )as contagem" : null;
         
  	}else if($update || $insert){
  		
  		//Modificar sql para retornar o registro inteiro após a inserção.
  		if ($this->returnInserted || $this->returnUpdated) {
  			
        $this->sql = "SET NOCOUNT ON " . $sql . " ".$sqlRetorno;
  			
  		//Modificar sql para retornar o id após a inserção.
  		} else if ($this->scopeIdentity) {
    			
  			$this->sql = "SET NOCOUNT ON " . $sql . " select scope_identity() as scopeIdentity";
    			
  		} else {
  			$this->sql = $sql;
  		}
    }
  	
  	$this->con->query = $this->sql;
  }

  private function getArrayColumnsFromStringQuery(string $query){
    //Construir array de colunas do sql.
    $strCols = str_between($query,'SELECT','FROM');
    
    //$pattern = ['~(.as.[\w]*,)~','~(.as.[\w]*)~','/_LINHA_/','/dbo.InitCap/','/|/','/\n/','/\r/','/\t/','/\0/'];
    $pattern = ['~( as.[\w]*,)~','~( as.[\w]*)~','/_LINHA_/','/dbo.InitCap/','/|/','/\n/','/\r/','/\t/','/\0/'];
    $replacement = ['║','','','','','','','',''];
    $colsWitouthAlias = preg_replace($pattern, $replacement, $strCols);
    $arrCols = explode("║",trim($colsWitouthAlias));

    return $arrCols;
  }
    
  public function read(){    	
  	//Iniciar transação.
  	if($this->transactionCounter == 0){
  		$this->con->beginTransaction();
  		$this->transactionCounter++;
  	}
    	
  	//Executar consulta.
  	$stmt = $this->con->prepare($this->sql);
  	$stmt->execute();
  	$data = $this->fetchClass == false
    	? $stmt->fetchAll(PDO::FETCH_OBJ)
    	: $stmt->fetchAll(PDO::FETCH_CLASS, $this->fetchClassModel);
    	
  	//Executar contador para paginação.
    if($this->paginar == true && $this->tamPagina != 0){
    	$stmtCount = $this->con->prepare($this->sqlCount);
    	$stmtCount->execute();
    	$dataCount = $stmtCount->fetch();
    }
    	    	
  	$this->registros = $data ?? null;
  	$this->totalRegistros = $this->paginar == true && $this->tamPagina != 0 && $this->totalRegistros !== 0
  	? $dataCount['totalRegistros'] : count($data ?? null);
  	$this->orderBy = $this->orderBy;
  	$this->totalPaginas = $this->paginar == true && $this->tamPagina != 0 && $this->totalRegistros != 0
  		? ceil($this->tamPagina / $this->totalRegistros) : 0;
  }
    
  public function update(){    	
  	//Iniciar transação.
  	if($this->transactionCounter == 0){
  		$this->con->beginTransaction();
  		$this->transactionCounter++;
  	}
  	
  	//Executar edição.
  	$stmt = $this->con->prepare($this->sql);
  	$stmt->execute();
  	
  	//Retornar consulta
  	if ($this->returnInserted == true || $this->scopeIdentity == true) {
  		
  		if( $this->con->nextRow ){ $stmt->nextRowSet(); }
  		
  		$data = ($this->fetchClass == false
  				? $stmt->fetchAll(PDO::FETCH_OBJ) : $stmt->fetchAll(PDO::FETCH_CLASS, 'GbGuicheModel'));
  		
  		$this->registros = @$data;
  		$this->totalRegistros = count($data);
  		$this->orderBy = $this->orderBy;
  		$this->totalPaginas = ($this->paginar == true && $this->tamPagina != 0 && $this->totalRegistros != 0
  				? ceil($this->tamPagina / $this->totalRegistros) : 0);
  	}
  }
    
  /**
   * Imprime em tela para depuração o sql vigente interrompendo todos os processos. die();
   */
  public function printSqlAndDie(){
    print SqlFormatter::format($this->sql); die();
  }

  public function commit() {
    $this->con->commit();
  }

  public function rollBack() {
    $this->con->rollBack();
  }

  /**
   * Indica ao Dao para retornar o resultado no model.
   */
  public function setFetchClass() {
    $this->fetchClass = true;
  }

  /**
   * SQL INSERT
   * Modifica o sql para retornar o SCOPE_IDENTITY()
   * "SET NOCOUNT ON ".$this->sql." select SCOPE_IDENTITY() as scopeIdentity" 
   */
  public function setScopeIdentity() {
    $this->scopeIdentity = true;
  }

  /**
   * SQL INSERT
   * Modifica o sql para retornar o registro criado.
   * "SET NOCOUNT ON ".$this->sql." select * from TABELA where Id = SCOPE_IDENTITY()"
   */
  public function setReturnInserted() {
    $this->returnInserted = true;
  }
    
  /**
   * SQL UPDATE
   * Modifica o sql para retornar o registro editado.
   * "SET NOCOUNT ON ".$this->sql." select * from TABELA where Id = $id"
   */
  public function setReturnUpdated() {
    $this->returnUpdated = true;
  }
    
  /**
   * Adiciona ao where uma condição entre dois valores - between.
   * @param string $column - campo a ser encontrado.
   * @param mixed $initial - valor inicial
   * @param mixed $final - valor final
   */
  public function filtrarEntre($campo, $inicio, $final) {
    if ($inicio != null && $final != null) {
      $this->where .= " and " . $campo . " between '{$inicio}' and '{$final}'";
    }
  }
    
  /**
   * Montar where da consulta.
   * @param {string} $campo - coluna da tabela. Ex: Tabela.Coluna
   * @param {int or array} $valor - valor do campo a ser filtrado
   */
  public function filtrarPorCampo($campo = false, $valor = false, $like=true) {        
    $arrA = array("''","'","%");
    $arrB = array("","","");
    if ($valor !== null && $valor !== 'null' && $valor !== false && !empty($valor)) {
      switch ($valor) {
        case is_array($valor): 
          $sin = count($valor) > 1 ? "in" : "="; 
          $valorWhere = count($valor) > 1 ? "(".implode(",",$valor).")" : $valor[0]; 
        break;
        case is_numeric($valor):
          $sin = "="; 
          $valorWhere = $valor;                    
        break;
        default: 
          $sin = $like ? "like" : "="; 
          $valorWhere = $like ? "'%".str_replace($arrA, $arrB, $valor)."%'" : "'".str_replace($arrA, $arrB, $valor)."'"; 
        break;
      }
      $this->where .= " and {$campo} {$sin} {$valorWhere}";
    }
    
    //Filtro de ativo
    if(strpos(strtolower($campo),'.ativo') || strpos(strtolower($campo),'.atv') || strpos(strtolower($campo),'.inativo')){
    	$this->where .= " and {$campo} = {$valor}";
    }
  }

  /**
   * Montar where da consulta por diferença de um valor '<>'.
   * @param {string} $campo - coluna da tabela. Ex: Tabela.Coluna
   * @param {int or array} $valor - valor do campo a ser filtrado
   */
  public function filtrarPorCampoDiferente(string $campo = null, int $valor = null) {
    if ($campo != null && $campo != 'null' && $valor != null) {
      $this->where .= " and {$campo} <> {$valor}";
    }
  }
    
  /**
   * Montar where da consulta excetuando um ou mais valores NOT IN.
   * @param {string} $campo - coluna da tabela. Ex: Tabela.Coluna
   * @param {int or array} $valor - valor do campo a ser filtrado
   */
  public function filtrarPorCampoExceto(string $campo = null, array $valor) {
  	if ($campo != null && $campo != 'null' && $valor != null && is_array($valor)) {
  		$_valores = implode(",",$valor);
  		$this->where .= " and {$campo} not in ({$_valores})";
  	}
  }
    
  /**
   * Montar where da consulta para quando maior que um valor '>'.
   * @param {string} $campo - coluna da tabela. Ex: Tabela.Coluna
   * @param {int or array} $valor - valor do campo a ser filtrado
   */
  public function filtrarPorCampoMaiorQue(string $campo = null, int $valor = null) {
    if ($campo != null && $campo != 'null' && $valor != null) {
      $this->where .= " and {$campo} > {$valor}";
    }
  }
    
  /**
   * Montar where da consulta para quando maior ou igual que um valor '>='.
   * @param {string} $campo - coluna da tabela. Ex: Tabela.Coluna
   * @param {int or array} $valor - valor do campo a ser filtrado
   */
  public function filtrarPorCampoMaiorIgual(string $campo = null, int $valor = null) {
    if ($campo != null && $campo != 'null' && $valor != null) {
      $this->where .= " and {$campo} >= {$valor}";
    }
  }
    
  /**
   * Montar where da consulta para quando menor que um valor '<'.
   * @param {string} $campo - coluna da tabela. Ex: Tabela.Coluna
   * @param {int or array} $valor - valor do campo a ser filtrado
   */
  public function filtrarPorCampoMenor(string $campo = null, int $valor = null) {
    if ($campo != null && $campo != 'null' && $valor != null) {
      $this->where .= " and {$campo} < {$valor}";
    }
  }
    
  /**
   * Montar where da consulta para quando menor ou igual que um valor '>='.
   * @param {string} $campo - coluna da tabela. Ex: Tabela.Coluna
   * @param {int or array} $valor - valor do campo a ser filtrado
   */
  public function filtrarPorCampoMenorIgual(string $campo = null, int $valor = null) {
    if ($campo != null && $campo != 'null' && $valor != null) {
      $this->where .= " and {$campo} <= {$valor}";
    }
  }
    
  /**
   * Montar where da consulta para valor não nulo 'is not null'.
   * @param {string} $campo - coluna da tabela. Ex: Tabela.Coluna
   * @param {int or array} $valor - valor do campo a ser filtrado
   */
  public function filtrarPorCampoNaoNulo(string $campo = null) {
    if ($campo != null && $campo != 'null') {
      $this->where .= " and {$campo} is not null";
    }
  }
    
  /**
   * Montar where da consulta para valor nulo 'is null'.
   * @param {string} $campo - coluna da tabela. Ex: Tabela.Coluna
   * @param {int or array} $valor - valor do campo a ser filtrado
   */
  public function filtrarPorCampoNulo(string $campo = null) {
    if ($campo != null && $campo != 'null') {
      $this->where .= " and {$campo} is null";
    }
  }

  /**
   * Montar sql where para filtrar por data.	 
   * @param $campo - campo de data a ser filtrado. ex: "GbUsuario.CriadoEm"
   * @param $dataInicial - período inicial para a pesquisa.
   * @param $dataFinal - período final para a pesquisa.
   */
  public function filtrarPorData($campo, $dataInicial, $dataFinal) {  	
    if ($dataInicial != null && $dataInicial != 'null' && !empty($dataInicial)) {
    	$this->where .= " and " . $campo . " between '{$dataInicial}' and '{$dataFinal}'";
    }
  }

  /**
   * Montar sql where para filtrar por data.
   * As datas nulas serão consideradas como a data atual date('Y-m-d');
   * @param $campo - campo de data a ser filtrado. ex: "GbUsuario.CriadoEm"
   * @param $dataInicial - período inicial para a pesquisa.
   * @param $dataFinal - período final para a pesquisa.
   */
  public function filtrarPorDataHoje($campo, $dataInicial=null) {
    if ($campo != null && $campo != 'null') {
      $dtIni = ($dataInicial != null && $dataInicial != 'null' ? $dataInicial : date('Y-m-d'));
      $this->where .= " and " . $campo . " = '{$dtIni}'";
    }
  }

  /**
   * Montar sql where para filtrar pelo botão padrão de busca Gepros.
   * O sinal da busca de valor string é sempre like e int é sempre in
   * @param $em - campo da tabela a ser filtrado. ex: "GbUsuario.Nome"
   * @param $valor - valor do campo a ser filtrado.
   */
  public function filtrarPorBuscaGepros($em, $valor) {
    //busca por intgers.
    if (is_numeric(trimAll(@$valor))) {
      $this->where .= " and {$em} in ({$valor})";
    //busca por strings.
    } else if (@$valor != 'null' && @$valor != null) {
      $this->where .= " and {$em} like '%{$valor}%' COLLATE SQL_Latin1_General_CP1_CI_AI";
    }
  }

  /**
   * Montar sql where para filtrar pelo botão padrão de busca Gepros.
   * O sinal da busca de valor string é sempre like mas int é sempre =
   * @param $em - campo da tabela a ser filtrado. ex: "GbUsuario.Nome"
   * @param $valor - valor do campo a ser filtrado.
   */
  public function filtrarPorBuscaExataGepros($em, $valor) {
    //busca por intgers.
    if (is_numeric(substr(@$valor, 0, 1))) {
      $this->where .= " and {$em} = ({$valor})";
    //busca por strings.
    } else if (@$valor != 'null' && @$valor != null) {
      $this->where .= " and {$em} like '%{$valor}%'";
    }
  }

  /**
   * Montar sql where para filtra pelo bot]ao select de atividade
   * do registro, padrão do Gepros. Caso a opção seja inválida por padrão
   * será filtrado somente os ativos.
   * @param $ativo - 1 para ativo, 0 para desativado e -1 para todos (is not null)
   * @param $campo - campo ativo. Ex.: "GbUsuario.Ativo" 
   */
  public function filtrarPorAtivoGepros($campo, $ativo) {
    if ($campo != null && $campo != 'null') {
      $atv = ($ativo != 'null' && $ativo != null ? $ativo : 1);
      //todas as situações;
      if ($atv < 0) {
        $this->where .= " and {$campo} is not null";
      //ativo ou desativado.
      } else {
        $this->where .= " and {$campo} = {$atv}";
      }
    }
  }

  /**
   * Define o where integralmente na montagem do sql.
   * Obs.: este método não substitui outros métodos de filtro declarados no Dao.
   * @param $where - parte where do sql.
   */
  public function where($where) {
    $whr = ($where != null && $where != 'null' ? $where : "");
    $this->where .= $whr;
  }

  /**
   * Ordenar select pelo número da coluna.
   * @param $orderBy - Ex.: "1 asc" ou agrupado "1,2,3 asc" - Importante ter o espaço entre os números e a direção.
   */
  public function ordernarPor($orderBy) {

  	$this->sort = [];
  	$this->order = [];
  	$this->orderBy = [];
  	
  	$_orderBy = is_array($orderBy) ? $orderBy : [$orderBy];
  	
  	for ($i = 0; $i < count($_orderBy); $i++) {
  		if (is_numeric(substr($_orderBy[$i], 0, 1))) {
  			$this->sort[] = substr($_orderBy[$i],0,-3) == 'asc' ? 'asc' : 'desc';
  			$this->order[] = $this->sort == 'asc' ? substr($_orderBy[$i],0,-3) : substr($_orderBy[$i],0,-4);
  			$this->orderBy[] = $_orderBy[$i];
  		} else {
  			throw new Exception("Order by deve ser numérico.", 1);
  		}
  	}
  }
    
  /**
   * Ordenar select pelo número da coluna.
   * @param $orderBy - Ex.: "1 asc" ou agrupado "1,2,3 asc" - Importante ter o espaço entre os números e a direção.
   */
  public function agruparPor($orderBy) {
    if (is_numeric(substr($orderBy, 0, 1))) {
      $arrOrderBy = explode(" ", $orderBy);

      $this->sort = (substr($orderBy, -3) == "asc" ? "asc" : "desc");
      $this->order = $arrOrderBy[0];

      $this->orderBy = $orderBy;
    } else {
      throw new Exception("Order by deve ser numérico.", 1);
    }
  }

  public function paginar($pagina, $tamPagina) {
    $pagina = ($pagina != null ? $pagina : 1);
    $tamPagina = ($tamPagina != null ? $tamPagina : 12);
    $this->paginar = true;
    $this->pagina = $pagina;
    $this->tamPagina = $tamPagina;
  }

  public function iTerator() {
    $iterator = array();
    for ($i = 0; $i < count($this->registros); $i++) {
      $iterator[] = $this->registros[$i]->iTerator();
    }
    return $iterator;
  }
	
  /**
   * Retorna a array de objetos da propriedade registros com o JSON.
   * Caso queira apresentar o JSON sem a array inicial defina level como -1
   * @param int $level 0 or -1
   * @return string
   */
  public function toJson(int $level=0) {
  	return $level < 0
  		? json_encode($this->registros[0], JSON_NUMERIC_CHECK) 
  		: json_encode($this->registros, JSON_NUMERIC_CHECK);
  }
    
  /**
   * Gerar over para paginação mediante array com as colunas da consulta.
   * @param $colunas - Ex.: Array(GbUsuario.Id, GbUsuario.Nome, GbUsuario.Data)
   */
  protected function makeOver($colunas) {
    $overStr = "";
    $_colunas = array_map("trim", $colunas);
    
    for ($i = 0; $i < count($this->order); $i++) {
    	$overStr .= $_colunas[intval($this->order[$i]) - 1] . " " . $this->sort[$i] . ",";
    }
    $over = substr($overStr, 0, -1);

    return $over;
  }

  public function getSql() {
    $this->con->rollback();
    return $this->sql;
  }
    
  public function getSqlCount() {
  	$this->con->rollback();
  	return $this->sqlCount;
  }

  public function getOrderBy() {
    return $this->orderBy;
  }

  public function getRegistros() {
    return $this->registros;
  }

  public function getTotalRegistros() {
    return $this->totalRegistros;
  }

  public function getPagina() {
    return $this->pagina;
  }

  public function getTamPagina() {
    return $this->tamPagina;
  }
    
  /**
   * Retorna todas as propriedades em uma array
   * @param string $property
   * @return NULL[]
   */
  public function getAllProperties(string $property){    	
  	$arrProps = [];
  	for ($i = 0; $i < count($this->registros); $i++) {
  		$arrProps[] = $this->registros[$i]->$property;
  	}
  	
  	return $arrProps;
  }
    
  /**
   * Retornar mensagem para o controller tanto de erro como parametros disposto
   * na url para redirecionamento.
   * Ex: pedido/1834565#pedido-ja-recebido
   */
  public function getReturnMessage(){      
    return $this->returnMessage;
  }

}