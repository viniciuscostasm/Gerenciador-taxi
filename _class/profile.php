<?php

class Profile {

    private $pro_int_id;
    private $pro_var_name;
    private $pro_cha_type;
    private $pro_dti_add;

    public function getPro_int_id() {
        return $this->pro_int_id;
    }

    public function setPro_int_id($pro_int_id) {
        $this->pro_int_id = $pro_int_id;
    }

    public function getPro_var_name() {
        return $this->pro_var_name;
    }

    public function setPro_var_name($pro_var_name) {
        $this->pro_var_name = $pro_var_name;
    }

    public function getPro_cha_type() {
        return $this->pro_cha_type;
    }

    public function setPro_cha_type($pro_cha_type) {
        $this->pro_cha_type = $pro_cha_type;
    }

    public function getPro_dti_add() {
        return $this->pro_dti_add;
    }

    public function setPro_dti_add($pro_dti_add) {
        $this->pro_dti_add = $pro_dti_add;
    }

}
