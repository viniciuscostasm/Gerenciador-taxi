<?php

class GForm extends GFormParent {

    /**
     * Gerar HTML da abertura do formulário
     *
     * @param string $id Ex: 'frm_login' default = 'form'
     * @param string $class Ex: form-horizontal default = 'form-vertical'
     * @param string $method Ex: 'get' default = 'post'
     * @param string $target Ex: '_blanc' default = '_self'
     * @param string $action Ex: 'frm_login.php' default = false
     * @param bool $enctype true ou false
     * @return string HTML de abertura do formulário gerado
     */
    function open($id = 'form', $class = 'form-vertical', $method = 'post', $target = '_self', $action = '', $enctype = false, $charset = "UTF-8") {
        if (strpos($class, 'filterForm') !== false) {
            $class .= ' clearfix';
        }
        return parent::open($id, $class, $method, $target, $action, $enctype, $charset);
    }

    /**
     * Gerar HTML de textarea
     *
     * @param string $id Ex: 'txt_texto' default = 'textarea'
     * @param string $text Ex: 'texto de exemplo' default = ''
     * @param string $title Ex: 'Texto' default = false
     * @param array $fieldParam Ex: 'class'=>'cls_campo', 'cols'=>'10' 'rols'=>'3' default = false
     * @param array $titleParam Ex: 'class'=>'cls_titulo' default = false
     * @param array $legends Ex: array('A'=>'R$', 'B'=>'Ex:1') default = false [After ou Before]
     * @param bool $control Default: true
     * @return string HTML do textarea gerado
     */
    function addTextarea($id, $text = '', $title = false, $fieldParam = false, $titleParam = false, $legends = false, $control = true) {
        if (strpos($fieldParam['class'], 'form-control') === false) {
            $fieldParam['class'] .= ' form-control';
        }
        return parent::addTextarea($id, $text, $title, $fieldParam, $titleParam, $legends, $control);
    }

    /**
     * Gerar HTML de Select/Combobox
     *
     * @param string $id Ex: 'slc_tipo' default = 'select'
     * @param array $options Ex: '0' => 'Inactive', '1' => 'Active' default = '-1' => 'selecione...'
     * @param string $selectOption Ex: '1' default = '-1'
     * @param string $title Ex: 'Tipo' default = false
     * @param array $fieldParam Ex: 'class' => 'cls_campo', 'size' => '1' 'multiple' => 'multiple' default = false
     * @param array $titleParam Ex: 'class'=>'cls_titulo' default = false
     * @param array $legends Ex: array('A'=>'R$', 'B'=>'Ex:1') default = false [After ou Before]
     * @param boolean $firstSelect "Para inserir o primeiro ítem 'Select...'" Ex: true (padrão), false
     * @param string $firstSelectValue o valor do primeiro elemento do combo
     * @param string $firstSelectText o texto do primeiro elemento do combo
     * @param bool $control Default: true
     * @param bool $hiddenLabel Default: true Insere ou não um input hidden que guarda a string do valor selecionado
     * @return string HTML do select gerado
     */
    function addSelect($id, $options, $selectedOption = '', $title = false, $fieldParam = false, $titleParam = false, $legends = false, $firstSelect = true, $firstSelectValue = '', $firstSelectText = 'Select...', $control = true, $hiddenLabel = true) {
        if (strpos($fieldParam['class'], 'form-control') === false) {
            $fieldParam['class'] .= ' form-control';
        }
        return parent::addSelect($id, $options, $selectedOption, $title, $fieldParam, $titleParam, $legends, $firstSelect, $firstSelectValue, $firstSelectText, $control, $hiddenLabel);
    }

