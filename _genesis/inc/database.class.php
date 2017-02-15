<?php

class GDbMysql {

    public $link;
    public $stmt;
    public $res = array();

    /**
     * Criar um conexão com o banco mysql e setar a Time Zone
     *
     */
    function __construct() {
        // carrega a conexao com mysql
        try {
            $this->connect();
            $mysql_timezone = MYSQL_TIMEZONE;
            // COMENTADO ATÉ RESOLVER A QUESTÃO DO BANCO ACEITAR time_zone NO FORMATO TEXTO
            // if(GSec::validarLogin()){
            //     $mysql_timezone = GSec::getUserSession()->getZone()->getZon_var_name();
            // }

            $this->execute("SET time_zone = '" . $mysql_timezone . "';", null, false);
        } catch (GDbException $e) {
            echo $e->getError();
        }
    }

    /**
     * Abrir a conexão com o mysql e setar o charset da conexão
     *
     */
    private function connect() {
        $this->link = mysqli_connect(MYSQL_HOST, MYSQL_USER, MYSQL_PASS, MYSQL_BASE, MYSQL_PORT);
        if (!$this->link) {
            throw new GDbException(mysqli_error($this->link), mysqli_errno($this->link));
        }

        mysqli_set_charset($this->link, MYSQL_CHARSET);
    }

    /**
     * Criar um array de referencia para o array de parametros
     * Função criada para solucionar o problema da versão 5.3 do PHP
     *
     * @param Array $arr
     * @return Array
     */
    public function refValues($arr) {
        if (strnatcmp(phpversion(), '5.3') >= 0) { //Reference is required for PHP 5.3+
            $refs = array();
            foreach ($arr as $key => $value)
                $refs[$key] = &$arr[$key];
            return $refs;
        }
        return $arr;
    }

    /**
     * Executar uma query
     *
     * @param String $query Sql para executar Ex: SELECT * FROM tabela
     * @param Array $param[optional] Parametros mysqli_stmt_bind_param Ex: array('i', 10)
     * @param boolean $consulta[optional = TRUE] Se for uma consulta
     * @param int $resultType MYSQLI_ASSOC, MYSQLI_NUM, or MYSQLI_BOTH.
     */
    public function execute($query, $param = NULL, $consulta = TRUE, $resultType = MYSQLI_BOTH) {
        $this->stmt = mysqli_prepare($this->link, $query);
        if ($param != NULL) {
            $indice = $param[0];
            array_shift($param);
            $arr = array_merge(array($this->stmt, $indice), $param);
            $ret = call_user_func_array('mysqli_stmt_bind_param', $this->refValues($arr));
        }
        if (mysqli_stmt_execute($this->stmt)) {
            if ($consulta) {
                $nof = mysqli_num_fields(mysqli_stmt_result_metadata($this->stmt));
                $fieldMeta = mysqli_fetch_fields(mysqli_stmt_result_metadata($this->stmt));
                $fields = array();
                for ($i = 0; $i < $nof; $i++) {
                    $fields[$i] = $fieldMeta[$i]->name;
                }
                $arg = array($this->stmt);
                for ($i = 0; $i < $nof; $i++) {
                    $campo = $fields[$i];
                    $arg[$i + 1] = &$this->res[$campo];
                    if ($resultType == MYSQLI_BOTH || $resultType == MYSQLI_NUM) {
                        $this->res[$i] = &$this->res[$campo];
                    }
                }
                call_user_func_array('mysqli_stmt_bind_result', $arg);
                mysqli_stmt_store_result($this->stmt);
            }
        } else {
            throw new GDbException(mysqli_stmt_error($this->stmt), mysqli_stmt_errno($this->stmt), $query, $param);
        }
    }

    /**
     * Executar uma consulta com a query passada e retornar um array com os valores
     *
     * @param String $query Consulta sql Ex: SELECT id,nome FROM tabela
     * @param array $param[optional] Parametros mysqli_stmt_bind_param Ex: array('i', 10)
     * @return array ex: array('1' => 'João','2' => 'Maria')
     */
    public function executeCombo($query, $param = false) {
        $array = array();
        try {
            if ($param)
                $this->execute($query, $param);
            else
                $this->execute($query);
            while ($this->fetch()) {
                $array[$this->res[0]] = $this->res[1];
            }
            $this->freeResult();
            $this->close();
        } catch (GDbException $e) {
            echo $e->getError();
        }
        return $array;
    }

    public function fetch() {
        return mysqli_stmt_fetch($this->stmt);
    }

    public function fieldCount() {
        return mysqli_stmt_field_count($this->stmt);
    }

    public function numRows() {
        return mysqli_stmt_num_rows($this->stmt);
    }

    public function affectedRows() {
        return mysqli_stmt_affected_rows($this->stmt);
    }

    public function insertId() {
        return mysqli_stmt_insert_id($this->stmt);
    }

    public function close() {
        return mysqli_stmt_close($this->stmt);
    }

    public function autoCommit($mode = false) {
        mysqli_autocommit($this->link, $mode);
    }

    public function commit() {
        return mysqli_commit($this->link);
    }

    public function rollback() {
        return mysqli_rollback($this->link);
    }
    
    public function freeResult() {
        $this->res = NULL;
        return mysqli_stmt_free_result($this->stmt);
    }
    
    public function reset() {
        return mysqli_stmt_reset($this->stmt);
    }

}