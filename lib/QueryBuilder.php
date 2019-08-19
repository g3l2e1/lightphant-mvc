<?php

class QueryBuilder {

    protected $obj;
    protected $sql;
    protected $sqlReturn = false;
    protected $returnInserted;
    protected $returnUpdated;
    protected $cnt;
    protected $tabela;
    protected $pk;
    protected $fk;
    protected $colunas;
    protected $colunasParaListar;
    protected $valores;
    protected $from;
    protected $over = false;
    protected $join = "";
    protected $union;
    protected $paginar = false;
    protected $pagina;
    protected $tamPagina;
    protected $totalRegistros;
    protected $orderBy = false;
    protected $where = false;
    protected $groupBy;
    protected $having;
    protected $scope = "";

    public function __construct($obj = null) {

        if ($obj != null) {
            $this->obj = $obj;

            $this->tabela = $obj->getTabela();

            $config = $obj->getConfig();

            $iterator = $obj->iTeratorForSql();

            $vals = array();
            $cols = array();
            $colsList = array();
            foreach ($config as $key => $value) {
                //Para editar, deletar e excluir.
                //if ($iterator->$key != 'null' && @$value->pk !== true) {
                if ($iterator->$key !== 'null' && $iterator->$key !== null && @$value->pk !== true) {
                    //Valores do objeto
                    $vals[] = $iterator->$key;
                    //Colunas do objeto
                    $cols[] = $value->coluna;
                }
                
                if($value->pk ?? false){ $this->pk[] = $value->coluna; }
                
                //Colunas Para listar
                $tbl = $value->tabela ?? $this->tabela;
                //$colsList[] = $tbl.".".$value->coluna;
                $colsList[] = $tbl.".".$value->coluna." as ".$key;
            }
            $this->colunas(implode($cols, ", "));
            $this->valores(implode($vals, "▀ "));
            $this->colunasParaListar = implode($colsList, ", ");
        }
    }
    
    public function tabela($tbl = null) {
        $this->tabela = $tbl;
    }

    /**
     * As colunas devem estar sepradas por vírgulas.
     * @param string $col
     */
    public function colunas(string $col = null) {
        $this->colunas = $col;
    }

    /**
     * Os valores devem estar separados pelo caractere especial [▀] (Alt+223)
     * Isso se faz necessário para preservação das vírgulas contidas em textos.
     * @param string $val
     */
    public function valores(string $val = null) {
        $this->valores = $val;
    }

    public function paginar($pagina = null, $tamPagina = null) {
        if ($pagina !== null && $tamPagina !== null) {
            $this->paginar = true;
            $this->pagina = $pagina;
            $this->tamPagina = $tamPagina;
        }
    }

    public function over($over = null) {
        if ($over != null) {
            $this->over = $over;
        }       
    }

    public function orderBy($coluna = null, $ordem = null) {
    	if($coluna !== null && $ordem !== null){
	        if ($this->orderBy == false) {
	            $this->orderBy .= "order by " . $coluna . " " . $ordem;
	        }else{
	            $this->orderBy .= ", ".$coluna . " " . $ordem; 
	        }
	        
	        $_colunas = $this->colunasParaListar ?? $this->colunas;
	        $pattern = ['~(.as.[\w]*,)~','~(.as.[\w]*)~','/_LINHA_/','/dbo.InitCap/','/|/','/\n/','/\r/','/\t/','/\0/'];
	        $replacement = [',','','','','','','','',''];
	        $colsWitouthAlias = preg_replace($pattern, $replacement, $_colunas);
	        
	        if($this->tabela != null && $colsWitouthAlias != null){
	            $overStr = "";        
	            $arrOrder = explode(",", $coluna);
	            $colunas = explode(",",$colsWitouthAlias);
	            for ($i = 0; $i < count($arrOrder); $i++) {
	                $overStr .= $colunas[$arrOrder[$i] - 1] . ",";
	            }
	            
	            if($coluna !== null && $ordem !== null && $this->over == false){
	                $this->over .= substr($overStr, 0, -1) . " " . $ordem;
	            }else{
	                $this->over .= ", ".substr($overStr, 0, -1) . " " . $ordem;
	            }
	        }
    	}
    }

    public function groupBy($coluna = null) {
        if ($coluna != null) {
            $this->groupBy = "group by " . $coluna;
        }
    }
    
    public function having($having = null) {
    	if ($having != null) {
    		$this->having = "having " . $having;
    	}
    }

