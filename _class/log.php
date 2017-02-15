<?php
class Log{
	private $env_int_id;
	private $log_int_id;
	private $log_var_key;
	private $log_var_evento;
	private $log_txt_conteudo;
	private $log_cha_tecnico;


	public function getEnv_int_id() {
		return $this->env_int_id;
	}

	public function setEnv_int_id($env_int_id) {
		$this->env_int_id = $env_int_id;
	}

	public function getLog_int_id() {
		return $this->log_int_id;
	}

	public function setLog_int_id($log_int_id) {
		$this->log_int_id = $log_int_id;
	}

	public function getLog_var_key() {
		return $this->log_var_key;
	}

	public function setLog_var_key($log_var_key) {
		$this->log_var_key = $log_var_key;
	}

	public function getLog_var_evento() {
		return $this->log_var_evento;
	}

	public function setLog_var_evento($log_var_evento) {
		$this->log_var_evento = $log_var_evento;
	}

	public function getLog_txt_conteudo() {
		return $this->log_txt_conteudo;
	}

	public function setLog_txt_conteudo($log_txt_conteudo) {
		$this->log_txt_conteudo = $log_txt_conteudo;
	}

	public function getLog_cha_tecnico() {
		return $this->log_cha_tecnico;
	}

	public function setLog_cha_tecnico($log_cha_tecnico) {
		$this->log_cha_tecnico = $log_cha_tecnico;
	}

}