    /**
     * Gerar HTML de input
     *
     * @param string $type Ex: 'text', 'password', 'button'
     * @param string $id Ex: 'txt_name'
     * @param string $title Ex: 'Name' default = false
     * @param array $fieldParam Ex: 'class'=>'cls_campo', 'size'=>'100' default = false
     * @param array $titleParam Ex: 'class'=>'cls_titulo' default = false
     * @param array $legends Ex: array('A'=>'R$', 'B'=>'Ex:1') default = false [After ou Before]
     * @param bool $control Default: true
     * @return string HTML do input gerado
     */
    function addInput($type, $id, $title = false, $fieldParam = false, $titleParam = false, $legends = false, $control = true) {
        if (strpos($fieldParam['class'], 'form-control') === false) {
            $fieldParam['class'] .= ' form-control';
        }
        return parent::addInput($type, $id, $title, $fieldParam, $titleParam, $legends, $control);
    }

    /**
     *
     * @param int $id
     * @param string $title default=false
     * @param string $selected default=A
     * @param class $class default=sepH_b
     * @param string $classBtn default=''
     * @param bool $control default=true
     * @return string
     */
    function addStatus($id, $title = false, $selected = 'A', $class = 'sepH_b', $classBtn = '', $control = true) {
        $ativeA = $selected == 'A' ? ' green' : '';
        $ativeI = $selected == 'I' ? ' red' : '';

        if ($control) {
            $return .= '<div class="form-group">';
        }

        if ($title) {
            $return .= $this->addLabel($id, $title, array('class' => 'control-label'));
        }
        $return .= $this->addInput('hidden', $id, false, array('value' => $selected, 'class' => 'status'), false, false, false);
        $return .= ($control) ? '<div class="controls">' : '';
        $return .= '<div id="' . $id . '_group" data-toggle="buttons-radio" class="btn-group btnChave clearfix ' . $class . '">';
        $return .= '<button type="button" id="btn_ativo_' . $id . '" rel="A" class="btn ' . $ativeA . ' ' . $classBtn . '">Ativo</button>';
        $return .= '<button type="button" id="btn_inativo_' . $id . '" rel="I" class="btn ' . $ativeI . ' ' . $classBtn . '">Inativo</button>';
        $return .= '</div>';
        $return .= ($control) ? '</div>' : '';

        $return .= "<script>";
        $return .= "$(function(){ ";
        $return .= "$('#btn_ativo_" . $id . "').click(function(){ ";
        $return .= "$('#" . $id . "').val('A'); ";
        $return .= "$('#btn_inativo_" . $id . "').removeClass('red'); ";
        $return .= "$('#btn_ativo_" . $id . "').addClass('green'); ";
        $return .= "}); ";
        $return .= "$('#btn_inativo_" . $id . "').click(function(){ ";
        $return .= "$('#" . $id . "').val('I'); ";
        $return .= "$('#btn_ativo_" . $id . "').removeClass('green'); ";
        $return .= "$('#btn_inativo_" . $id . "').addClass('red'); ";
        $return .= "}); ";
        $return .= "}); ";
        $return .= "</script>";
        if ($control) {
            $return .= '</div>';
        }
        return $return;
    }

    /**
     *
     * @param string $id
     * @param bool $title = false
     * @param string $selected default = 'S'
     * @param string $class default = 'sepH_b'
     * @param string $classBtn default = ''
     * @param bool $control default=true
     * @return string
     */
    function addYesNo($id, $title = false, $selected = 'S', $class = 'sepH_b', $classBtn = '', $control = true) {
        $ativeA = $selected == 'S' ? 'active green' : '';
        $ativeI = $selected == 'N' ? 'active red' : '';

        if ($control) {
            $return .= '<div class="form-group">';
        }

        if ($title) {
            $return .= $this->addLabel($id, $title, array('class' => 'control-label'));
        }
        $return .= $this->addInput('hidden', $id, false, array('value' => $selected), false, false, false);
        $return .= ($control) ? '<div class="controls">' : '';
        $return .= '<div id="' . $id . '_group" data-toggle="buttons-radio" class="btn-group btnChave clearfix ' . $class . '">';
        $return .= '<button type="button" id="btn_sim_' . $id . '" rel="S" class="btn ' . $ativeA . ' ' . $classBtn . '">Yes</button>';
        $return .= '<button type="button" id="btn_nao_' . $id . '" rel="N" class="btn ' . $ativeI . ' ' . $classBtn . '">No</button>';
        $return .= '</div>';
        $return .= ($control) ? '</div>' : '';

        $return .= "<script>
                    $(function(){
                        $('#btn_sim_" . $id . "').click(function(){
                            $('#" . $id . "').val('S');
                            $('#btn_nao_" . $id . "').removeClass('red');
                            $('#btn_sim_" . $id . "').addClass('green');
                        });
                        $('#btn_nao_" . $id . "').click(function(){
                            $('#" . $id . "').val('N');
                            $('#btn_sim_" . $id . "').removeClass('green');
                            $('#btn_nao_" . $id . "').addClass('red');
                        });
                    });
                </script>
        ";
        if ($control) {
            $return .= '</div>';
        }
        return $return;
    }

    

    

