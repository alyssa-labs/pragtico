<?php
/**
 * Este archivo contiene toda la logica de negocio asociada a los documentos modelo del sistema.
 *
 * PHP versions 5
 *
 * @filesource
 * @copyright		Copyright 2005-2008, Pragmatia de RPB S.A.
 * @link            http://www.pragmatia.com
 * @package         pragtico
 * @subpackage      app.controllers
 * @since           Pragtico v 1.0.0
 * @version         $Revision: 1042 $
 * @modifiedby      $LastChangedBy: mradosta $
 * @lastmodified    $Date: 2009-10-01 15:50:25 -0300 (jue 01 de oct de 2009) $
 * @author          Martin Radosta <mradosta@pragmatia.com>
 */
/**
 * La clase encapsula la logica de negocio asociada a los documentos modelo del sistema.
 *
 * @package     pragtico
 * @subpackage  app.controllers
 */
class DocumentosController extends AppController {

    var $paginate = array(
        'order' => array(
            'Documento.nombre' => 'asc'
        )
    );

    function patterns($id) {
        $this->Documento->contain("DocumentosPatron");
        $this->data = $this->Documento->read(null, $id);
    }
    
    
/**
 * Permite la generacion de un documento a partir de una plantilla rtf.
 */
	function generar($id = null) {
        
        if (!empty($this->data['Documento']['id'])) {
			$documentos = $this->Documento->find('all',
					array('conditions' => array('Documento.id' => $this->data['Documento']['id'])));
		} elseif (!empty($this->params['named']['model'])) {
			$documentos = $this->Documento->find('all',
					array('conditions' => array('Documento.model' => $this->params['named']['model'])));
		} elseif (!empty($this->data['Formulario']['accion'])) {
            $documentos = $this->Documento->find('all',
                    array('conditions' => array('Documento.model' => $this->data['Formulario']['accion'])));
        }

		if (empty($id)) {
			if (!empty($this->params['data']['seleccionMultiple'])) {
				$id = $this->Util->extraerIds($this->params['data']['seleccionMultiple']);
			} elseif (!empty($this->data['seleccionMultiple'])) {
                $id = $this->Util->extraerIds($this->data['seleccionMultiple']);
            }
		}

		if (!empty($documentos)) {

			if (count($documentos) > 1) {
				$this->set('id', $id);
				$this->set('documentos', Set::combine($documentos, '{n}.Documento.id', '{n}.Documento.nombre'));
			} else {

                if (!empty($this->data['Model']['id'])) {
                    $id = $this->data['Model']['id'];
                }
                
				/** When just one document per model, use it */
				$Model = ClassRegistry::init($documentos[0]['Documento']['model']);
				if (!empty($documentos[0]['Documento']['contain'])) {
					$Model->contain($documentos[0]['Documento']['contain']);
				}
                
				$data = $Model->find('first', array('conditions' => array($Model->name . '.' . $Model->primaryKey => $id)));
                
				$reemplazarTexto['texto'] = Set::combine($documentos[0]['DocumentosPatron'], '{n}.identificador', '{n}.patron');
				$reemplazarTexto['reemplazos'] = $data;

				$archivo['data'] = $documentos[0]['Documento']['file_data'];
				$archivo['type'] = $documentos[0]['Documento']['file_type'];
				$archivo['name'] = $documentos[0]['Documento']['id'] . '-' . $this->Util->getFileName($documentos[0]['Documento']['nombre'], $documentos[0]['Documento']['file_type']);

				$this->set('reemplazarTexto', $reemplazarTexto);
				$this->set('archivo', $archivo);
				$this->render('/elements/descargar', 'descargar');
			}
		} else {
			$this->Session->setFlash(__('No document found. Please verify', true), 'error');
			$this->History->goBack();
		}
	}


/**
 * Gets file extension based on it's mime type.
 *
 * @param $type string Mime type of the file
 * @return string File extension.
 * @access private
 */
	function __getExtension($type) {
        App::import('Vendor', 'files', 'pragmatia');
        $File = new Files();
		return $File->getType($type);
	}


/**
 * Quita los campos propios del framework, ya que el usuario no tiene porque verlos, lo confunde.
 */
	function __removerFrameworkData_deprecated($array) {
		if (!is_array($array)) {
			$array;
		}

		$removeKey = array('user_id', 'role_id', 'group_id', 'permissions', 'write', 'delete', 'created', 'modified', 'file_data', 'file_size', 'file_type');
		foreach ($array as $k=>$v) {
			if (is_array($v)) {
				foreach ($v as $k1=>$v1) {
					if (is_array($v1)) {
						foreach ($v1 as $k2=>$v2) {
							if (is_array($v2)) {
								foreach ($v2 as $k3=>$v3) {
									if (in_array($k3, $removeKey)) {
										unset($array[$k][$k1][$k2][$k3]);
									}
								}
							}
							elseif (in_array($k2, $removeKey)) {
								unset($array[$k][$k1][$k2]);
							}
						}
					}
					elseif (in_array($k1, $removeKey)) {
						unset($array[$k][$k1]);
					}
				}
			}
			elseif (in_array($k, $removeKey)) {
				unset($array[$k]);
			}
		}
		return $array;
	}


/**
 * Si lo subio correctamente, lo graba en la session para luego poder hacer un preview.
 */
    function __getFile() {

        if (!empty($this->data['Documento']['archivo']) && is_uploaded_file($this->data['Documento']['archivo']['tmp_name'])) {
            if (isset($this->data['Documento']['archivo']['error']) && $this->data['Documento']['archivo']['error'] === 0) {
                $fileName = basename($this->data['Documento']['archivo']['tmp_name']);
                copy($this->data['Documento']['archivo']['tmp_name'], TMP . $fileName);
                return $fileName;
            } else {
                $this->Documento->dbError['errorDescripcion'] = 'El archivo no se subio correctamente. Intentelo nuevamente.';
                return false;
            }
        }
    }


/**
 * El metodo add debe primero buscar los patrones dentro del documento que proporciono el usuario, luego
 * presentara un preview de los patrones encontrados, y si el usuario lo confirma, se graba.
 */
	function save() {
        if (isset($this->data['Form']['confirmar'])) {
			return parent::save();
        } elseif ($fileName = $this->__getFile()) {
			$extension = $this->__getExtension($this->data['Documento']['archivo']['type']);
			$this->data['DocumentosPatron'] = $this->Documento->getPatternsFromFile(TMP . $fileName, $extension);
			if (!empty($this->data['DocumentosPatron'])) {
				$this->data['Documento']['file_name'] = $fileName;
				$this->data['Documento']['file_type'] = $this->data['Documento']['archivo']['type'];
				$this->data['Documento']['file_size'] = $this->data['Documento']['archivo']['size'];
				$this->data['Documento']['file_extension'] = $extension;
			} else {
				$this->Session->setFlash('No se encontraron patrones en el archivo origen. Verifiquelo y reintentelo nuevamente.', 'error');
			}
		}
		$this->render('add');
	}


	function beforeRender() {
		if (in_array($this->action, array('add', 'edit', 'save'))) {
			$modelsTmp = Configure::listObjects('model');
			sort($modelsTmp);
			foreach ($modelsTmp as $v) {
				$models[$v] = $v;
			}
			$this->set('models', $models);
		}
		return parent::beforeRender();
	}


/**
 * Download document.
 */
    function download($id) {
		
		$documento = $this->Documento->findById($id);
        $this->view = 'Media';
        $params = array(
              'id' => $this->Documento->getFileName($id, $documento['Documento']['nombre'], $documento['Documento']['file_extension'], false),
              'name' => Inflector::classify(strtolower(str_replace(' ', '_', $documento['Documento']['nombre']))),
              'download' => true,
              'extension' => $documento['Documento']['file_extension'],
              'path' => WWW_ROOT . 'files' . DS . 'documents' . DS
        );
       	$this->set($params);
    }

    
}
?>