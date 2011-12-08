<?php
/** Crea el hidden que lleva la accion, esta accion puede ser buscar o limpiar.*/
$out[] = $appForm->input('Formulario.accion', array('type' => 'hidden', 'id' => 'accion', 'value' => 'buscar'));

if ($this->params['isAjax']) {

	/**
	* Creo los botones de los buscadores.
	* El boton de Buscar y el de Limpiar.
	*/
	$out[] = $appForm->button(__('Clear', true), array('value' => 'limpiar', 'class' => 'buscador_ajax', 'title' => 'Limpiar los criterios de busqueda'));
	$out[] = $appForm->button(__('Search', true), array('value' => 'buscar', 'class' => 'buscador_ajax', 'title' => 'Realizar la busqueda'));
	
	$out[] = $appForm->codeBlock("
jQuery(document).ready(function($) {

        jQuery.bindMultipleCheckBoxManipulation('#lov');
    
        /** When #opened_lov_options not empty, because I'm on a lov */
        if (jQuery('#opened_lov_options').val() != '') {

            /**Hides everything but select option */
            jQuery('td.acciones > a', jQuery('#simplemodal-container')).hide();
            jQuery('td.acciones > img:not(\'.seleccionar\')', jQuery('#simplemodal-container')).hide();

            var params = jQuery.makeObject(jQuery('#opened_lov_options').val());
            if (params['seleccionMultiple'] == 0) {
                jQuery('input.selection_lov', jQuery('#simplemodal-container')).remove();
            }


            jQuery('.seleccionar').click(function() {

                var selectedData = new Array();
                var selectedIds = new Array();
                var toReturn = params['camposRetorno'].split('|');

                if (params['seleccionMultiple'] == 0) {
                    var r = getData(jQuery(this).parent(), toReturn);
                    selectedData.push(r[0]);
                    selectedIds.push(r[1]);
                } else {
                    /** Marks the checkbox associated as checked */
                    jQuery('.selection_lov', jQuery(this).parent()).attr('checked', true);

                    jQuery('.selection_lov').filter(':checked').each(
                        function() {
                            var r = getData(jQuery(this).parent(), toReturn);
                            selectedData.push(r[0]);
                            selectedIds.push(r[1]);
                        }
                    );
                }
    
                jQuery('#' + params['retornarA'] + '__').val(selectedData.join('\\n'));
                jQuery('#' + params['retornarA']).val(selectedIds.join('**||**'));
                jQuery('a.modalCloseImg').trigger('click');
            });

            var getData = function(cell, toReturn) {
                var row = jQuery(cell).parent();
                var returnRowData = new Array();
                jQuery('td:not(\'.acciones\')', row).each(
                    function() {
                        if (jQuery.inArray(jQuery(this).attr('axis'), toReturn) >= 0) {
                            returnRowData.push(jQuery(this).html());
                        }
                    }
                );
                var r = new Array();
                if (returnRowData.length > 0) {
                    r[0] = jQuery.vsprintf(params['mask'], returnRowData);
                } else {
                    r[0] = '';
                }
                r[1] = row.attr('charoff');
                return r;
            }
    
        }
            

        /** Binds enter key to sumbit function */
        jQuery('#lov').keypress(function (e) {
            if (e.which == 13) {
                submitData();
                return;
            }
        });
    
        /** Binds click to sumbit function */
        jQuery('.buscador_ajax', jQuery('#lov')).click(
            function() {
                submitData(this);
            }
        );

        /** Do ajax submit. */
        var submitData = function(el) {
            /** Set action (clean or search) */
            var accion = jQuery(el).val().toLowerCase();
            jQuery('#accion', jQuery('#lov')).val(accion);
            /** Seteo las opciones para hacer el request ajax. */
            var url = jQuery('#form', jQuery('#lov')).attr('action');
            var options = {
                target:     '#lov',
                type:       'POST',
                url:        url
            };
            jQuery('#form', jQuery('#lov')).ajaxSubmit(options);
        }
    
    
        /** Finds what is already selected and mark it up */
        var data = jQuery('#' + params['retornarA']).val().split('**||**');
        jQuery('tr', jQuery('#lov')).each(
            function() {
                if (jQuery.inArray(jQuery(this).attr('charoff'), data) >= 0) {
                    jQuery('input.selection_lov', jQuery(this)).attr('checked', true);
                }
            }
        );
    
    });");
	
} else {
	/**
	* hidden para no perder el layout en el que estoy ni si es permitido seleccion Multiple o no.
	*/
	//$out[] = $appForm->input("Formulario.layout", array("type"=>"hidden", "value"=>$this->layout));
	
	//$limpiar = $appForm->button(__("Clear", true), array("class"=>"limpiar", "onclick"=>"document.getElementById('accion').value='limpiar';form.action='" . Router::url(array("controller" => $this->params['controller'], "action" => $opcionesForm['action'])) . "';form.submit();"));
    $limpiar = $appForm->button(__("Clear", true), array("class"=>"limpiar", "onclick"=>"document.getElementById('accion').value='limpiar';form.submit();"));
	$buscar = $appForm->submit(__("Search", true));
	
	if(isset($botonesExtra['opciones']['botones'])) {
		foreach($botonesExtra['opciones']['botones'] as $v) {
			switch ($v) {
				case "limpiar":
					$out[] = $limpiar;
					break;
				case "buscar":
					$out[] = $buscar;
					break;
				default:
					$out[] = $v;
					break;
			}
		}
	} else {
		/**
		* Creo los botones de los buscadores.
		* El boton de Buscar y el de Limpiar.
		*/
		$out[] = $limpiar;
		$out[] = $buscar;
	}
}

/**
* Creo un bloque con los botones y agrego el div clear antes para cerrar la caja redondeada.
*/
$out[] = $appForm->tag('div', '', array('class' => 'clear'));


echo $appForm->tag('div', $out, array('class' => 'buscadores'));

?>