    /**
     * Gerar HTML e Javascript para um campo de Hora
     *
     * @param int $id
     * @param string $title
     * @param array $titleParam
     * @param bool $required
     * @param bool $control
     * @return string
     */
    function addDateHourField($id, $title = false, $titleParam = false, $required = false, $control = false) {
        $return = '';
        if ($control) {
            $return .= '<div class="form-group">';
        }

        if ($title) {
            $return .= $this->addLabel($id . '_date', $title, $titleParam);
        }

        if ($control) {
            $return .= '<div class="controls">';
        }
        $req = ($required) ? ';required' : '';

        $paramDate = array('style' => 'width: 75px;', 'class' => 'form-control');
        $paramHour = array('style' => 'width: 40px; border-left: 0px none;', 'class' => 'form-control');

        $return .= '';
        $return .= $this->addInput('text', $id . '_date', 'Data (' . $title . ')', $paramDate, array('style' => 'display:none'), array('A' => '<label for="' . $id . '_date"><i class="icon-calendar"></i></label>'), false);
        $return .= $this->addInput('text', $id . '_hour', 'Hora (' . $title . ')', $paramHour, array('style' => 'display:none'), array('A' => '<label for="' . $id . '_hour"><i class="icon-time"></i></label>'), false);
        $return .= $this->addInput('hidden', $id, $title, array('validate' => 'dataHora' . $req), array('style' => 'display:none'), false, false);

        if ($control) {
            $return .= '</div></div>';
        }

        $return .= '<script> $(function(){';
        $return .= '
            jQuery("#' . $id . '_hour").timepicker({showMeridian: false}).on("hide.timepicker", function(e) {
                jQuery("#' . $id . '").val(jQuery("#' . $id . '_date").val()+" "+jQuery("#' . $id . '_hour").val());
            });';
        $return .= '
            jQuery("#' . $id . '_date").datepicker({format: "dd/mm/yyyy",autoclose:true,language:"pt-BR"}).on("changeDate", function(ev){
                jQuery("#' . $id . '").val(jQuery("#' . $id . '_date").val()+" "+jQuery("#' . $id . '_hour").val());

                jQuery("#' . $id . '_date").datepicker("hide");
                jQuery("#' . $id . '_hour").focus();
            });
            jQuery("#' . $id . '_hour").on("blur", function() {
                jQuery("#' . $id . '").val(jQuery("#' . $id . '_date").val()+" "+jQuery("#' . $id . '_hour").val());
             });
            ';

        $return .= 'jQuery("#' . $id . '_hour").mask("99:99");';
        $return .= 'jQuery("#' . $id . '_date").mask("99/99/9999");';
        $return .= '}); </script>';
        return $return;
    }

