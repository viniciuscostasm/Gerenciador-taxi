(function($) {
    $.gDisplay = {
        loadStart: function(target, preloader) {
            Metronic.blockUI({
                target: target,
                boxed: true
            });
        },
        loadStop: function(target) {
            Metronic.unblockUI(target);
        },
        loadError: function(target, msg) {
            this.loadStop(target);
            $.gDisplay.showError(msg, '');
        },
        showAlert: function(json, success, error) {
            if (json.status) {
                this.showSuccess(json.msg, success);
            } else {
                this.showError(json.msg, error);
            }
        },
        showSuccess: function(msg, success) {
            $('.modal:not([class~="bootbox"])').css('display', 'none');
            bootbox.dialog({
                message: msg,
                title: "Success",
                buttons: {
                    ok: {
                        label: "Ok",
                        className: "btn-success",
                        callback: function() {
                            $('.modal:not([class~="bootbox"])').css('display', 'block');
                            $('.modal-backdrop:not(:last)').css('display', 'block');
                            if (typeof success === 'function') {
                                success.call(this);
                            }
                        }
                    }
                }
            });
        },
        showError: function(msg, error) {
            $('.modal:not([class~="bootbox"])').css('display', 'none');
            bootbox.dialog({
                message: msg,
                title: "Error",
                buttons: {
                    ok: {
                        label: "Ok",
                        className: "btn-danger",
                        callback: function() {
                            $('.modal:not([class~="bootbox"])').css('display', 'block');
                            $('.modal-backdrop:not(:last)').css('display', 'block');
                            if (typeof error === 'function') {
                                error.call(this);
                            }
                        }
                    }
                }
            });
        },
        showConfirm: function(msg, ok, cancel) {
            $('.modal:not([class~="bootbox"])').css('display', 'none');
            bootbox.dialog({
                message: msg,
                title: "Confirm?",
                buttons: {
                    cancel: {
                        label: "Cancel",
                        className: "btn-default",
                        callback: function() {
                            $('.modal:not([class~="bootbox"])').css('display', 'block');
                            $('.modal-backdrop:not(:last)').css('display', 'block');
                            if (typeof cancel === 'function') {
                                cancel.call(this);
                            }
                        }
                    },
                    ok: {
                        label: "Ok",
                        className: "btn-primary",
                        callback: function() {
                            $('.modal:not([class~="bootbox"])').css('display', 'block');
                            $('.modal-backdrop:not(:last)').css('display', 'block');
                            if (typeof ok === 'function') {
                                ok.call(this);
                            }
                        }
                    }
                }
            });
        },
        showYN: function(msg, yes, no) {
            $('.modal:not([class~="bootbox"])').css('display', 'none');

            bootbox.dialog({
                message: msg,
                title: "Confirm?",
                buttons: {
                    no: {
                        label: "Não",
                        className: "btn-default",
                        callback: function() {
                            $('.modal:not([class~="bootbox"])').css('display', 'block');
                            $('.modal-backdrop:not(:last)').css('display', 'block');
                            if (typeof no === 'function') {
                                no.call(this);
                            }
                        }
                    },
                    yes: {
                        label: "Sim",
                        className: "btn-primary",
                        callback: function() {
                            $('.modal:not([class~="bootbox"])').css('display', 'block');
                            $('.modal-backdrop:not(:last)').css('display', 'block');
                            if (typeof yes === 'function') {
                                yes.call(this);
                            }
                        }
                    }
                }
            });
        }
    }
})(jQuery);
