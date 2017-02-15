<?php

/**
 * Classe para gerar a estrutura de um email
 */
class GEmail {

    private $headers;
    private $subject;
    private $message;
    private $from = SYS_EMAIL_NOREPLY;
    private $to;
    private $cc;
    private $cco;

    function __construct() {
        $headers = "MIME-Version: 1.1\n";
        $headers .= "Content-type: text/html; charset=iso-8859-1\n";
        $headers .= "Content-Transfer-Encoding: base64\n";
        $this->setHeaders($headers);
    }

    /**
     * Monta a estrutura do email e envia.
     *
     * @return bool
     */
    function send() {
        $headers = $this->getHeaders();
        $headers .= "From: " . GF::convertCharset($this->getFrom(), false) . "\n";
        $headers .= "Return-Path: " . GF::convertCharset($this->getFrom(), false) . "\n";
        $headers .= ( is_null($this->getCc())) ? "" : "Cc: " . GF::convertCharset($this->getCc(), false) . "\n";
        $headers .= ( is_null($this->getCco())) ? "" : "Cco: " . GF::convertCharset($this->getCco(), false) . "\n";
        $this->setHeaders($headers);
        $message = rtrim(chunk_split(base64_encode(GF::convertCharset($this->getMessage(), false))));
        return mail(GF::convertCharset($this->getTo(), false), GF::convertCharset($this->getSubject(), false), $message, $this->getHeaders());
    }

    public function getHeaders() {
        return $this->headers;
    }

    public function setHeaders($headers) {
        $this->headers = $headers;
    }

    public function getSubject() {
        return $this->subject;
    }

    public function setSubject($subject) {
        $this->subject = $subject;
    }

    public function getMessage() {
        return $this->message;
    }

    public function setMessage($message) {
        $this->message = $message;
    }

    public function getFrom() {
        return $this->from;
    }

    public function setFrom($from) {
        $this->from = $from;
    }

    public function getTo() {
        return $this->to;
    }

    public function setTo($to) {
        $this->to = $to;
    }

    public function getCc() {
        return $this->cc;
    }

    public function setCc($cc) {
        $this->cc = $cc;
    }

    public function getCco() {
        return $this->cco;
    }

    public function setCco($cco) {
        $this->cco = $cco;
    }

}

?>
