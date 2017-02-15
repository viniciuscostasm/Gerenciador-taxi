<?php

class User {

    private $usr_int_id;
    private $usr_var_name;
    private $usr_var_email;
    private $usr_var_password;
    private $usr_cha_status;
    private $usr_var_token;
    private $usr_dti_lastaccess;
    private $usr_var_phone;
    private $usr_var_function;
    private $usr_dti_add;
    /* @var $profile Profile */
    private $profile;

    public function getUsr_int_id() {
        return $this->usr_int_id;
    }

    public function setUsr_int_id($usr_int_id) {
        $this->usr_int_id = $usr_int_id;
    }

    public function getUsr_var_name() {
        return $this->usr_var_name;
    }

    public function setUsr_var_name($usr_var_name) {
        $this->usr_var_name = $usr_var_name;
    }

    public function getUsr_var_email() {
        return $this->usr_var_email;
    }

    public function setUsr_var_email($usr_var_email) {
        $this->usr_var_email = $usr_var_email;
    }

    public function getUsr_var_password() {
        return $this->usr_var_password;
    }

    public function setUsr_var_password($usr_var_password) {
        $this->usr_var_password = $usr_var_password;
    }

    public function getUsr_cha_status() {
        return $this->usr_cha_status;
    }

    public function setUsr_cha_status($usr_cha_status) {
        $this->usr_cha_status = $usr_cha_status;
    }

    public function getUsr_var_token() {
        return $this->usr_var_token;
    }

    public function setUsr_var_token($usr_var_token) {
        $this->usr_var_token = $usr_var_token;
    }

    public function getUsr_dti_lastaccess() {
        return $this->usr_dti_lastaccess;
    }

    public function setUsr_dti_lastaccess($usr_dti_lastaccess) {
        $this->usr_dti_lastaccess = $usr_dti_lastaccess;
    }

    public function getUsr_dti_ultimoping() {
        return $this->usr_dti_ultimoping;
    }

    public function setUsr_dti_ultimoping($usr_dti_ultimoping) {
        $this->usr_dti_ultimoping = $usr_dti_ultimoping;
    }

    public function getUsr_dti_add() {
        return $this->usr_dti_add;
    }

    public function setUsr_dti_add($usr_dti_add) {
        $this->usr_dti_add = $usr_dti_add;
    }

    public function getUsr_var_phone() {
        return $this->usr_var_phone;
    }

    public function setUsr_var_phone($usr_var_phone) {
        $this->usr_var_phone = $usr_var_phone;
    }

    public function getUsr_var_function() {
        return $this->usr_var_function ;
    }

    public function setUsr_var_function($usr_var_function) {
        $this->usr_var_function = $usr_var_function;
    }

    /** @return Profile */
    public function getProfile() {
        return $this->profile;
    }

    /** @param Profile $profile */
    public function setProfile($profile) {
        $this->profile = $profile;
    }

}
