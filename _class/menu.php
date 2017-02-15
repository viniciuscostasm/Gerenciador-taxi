<?php

class Menu {

    private $men_int_id;
    private $men_var_name;
    private $men_cha_status;
    private $men_var_url;
    private $men_var_icon;
    private $men_var_class;
    private $men_int_order;
    private $men_int_level;
    private $men_var_key;
    private $men_txt_path;
    private $men_cha_consolidator;
    /* @var $menuFather Menu */
    private $menuFather;
    private $men_cha_type;
    private $men_dti_add;

    public function getMen_int_id() {
        return $this->men_int_id;
    }

    public function setMen_int_id($men_int_id) {
        $this->men_int_id = $men_int_id;
    }

    public function getMen_var_name() {
        return $this->men_var_name;
    }

    public function setMen_var_name($men_var_name) {
        $this->men_var_name = $men_var_name;
    }

    public function getMen_cha_status() {
        return $this->men_cha_status;
    }

    public function setMen_cha_status($men_cha_status) {
        $this->men_cha_status = $men_cha_status;
    }

    public function getMen_var_url() {
        return $this->men_var_url;
    }

    public function setMen_var_url($men_var_url) {
        $this->men_var_url = $men_var_url;
    }

    public function getMen_var_icon() {
        return $this->men_var_icon;
    }

    public function setMen_var_icon($men_var_icon) {
        $this->men_var_icon = $men_var_icon;
    }

    public function getMen_var_class() {
        return $this->men_var_class;
    }

    public function setMen_var_class($men_var_class) {
        $this->men_var_class = $men_var_class;
    }

    public function getMen_int_order() {
        return $this->men_int_order;
    }

    public function setMen_int_order($men_int_order) {
        $this->men_int_order = $men_int_order;
    }

    public function getMen_int_level() {
        return $this->men_int_level;
    }

    public function setMen_int_level($men_int_level) {
        $this->men_int_level = $men_int_level;
    }

    public function getMen_var_key() {
        return $this->men_var_key;
    }

    public function setMen_var_key($men_var_key) {
        $this->men_var_key = $men_var_key;
    }

    public function getMen_txt_path() {
        return $this->men_txt_path;
    }

    public function setMen_txt_path($men_txt_path) {
        $this->men_txt_path = $men_txt_path;
    }

    public function getMen_cha_consolidator() {
        return $this->men_cha_consolidator;
    }

    public function setMen_cha_consolidator($men_cha_consolidator) {
        $this->men_cha_consolidator = $men_cha_consolidator;
    }

    /** @return Menu */
    public function getMenuFather() {
        return $this->menuFather;
    }

    /** @param Menu $menuFather */
    public function setMenuFather($menuFather) {
        $this->menuFather = $menuFather;
    }

    public function getMen_cha_type() {
        return $this->men_cha_type;
    }

    public function setMen_cha_type($men_cha_type) {
        $this->men_cha_type = $men_cha_type;
    }

    public function getMen_dti_add() {
        return $this->men_dti_add;
    }

    public function setMen_dti_add($men_dti_add) {
        $this->men_dti_add = $men_dti_add;
    }

}
