<?php
/**
 * Este archivo contiene toda la logica de acceso a datos asociada a las categorias.
 *
 * PHP versions 5
 *
 * @filesource
 * @copyright       Copyright 2007-2009, Pragmatia
 * @link            http://www.pragmatia.com
 * @package         pragtico
 * @subpackage      app.models
 * @since           Pragtico v 1.0.0
 * @version         $Revision: 1109 $
 * @modifiedby      $LastChangedBy: mradosta $
 * @lastmodified    $Date: 2009-11-04 11:49:22 -0300 (mié 04 de nov de 2009) $
 * @author          Martin Radosta <mradosta@pragmatia.com>
 */
/**
 * La clase encapsula la logica de acceso a datos asociada a las categorias.
 *
 * Se refiere a las categorias (puestos) de los convenios colectivos.
 *
 * @package     pragtico
 * @subpackage  app.models
 */
class ConveniosCategoria extends AppModel {

    var $permissions = array('permissions' => 508, 'group' => 'none', 'role' => 'higher');
    
    var $breadCrumb = array('format'    => '%s de %s',
                            'fields'    => array('ConveniosCategoria.nombre', 'Convenio.nombre'));

	/**
	* Establece modificaciones al comportamiento estandar de app_controller.php
	*/
	var $modificadores = array('index'=>array('contain'=>array('Convenio', 'ConveniosCategoriasHistorico')));
	
	var $validate = array(
        'nombre' => array(
			array(
				'rule'		=> VALID_NOT_EMPTY, 
				'message'	=> 'Debe especificar el nombre de la categoria.')
        ),
        'convenio_id' => array(
			array(
				'rule'		=> VALID_NOT_EMPTY, 
				'message'	=> 'Debe seleciconar un conveio.')
        ),
        'jornada' => array(
			array(
				'rule'		=> VALID_NOT_EMPTY, 
				'message'	=> 'Debe seleccionar el tipo de jornada.')
        )        
	);

	var $belongsTo = array('Convenio');
	
	var $hasMany = array(	'Relacion' =>
                        array('className'    => 'Relacion',
                              'foreignKey'   => 'convenios_categoria_id'),
							'ConveniosCategoriasHistorico' =>
                        array('className'    => 'ConveniosCategoriasHistorico',
                              'foreignKey'   => 'convenios_categoria_id'));

	
	function afterFind($results, $primary = false) {
		if ($primary) {
			foreach ($results as $k=>$v) {
				if (isset($v['ConveniosCategoriasHistorico'])) {
					$results[$k]['ConveniosCategoria']['costo'] = $this->__getCosto($v['ConveniosCategoriasHistorico']);
				}
			}
		} else {
			if (!empty($results['ConveniosCategoriasHistorico'])) {
				$results['costo'] = $this->__getCosto($results['ConveniosCategoriasHistorico']);
			} else {
				foreach ($results as $k=>$v) {
					if (Set::check($v, 'ConveniosCategoria.0')) {
						foreach ($v['ConveniosCategoria'] as $k1=>$v1) {
							if (is_numeric($k1) && isset($v1['ConveniosCategoriasHistorico'])) {
								if (is_array($v1)) {
									$results[$k]['ConveniosCategoria'][$k1]['costo'] = $this->__getCosto($v1['ConveniosCategoriasHistorico']);
								}
							}
						}
					}
				}
			}
		}
		return parent::afterFind($results, $primary);
	}



	function __getCosto($data) {
		$costo = 0;
		$hoy = date('Y-m-d');
		foreach ($data as $v) {
			if ($v['desde'] <= $hoy && ($v['hasta'] >= $hoy || $v['hasta'] == '0000-00-00')) {
				$costo = $v['costo'];
				break;
			}
		}
		return $costo;
	}
}

?>