<?php

require_once 'UploadHandler.php';

class CustomUploadHandler extends UploadHandler {

    private $old_name;
    private $new_name;
    private $size;
    private $extension;
    private $content;

    protected function trim_file_name($name, $type, $index, $content_range) {
        $name = $this->format_name($name);

        return parent::trim_file_name($name, $type, $index, $content_range);
    }

    protected function generate_response($content, $print_response = false) {
        $arrayName = explode('.', $this->old_name);
        $this->extension = $arrayName[count($arrayName) - 1];

        $content['files'][0]->name = $this->old_name;
        $content['files'][0]->url = null;
        $content['files'][0]->delete_url = null;
        $content['files'][0]->extension = $this->extension;
        $this->size = $content['files'][0]->size;
        $this->content = $content;

        return parent::generate_response($content, false);
    }

    protected function format_name($name) {
        $this->old_name = $name;

        $arrayName = explode('.', $name);
        $amb_int_codigo = GSec::getAmbienteSessao()->getAmb_int_codigo();
        $name = $amb_int_codigo . '-' . md5(uniqid()) . '.' . $arrayName[count($arrayName) - 1];

        $this->new_name = $name;
        return $name;
    }

    public function getOld_name() {
        return $this->old_name;
    }

    public function getNew_name() {
        return $this->new_name;
    }

    public function setNew_name($new_name) {
        $this->new_name = $new_name;
    }

    public function getSize() {
        return $this->size;
    }

    public function getExtension() {
        return $this->extension;
    }

    public function getContent() {
        return $this->content;
    }

}
