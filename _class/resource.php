<?php

class Resource {

    private $res_int_id;
    /* @var $menu Menu */
    private $menu;
    private $res_var_key;
    private $res_var_name;
    private $res_cha_type;
    private $res_var_path;
    private $res_txt_parameters;

    public function getRes_int_id() {
        return $this->res_int_id;
    }

    public function setRes_int_id($res_int_id) {
        $this->res_int_id = $res_int_id;
    }

    /** @return Menu */
    public function getMenu() {
        return $this->menu;
    }

    /** @param Menu $menu */
    public function setMenu($menu) {
        $this->menu = $menu;
    }

    public function getRes_var_key() {
        return $this->res_var_key;
    }

    public function setRes_var_key($res_var_key) {
        $this->res_var_key = $res_var_key;
    }

    public function getRes_var_name() {
        return $this->res_var_name;
    }

    public function setRes_var_name($res_var_name) {
        $this->res_var_name = $res_var_name;
    }

    public function getRes_cha_type() {
        return $this->res_cha_type;
    }

    public function setRes_cha_type($res_cha_type) {
        $this->res_cha_type = $res_cha_type;
    }

    public function getRes_var_path() {
        return $this->res_var_path;
    }

    public function setRes_var_path($res_var_path) {
        $this->res_var_path = $res_var_path;
    }

    public function getRes_txt_parameters() {
        return $this->res_txt_parameters;
    }

    public function setRes_txt_parameters($res_txt_parameters) {
        $this->res_txt_parameters = $res_txt_parameters;
    }

}