    /**
     * Definir where do Sql.
     * @param string $whr
     */
    public function where($whr = false) {
        $this->where .= $whr;
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
     * Adiciona ao where uma condição.
     * @param string $column - campo a ser filtrado.
     * @param mixed $value - valor a ser encontrado.
     */
    public function filtrarPor($campo, $valor) {
        if ($valor != null) {
            $signal = is_array($valor) ? "in" : "=";
            $whrVal = is_array($valor) ? "(" . implode(",", $valor) . ")" : $valor;

            $this->where .= " and {$campo} {$signal} {$whrVal}";
        }
    }
    
    /**
     * Adiciona ao where uma condição de filtro por id.
     * @param int $id - campo a ser filtrado.
     */
    public function filtrarPorId($id) {
        if ($id != null) {
            
            for ($i = 0; $i < count($this->pk); $i++) {
                
                $campo = "{$this->tabela}.{$this->pk[$i]}";
                
                $this->where .= " and {$campo} = {$id}";
            }
            
        }
    }
    
    public function join($join){
    	if(!empty($join)){ $this->join .= $join; }
    }

    public function contar() {
        $this->listar();
    }
    
    public function retornarScopeIdentitity(string $alias = 'scopeIdentity') {
        $this->scope = "select SCOPE_IDENTITY() as {$alias}";
    }

    /**
     * Passar sql de retorno.
     */
    public function setSqlReturn($sql) {
        $this->sqlReturn = $sql; 
    }
    
    public function retornarInserido($retorno=true) {
        $this->returnInserted = $retorno;
    }

    public function retornarEditado($retorno=true) {
        $this->returnUpdated = $retorno;
    }

    public function listar() {
    	
        $where = strlen($this->where)>0 ? "where 1=1".$this->where : "";
        $colunas = $this->colunasParaListar ?? $this->colunas;
        $join = strlen($this->join)>0 ? $this->join : "";
        $pag = ($this->tamPagina * $this->pagina) - $this->tamPagina;
        $orderBy = $this->orderBy ?? '';
        
        $this->cnt = "
            select count(*) as totalRegistros from (
                select {$colunas}
                from {$this->tabela}
                {$join}
                {$where}
                {$this->groupBy}
            ) as contagem
        ";

        if ($this->paginar == false) {
            $this->sql = "
                select {$colunas}
                from {$this->tabela}
                {$join}
                {$where}
                {$this->groupBy}
				{$this->having}
                {$orderBy} 
            ";
        } else {
            $this->sql = "
                select top ({$this->tamPagina}) * from (
                    select 
                        {$colunas},
                        linha = row_number() over(order by {$this->over})
                    from {$this->tabela}
                    {$join}
                    {$where}
                    {$this->groupBy}
					{$this->having}
                )as paginacao
                where linha > {$pag}
                {$orderBy} "; 
        }
    }

    public function criar() {
		
    	$valoresWithoutAltCode = str_replace("▀ ",", ",$this->valores);
        $this->sql = "insert into {$this->tabela} ({$this->colunas})
            values ({$valoresWithoutAltCode})
            {$this->scope}";
        
        if ($this->returnInserted) {
            $this->sql .= $this->sqlReturn;
        }
    }
    
    public function criarForcandoInsert(Array $pkCol, Array $pkVals) {
    	
    	
    	$strPkCol = implode(",",$pkCol).",";
    	$strPkVals = implode(",",$pkVals).",";
    	
    	
    	$valoresWithoutAltCode = str_replace("▀ ",", ",$this->valores);
    	$this->sql = "insert into {$this->tabela} ({$strPkCol} {$this->colunas})
            values ({$strPkVals} {$valoresWithoutAltCode})
            {$this->scope}";
            
            if ($this->returnInserted) {
            	$this->sql .= $this->sqlReturn;
            }
    }

    public function editar() {

        $colArr = explode(", ", trim($this->colunas));
        $valArr = explode("▀ ", trim($this->valores));
        
        $setStr = "";
        for ($i = 0; $i < count($valArr); $i++) {
        	$setStr .= "{$colArr[$i]} = {$valArr[$i]}, ";
        }
        $set = substr($setStr, 0, -2);
        
        $whrArr = explode("and ", trim($this->where));
        $where = "1=1 and " . implode("and ", $whrArr);
        
        $this->sql = "update {$this->tabela} set {$set}
             where {$where}";
             
        if ($this->returnUpdated) {
            $this->sql .= $this->sqlReturn ?? "select * from {$this->tabela} where {$where}";
        }
    }

    public function excluir() {
        $whrArr = explode("and ", trim($this->where));
        $where = "1=1 " . implode("and ", $whrArr);

        $this->sql = "
			delete from {$this->tabela} 
			where {$where}
		";
    }
    
    public function getWhere(){
    	return $this->where;
    }

    public function getSql() {
        return $this->sql;
    }

    public function getSqlCount() {
        return $this->cnt;
    }

}

?>