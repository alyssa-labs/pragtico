<?php

/**
 * Creo la miga de pan.
 */
$modelName = Inflector::classify($this->params['controller']);
$appForm->addCrumb($this->name,
		array('controller' 	=> $this->params['controller'],
			  'action' 		=> 'index'));

if ($this->action === 'add') {
	$appForm->addCrumb(__('New', true));
} else {
	$appForm->addCrumb(__('Edit', true));
	$count = count($this->data);
	if ($count === 1 && isset($this->data[0])) {
		$appForm->addCrumb('<span class="bread_crumb_class">' . $this->data[0][$modelName]['bread_crumb_text'] . '</span>');
	} else {
		$appForm->addCrumb(sprintf(__('%s Records', true), $count));
	}
}


$bloques[] = $fieldset;


/**
 * Si me pasaron un bloque adicional, lo agrego.
 */
if (!empty($bloqueAdicional)) {
	$bloques[] = $bloqueAdicional;
}


/** Add actions, permissions in mind */
if (!empty($this->data) && $this->action == 'edit') {

    $edit = $delete = true;
    foreach ($this->data as $v) {
        if (!$v[$modelName]['write']) {
            $edit = false;
        }
        if (!$v[$modelName]['delete']) {
            $delete = false;
        }
    }

    $js = '
			/** Replace select by input */
			jQuery("label", jQuery("select").parent()).after(jQuery("<input/>").attr("type", "text").val(jQuery("select option:selected").text()));
			jQuery("select").remove();

			/** Disable editing */
			jQuery("input, textarea, select").attr("disabled", "disabled");
			jQuery("input.cancelar").removeAttr("disabled");

			/** Hide extra unnecesary html */
            jQuery("img.lupa_lov").hide();
            jQuery("img.fecha").hide();
			jQuery("span.color_rojo").hide();

			/** Add option to go back */
			jQuery("input.cancelar").removeAttr("onclick").click(function() {javascript:history.back();});
	';

    if ($edit === true && $delete === true) {
        if (!isset($accionesExtra)) {
            $bloques[] = $this->element('add/acciones');
        } else {
            $bloques[] = $this->element('add/acciones', array('accionesExtra' => $accionesExtra));
        }
    } elseif ($edit === true) {
        $bloques[] = $this->element('add/acciones', array('accionesExtra' => array(
            'opciones' => array('acciones' => array('grabar')))));
    } elseif ($delete === true) {
        $bloques[] = $this->element('add/acciones', array('accionesExtra' => array(
            'opciones' => array('acciones' => array('eliminar')))));
        $appForm->addScript($js);
    } else {
        $bloques[] = $this->element('add/acciones', array('accionesExtra' => array(
            'opciones' => array('acciones' => array('cancelar')))));
        $appForm->addScript($js);
    }
} else {
	if (!isset($accionesExtra)) {
		$bloques[] = $this->element('add/acciones');
	} else {
		$bloques[] = $this->element('add/acciones', array('accionesExtra' => $accionesExtra));
	}
}

/**
 * Creo el formulario y pongo todo dentro.
 */
if (!isset($opcionesForm['action'])) {
	$opcionesForm['action'] = 'save';
}

echo $appForm->tag('div', $appForm->form($bloques, $opcionesForm), array('class' => 'add'));
?>