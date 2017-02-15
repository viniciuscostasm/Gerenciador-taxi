<?php

/**
 * Classe com as funções comuns do Genesis
 */
class GF {

    /**
     * Converter Chartset de UTF-8 para ISO ou de ISO para UTF-8
     *
     * @param string $text
     * @param bool $utf8
     * @return string
     */
    public static function convertCharset($text, $utf8 = TRUE) {
        $return = null;
        if (!empty($text)) {
            if ($utf8) {
                // $return = iconv("ISO-8859-1", "UTF-8", $text);
                $return = utf8_encode($text);
            } else {
                // $return = iconv("UTF-8", "ISO-8859-1", $text);
                $return = utf8_decode($text);
            }
        }
        return $return;
    }

    /**
     * Converter um array para uma string
     *
     * @param array $array
     * @return type
     * @example convertArrayToString(array('teste1','teste2')) vai retornar 'teste1','teste2'
     */
    public static function convertArrayToString($array) {
        return "'" . implode("','", $array) . "'";
    }

    /**
     * Retirar as formatações HTML, \n e \t, parágrafo em branco para a exibição no grid
     *
     * @param string $text
     * @return string
     */
    public static function formatTextToGrid($text) {
        $return = $text;
        $return = str_replace("\n", "", $return);
        $return = str_replace("\t", "", $return);
        $return = str_replace("&ldquo;", '"', $return);
        $return = str_replace("&rdquo;", '"', $return);
        $return = str_replace("<p>&nbsp;</p>", '', $return);
        $return = str_replace("&nbsp;", " ", $return);
        $return = strip_tags($return);
        $return = trim($return);
        $return = addslashes($return);

        return $return;
    }

    /**
     * Importar as classes DAO que estão na pasta definida na constante ROOT_SYS_CLASS no arquivo global.php
     * Deve ser passado apenas o nome da Classe, sem o Dao.php
     *
     * @param array classes
     */
    public static function importClass($arrClass) {
        foreach ($arrClass as $class) {
            require_once(ROOT_SYS_CLASS . $class . 'Dao.php');
        }
    }

