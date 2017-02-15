jQuery(document).ready(function() {
    jQuery("#btn_carregar_tabelas").click(function() {
        var db = jQuery("#cmb_databases").val();
        if (db != '-1')
            jQuery.gAjax.load('carregarTabelas.php', {
                database: db
            }, '#lbl_tabelas');
        else
            jQuery.gDisplay.showError('Selecione um Database válido');
    });
});