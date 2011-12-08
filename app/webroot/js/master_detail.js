var botonQuitar = function (fieldSet, id) {
    var imagenQuitar = '<div class="quitarDetalle"><a class="link_boton_rojo" href="javascript:void(0);" alt="Quitar" id="linkQuitar_' + id + '">Quitar</a></div>';
    jQuery(fieldSet).find('legend > span').append(imagenQuitar);

    jQuery('#linkQuitar_' + id).bind('click', function(){
        /**
        * Solo puedo quitar mientras tenga por lo menos un detail.
        */
        if (jQuery('fieldSet.detail').length > 1) {
            jQuery('#' + jQuery(this).attr('id').replace('linkQuitar_', 'fieldset_')).remove();
            jQuery(this).remove();
        }
        else {
            alert('Debe dejar por lo menos un registro de detalle.');
        }
    });
}


var detalle = function() {
    var id = 0;
    jQuery('.fieldset_multiple fieldSet.detail').each(
        function() {
            jQuery(this).attr('id', 'fieldset_' + id);
            botonQuitar(this, id);
            id++;
        }
    )
            
    var img = "<img alt='Agregar' title='Agregar' src='" + jQuery.url() + "img/add.gif' />";
    var i = 0;
    jQuery('.fieldset_multiple').each(
        function() {
            jQuery(this).attr('id', 'fieldsetMultiple_' + i);
            //jQuery(this).createAppend('div', {class : 'agregarDetalle'}, ['a', {class: 'link_boton', id : 'agregarDetalle_' + i, href : '#agregarDetalle_' + i}, 'Agregar']);
            jQuery(this).append('<div class=\"agregarDetalle\"><a class=\"link_boton\" id=\"agregarDetalle_' + i + '\" href=\"#agregarDetalle_' + i + '\">Agregar</a></div>');
            i++;
        }
    );
}


var agregar = function() {

    var fsM = jQuery(this).attr('id').replace('agregarDetalle_', 'fieldsetMultiple_');
    var id = jQuery('#' + fsM + ' fieldSet.detail').length;

    /**
    * Clono el fieldset y saco el campo hidden del id (para que cake haga un insert, sino hara un update
    * y al estar agregando, estoy seguro de que es uno nuevo.
    */
    var fieldset = jQuery('#' + fsM + ' fieldset.detail:last').clone(true);
    fieldset.attr('id', 'fieldset_' + id);

    fieldset.find('a:first').attr('id', 'linkQuitar_' + id);

    /**
    * Si hay un control de tipo fecha, el campo al que retorna debo alterarle el id, sino no lo encontrara al correcto.
    */
    fieldset.find('a').each(function(){
        this.href = this.href.replace(/(javascript:NewCal\(\')([a-zA-Z]+)(\',\'dd\/mm\/yyyy\'\))/, '$1$2_' + id + '$3');
    });

    /**
    * Si hay un control de tipo radio, la label de este hara referencia al cual fue clonado, debo cambiarla por el
    * nuevo id que tendra el control.
    */
    fieldset.find('label.radio_label').each(function(){
        jQuery(this).attr('for', jQuery(this).attr('for') + '_' + id);
    });

    var indice = '';
    fieldset.find('input, select, textarea').each(function(){

        if (jQuery(this).attr('name').substr(-4) == '[id]') {
            jQuery(this).attr('value', '');
        }

        /**
        * Debo cambiar el subindice del nombre (name) de cada control,
        * para que no se me repita y se me pisen.
        * puede ser un solo registro o un edit multiple.
        */
        this.id = this.id + '_' + id;
        indice = parseInt(this.name.replace(/(^data\[[a-zA-Z]+\])\[([0-9]+)\](\[[a-zA-Z_]+\])/, '$2')) + 100;
        if (isNaN(indice)) {
            indice = parseInt(this.name.replace(/(^data\[[0-9]+\]\[[a-zA-Z]+\])\[([0-9]+)\](\[[a-zA-Z_]+\])/, '$2')) + 1;
            this.name = this.name.replace(/(^data\[[0-9]+\]\[[a-zA-Z]+\])\[([0-9]+)\](\[[a-zA-Z_]+\])/, '$1\[' + indice + '\]$3')
        } else {
            this.name = this.name.replace(/(^data\[[a-zA-Z]+\])\[([0-9]+)\](\[[a-zA-Z_]+\])/, '$1\[' + indice + '\]$3')
        }

    });

    /**
    * Agrego el nuevo fieldset y al boton agregar lo corro al final de este.
    */
    jQuery('#' + fsM).append(fieldset);
    jQuery('#' + fsM).append(jQuery(this).parent());

    /**
    * Ejectuto la callback si existe.
    * Envio el id del nuevo FrameSet creado y del nuevo elemento.
    */
    if(typeof(jQuery.detailAfterAdd) == 'function'){
        jQuery.detailAfterAdd(id, indice);
    };
}