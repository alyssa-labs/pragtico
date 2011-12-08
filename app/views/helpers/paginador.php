<?php
/**
 * Helper que me facilita la paginacion.
 *
 * Permite simplificar la creacion de los links para la nevagacion.
 *
 * PHP versions 5
 *
 * @filesource
 * @copyright		Copyright 2005-2008, Pragmatia de RPB S.A.
 * @link            http://www.pragmatia.com
 * @package         pragtico
 * @subpackage      app.views.helpers
 * @since           Pragtico v 1.0.0
 * @version			$Revision$
 * @modifiedby		$LastChangedBy$
 * @lastmodified	$Date$
 * @author          Martin Radosta <mradosta@pragmatia.com>
 */
/**
 * Clase que contiene el helper para la paginacion.
 *
 * @package     pragtico
 * @subpackage  app.views.helpers
 */
class PaginadorHelper extends AppHelper {

/**
 * Los helpers que utilizare.
 *
 * @var arraya
 * @access public.
 */
	var $helpers = array('Paginator', 'AppForm');

	
/**
 * Generea el link que permite ordenar una columna.
 *
 * @param  string $title Titulo del link (lo que se mostrara).
 * @param  string $key El nombre de la clave del recordset que debe ordenarse.
 * @param  array $options Las opciones posibles para el orden.
 * @return string Un link que permitira ordenar una columna en forma ascendente en forma predeterminada.
 * @access public.
 */
	function sort($title, $key = null, $options = array()) {
		
        $options['url'] = array();
        $options['title'] = __('No order', true);
        $options['class'] = 'sin_orden';
        if ($options['model'] . '.' . $key == $this->Paginator->sortKey()) {
            $dir = $this->Paginator->sortDir();
            if (!empty($dir)) {
                $options['class'] = $dir . '_orden';
                $options['title'] = __(ucfirst($dir) . 'ending order', true);
            }
        }
		$model = $options['model'];
		unset($options['model']);
		return $this->Paginator->sort($title, $model . '.' . $key, $options);
	}
	
	
/**
 * Generea un bloque con los objetos propios del paginador (posicion dentro del recordset y flechas de navegacion).
 *
 * @param  string $accion Indica el bloque que se generara:
 *						- posicion		(el numero de registro, de pagina, ...)
 *						- navegacion 	(las flechas)
 * @param  array $options Las opciones posibles para la creacion del bloque.
 * @return string Un bloque HTML.
 * @access public.
 */
	function paginador($accion = 'posicion', $opciones = array()) {
		/**
		* Si no estan seteadas la variables de la paginacion, no hago nada con el paginador.
		*/
		$model = Inflector::classify($this->Paginator->params['controller']);
		if (empty($this->Paginator->params['paging'][$model]['count'])) {
			return '';
		}
		
		switch ($accion) {
			case 'posicion':
				return $this->Paginator->counter(array('format'=>__('page %page% of %pages%. %count% records.', true)));
			break;
			
			case 'navegacion':

				$out = null;

                if (!empty($this->Paginator->params['isAjax'])) {
                    $this->Paginator->options['update'] = '#lov';
                }
                
                $opciones['escape'] = false;
				if ($this->Paginator->hasPrev()) {
					$out[] = $this->Paginator->link($this->AppForm->image('primera.gif', array('alt' => __('Go to first page', true))), array('page' => 1), $opciones);
                    $out[] = $this->Paginator->prev($this->AppForm->image('anterior.gif', array('alt' => __('Go to previews page', true))), $opciones);
				} else {
                    $out[] = $this->AppForm->tag('div', $this->AppForm->image('primeraoff.gif'), array('class' => 'paginator_prev_disabled'));
                    $out[] = $this->AppForm->tag('div', $this->AppForm->image('anterioroff.gif'), array('class' => 'paginator_prev_disabled'));
				}
				
                if ($this->Paginator->hasNext()) {
                    $out[] = $this->Paginator->next($this->AppForm->image('siguiente.gif', array('alt' => __('Go to next page', true))), $opciones);
                    $out[] = $this->Paginator->link($this->AppForm->image('ultima.gif', array('alt' => __('Go to last page', true))), array('page' => $this->Paginator->params['paging'][$model]['pageCount']), $opciones);
                } else {
                    $out[] = $this->AppForm->tag('div', $this->AppForm->image('siguienteoff.gif'));
                    $out[] = $this->AppForm->tag('div', $this->AppForm->image('ultimaoff.gif'));
                }

				return implode('', $out);
			break;
		}
	}
}
?>