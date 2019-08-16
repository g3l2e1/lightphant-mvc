<?php

class SqlBuilder {

    protected $obj;
    protected $arrJoinModels;
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
    protected $join = false;
    protected $union;
    protected $paginar = false;
    protected $pagina;
    protected $tamPagina;
    protected $totalRegistros;
    protected $orderBy = false;
    protected $where = false;
    protected $having;
    protected $groupBy;
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
            $this->valores(implode($vals, ", "));
            $this->colunasParaListar = implode($colsList, ", ");
        }
    }

    public function tabela($tbl = null) {
        $this->tabela = $tbl;
    }

    public function colunas($col = null) {
        $this->colunas = $col;
    }

    public function valores($val = null) {
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
       
        if ($coluna !== null && $ordem !== null && $this->orderBy == false) {
            $this->orderBy .= "order by " . $coluna . " " . $ordem;
        }else{
            $this->orderBy .= ", ".$coluna . " " . $ordem; 
        }
        
        $_colunas = $this->colunasParaListar ?? $this->colunas;
        if($this->tabela != null && $_colunas != null){
            $overStr = "";        
            $arrOrder = explode(",", $coluna);
            $colunas = explode(",",$_colunas);
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

    public function groupBy($coluna = null) {
        if ($coluna != null) {
            $this->groupBy = "group by " . $coluna;
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

    /**
     * Ativar cláusula inner ou left join no sql.
     * @param $metodo - método inner ou left
     * @param $tabela - tabéla a se unir
     * @param $on - coluna a ser ligada pelo join.
     */
    public function join($metodo = "inner", $tabela = false, $on = false) {
        $inner = "{$metodo} join {$tabela} on {$tabela}.{$on} = {$this->tabela}.{$this->fk}";
        $this->join[] = $inner;
    }
    
    /**
     * Retorna o nome da coluna no banco de dados com ligação fk indicado 
     * no model da tabela. 
     * @param array $innerModel - array de models indicados pela função innerJoin()
     * @return array 
     */
    private function getParamsForJoin($pJoinModel, $sJoinModel, $inverter=false){
        
        //Variáveis utilizadas.
        $pConfig = null;
        $sConfig = null;
        $pAttr = null;
        //$sAttr = null;
        
        //Objeto de Retorno.
        $obj = (object) array();
        $obj->pTabela = false;
        $obj->pColuna = false;
        $obj->sTabela = false;
        $obj->sColuna = false;
        
        
        //Instancia o Model primario.
        $pModel = new $pJoinModel();
        
        //Pegar Tabela primária.
        $obj->pTabela = $pModel->getTabela();

        //Pega as configurações do Model primario.
        $pConfig = $pModel->getConfig(); 

        //Varrer os atributos do config do Model primario.
        foreach ($pConfig as $value) {
            //Se houver ForeingKey.
            if(isset($value->fk)){
                /* Valor da ForeignKey é indicado no config: $fk => 'ExemploModel.atributo'
                 * a variável fk é preenchida com explode tornando $fk[0] = ExemploModel 
                 * e fk[1] atributo */
                $pfk = explode(".",$value->fk);

                //Se o Model coincidir com a tabela atual (model atual) existe chave estrangeira.
                //Pegar as informações do model encontrado equivalente a tabela estrangeira;
                if($pfk[0] == $sJoinModel){
                    
                    $pAttr = $pfk[1];
                    
                    $obj->pColuna = $value->coluna;
                    
                    //Instancia o Model secundário.
                    $sModel = new $pfk[0]();
                    
                    //Definir tabela secundário.
                    $obj->sTabela = $sModel->getTabela();

                    //Pega as configurações do Model primario.
                    $sConfig = $sModel->getConfig();
                    
                    //Varrer os atributos do config do Model secundario.
                    foreach ($sConfig as $_key => $_value) {
                        //Se houver ForeingKey.
                        if($_key == $pAttr){
                            $obj->sColuna = $_value->coluna;
                        }
                    }
                }
            }
        }   
        
        //inverter
        if($inverter){
            $iObj = (object) array();
            $iObj->pTabela = $obj->sTabela;
            $iObj->pColuna = $obj->sColuna;
            $iObj->sTabela = $obj->pTabela;
            $iObj->sColuna = $obj->pColuna;
            
            $obj = $iObj;
        }
        
        return $obj;
    }
    
    public function innerJoin($joinModel){
        //print "{$joinModel}: verificar se a ligação está no próprio model join. <br/>";
        //verificar se a ligação está no próprio model join.
        $p = $this->getParamsForJoin($joinModel, $this->tabela."Model", true);
        
        
        //verificar se a ligação está no historico de inners feitos e se existir histórico.
        if(($p->pTabela==false || $p->pColuna==false || $p->sTabela==false || $p->sColuna==false) && count($this->arrJoinModels) > 0){
            //print "{$joinModel}: verificar se a ligação está no historico de inners feitos e se existir histórico. <br/>";
            for ($i = 0; $i < count($this->arrJoinModels); $i++) {
                $a = $this->getParamsForJoin($this->arrJoinModels[$i], $joinModel);
                
                if(!$a->pTabela==false && !$a->pColuna==false && !$a->sTabela==false && !$a->sColuna==false){
                    $p = $a;
                }
            }            
        }
        
        
        //verificar se a ligação está no próprio model com o histórico de inners.
        if(($p->pTabela==false || $p->pColuna==false || $p->sTabela==false || $p->sColuna==false) && count($this->arrJoinModels) > 0){
            //print "{$joinModel}: verificar se a ligação está no próprio model com o histórico de inners. <br/>";
            
            for ($y = 0; $y < count($this->arrJoinModels); $y++) {
                $b = $this->getParamsForJoin($joinModel, $this->arrJoinModels[$y], true);
                
                if(!$b->pTabela==false && !$b->pColuna==false && !$b->sTabela==false && !$b->sColuna==false){
                    $p = $b;
                }
            }            
        }
        
        //verificar se a ligação está no model principal.
        if($p->pTabela==false || $p->pColuna==false || $p->sTabela==false || $p->sColuna==false){
            //print "{$joinModel}: verificar se a ligação está no model principal. <br/>";
            $p = $this->getParamsForJoin($this->tabela."Model", $joinModel);
        }
        
        //Armazenar o último join.
        $this->arrJoinModels[] = $joinModel;
        
        
        //Montar inner join se houver correlação.
        if($p->pTabela==false || $p->pColuna==false || $p->sTabela==false || $p->sColuna==false){
            //print "Não existe correlação inner join entre os models utilizados e o model: {$joinModel} <br/>";
            throw new Exception("Não existe correlação inner join entre os models utilizados e o model: {$joinModel}");
        }else{
            //print "{$joinModel} encontrado com sucesso!!! <br/><br/><br/>";
            $inner = "inner join {$p->sTabela} on {$p->sTabela}.{$p->sColuna} = {$p->pTabela}.{$p->pColuna}";
            $this->join[] = $inner;
           
        }
            
    }
    
    /*public function leftJoin($campo){
        
        $modelFk = new $innerModel();
        $configFk = $modelFk->getConfig(); 
        $colunaFk = null;
        foreach ($configFk as $key => $value) {
            if(isset($value->fk)){
                $fk = explode(".",$value->fk);
                if($fk[0] == $this->tabela."Model"){
                    $colunaFk = $value->coluna;
                }                
            }
        }
        $tabelaFk = $modelFk->getTabela();
        
        $coluna = null;
        $config = $this->obj->getConfig();
        foreach ($config as $_key => $_value) { 
            if($_key == $fk[1]){
                $coluna = $_value->coluna;  
            }
        }
        
        $inner = "left join {$tabelaFk} on {$tabelaFk}.{$colunaFk} = {$this->tabela}.{$coluna}";
        $this->join[] = $inner;
    }*/

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
        $inner = ($this->join == false ? "" : implode(" ", $this->join));
        $pag = ($this->tamPagina * $this->pagina) - $this->tamPagina;

        $this->cnt = "
            select count(*) as totalRegistros from (
                select {$colunas}
                from {$this->tabela}
                {$inner}
                {$where}
                {$this->groupBy}
            ) as contagem
        ";

        if ($this->paginar == false) {
            $this->sql = "
                select {$colunas}
                from {$this->tabela}
                {$inner}
                {$where}
                {$this->groupBy}
                {$this->orderBy} 
            ";
        } else {
            $this->sql = "
                select top ({$this->tamPagina}) * from (
                    select 
                        {$colunas},
                        linha = row_number() over(order by {$this->over})
                    from {$this->tabela}
                    {$inner}
                    {$where}
                    {$this->groupBy}
                )as paginacao
                where linha > {$pag}
                {$this->orderBy} "; 
        }
    }

    public function criar() {

        $this->sql = "insert into {$this->tabela} ({$this->colunas})
            values ({$this->valores})
            {$this->scope}";
        
        if ($this->returnInserted) {
            $this->sql .= $this->sqlReturn;
        }
    }

    public function editar() {

        $colArr = explode(", ", trim($this->colunas));
        $valArr = explode(", ", trim($this->valores));

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
        $where = "1=1 and " . implode("and ", $whrArr);

        $this->sql = "
			delete from {$this->tabela} 
			where {$where}
		";
    }

    public function getSql() {
        return $this->sql;
    }

    public function getSqlCount() {
        return $this->cnt;
    }

}

?>