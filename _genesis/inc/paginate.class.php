<?php

class GPaginate {

    private $id;
    private $post; // Arquivo que será enviada a requisição ajax
    private $filter;
    private $pagTop;
    private $pagBottom;
    private $rp; // Registros por página

    /**
     *
     * @param type $id
     * @param type $post
     * @param type $rp
     * @param type $filter
     * @param type $pagTop
     * @param type $pagBottom
     */

    public function __construct($id, $post, $rp = 10, $filter = '', $pagTop = true, $pagBottom = true) {
        $this->id = $id;
        $this->post = $post;
        $this->filter = $filter;
        $this->pagTop = $pagTop;
        $this->pagBottom = $pagBottom;
        $this->rp = $rp;
    }

    public function get() {
        $pag = '';

        $pag .= '<div id="' . $this->id . '">';

//        $pag .= '<div class="jqpagination input-prepend input-append">';
//        $pag .= '<a class="first btn" data-action="first"><<</a>';
//        $pag .= '<a class="previous btn" data-action="previous"><</a>';
//        $pag .= '<input type="text" readonly="readonly" />';
//        $pag .= '<a class="next btn" data-action="next">></a>';
//        $pag .= '<a class="last btn" data-action="last">>></a>';
//        $pag .= '</div>';

        $pag .= '<div id="' . $this->id . 'Load" class="jqpaginationContent"></div>';

        $pag .= '<div class="jqpagination">';
        $pag .= '<ul style="margin: 0px !important;">';
        $pag .= '<li><a class="first btn hidden-phone" data-action="first"><<</a></li>';
        $pag .= '<li><a class="previous btn" data-action="previous"><</a></li>';
        $pag .= '<li><input type="text" readonly="readonly" class="m-wrap" style="width:120px;" /></li>';
        $pag .= '<li><a class="next btn" data-action="next">></a></li>';
        $pag .= '<li><a class="last btn hidden-phone" data-action="last">>></a></li>';
        $pag .= '</ul>';
        $pag .= '</div>';

        $pag .= '</div>'; // fecha div "id"
        $pag .= '<input type="hidden" id="pag_atual_' . $this->id . '" />';

        $pag .= '<script>';
        $pag .= 'function ' . $this->id . 'Load(rp,sortname,sortorder,filter,page){' . "\n";
        $pag .= 'page = (page === undefined) ? $("#pag_atual_' . $this->id . '").val() : page;' . "\n";
        $pag .= 'page = page.length == 0 ? 1 : page; ' . "\n";
        $pag .= 'rp = (rp === undefined || rp == "") ? ' . $this->rp . ' : rp;' . "\n";
        $pag .= 'sortname = (sortname === undefined) ? "" : sortname;' . "\n";
        $pag .= 'sortorder = (sortorder === undefined) ? "" : sortorder;' . "\n";
        $pag .= 'filter = (filter === undefined) ? "" : filter;' . "\n";
        $pag .= '__jqPaginate("' . $this->id . '","' . $this->post . '","#' . $this->id . 'Load",rp,sortname,sortorder,filter,page);' . "\n";
        $pag .= '}' . "\n";
        $pag .= '</script>';

        return $pag;
    }

    public function show() {
        echo $this->get();
    }

}