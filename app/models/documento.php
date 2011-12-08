<?php
/**
 * Este archivo contiene toda la logica de acceso a datos asociada a los documentos modelo del sistema.
 *
 * PHP versions 5
 *
 * @filesource
 * @copyright		Copyright 2005-2008, Pragmatia de RPB S.A.
 * @link            http://www.pragmatia.com
 * @package         pragtico
 * @subpackage      app.models
 * @since           Pragtico v 1.0.0
 * @version         $Revision: 811 $
 * @modifiedby      $LastChangedBy: mradosta $
 * @lastmodified    $Date: 2009-07-31 15:56:04 -0300 (vie 31 de jul de 2009) $
 * @author          Martin Radosta <mradosta@pragmatia.com>
 */
/**
 * La clase encapsula la logica de acceso a datos asociada a los documentos modelo del sistema.
 *
 * @package     pragtico
 * @subpackage  app.models
 */
class Documento extends AppModel {

    var $permissions = array('permissions' => 508, 'group' => 'none', 'role' => 'higher');

	var $validate = array(
        'nombre' => array(
			array(
				'rule'		=> VALID_NOT_EMPTY,
				'message'	=> 'Debe especificar el nombre del documento.')
        ),
        'model' => array(
			array(
				'rule'		=> VALID_NOT_EMPTY,
				'message'	=> 'Debe seleccionar el Model asociado.')
        )
	);

    var $hasMany = array('DocumentosPatron' => array('dependent' => true));
    

/**
 * After save, moves the uploaded file to documents directory.
 */	
	function afterSave($created) {
		copy(TMP . $this->data['Documento']['file_name'], $this->getFileName($this->id, $this->data['Documento']['nombre'], $this->data['Documento']['file_extension']));
		@unlink(TMP . $this->data['Documento']['file_name']);
	}
	

/**
 * Before delete, saves the current record.
 */			
	function beforeDelete() {
		$this->__document = $this->findById($this->id);
		return parent::beforeDelete();
	}


/**
 * After delete, deletes file from file system
 */	
	function afterDelete() {
		@unlink($this->getFileName($this->__document['Documento']['id'], $this->__document['Documento']['nombre'], $this->__document['Documento']['file_extension']));
		return parent::afterDelete();
	}
	

/**
 * Returns file name.
 */		
	function getFileName($id, $name, $extension, $path = true) {
		if ($path === true) {
			return WWW_ROOT . 'files' . DS . 'documents' . DS . $id . '-' . Inflector::classify(strtolower(str_replace(' ', '_', $name))) . '.' . $extension;
		} else {
			return $id . '-' . Inflector::classify(strtolower(str_replace(' ', '_', $name))) . '.' . $extension;
		}
	}


/**
 * Extract patters from file based on it's mime type.
 *
 * @param $file String File name.
 * @param $extension String file extension.
 * @return array Array of patters found in file.
 * @access private
 */
    function getPatternsFromFile($file, $extension) {

        switch ($extension) {
			case 'rtf':
			case 'txt':
				return $this->getPatterns(file_get_contents($file));
				break;	
            case 'xls':
            case 'xlsx':
                set_include_path(get_include_path() . PATH_SEPARATOR . APP . 'vendors' . DS . 'PHPExcel' . DS . 'Classes');
                App::import('Vendor', 'IOFactory', true, array(APP . 'vendors' . DS . 'PHPExcel' . DS . 'Classes' . DS . 'PHPExcel'), 'IOFactory.php');
                
                if ($extension === 'xls') {
                    $objReader = PHPExcel_IOFactory::createReader('Excel5');
                } else {
                    $objReader = PHPExcel_IOFactory::createReader('Excel2007');
                }
				//$objReader->setReadDataOnly(true);
                $objPHPExcel = $objReader->load($file);
                $worksheet = $objPHPExcel->getActiveSheet();
                $lastRow = $worksheet->getHighestRow();
                $lastCol = $worksheet->getHighestColumn();
                $cells = $worksheet->getCellCollection();
				//d($worksheet->getStyle('C14'));
                $texto = '';
                for ($row = 1; $row <= $lastRow; $row++){
                    for ($col = 'A'; $col <= $lastCol; $col++){
                        $cell = $col . $row;
                        if (isset($cells[$cell])) {
							//d($cells[$cell]->getStyle());
                            $tmp = $cells[$cell]->getValue();
                            if (preg_match('/(#\*.+\*#)/', $tmp, $mathes)) {
                                $documentosPatron[] = array('identificador' => $cell, 'patron' => $mathes[1]);
                            }
                        }
                    }
                }
                return $documentosPatron;
        }
		return false;
    }
	
}
?>