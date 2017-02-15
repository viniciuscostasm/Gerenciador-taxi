<?php

/**
 * Classe de Exceção do Bando de Dados
 */
class GDbException extends Exception {

    private $query = '';
    private $param = array();
    private $arrMsg = array("1062" => "Registro duplicado", "1451" => "Este item possui dependência", "1452" => "Dependência não encontrada");

    public function __construct($message, $code, $query = '', $param = array()) {
        parent::__construct($message, $code);
        $this->query = $query;
        $this->param = $param;
    }

    public function getError() {
        switch (SERVER) {
            case 'D':
                $erro = '<div class="__erro"> Tipo: <b>DbException</b> <br>' .
                        'Código: <b>' . $this->code . '</b><br>' .
                        'Mensagem: <b><span style="color:red">' . $this->message . '</span></b><br/>' .
                        'Erro Produção: <b><span style="color:blue">' . $this->getMsg($this->code) . '</span></b><br/><br/>' .
                        'Query: <b>' . $this->query . '</b><br/><br/>';
                $erro .= 'Caminho:<br>';
                $arrayErro = $this->getTrace();
                foreach ($arrayErro as $key => $value) {
                    $erro .= '#' . $key . ' ' . $value['file'] . '(' . $value['line'] . ') | ' . $value['class'] . $value['type'] . $value['function'] . '<br>';
                }
                $erro .= '</div>';
                return $erro;
                break;
            case 'H':
                return $this->code . ' - ' . $this->getMsg($this->code);
                break;
            case 'P':
            default:
                return $this->getMsg($this->code);
                break;
        }
    }

    private function getMsg($code) {
        if ($this->arrMsg[$code] == '')
            $erro = "An unexpected error, sorry for the inconvenience.";
        else
            $erro = $this->arrMsg[$code];
        return $erro;
    }

}

?>
