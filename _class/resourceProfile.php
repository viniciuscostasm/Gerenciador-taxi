<?php

class ResourceProfile {

    private $rpr_int_id;
    /* @var $resource Resource */
    private $resource;
    /* @var $profile Profile */
    private $profile;

    public function getRpr_int_id() {
        return $this->rpr_int_id;
    }

    public function setRpr_int_id($rpr_int_id) {
        $this->rpr_int_id = $rpr_int_id;
    }

    /** @return Resource */
    public function getResource() {
        return $this->resource;
    }

    /** @param Resource $resource */
    public function setResource($resource) {
        $this->resource = $resource;
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