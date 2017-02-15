<?php

/**
 * Classe para os filtros de uma consulta
 */
class GFilter {

    var $where = array();
    var $typesParam = '';
    var $valuesParam = array();
    var $order;
    var $group;
    var $limit;

    /**
     *
     * @param string $logic ['AND','OR']
     * @param string $field campo na tabela
     * @param string $op ['<','=','BETWEEN'...]
     * @param string $typeParam ['i','s', ou 'd']
     * @param string $valueParam
     * @param char $opType ['O','F'] Operação ou Função
     */
    public function addFilter($logic, $field, $op, $typeParam, $valueParam, $opType = 'O') {
        if ($opType == 'F') {
            $val = '';

            if ($op == 'BETWEEN' || $op == 'NOT BETWEEN') {
                $val = '? AND ?';
                $this->valuesParam[] = $valueParam[0];
                $this->valuesParam[] = $valueParam[1];
                $this->where[] = $logic . ' ' . $field . ' ' . $op . ' ' . $val . ' ';
                $this->typesParam .= $typeParam;
            } else {
                $val = $valueParam;
                $this->where[] = $logic . ' ' . $field . ' ' . $op . '(' . $val . ') ';
            }
        } else if ($opType == 'O') {
            $this->where[] = $logic . ' ' . $field . ' ' . $op . ' ? ';
            $this->typesParam .= $typeParam;
            $this->valuesParam[] = $valueParam;
        }
    }

    public function addClause($clause, $typeParam = null, $valueParam = null) {
        $this->where[] = $clause . ' ';
        if (!is_null($typeParam) && !is_null($valueParam)) {
            $this->typesParam .= $typeParam;
            if (!is_array($valueParam)) {
                $this->valuesParam[] = $valueParam;
            } else {
                $this->valuesParam = $this->valuesParam + $valueParam;
            }
        }
    }

    public function setOrder($arrayFields) {
        if (count($arrayFields) > 0) {
            $this->order = ' ORDER BY ';
            foreach ($arrayFields as $field => $direction) {
                $this->order .= $field . ' ' . $direction . ',';
            }
            $this->order = substr($this->order, 0, -1);
        }
    }

    public function setGroupBy($field) {
        $this->group = ' GROUP BY ' . $field . ' ';
    }

    public function setLimit($start, $end) {
        $this->limit = ' LIMIT ' . $start . ',' . $end . ' ';
    }

    public function getWhere() {
        $strWhere = '';
        $strWhere = ' WHERE 1 ';

        foreach ($this->where as $condition) {
            $strWhere .= $condition;
        }
        $strWhere .= $this->order;
        $strWhere .= $this->group;
        $strWhere .= $this->limit;

        return $strWhere;
    }

    public function getParam() {
        $array = array_merge(array($this->typesParam), $this->valuesParam);

        return $array[0] == '' ? false : $array;
    }

}