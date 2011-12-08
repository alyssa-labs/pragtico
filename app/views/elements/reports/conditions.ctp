<?php

$__defaultConditions = array('Bar-grupo_id' => true, 'Bar-file_format' => true);
if (!empty($options['conditions'])) {
    $options['conditions'] = array_merge($__defaultConditions, $options['conditions']);
} else {
    $options['conditions'] = $__defaultConditions;
}

$conditions = array();
if ($options['conditions']['Bar-grupo_id'] !== false) {
    
    $groups = User::getUserGroups();
    $defaultGroup = User::get('/Usuario/preferencias/grupo_default_id');
    if (count($groups) > 1 && isset($groups[$defaultGroup])) {
        $conditions['Condicion.Bar-grupo_id'] = array(
            'options'   => $groups,
            'empty'     => false,
            'value'     => $defaultGroup);
    } else {
        $conditions['Condicion.Bar-grupo_id'] = array('options' => $groups, 'empty' => false);
    }

    if ($options['conditions']['Bar-grupo_id'] === 'multiple') {
        $conditions['Condicion.Bar-grupo_id']['type'] = 'select';
    	$conditions['Condicion.Bar-grupo_id']['multiple'] = 'checkbox';
    }
}

foreach ($this->params['named'] as $k => $v) {
    $conditions['Condicion.Bar-' . $k] = array('type' => 'hidden', 'value' => $v);
}


if (!empty($aditionalConditions)) {
    $conditions = array_merge($conditions, $aditionalConditions);
}

if ($options['conditions']['Bar-file_format'] === true) {
    $conditions['Condicion.Bar-file_format'] = array('type' => 'radio', 'options' => array('Excel5' => 'Excel', 'Excel2007' => 'Excel 2007'), 'value' => 'Excel2007');
}

$fieldsets[] = array('campos' => $conditions);

$fieldset = $appForm->pintarFieldsets($fieldsets, array('fieldset' => array('legend' => $options['title'], 'imagen' => 'reports.gif')));

$accionesExtra['opciones'] = array('acciones' => array());
$botonesExtra[] = $appForm->button('Limpiar', array('id' => 'cleanup_report', 'title' => 'Limpiar', 'onclick' => 'location.replace("' . $this->action . '")'));
$botonesExtra[] = $appForm->submit('Generar', array('title' => $options['title'], 'onclick' => 'document.getElementById("accion").value="generar"'));
echo $this->element('index/index', array(
                    'opcionesTabla' => array('tabla' => array('omitirMensajeVacio' => true)),
                    'botonesExtra'  => array('opciones' => array('botones' => $botonesExtra)),
                    'accionesExtra' => $accionesExtra,
                    'opcionesForm'  => array('action' => $this->action),
                    'condiciones'   => $fieldset,
                    'cuerpo'        => null));
?>