    /**
     *
     * @param type $id
     * @param type $title
     * @param type $selected
     * @return string
     */
    function addButtonPassengerType($id, $title = false, $selected = 'EMP', $class = 'sepH_b', $classBtn = '') {
        $ativeEMP = $selected == 'EMP' ? 'active blue' : '';
        $ativeEXT = $selected == 'EXT' ? 'active blue' : '';
        if ($title)
            $return .= $this->addLabel($id, $title);

        $return .= $this->addInput('hidden', $id, false, array('value' => $selected), false, false, false);
        $return .= '<div id="' . $id . '_group" data-toggle="buttons-radio" class="btn-group btnChave clearfix ' . $class . '">';
        $return .= '<button type="button" id="btn_employee_' . $id . '" rel="EMP" class="btn ' . $ativeEMP . ' ' . $classBtn . ' ' . $id . '">Funcionário</button>';
        $return .= '<button type="button" id="btn_external_' . $id . '" rel="EXT" class="btn ' . $ativeEXT . ' ' . $classBtn . ' ' . $id . '">Convidado ou Terceiro</button>';
        $return .= '</div>';

        $return .= "<script>
                    $(function(){
                        $('#btn_employee_" . $id . "').click(function(){
                            $('#" . $id . "').val('EMP');
                            $('#btn_external_" . $id . "').removeClass('blue');
                            $('#btn_employee_" . $id . "').addClass('blue');
                        });
                        $('#btn_external_" . $id . "').click(function(){
                            $('#" . $id . "').val('EXT');
                            $('#btn_employee_" . $id . "').removeClass('blue');
                            $('#btn_external_" . $id . "').addClass('blue');
                        });
                    });
                </script>
        ";
        return $return;
    }

    /**
     *
     * @param type $id
     * @param type $title
     * @param type $selected
     * @return string
     */
    function addButtonRequestType($id, $title = false, $selected = 'EMP', $class = 'sepH_b', $classBtn = '') {
        $ativeSCO = $selected == 'SCO' ? 'active blue' : '';
        $ativeDCO = $selected == 'DCO' ? 'active blue' : '';
        $ativeEXT = $selected == 'EXT' ? 'active blue' : '';
        
        if ($title)
            $return .= $this->addLabel($id, $title);

        $return .= $this->addInput('hidden', $id, false, array('value' => $selected), false, false, false);
        $return .= '<div id="' . $id . '_group" data-toggle="buttons-radio" class="btn-group btnChave clearfix ' . $class . '">';
        $return .= '<button type="button" id="btn_sco_' . $id . '" rel="SCO" class="btn ' . $ativeSCO . ' ' . $classBtn . ' ' . $id . '">Origem Continental</button>';
        $return .= '<button type="button" id="btn_dco_' . $id . '" rel="DCO" class="btn ' . $ativeDCO . ' ' . $classBtn . ' ' . $id . '">Destino Continental</button>';
        $return .= '<button type="button" id="btn_ext_' . $id . '" rel="EXT" class="btn ' . $ativeEXT . ' ' . $classBtn . ' ' . $id . '">Corrida Externa</button>';
        $return .= '</div>';

        $return .= "<script>
                    $(function(){
                        $('#btn_sco_" . $id . "').click(function(){
                            $('#" . $id . "').val('SCO');
                            $('#btn_dco_" . $id . "').removeClass('blue');
                            $('#btn_ext_" . $id . "').removeClass('blue');
                            $('#btn_sco_" . $id . "').addClass('blue');
                        });
                        $('#btn_dco_" . $id . "').click(function(){
                            $('#" . $id . "').val('DCO');
                            $('#btn_sco_" . $id . "').removeClass('blue');
                            $('#btn_ext_" . $id . "').removeClass('blue');
                            $('#btn_dco_" . $id . "').addClass('blue');
                        });
                        $('#btn_ext_" . $id . "').click(function(){
                            $('#" . $id . "').val('EXT');
                            $('#btn_sco_" . $id . "').removeClass('blue');
                            $('#btn_dco_" . $id . "').removeClass('blue');
                            $('#btn_ext_" . $id . "').addClass('blue');
                        });
                    });
                </script>
        ";
        return $return;
    }

}

?>