    /**
     * Criar Permalink (URL amigável) retirando caracteres especiais, acentos, espaços e colocando - no lugar de espaço em branco
     *
     * @param String $text
     * @return String
     */
    public static function createPermalink($text) {
        $clean = GF::removeAccent($text);
        $clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $clean);
        $clean = strtolower(trim($clean, '-'));
        $clean = preg_replace("/[\/_|+ -]+/", '-', $clean);
        return $clean;
    }

    /**
     * Função para retirar acentos de uma string
     *
     * @param String $text
     * @return String
     */
    public static function removeAccent($text) {
        $a = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿŔŕ';
        $b = 'AAAAAAACEEEEIIIIDNOOOOOOUUUUYbsaaaaaaaceeeeiiiidnoooooouuuyybyRr';
        $text = utf8_decode($text);
        $text = strtr($text, utf8_decode($a), $b);
        return utf8_encode($text);
    }

    /**
     * Função para retirar acentos e os espaços em branco de uma string
     *
     * @param String $text
     * @return String
     */
    public static function createFilename($text) {
        return preg_replace("/[\/_|+ -]+/", '-', strtolower(trim(GF::removeAccent($text), '-')));
    }

    /**
     * Converter a data para o formato brasileiro(dd/mm/AAAA) ou americano()
     * Se o @param $brasil for false, ele converte de americano para brasileiro
     *
     * @param String $data
     * @param bool $brasil default: true
     */
    public static function convertDate($string, $brasil = true) {
        $return = $string;
        if (!empty($string)) {
            if ($brasil) {
                $dataHora = split(" ", $string);
                $data = split("/", $dataHora[0]);
                if (count($data) > 1) {
                    $return = $data[2] . "-" . $data[0] . "-" . $data[1] . ' ' . $dataHora[1];
                }
            } else {
                $dataHora = split(" ", $string);
                $data = split("-", $dataHora[0]);
                if (count($data) > 1) {
                    $return = $data[1] . "/" . $data[2] . "/" . $data[0] . ' ' . $dataHora[1];
                }
            }
            return trim($return);
        } else {
            return null;
        }
    }

    /**
     * Encurtar uma URL usando o Google
     *
     * @param string $url
     * @return string
     */
    public static function googleUrlShortener($longUrl) {

        // initialize the cURL connection
        $ch = curl_init(sprintf('https://www.googleapis.com/urlshortener/v1/url?key=%s', GOOGLE_API_KEY));

        // tell cURL to return the data rather than outputting it
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // create the data to be encoded into JSON
        $requestData = array(
            'longUrl' => $longUrl
        );

        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($requestData));

        $result = curl_exec($ch);
        curl_close($ch);

        $result = json_decode($result, true);

        return $result['id'];
    }

    /**
     * Carregar uma folha de estilo de CSS
     *
     * @param String $css
     */
    public static function loadCss($css) {
        echo '<link href="' . $css . '" rel="stylesheet" type="text/css" />';
    }

    /**
     * Carregar um script JavaScript
     *
     * @param String $js
     */
    public static function loadJs($js) {
        echo '<script src="' . $js . '" type="text/javascript" charset="utf-8"></script>';
    }

    /**
     * Executar a função stripslashes em todos os elementos do array de maneira recursiva
     *
     * @param array $array
     * @return array
     */
    public static function unstrip_array($array) {
        foreach ($array as &$val) {
            if (is_array($val)) {
                $val = GF::unstrip_array($val);
            } else {
                $val = stripslashes($val);
            }
        }
        return $array;
    }

    /**
     * Cortar uma string em uma quantidade de caracteres informada sem cortar palavras.
     *
     * @param String $str
     * @param int $len
     * @param String $etc
     * @return String
     */
    public static function truncate($str, $len, $etc = '') {
        $end = array(' ', '.', ',', ';', ':', '!', '?');
        if (strlen($str) <= $len)
            return $str;
        if (!in_array($str{$len - 1}, $end) && !in_array($str{$len}, $end))
            while (--$len && !in_array($str{$len - 1}, $end)
            );
        return rtrim(substr($str, 0, $len)) . $etc;
    }

    /**
     * Retirar uma mascara utilizada pelo plugin jQuery.mask
     * Remove ".", "-" e "/"
     *
     * @param string $text
     * @return string
     */
    public static function removeMask($text) {
        return str_replace(array(".", "-", "/"), "", $text);
    }

    /**
     * Trocar ponto por virgula ou virgula por ponto de acordo com o $type
     * Se o $type for 'C', troca a vrigula por ponto
     *
     * @param type $value
     * @param char $type ['C']
     * @return type
     */
    public static function changeDotToComma($value, $type = '') {
        return ($type == 'C') ? str_replace(',', '.', $value) : str_replace('.', ',', $value);
    }

    /**
     * Formatar um número com 2 casas decimais após a virgula e ponto para diferenciar os milhares
     *
     * @param dec $numero
     * @param bool $color
     * @param bool $cash defaul: true inclui ou não o caractere de moeda
     * @param bool $signal default: true inclui ou não sinal para numeros negativos
     * @return type
     */
    public static function numberFormat($number, $color = false, $cash = true, $signal = true, $decimal = 2) {
        $moeda = ($cash) ? 'R$ ' : '';
        $number = (!$signal) ? abs($number) : $number;

        if ($color) {
            $cor = ($number >= 0) ? '' : '#E95D3C';
            $number = number_format($number, $decimal, ',', '.');
            return '<font color="' . $cor . '">' . $moeda . $number . '</font>';
        } else {
            return $moeda . number_format($number, $decimal, ',', '.');
        }
    }

    /**
     * Formatar um número com 2 casas decimais após a virgula e ponto para diferenciar os milhares
     *
     * @param type $numero
     * @return type
     */
    public static function numberUnformat($number) {
        return (!empty($number)) ? str_replace(',', '.', str_replace('.', '', $number)) : null;
    }

    /**
     * Formatar a data atual por exetenso em Português
     *
     * @param $prefix string - Prefixo para a data por extenso
     * @param $time string  - Se o fuso horário do seu servidor é diferente do seu, basta ajustar adicionando ou diminuindo horas. Ex.: "- 3 hours" ou "+ 1 hours"
     * @return string Ex.: Quinta-feira, 01 de Abril de 2011
     * @exemple: echo longDate('Salvador (BA) - ', '- 3 hours');
     */
    public static function longDate($prefix = '', $time = 'now') {
        $hoje = strtotime($time);
        $i = getdate($hoje); // Consegue informações data/hora
        $data = $i[mday]; //Representação numérica do dia do mês (1 a 31)
        $dia = $i[wday]; // representação numérica do dia da semana com 0 (para Domingo) a 6 (para Sabado)
        $mes = $i[mon]; // Representação numérica de um mês (1 a 12)
        $ano = $i[year]; // Ano com 4 digitos, lógico, né?
        $data = str_pad($data, 2, "0", STR_PAD_LEFT); // só para colocar um zerinho à esquerda caso seja de 1 à 9, sacou?
        $nomedia = array("Domingo", "Segunda-feira", "Terça-feira", "Quarta-feira", "Quinta-feira", "Sexta-feira", "Sábado");
        $nomemes = array("", "Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho", "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro");
        return "$prefix{$nomedia[$dia]}, $data de {$nomemes[$mes]} de $ano";
    }

    /**
     * Transformar um objeto do tipo stdClass em um array
     *
     * @param stdClass $object
     * @return array
     */
    public static function objectToArray($d) {
        if (is_object($d)) {
            // Gets the properties of the given object
            // with get_object_vars function
            $d = get_object_vars($d);
        }

        if (is_array($d)) {
            /*
             * Return array converted to object
             * Using __FUNCTION__ (Magic constant)
             * for recursive call
             */
            return array_map("GF::objectToArray", $d);
        } else {
            // Return array
            return $d;
        }
    }

    /**
     * Retirar os caracteres do telefone que não sejam numeros
     *
     * @param string $text
     * @return string
     */
    public static function formatTelefone($text) {
        $return = $text;
        $return = str_replace("(", '', $return);
        $return = str_replace(")", '', $return);
        $return = str_replace(" ", '', $return);
        $return = str_replace("-", '', $return);

        return $return;
    }

    /**
     * Função para enviar email usando o SES da AWS ou a função nativa do PHP: mail() <br>
     * Para definir o método de envio, alterar a constante "SYS_EMAIL_VIA" no global.php.
     * 'AWS': SES da Amazon, 'PHP': função mail() do PHP
     *
     * @param string $from Remetente
     * @param string $to Destinatário
     * @param string $subject Assunto
     * @param string $message O corpo do email
     * @param string $replyTo Opcional -> Email para onde deve ser encaminhada a resposta
     * @param string $returnPath Opcional -> Email para onde encaminhar bounces/erros na entrega
     * @param string $bcc Opcional -> Email para onde der ser enviada uma cópia oculta
     * @return array 'status' => true <br> false, 'msg' => 'Mensagem de retorno' <br> 'messageId' => 'Id da Amazon'
     */
    public static function sendEmail($from, $to, $subject, $message, $replyTo = '', $returnPath = '', $bcc = '') {
        $returnPath = (empty($returnPath)) ? $from : $returnPath;

        if (SYS_EMAIL_VIA == 'PHP') {
            $from = utf8_decode($from);
            $replyTo = (empty($replyTo)) ? $from : utf8_decode($replyTo);

            $header = "From: " . $from . "\n";
            if (!empty($bcc)) {
                $header .= "Bcc: " . $bcc . "\n";
            }
            $header .= "Reply-To: " . $replyTo . "\n";
            $header .= "Return-Path: " . $returnPath . "\n";

            $header .= "MIME-Version: 1.1" . "\n";
            $header .= "Content-type: text/html; charset=iso-8859-1" . "\n";
            $header .= "Content-Transfer-Encoding: base64" . "\n";

            $message = rtrim(chunk_split(base64_encode(GF::convertCharset($message, false))));

            if (@mail($to, utf8_decode($subject), $message, $header)) {
                $ret['status'] = true;
                $ret['msg'] = 'Email enviado com sucesso!';
                $ret['messageId'] = '';
            } else {
                $ret['status'] = false;
                $ret['msg'] = 'Falha ao enviar email';
                $ret['messageId'] = '';
            }
        } else if(SYS_EMAIL_VIA == 'SMTP') {
            // Inclui o arquivo class.phpmailer.php localizado na pasta phpmailer
            require(ROOT_GENESIS . "inc/phpmailer/class.phpmailer.php");

            $mail = new PHPMailer();

            $mail->IsSMTP(); // Define que a mensagem será SMTP
            $mail->Host = SYS_EMAIL_SMTP; // Endereço do servidor SMTP
            $mail->SMTPAuth = false; // Usa autenticação SMTP? (opcional)
            $mail->Username = SYS_EMAIL_SMTP_USUARIO; // Usuário do servidor SMTP
            $mail->Password = SYS_EMAIL_SMTP_SENHA; // Senha do servidor SMTP

            $mail->From = $from;
            $mail->FromName = 'Continental'; // Seu nome

            $mail->AddAddress($to);
            if (!empty($bcc)) {
                $mail->AddCC($bcc); // Copia
            }

            $mail->IsHTML(true); // Define que o e-mail será enviado como HTML
            $mail->CharSet = 'iso-8859-1'; // Charset da mensagem (opcional)

            $mail->Subject  = utf8_decode($subject); // Assunto da mensagem
            $mail->Body = utf8_decode($message);

            $enviado = $mail->Send();

            $mail->ClearAllRecipients();
            $mail->ClearAttachments();

	    if($enviado){
		$ret['status'] = true;
	    }else{
		$ret['status'] = false;
	    }

        } else {
            $ret['status'] = false;
            $ret['msg'] = 'Tipo de envio de email não encontrado';
            $ret['messageId'] = '';
        }
        return $ret;
    }

}
