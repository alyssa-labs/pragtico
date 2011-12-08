<?php
/**
 * Helper para la creacion de la capa de presentacion.
 *
 * Permite la creacion de todos los componentes html necesarios para la presentacion.
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
App::import('Helper', 'Form');
/**
 * Helper para la creacion de la capa de presentacion.
 *
 * @package     pragtico
 * @subpackage  app.views.helpers
 */
class AppFormHelper extends FormHelper {
	
/**
 * Los helpers que utilizare.
 *
 * @var arraya
 * @access public.
 */
	var $helpers = array('Html', 'Ajax', 'Session', 'Javascript', 'Paginador', 'Formato');
	
	
/**
 * Adds a link to the breadcrumbs array.
 *
 * @param string $name Text for link
 * @param string $link URL for link (if empty it won't be a link)
 * @param mixed $options Link attributes e.g. array('id' => 'selected')
 */
	function addCrumb($name, $link = null, $options = null) {
		$this->Html->addCrumb($name, $link, $options);
	}
	
	
/**
 * Returns the breadcrumb trail as a sequence of &raquo;-separated links.
 *
 * @param  string  $separator Text to separate crumbs.
 * @param  string  $startText This will be the first crumb, if false it defaults to first crumb in array
 * @return string
 */
	function getCrumbs($separator = '&nbsp;&raquo;&nbsp;', $startText = false) {
		return $this->Html->getCrumbs($separator, $startText);
	}
	
	
/**
 * Returns a formatted block tag, i.e DIV, SPAN, P.
 *
 * @param string $name Tag name.
 * @param string $text String content that will appear inside the div element.
 *			If null, only a start tag will be printed
 * @param array $attributes Additional HTML attributes of the DIV tag
 * @param boolean $escape If true, $text will be HTML-escaped
 * @return string The formatted tag element
 */
	function tag($name, $text = null, $attributes = array(), $escape = false) {
		if (is_array($text)) {
			$text = implode("", $text);
		}
		$out = $this->Html->tag($name, $text, $attributes, $escape);
		return $out;
	}
	
	
/**
 * Genera el codigo HTML correspondiente a un fieldset.
 *
 * @param array $fieldsets Los campos que debe inlcuir el fieldset.
 * @param array $opcionesFs Las opciones con las que se dibujara el fieldset.
 *		- $opcionesFs['fieldset']['legend']	=> 	String con la leyenda que tendra el fieldset. Si se especifica
 *												como !Leyenda, se tomara el string literalmente. En caso de que
 *												no se especifique precedida por !, se le concatenara precedida por
 *												la accion realizada en el momento.
 *												Si se deja en blanco se generara a partir de la accion y el model.
 *
 * @return string El codigo HTML del fieldset.
 */
	function pintarFieldsets($fieldsets, $opcionesFs = array()) {

		/**
		* En caso de que no valide, me vendra seteada la accion en el Form, y esto causara un error.
		* Por esto, tomo el recaudo y los desseteo, por si a caso....
		*/
		unset($this->data['Form']);
		
		/**
		* Busco el nombre de la clase del model a partir del controller,
		* que es con lo que cuento dentro del helper.
		*/
		$model = Inflector::classify($this->params["controller"]);

		/**
		* Genero la leyenda.
		*/
		$legend = '';
		if ($this->action === 'edit') {
			$legend = __('Edit', true) . ' ';
		} elseif ($this->action === 'add') {
			$legend = __('New', true) . ' ';
		} elseif ($this->action === 'index') {
			$legend = __('Search', true) . ' ';
		}
		if (!empty($opcionesFs['fieldset']['legend'])) {
			if ($opcionesFs['fieldset']['legend'][0] !== '!') {
				$opcionesFs['fieldset']['legend'] = $legend . ' ' . $opcionesFs['fieldset']['legend'];
			} else {
				$opcionesFs['fieldset']['legend'] = substr($opcionesFs['fieldset']['legend'], 1);
			}
		} else {
			$opcionesFs['fieldset']['legend'] = $legend . ' ' . $model;
		}


		/**
		* Me aseguro de trabajar siempre con un array. Si no lo es, lo convierto en uno.
		*/
		if (!isset($this->data[0])) {
			$this->data = array($this->data);
		}
		/**
		* Separo los fieldSets de master de los de details.
		*/
		foreach ($fieldsets as $fieldset) {
			$classes = null;
			if (empty($fieldset['opciones'])) {
				$fieldset['opciones'] = array();
			}
			
			if (empty($fieldset['opciones']['fieldset']['class']) || strpos('master', $fieldset['opciones']['fieldset']['class']) !== false) {
				if (empty($fieldset['opciones']['fieldset']['class'])) {
					$fieldset['opciones']['fieldset']['class'] = 'master';
				}
				$fieldsetsMaster[] = $fieldset;
				$classes[] = 'master';
			} else {
				$fieldsetsDetail[] = $fieldset;
				$classes[] = 'detail';
			}
		}

		$salida = null;
		$cantidadRegistros = count($this->data);
		foreach ($this->data as $k=>$v) {

			foreach ($fieldsetsMaster as $key=>$fieldset) {
				$salidaMaster = null;
				foreach ($fieldset['campos'] as $campo=>$opcionesCampo) {

					if (substr($campo, 0, 9) === 'Condicion') {
						$tmpName = $campo;
						$tmpName = preg_replace('/^Condicion./', '', $tmpName);
						list($model, $field) = explode('-', $tmpName);

						if (substr($field, strlen($field) - 7) === '__desde') {
							$field = str_replace('__desde', '', $field);
						} elseif (substr($field, strlen($field) - 7) === '__hasta') {
							$field = str_replace('__hasta', '', $field);
						}
					} else {
						list($model, $field) = explode('.', $campo);
					}

					if (isset($v[$model][$field]) && !isset($opcionesCampo['value'])) {
						$opcionesCampo['value'] = $v[$model][$field];
					}

					if (!empty($this->validationErrors[$model][$k][$field]) || !empty($this->validationErrors[$model][$k][$model][$field])) {
						if (empty($opcionesCampo['after'])) {
							$opcionesCampo['after'] = '';
						}
						if (!empty($this->validationErrors[$model][$k][$field])) {
							$opcionesCampo['after'] .= $this->tag('div', $this->validationErrors[$model][$k][$field], array('class' => 'error-message'));
						} else {
							$opcionesCampo['after'] .= $this->tag('div', $this->validationErrors[$model][$k][$model][$field], array("class" => "error-message"));
						}
					}
					/**
					* Si no se trata de una edicionMultiple o un formulario en el que haya mas de un fs,
					* no la complico con arrays y me manejo con la forma de cakePHP.
					*/
					if ($cantidadRegistros > 1) {
						$opcionesCampo['name'] = "data[" . $k . "][" . $model . "][" . $field . "]";
					}
					$this->data = $v;
					$salidaMaster .= $this->input($campo, $opcionesCampo);
				}
				$fsMaster[$k][] = $this->bloque($salidaMaster, $fieldset['opciones']);
				//$fsMaster[$k][] = $this->tag('div', $salidaMaster, $fieldset['opciones']);
				$salidaMaster = null;

				if (!empty($fieldsetsDetail)) {
					$salidaDetail = null;
					foreach ($fieldsetsDetail as $key=>$fieldset) {
						$modelsDetail = null;
						foreach ($fieldset['campos'] as $campo=>$opcionesCampoDetail) {
							list($modelDetail, $fieldDetail) = explode(".", $campo);
							$modelsDetail[$modelDetail] = $modelDetail;
						}
						
						foreach ($modelsDetail as $modelDetail) {
							/**
							* Cuando sea un nuevo registro, esto estara vacio. Lo creo vacio para que de una vuelta.
							*/
							if (empty($v[$modelDetail])) {
								$v[$modelDetail] = array(array());
							}
							
							foreach ($v[$modelDetail] as $kDetail=>$vDetail) {
								$this->data = $vDetail;
								foreach ($fieldset['campos'] as $campo=>$opcionesCampoDetail) {
									list($modelDetail, $fieldDetail) = explode(".", $campo);
									$opcionesCampoDetail['error'] = false;
									if (!empty($this->validationErrors[$model][$k][$modelDetail][$kDetail][$fieldDetail])) {
										if (empty($opcionesCampoDetail['after'])) {
											$opcionesCampoDetail['after'] = "";
										}
										$opcionesCampoDetail['after'] .= $this->Html->tag("div", $this->validationErrors[$model][$k][$modelDetail][$kDetail][$fieldDetail], array("class" => "error-message"));
									}

									if (isset($vDetail[$fieldDetail]) && !isset($opcionesCampoDetail['value'])) {
										$opcionesCampoDetail['value'] = $vDetail[$fieldDetail];
									}
									if ($cantidadRegistros > 1) {
										$opcionesCampoDetail['name'] = "data[" . $k . "][" . $modelDetail . "][" . $kDetail . "][" . $fieldDetail . "]";
									} else {
										$opcionesCampoDetail['name'] = "data[" . $modelDetail . "][" . $kDetail . "][" . $fieldDetail . "]";
                                        $opcionesCampoDetail['id'] = $modelDetail . Inflector::Classify($fieldDetail) . '_' . $kDetail;
									}
									$salidaDetail .= $this->input($campo, $opcionesCampoDetail);
								}
								$fsDetail[$k][] = $this->bloque($salidaDetail, $fieldset['opciones']);
								$salidaDetail = null;
							}
						}
					}
				}
			}
		}

		if (count($fsMaster) > 1) {
			if (empty($opcionesFs['fieldset']['legend'])) {
				$legend = " (Registro ##NUMERO## de " . count($fsMaster) . ")";
			}
			else {
				$legend = $opcionesFs['fieldset']['legend'] . " (Registro ##NUMERO## de " . count($fsMaster) . ")";
			}
		}
		
		$salida = null;
		foreach ($fsMaster as $k=>$v) {
			$return = null;
			foreach ($v as $contenidoMaster) {
				$return .= $contenidoMaster;
			}
			if (!empty($fsDetail[$k])) {
				foreach ($fsDetail[$k] as $contenidoDetail) {
					$return .= $contenidoDetail;
				}
			}
			if (count($fsMaster) > 1) {
				$k++;
				$opcionesFs['fieldset']['legend'] = str_replace("##NUMERO##", $k, $legend);
			}
			$opcionesFs['fieldset']['class'] = "fieldset_multiple";
			$salida .= $this->bloque($return, $opcionesFs);
		}
		return $salida;
	}



/**
 * Agrega a una variable privada (mia) de la clase View codigos Js.
 * Los puede searar en tres posibles ubicaciones/tipos donde iran (siempre al header):
 *			- ready:	Va a la funcion ready de JS.
 */
	
	function addScript($script) {
		ClassRegistry::getObject('view')->__jsCodeForReady[] = $script;
	}
	

/**
 * Returns a JavaScript script tag.
 *
 * @param  string $script The JavaScript to be wrapped in SCRIPT tags.
 * @param  boolean $allowCache Allows the script to be cached if non-event caching is active
 * @param  boolean $safe Wraps the script in an HTML comment and a CDATA block
 * @return string The full SCRIPT element, with the JavaScript inside it.
 */
	function codeBlock($script = null, $options = array()) {
		if (!empty($options['ready']) && $options['ready'] === true) {
			$script = "jQuery(document).ready(function($) {" . $script . "});";
		}
		if (isset($options['script']) && $options['script'] === false) {
			return $script;
		}
		
		return $this->Javascript->codeBlock($script, true, false);
	}


/**
 * Crea un elemento IMG formateado xhtml.
 *
 * Se encarga de validar que el archivo exista (si no existe, el browser demora buscandolo hasta que salta por timeout).
 * Tambien asegura que siempre una IMG tenga los atributos title y alt, por compatibilidad con ambos navegadores que los interpretan disferente.
 *
 * @param string $path Path a un archivo de image, relativo a el directorio webroot/img/.
 * @param array	$htmlAttributes Array de attributos HTML.
 * @return string
 */
	function image($path, $htmlAttributes = array()) {
		/**
		* Siempre las rutas y los archivos van en minusculas.
		*/
		$path = strtolower($path);

		/**
		* Me aseguro que el archivo de la imagen exista, sino pongo una por defecto.
		*/
		if (!file_exists(WWW_ROOT . IMAGES_URL . $path)) {
			$path = 'no_image.gif';
			$htmlAttributes['alt'] = 'no_image';
			$htmlAttributes['title'] = __('Non existing image', true);
		}

		/**
		* Me aseguro de que siempre tenga los atributos title y alt cuando tenga uno de ellos por lo menos.
		*/
		if (isset($htmlAttributes['alt']) && !isset($htmlAttributes['title'])) {
			$htmlAttributes['title'] = Inflector::humanize($htmlAttributes['alt']);
		} elseif (isset($htmlAttributes['title']) && !isset($htmlAttributes['alt'])) {
			$htmlAttributes['alt'] = Inflector::classify($htmlAttributes['title']);
		}

		return $this->Html->image($path, $htmlAttributes);
    }
    
    
/**
 * Crea una tabla html. Esta tabla tiene multiples opciones.
 *
 * @param array	$datos .
 *
 * Si no paso un array con los encabezados dentro del parametro "encabezado", y dentro de los atributos
 * de la tabla seteo a true el atributo "mostrarEncabezados" los saca de la lista de campos.
 *

 * Ejemplo del array de encabezados encaso de que lo quiera especificar manualmente.
 *
 * <code>
 * $encabezado[] = $paginador->sort('Campo 1', 'campo1', array("model" => "Model1"));
 * $encabezado[] = $paginador->sort('Campo 2', 'campo2', array("model" => "Model2"));
 * </code>
 *
 *
 * Ejemplo del array del cuerpo.
 *
 * <code>
 *
 *	$fila = array();
 *	$fila[] 	= array("model" => "Model1", "field" => "campo1", "valor" => "xx1");
 *	$fila[] 	= array("model" => "Model1", "field" => "campo1", "valor" => "xx1", "orden"=>false);
 *	$fila[] 	= array("model" => "Model2", "field" => "campo1", "valor" => "xxx2", "nombreEncabezado" => "Cod.");
 *	$fila[] 	= array("tipo" => "idDetail", "model" => "Model2", "field" => "campo1", "valor" => "xxx2", "nombreEncabezado" => "Cod.");
 *	$fila[] 	= array("tipo" => "accion", "model" => "Model2", "field" => "campo1", "valor" => "xxx2", "nombreEncabezado" => "Cod.");
 *	$fila[] 	= array("tipo" => "valor", "model" => "Model2", "field" => "campo1", "valor" => "xxx2", "nombreEncabezado" => "Cod.");
 *	$cuerpo[] = $fila;
 *  Los tipos pueden ser:
 *			- valor: el valor que tiene para pintarse.
 *			- accion: codigo HTML que se pintara (ejemplo, un link que dispare un accion X).
 *			- idDetail: un array (urls) con las urls de las acciones.
 *			- desglose: datos para generar un desglose.
 *
 * Puedo tambien querer enviar opciones a la fila, en cuyo caso queda asi:
 *  $cuerpo[] = array("contenido"=>$fila, 'opciones' => array("class" => "fila_resaltada", "seleccionMultiple"=>false, "eliminar"=>false, "modificar"=>false, "permisos"=>false));
 *
 * Ejemplo de uso de la funcion tabla.
 *
 * <code>
 * $tabla = $appForm->tabla(array(	"tabla"		=> array(	"class"				=>"grilla",
 *																"eliminar"			=>true,
 *																"seleccionLov"		=>false,
 *																"modificar"			=>true,
 *																"seleccionMultiple"	=>true,
 * 																"permisos"			=>true,
 *																"mostrarEncabezados"=>true,
 *																"ordenEnEncabezados"=>true,
 * 																"zebra"				=>true,
 * 																"simple"			=>false,
 *																"omitirMensajeVacio"=>false,
 *																"mostrarIds"		=>false),
 *									"encabezado" 	=> $encabezado,
 *									"cuerpo"		=> $cuerpo,
 * 									"pie"			=> $pie));
 * </code>
 * @return string
 */
	function tabla($datos = array()) {
		/**
		 * Default options.
		 */
		$opciones = array(	'seleccionLov'		=> false,
							'seleccionMultiple'	=> true,
							'permisos'			=> true,
							'eliminar'			=> true,
							'modificar'			=> true,
							'mostrarEncabezados'=> true,
							'ordenEnEncabezados'=> true,
							'mostrarIds'		=> false,
							'omitirMensajeVacio'=> false,
							'zebra'				=> true,
							'simple'			=> false);

		$tabla = "";
		
		if (!empty($datos['tabla'])) {
			$opciones = array_merge($opciones, $datos['tabla']);
		}

		if (isset($this->params['named']['seleccionMultiple']) && $this->params['named']['seleccionMultiple'] == 0) {
			$opciones['seleccionMultiple'] = false;
		}
		
		$opcionesHtmlValidas = array('class', 'colspan', 'id');
		$opcionesHtml = array();
		foreach ($opcionesHtmlValidas as $v) {
			if (isset($opciones[$v])) {
				$opcionesHtml[$v] = $opciones[$v];
			}
		}


		/**
		* Si es una tabla simple, no le pongo inteligencia, es decir,
		* pinto lo que me venga.
		*/
		if ($opciones['simple']) {
			foreach ($datos['cuerpo'] as $f) {
				$cells = $headers = array();
				foreach ($f as $columna) {
					if (empty($columna['type'])) {
						$columna['type'] = "cell";
					}
					if (empty($columna['opciones'])) {
						$columna['opciones'] = array();
					}
					
					if ($columna['type'] === "header") {
						$headers[] = array($columna['valor'], $columna['opciones']);
					}
					elseif ($columna['type'] === "cell") {
						$cells[] = array($columna['valor'], $columna['opciones']);
					}
				}
				
				if (!empty($headers)) {
					$filaHeaders[] = $headers;
				}
				
				if (!empty($cells)) {
					$filaCells[] = $cells;
				}
			}
			
			/**
			* La funcion tableHeaders del framework no maneja multiples rows de header, por eso uso la funcion
			* tableCells y reemplazo los tds por ths.
			*/
			$out[] = str_replace("td", "th", $this->Html->tableCells($filaHeaders));
			$out[] = $this->Html->tableCells($filaCells);
			return $this->output("\n\n<table " . $this->_parseAttributes($opcionesHtml, null, '', '') . ">" . $this->output(join("\n", $out)) . "\n</table>\n\n");
			
		}
		
		$encabezados = array();
		if (!empty($datos['encabezado'])) {
			$encabezados = $datos['encabezado'];
		}
		
		if (!empty($datos['cuerpo'])) {
			$cuerpo = array();
			$view = ClassRegistry::getObject('view');
			$modelName = Inflector::classify($this->params['controller']);
			$Model = ClassRegistry::getObject($modelName);
			
			
			if ($opciones['permisos']) {
				/**
				* Lo agrego al array del cuerpo de la tabla.
				*/
				foreach ($datos['cuerpo'] as $kk=>$vv) {
					/**
					* El contenido de la fila puede venir como un array puro o dentro del elemento contenido.
					*/
					$opcionesFila = array();
					if (!empty($vv['opciones'])) {
						$opcionesFila = $vv['opciones'];
					}

					/**
					* Agrego el desglose de los permisos.
					*/
					if (!(isset($opcionesFila['permisos']) && $opcionesFila['permisos'] === false)) {
						$contenido = false;
						if (!empty($vv['contenido'])) {
							$vv = $vv['contenido'];
							$contenido = true;
						}
						foreach ($vv as $kk1=>$vv1) {
							if (isset($vv1['field']) && $vv1['field'] === "id" && !empty($vv1['valor'])) {
								$registroPermisos = array(
									"tipo"		=> "desglose",
									"id"		=> $vv1['valor'],
									"imagen"	=> array('nombre'	=> 'permisos.gif',
									"alt"		=> "Permisos"),
									"url"		=> array(	'controller' 	=> strtolower(Inflector::pluralize(Inflector::underscore($vv1['model']))),
															'action' 		=> 'permisos'));
								if ($contenido === true) {
									array_unshift($datos['cuerpo'][$kk]['contenido'], $registroPermisos);
								} else {
									array_unshift($datos['cuerpo'][$kk], $registroPermisos);
								}
							}
						}
					}
				}
			}
			
			foreach ($datos['cuerpo'] as $k => $v) {
				/**
				* El contenido de la fila puede venir como un array puro o dentro del elemento contenido.
				*/
				$opcionesFila = array();
				if (!empty($v['opciones'])) {
					$opcionesFila = $v['opciones'];
					/**
					* Si por parametros me dice que no se permite la seleccion multiple, prevalece a la especificacion
					* de la tabla y de la fila.
					*/
					if (isset($this->params['named']['seleccionMultiple']) && $this->params['named']['seleccionMultiple'] == 0) {
						$opcionesFila['opciones']['seleccionMultiple'] = false;
					}
					
				}
				if (!empty($v['contenido'])) {
					$v = $v['contenido'];
				}
				
				$cellsOut = array();
				$outDesgloses = array();

				$acciones = array();
				foreach ($v as $campo) {
					$valor = "&nbsp;";
					$atributosCelda = null;
					
					if (isset($campo['valor']) && $campo['valor'] !== '0') {
						$valor = $campo['valor'];
					}
					
					if (!isset($campo['tipo']) || $campo['tipo'] === "datos") {
						$tipoCelda = "datos";
						if (isset($campo['model'])) {
							$modelKey = $campo['model'];
						}
						if (isset($campo['field'])) {
							$nombreCampo = $campo['field'];
						}
					}
					else {
						$tipoCelda = $campo['tipo'];
					}

					if ($tipoCelda === "accion") {
						$acciones[] = $valor;
						continue;
					}
					elseif ($tipoCelda === "idDetail"){
						$detailUrls = $campo['urls'];
						continue;
					}
					elseif ($tipoCelda === "desglose") {

						if (isset($campo['imagen']['nombre'])) {
							$nombre = $campo['imagen']['nombre'];
							unset($campo['imagen']['nombre']);
						}
						
						$url = null;
						if (is_string($campo['url'])) {
							$url['controller'] = $this->params['controller'];
							$url['action'] = $campo['url'];
						} else {
							$url = $campo['url'];
						}
						$url[] = $campo['id'];
						
						if (!isset($campo['bread_crumb'])) {
							$campo['bread_crumb'] = '';
						}

						if (!empty($view->viewVars['registros'])) {
							$breadCrumbData = $Model->getCrumb(array_pop(Set::extract('/' . $modelName . '[id=' . $campo['id'] . ']/..', $view->viewVars['registros'])));
							$title = sprintf(__('Show %s of %s', true), $campo['imagen']['alt'], $breadCrumbData);
						} else {
							$breadCrumbData = '';
							$title = sprintf(__('Show %s', true), $campo['imagen']['alt']);
						}
		
						$acciones[] = $this->image($nombre, array_merge($campo['imagen'], array(
								'title'		=> $title,
								'alt'		=> $breadCrumbData,
								'class'		=> 'breakdown_icon',
								'longdesc'	=> Router::url($url))));
						
						continue;
					}
					
					$atributos = array();
					foreach ($opcionesHtmlValidas as $opcionHtml) {
						if (isset($campo[$opcionHtml])) {
							$atributos[$opcionHtml] = $campo[$opcionHtml];
						}
					}
					if (!empty($modelKey)) {
						if ($k===0 && $opciones['mostrarEncabezados'] && empty($datos['encabezado'])) {
							/**
							* El parametro, en caso de ser una lov, viene o por url o en $this->data
							*/
							if (isset($this->params['pass']['retornarA']) && !empty($this->params['pass']['retornarA'])) {
								$params['url'] = array("retornarA"=>$opciones['seleccionLov']['retornarA'], "layout" => "lov");
							}
							elseif (isset($this->data['AppForm']['retornarA']) && !empty($this->data['AppForm']['retornarA'])) {
								$params['url'] = array("retornarA"=>$this->data['AppForm']['retornarA'], "layout" => "lov");
							}
							$params['model'] = $modelKey;

							if (isset($campo['nombreEncabezado'])) {
								$nombre = $campo['nombreEncabezado'];
							} else {
								$nombre = Inflector::humanize($nombreCampo);
							}
							
							if (!($nombreCampo === 'id' && !$opciones['mostrarIds'])) {
								if (isset($campo['orden']) && $campo['orden'] === false) {
									$encabezados[] = $nombre;
								}
								else {
									if ($opciones['ordenEnEncabezados']) {
										$encabezados[] = $this->Paginador->sort($nombre, $nombreCampo, $params);
									} else {
										$encabezados[] = $nombre;
									}
								}
							}
						}

						$model = ClassRegistry::getObject($modelKey);
						if (!array_key_exists('class', $atributos) && is_object($model)) {
							$columnType = $model->getColumnType($nombreCampo);
							if (substr($columnType, 0, 5) == 'enum(') {
								$columnType = 'enum';
							}
						}
						if (!empty($campo['tipoDato'])) {
							$columnType = $campo['tipoDato'];
						}
						if (!empty($columnType)) {
							switch($columnType) {
								case 'moneda':
								case 'currency':
								case 'percentage':
									$clase = 'derecha';
									$valor = $this->Formato->format($valor, array('type' => $columnType));
									break;
								case 'enum':
									$clase = 'centro';
									break;
								case 'integer':
								case 'float':
								case 'decimal':
									$clase = 'derecha';
									break;
								case 'date':
								case 'datetime':
									$clase = 'centro';
                                    if ($valor === '0000-00-00' || $valor === '0000-00-00 00:00:00') {
                                        $valor = '';
                                    }
									break;
								case 'string':
								case 'text':
								default:
									$clase = 'izquierda';
									break;
							}
							if (empty($campo['class'])) {
								$atributos = array('class' => $clase);
							} else {
								$atributos = array('class' => $campo['class'] . ' ' . $clase);
							}
						}
						
						if ($nombreCampo === 'id') {
							$id = $valor;

							$controller = '';
							if (isset($this->params['url']['url'])) {
								$parse = Router::parse($this->params['url']['url']);
								$esController = strtolower(inflector::pluralize(inflector::underscore($campo['model'])));
								if ($parse['controller'] != $esController) {
									$controller = "../" . $esController . "/";
								}
							}

                            /** Only when in lov would be an ajax request */
                            if ($this->params['isAjax'] === true) {
                                $acciones[] = $this->image('seleccionar.gif', array('class' => 'seleccionar', 'alt' => __('Select current record', true)));
                            }
							
							if ($opciones['eliminar'] && (!(isset($opcionesFila['eliminar']) && $opcionesFila['eliminar'] === false))) {
								if ($campo['delete']) {
									$urlLink = $controller . "delete/" . $id;
									if (isset($detailUrls['delete'])) {
										$urlLink = $detailUrls['delete'];
									}
									array_unshift($acciones, $this->link($this->image('delete.gif', array("alt" => "Elimina este registro")), $urlLink, array(), "Esta seguro que desea eliminar el registro?", false));
								} else {
									array_unshift($acciones, $this->image('delete_disable.gif', array("alt" => "Elimina este registro")));
								}
							}
							
							if ($opciones['modificar'] && (!(isset($opcionesFila['modificar']) && $opcionesFila['modificar'] === false))) {
                                $urlLink = $controller . 'edit/' . $id;
								if ($campo['write']) {
									if (isset($detailUrls['edit'])) {
										$urlLink = $detailUrls['edit'];
									}
                                    $image = $this->image('edit.gif', array('alt' => 'Modifica este registro'));
								} else {
                                    $image = $this->image('edit_disable.gif', array('alt' => 'Muestra este registro'));
								}
                                array_unshift($acciones, $this->link($image, $urlLink));
							}
							
							/**
							* Puede que la tabla indique seleccion multiple, pero esta fila particular no.
							*/
							if ($opciones['seleccionMultiple'] && (!(isset($opcionesFila['seleccionMultiple']) && $opcionesFila['seleccionMultiple'] === false))) {
								/**
								* Si debo agregarlo, lo agrego al principio de todas las acciones.
								*/
								array_unshift($acciones,
                                              $this->input("seleccionMultiple.id_" . $id, array(
                                                        'type'  => 'checkbox',
                                                        'label' => false,
                                                        'class' => 'selection_lov',
                                                        'div'   => false)));
							}
							
							/**
							* Si debo mostrar los Ids y tengo alguna accion debo agregar una columna
							*/
							if ($opciones['mostrarIds'] && !empty($acciones)) {
								if (empty($atributos['class'])) {
									$atributos['class'] = 'derecha';
								}
							} else {
								$valor = 'NO PINTAR';
							}
						}
					}
					
					/**
					* Fuerzo $valor a string, porque si $valor = 0, no evaluara.
					*/
					if ($valor . "" !== "NO PINTAR") {
                        if (!empty($modelKey) && !empty($nombreCampo)) {
                            $atributos['axis'] = $modelKey . '.' . $nombreCampo;
                        }
						$cellsOut[] = array($valor, $atributos);
					}
				}

				if (!empty($acciones)) {
					array_unshift($cellsOut, array(implode("", $acciones), array("class" => "acciones")));
				}
				
				/**
				* Utilizo el atributo charoff de html para guardar el id del registro.
				* Lo uso porque este atributo es valido por la w3c (si pusiera un atributo id, por ejemplo,
				* se veria igual, aunque w3c no validaria) y si no se usa align char, no cambia nada.
				* ver: http://www.w3schools.com/tags/tag_tr.asp
				*/
				if (!empty($id)) {
					$atributosFila = array("class" => "fila_datos", "charoff"=>$id);
				}
				else {
					$atributosFila = array("class" => "fila_datos");
				}
				$atributosFila = array_merge($atributosFila, $opcionesFila);
				$rowsOut[] = $this->_fila($cellsOut, $atributosFila);

				/**
				* Si tengo desgloses, los agrego.
				if (!empty($outDesgloses)) {
					foreach ($outDesgloses as $outDesglose) {
						if (!empty($outDesglose['2'])) {
							$atributosFila = $outDesglose['2'];
							unset($outDesglose['2']);
						}
						$rowsOut[] = $this->_fila(array($outDesglose), $atributosFila);
					}
				}
				*/
			}
		}
		
		if (!empty($encabezados) && $opciones['mostrarEncabezados']) {
			if ($opciones['eliminar'] || $opciones['modificar'] || $opciones['seleccionMultiple'] || $opciones['seleccionLov'] || !empty($acciones)) {
			
				if ($opciones['seleccionMultiple']) {
					$seleccion[] = $this->link("T", null, array("class" => "seleccionarTodos")) . " ";
					$seleccion[] = $this->link("N", null, array("class" => "deseleccionarTodos")) . " ";
					$seleccion[] = $this->link("I", null, array("class" => "invertir"));
					$accionesString = $this->tag("div", implode("", $seleccion) . $this->tag('span', $this->tag('span', '', array('class' => 'hide_actions')) . 'Acciones'), array("class" => "acciones"));
				} else {
					$accionesString = "Acciones";
				}
				array_unshift($encabezados, $accionesString);
			}
			$tabla .= "\n<thead>\n" . $this->Html->tableHeaders($encabezados) . "\n</thead>";
		}

		if (!empty($rowsOut)) {
			$tabla .= $this->tag("tbody", implode("\n", $rowsOut));
		}
		elseif (!empty($encabezados)){
			$tabla .= $this->output("\n<tbody></tbody>");
		}

		/**
		* Si la tabla tiene un pie, lo agrego.
		*/
		if (!empty($datos['pie'])) {
			$out = array();
			$cellsOut = array();
			if (!empty($datos['cuerpo'][0])) {
				if (!empty($datos['cuerpo'][0]['contenido'])) {
					$v = $datos['cuerpo'][0]['contenido'];
				}
				else {
					$v = $datos['cuerpo'][0];
				}
				foreach ($v as $columna) {
					if (isset($columna['model'])) {
						foreach ($datos['pie'] as $k=>$pies) {
							foreach ($pies as $pie) {
								if ($columna['model'] == $pie['model'] && $columna['field'] == $pie['field']) {
									$cellsOut[] = $pie['valor'];
								}
							}
						}
					}
				}
			}
			if (!empty($cellsOut)) {
				$out[] = $this->Html->tableHeaders($cellsOut);
				$tabla .= "\n<tfoot>\n" . implode("", $out) . "\n</tfoot>";
			}
		}
		
		if (!empty($tabla)) {
			$tabla = $this->tag("table", $tabla, $opcionesHtml);
		}


		/**
		* Agrego codigo JS.
		*/

		/**
		* Escribo el codigo js (jquery) que me ayudara con las funciones de selecciona multiple.
		*/
        $jsHideActionColumn = '
                jQuery(".hide_actions").click(function() {
                    jQuery("td.acciones").remove();
                    jQuery(this).parent().parent().parent().remove();
                });
        ';

		$jsSeleccionMultiple = '
			jQuery("#modificar").click(
				function() {
					var c = jQuery(".tabla :checkbox").checkbox("contar");
					if (c > 0) {
						var action = jQuery.url("' . $this->params['controller'] . '") + "/edit";
						jQuery("#form")[0].action = action;
						jQuery("#form")[0].submit();
					} else {
						alert("Debe seleccionar al menos un registro.");
					}
					return false;
				}
			);
			
			jQuery("#eliminar").click(
				function() {
					var c = jQuery(".tabla :checkbox").checkbox("contar");
					if (c > 0) {
						var mensaje = "Esta seguro que desea eliminar " + c;
						if (c==1) {
							mensaje = mensaje + " registro?";
						} else {
							mensaje = mensaje + " registros?";
						}
						if (confirm(mensaje)) {
							var action = jQuery.url("' . $this->params['controller'] . '") + "/delete";
							jQuery("#form")[0].action = action;
							jQuery("#form")[0].submit();
						}
					}
					else {
						alert("Debe seleccionar al menos un registro.");
					}
					return false;
				}
			);
		';
		
		$jsTabla = "";
		if (($opciones['zebra'] || $opciones['seleccionMultiple'] || !empty($jsDesglose)) && !empty($tabla)) {
			//d($jsDesglose);
			if (!empty($jsDesglose)) {
				$jsTabla .= implode("\n", $jsDesglose);
			}
			if ($opciones['seleccionMultiple']) {
				$jsTabla .= $jsSeleccionMultiple;
			}
            $jsTabla .= $jsHideActionColumn;
            /*
			if (!empty($opciones['seleccionLov'])) {
				if (isset($padre) && $padre == "opener") {
					$hidden = "opener.document.getElementById('" . $opciones['seleccionLov']['retornarA'] . "')";
				}
				else {
					$hidden = "document.getElementById('" . $opciones['seleccionLov']['retornarA'] . "')";
				}
				
				$jsSeleccionLov = '
					var seleccionMultipleId = ' . $hidden . ';
					var ids = new Array();
					if (seleccionMultipleId != null) {
						if (seleccionMultipleId.value.indexOf("**||**") > 0) {
							ids = seleccionMultipleId.value.split("**||**");
						}
						if (seleccionMultipleId.value.length > 0 && ids.length == 0) {
							ids.push(seleccionMultipleId.value);
						}
						for (var i=0; i< ids.length; i++ ) {
							jQuery("#seleccionMultipleId" + ids[i]).attr("checked", "true");
						}
					}
				';
				$jsTabla .= $jsSeleccionLov;
			}
            */
			$this->addScript($jsTabla, "ready");
		}

		if (empty($tabla)) {
			if ($opciones['omitirMensajeVacio'] === false) {
				$tabla = $this->tag('span', __('The grid is empty. Enter some data or verify your search criterias.', true), array('class' => 'color_rojo'));
			}
			else {
				/**
				* Creo una tabla vacia.
				*/
				$tableEstructura = "<thead></thead>";
				$tableEstructura .= "<tbody></tbody>";
				$tableEstructura .= "<tfoot></tfoot>";
				$tabla = $this->output("\n<table " . $this->_parseAttributes($opcionesHtml, null, '', '') . ">" . $tableEstructura . "\n</table>\n\n");
			}
		}

		return $tabla;
	}

	function _fila($celdas, $trOptions = null) {

        /** TODO: http://book.cakephp.org/bg/view/206/Inserting-Well-Formatted-elements */
		
		static $count = 0;
		$out = null;
		foreach ($celdas as $celda) {
			if (is_string($celda)) {
				$out[] = $this->tag("td", $celda);
			}
			elseif (isset($celda[0]) && isset($celda[1])) {
				$out[] = $this->tag("td", $celda[0], $celda[1]);
			}
			elseif (isset($celda[0]) && !isset($celda[1])) {
				$out[] = $this->tag("td", $celda[0]);
			}
			elseif (!isset($celda[0])) {
				$out[] = $this->tag("td", $celda);
			}
		}
		
		/**
		* Las filas de desglose y las resaltadas, no las cuento para la zebra.
		*/
		//if (!in_array($trOptions['class'], array("desglose", "fila_resaltada"))) {
		if ($trOptions['class'] !== "fila_resaltada") {
			if ($count % 2) {
				if (!empty($trOptions['class'])) {
					$trOptions['class'] .= " alternativo";
				}
				else {
					$trOptions['class'] = "alternativo";
				}
			}
			$count++;
		}
		
		/**
		* Las filas de desglose, no las cuento para la zebra.
		if (!(!empty($trOptions['tipo']) && $trOptions['tipo'] == "desglose")) {
			$count++;
		}
		*/
		unset($trOptions['tipo']);
		return $this->tag("tr", $out, $trOptions);
	}


	function link($title, $href = null, $options = array(), $confirm = null, $escapeTitle = true) {

		/**
		* Si viene nulo, asumo que solo esta puesto el link para ejecutar codigo JS, entonces, hago que se quede en el lugar.
		*/
		if (empty($href)) {
			$href = "javascript:void(0);";
		}
		
		/**
		* Detecto si viene una imagen, hay que escarparlo.
		*/
		if (strstr($title, '<img src="')) {
			$escapeTitle = false;
		}
		$options['escape'] = $escapeTitle;
		
		if (isset($options['tipo']) && $options['tipo'] === "ajax") {
			unset($options['tipo']);
			return $this->output($this->Ajax->link($title, $href, $options, $confirm, $escapeTitle));
		} else {
			if (is_null($confirm)) {
				$confirmMessage = false;
			} else {
				$confirmMessage = $confirm;
			}
			return $this->output($this->Html->link($title, $href, $options, $confirmMessage, $escapeTitle));
		}
	}


	function bloque($contenido=null, $opciones=array()) {
		//trigger_error("bloque esta deprecado.");
		if (is_array($contenido)) {
			$codigo_html = implode("\n", $contenido);
		}
		else {
			$codigo_html = "\n" . $contenido;
		}

		if (isset($opciones['fieldset'])) {
			$imagen = "";
			$legend = "";
			if (!empty($opciones['fieldset']['imagen'])) {
				$imagen = $this->image($opciones['fieldset']['imagen'], array("class" => "legend"));
				unset($opciones['fieldset']['imagen']);
			}
			if (!empty($opciones['fieldset']['legend']) && is_string($opciones['fieldset']['legend'])) {
				$legend = sprintf($this->Html->tags['legend'], $imagen . $opciones['fieldset']['legend']);
				unset($opciones['fieldset']['legend']);
			}
			$attr = "";
			if (!empty($opciones['fieldset'])) {
				$attr = " " . $this->_parseAttributes($opciones['fieldset'], null, '', '');
			}
			$fieldset = sprintf($this->Html->tags['fieldset'], $attr, $legend . $codigo_html);
			$codigo_html = $fieldset;
		}
		
		if (!empty($opciones['div'])) {
			if (!isset($opciones['fieldset'])) {
				$codigo_html = "\n\n" . $this->Html->div(null,"\n" . $codigo_html . "\n", $opciones['div'], false);
			}
			else {
				$codigo_html = "\n" . $this->Html->div(null, $fieldset, $opciones['div'], false);
			}
		}
		return $this->output($codigo_html);
	}
    
	
/**
 * Crea un tag form de html con contenido html dentro.
 *
 * @param mixed $contenido Puede ser contenido html (string) o un array de contenido html.
 * @param array $opctiones.
 * @return string Un formulario html biien formado con contenido dentro.
 * @access public
*/
	function form($contenido=null, $opciones=array()) {

		/**
		* Cuando data tiene mas de dos dimensiones, es porque es un update multiple.
		*/
		if (!isset($opciones['action']) && isset($this->data) && $this->action === "edit") {
			$opciones['action'] = "saveMultiple";
		}
		
		if (!isset($opciones['id'])) {
			$opciones['id'] = "form";
		}
		
		$form = "\n" . parent::create(null, $opciones);
		if (is_array($contenido)) {
			$form .= implode("\n", $contenido);
		} elseif (is_string($contenido)) {
			$form .= $contenido;
		}
		$form .= "\n" . parent::end();
		return $this->output($form);
	}

	
/**
 * Generates a form input element complete with label and wrapper div
 *
 * @param string $tagName This should be "Modelname.fieldname", "Modelname/fieldname" is deprecated
 * @param array $options
 * Las opciones para el caso de lov son:
 *		 array("lov"=>array("controller"		=> "nombreController",
 * 							"separadorRetorno"	=> " - ",
 *		 					"seleccionMultiple"	=>	true,
 * 							"camposRetorno"		=> array(	"Convenio.numero",
 *															"Convenio.nombre")));
 *
 *		$options['verificarRequerido']
 *				- true  	=> Opcion por defecto. Indica que si un campo es requerido, se lo marcara como tal.
 *				- false 	=> No se marcara un campo como requerido aunque lo sea.
 *				- forzado 	=> Se marcara el campo como requerido aunque no lo sea.
 * @return string
 */
	function input($tagName, $options = array()) {

		$defaults['class'] = '';
		$defaults['after'] = '';
		$options = array_merge($defaults, $options);

		$requerido = "";
		if (!isset($options['verificarRequerido'])) {
			$verificarRequerido = true;
		} else {
			$verificarRequerido = $options['verificarRequerido'];
			unset($options['verificarRequerido']);
		}
		
		/**
		* En caso de ser un campo de condiciones (los filtros),
		* si no me cargo el valor de label para el campo, lo saco del nombre del campo.
		*/
		if (preg_match('/^Condicion\..+/', $tagName)) {
			/**
			* A las condiciones no las marco como requeridas. No me interesa esto.
			*/
			$verificarRequerido = false;
			
			$tmpName = $tagName;
			$tmpName = preg_replace('/^Condicion\./', '', $tmpName);
			list($model, $field) = explode('-', $tmpName);
			
			$tmpName = str_replace('-', '.', $tmpName);
			if (strpos($tmpName, '.') !== false) {
				list( , $texto) = preg_split('/[\.]+/', $tmpName);
			} else {
				$texto = $tmpName;
			}
			$texto = str_replace('_id', '', str_replace('__hasta', '', str_replace('__desde', '', $texto)));
			if (!isset($options['label'])) {
				$options['label'] = Inflector::humanize($texto);
			}

			if (empty($options['value']) && !empty($this->data['Condicion'][$model . '-' . $field])) {
				$options['value'] = $this->data['Condicion'][$model . '-' . $field];
			}
			
			if (substr($field, strlen($field) - 7) === '__desde') {
				$field = str_replace('__desde', '', $field);
			} elseif (substr($field, strlen($field) - 7) === '__hasta') {
				$field = str_replace('__hasta', '', $field);
			}
		}

		if (!isset($model) && !isset($field)) {
			list($model, $field) = explode(".", $tagName);
		}
		
		if (!isset($options['label'])) {
			$options['label'] = Inflector::humanize(str_replace('_id', '', $field));
		}
		
		/**
		* Busco que tipo de campo es.
		*/
		if (isset($options['type'])){
			$tipoCampo = $options['type'];
		}
		/**
		* Si viene la opcion lov no vacia y no seteo el tipo, especifico el tipo a lov.
		*/
		elseif (!empty($options['lov']) && is_array($options['lov'])) {
			$tipoCampo = "lov";
		}
		
		if (ClassRegistry::isKeySet($model) &&
			!(!empty($options['options']) && is_string($options['options']) && $options['options'] === "listable")) {
			$modelClass =& ClassRegistry::getObject($model);
			$tableInfo = $modelClass->schema();
			if (empty($options['options']) && !empty($modelClass->opciones[$field])) {
				$options['options'] = $modelClass->opciones[$field];
				if (empty($tipoCampo)) {
					$tipoCampo = "checkboxMultiple";
				}
			}
			
			/**
			* Determino si es un campo requerido para marcarlo con el (*).
			* Esto lo hago si es que no viene dado ningun "after" en las opciones.
			*/
			if ($verificarRequerido === true) {
				if (isset($modelClass->validate[$field])) {
					foreach ($modelClass->validate[$field] as $regla) {
						if (isset($regla["rule"]) && $regla["rule"] == "/.+/") {
							$requerido = $this->tag("span", "(*)", array("class" => "color_rojo"));
						}
					}
				}
			}

			/**
			* Si es un nuevo registro agrego el valor por defecto en caso de que este exista.
			*/
			if ($this->action === "add" && !isset($this->data[$model][$field]) && !empty($tableInfo[$field]['default']) && !isset($options['value']) && $tableInfo[$field]['default'] !== "0000-00-00") {
				$options['value'] = $tableInfo[$field]['default'];
			}

			if (!empty($tableInfo[$field]['type'])) {
				if (substr($tableInfo[$field]['type'], 0, 5) == "enum(") {
					/**
					* De los tipo de campo enum, busco las opciones.
					* Si ya me viene especificado las options, respeto lo que viene,
					* sino, cargo con los valores de la DB.
					*/
					if (empty($options['options'])) {
						$valores = str_replace("'", "", str_replace(")", "", substr($tableInfo[$field]['type'], 5)));
						$values = explode(",", $valores);
						foreach ($values as $v) {
							$options['options'][$v] = $v;
						}
					}
					$tipo = "enum";
				}
				else {
					$tipo = $tableInfo[$field]['type'];
				}
				
				if (empty($tipoCampo)) {
					$mapeoTipos['string'] = "text";
					$mapeoTipos['enum'] = "radio";
					
					if (isset($mapeoTipos[$tipo])) {
						$tipoCampo = $mapeoTipos[$tipo];
					}
					else {
						$tipoCampo = $tipo;
					}
				}
			}


			/**
			* Verifico el largo del campo para setear el maxLength
			*/
			if (!empty($tableInfo[$field]['length']) && !isset($options['maxlength']) && $tipoCampo != "float") {
				$options['maxlength'] = $tableInfo[$field]['length'];
			}
		} elseif (!empty($options['options']) && is_string($options['options']) && $options['options'] === "listable") {
			$opcionesValidas = array("displayField", "groupField", "conditions", "fields", "order", "limit", "recursive", "group", "contain", "model");
			$opcionesValidasArray = array("displayField", "groupField", "conditions", "fields", "order", "contain");
			foreach ($opcionesValidas as $opcionValida) {
				if (!empty($options[$opcionValida])) {
					if (!is_array($options[$opcionValida]) && in_array($opcionValida, $opcionesValidasArray)) {
						$condiciones[$opcionValida] = $opcionValida . ":" . serialize(array($options[$opcionValida]));
					} elseif (in_array($opcionValida, $opcionesValidasArray)) {
						$condiciones[$opcionValida] = $opcionValida . ":" . serialize($options[$opcionValida]);
					} else {
						$condiciones[$opcionValida] = $opcionValida . ":" . $options[$opcionValida];
					}
					unset($options[$opcionValida]);
				}
			}

			/**
			* Armo la url y voy al controller a buscar los valores.
			*/
			//$url = array('controller' => $this->params['controller'], 'action' => 'listable', 'named' => $condiciones);
			//$options['options'] = $this->requestAction($url);
			$options['options'] = $this->requestAction("/" . $this->params['controller'] . "/" . $options['options'] . "/" . implode("/", $condiciones));
		}

        /** Sets default value for input field */
        if (empty($this->data[$model][$field]) && !empty($options['default'])) {
            $options['value'] = $options['default'];
        }

		if ($verificarRequerido === "forzado") {
			$requerido = $this->tag("span", "(*)", array("class" => "color_rojo"));
		}

		if (isset($tipoCampo)) {

			if (isset($this->data[$model][$field])) {
				$valorCampo = $this->data[$model][$field];
			}
			elseif (isset($options['value'])) {
				$valorCampo = $options['value'];
			}
			else {
				$valorCampo = null;
			}
			
			if (!empty($options['format'])) {
				$valorCampo = $this->Formato->format($valorCampo, $options['format']);
			}
			
			/**
			* Wysiwyg control based on FCKEditor.
			* Isolate vendors code in js/vendors/fckeditor for easily later upgrade.
			* Add a custom config file in js dir called fckconfig.js.
			*/
			if($tipoCampo === 'wysiwyg') {
				include_once(WWW_ROOT . 'js' . DS . 'vendors' . DS . 'fckeditor' . DS . 'fckeditor.php');
				$oFCKeditor = null;
				if(empty($options['name'])) {
						$oFCKeditor = new FCKeditor(sprintf("data[%s][%s]", $model, $field));
				} else {
						$oFCKeditor = new FCKeditor($options['name']);
				}
				$path = Router::url('/', true);
				$oFCKeditor->BasePath = $path . 'js/vendors/fckeditor/' ;
				$oFCKeditor->Config["CustomConfigurationsPath"] = $path . 'js/fckconfig.js';
				$oFCKeditor->ToolbarSet = 'Custom';
				//$oFCKeditor->ToolbarSet = 'Basic';
				if (!empty($this->data[$field])) {
					$oFCKeditor->Value = $this->data[$field];
				} elseif (!empty($this->data[$model][$field])) {
					$oFCKeditor->Value = $this->data[$model][$field];
				}
				ob_start();
				$oFCKeditor->Create() ;
				$out = ob_get_clean();
				$label = $this->tag('label', $options['label']);
				return $this->tag('div', $label . $this->tag('div', $out, array('class' => 'editor')), array('class' => 'wysiwyg'));
			}
			
			/**
			* Manejo los tipos de datos date para que me arme el control seleccion de fechas.
			*/
			if ($tipoCampo === "soloLectura") {
				//return $this->tag("div", $this->label($options['label'], null, array("for"=>false)) . $this->tag("span", $valorCampo, array("class" => "solo_lectura")), array("class" => "input text"));
                return $this->tag("div", $this->tag("span", $options['label'], array("class"=>'label')) . $this->tag("span", $valorCampo, array("class" => "solo_lectura")), array("class" => "input text"));
			}
			
			/**
			* Manejo los tipos de datos date para que me arme el control seleccion de fechas.
			*/
			else if ($tipoCampo === 'date') {
				$options['type'] = 'text';
				$options['class'] = 'fecha';
                if ($valorCampo === '0000-00-00') {
                    $options['value'] = '';
                } else {
                    $options['value'] = $valorCampo;
                }
				$options['after'] = $this->__inputFecha($tagName, $options, $tipoCampo) . $options['after'];
			}

			/**
			* Manejo los tipos de datos datetime para que me arme el control seleccion de fechas con hora.
			*/
			elseif ($tipoCampo === 'datetime') {
				$options['type'] = 'text';
				$options['class'] = 'fecha';
                if ($valorCampo === '0000-00-00') {
                    $options['value'] = '';
                } else {
                    $options['value'] = $valorCampo;
                }
				$options['after'] = $this->__inputFecha($tagName, $options, $tipoCampo) . $options['after'];
			}

			/**
			* Agrega el link para poder descargar en caso de que sea un edit.
			*/
			elseif ($tipoCampo === "file") {
				if (!empty($options['descargar']) && $options['descargar'] === true && $this->action == "edit") {
					if ($this->params['action'] == "edit" && !empty($this->params['pass'][0])) {
						$options['aclaracion'] = "Puede descargar el archivo y ver su contenido desde aca " . $this->link($this->image('archivo.gif', array("alt" => "Descargar")), "descargar/" . $this->params['pass'][0]);
					}
				}
				if (!empty($options['mostrar']) && $options['mostrar'] === true && $this->action == "edit" && isset($this->params['pass'][0])) {
					$options['after'] = str_replace(">", " />" ,$this->tag("img", null, array("alt" => "", "class" => "imagen_mostrar", "src"=>Router::url("/") . $this->params['controller'] . "/descargar/" . $this->params['pass'][0] . "/mostrar:true")));
				}
				unset($options['descargar']);
				unset($options['mostrar']);
			}
			
			/**
			* Manejo los campos periodo.
			*/
			elseif ($tipoCampo === "periodo") {
				$rnd = intval(rand());
				$options['type'] = 'text';
				$options['class'] .= ' periodo';
				$options['id'] = $rnd;
				$after = '';
				$q1 = $this->link($this->image('1q.gif', array('class' => 'periodo 2q')), null, array("title" => "Primera Quincena", "onclick" => "jQuery('#" . $rnd . "').attr('value', '" . $this->Formato->format(null, array('type' => '1QAnterior')) . "');"));
				$q2 = $this->link($this->image('2q.gif', array('class' => 'periodo 1q')), null, array("title" => "Segunda Quincena", "onclick" => "jQuery('#" . $rnd . "').attr('value', '" . $this->Formato->format(null, array('type' => '2QAnterior')) . "');"));
				$m = $this->link($this->image('m.gif', array('class' => 'periodo m')), null, array("title" => "Mensual", "onclick" => "jQuery('#" . $rnd . "').attr('value', '" . $this->Formato->format(null, array('type' => 'mensualAnterior')) . "');"));
				if (empty($options['periodo'])) {
					$after .= $q1 . $q2 . $m;
				} else {
					foreach ($options['periodo'] as $v) {
						switch($v) {
							case "1Q":
								$after .= $q1;
								break;
							case "2Q":
								$after .= $q2;
								break;
							case "1S":
								$after .= $this->link($this->image('1q.gif', array('class' => 'periodo 1s')), null, array("title" => "Primer Semestre", "onclick" => "jQuery('#" . $rnd . "').attr('value', '" . $this->Formato->format(null, array("type" => "1SAnterior")) . "');"));
								break;
							case "2S":
								$after .= $this->link($this->image('2q.gif', array('class' => 'periodo 2s')), null, array("title" => "Segundo Semestre", "onclick" => "jQuery('#" . $rnd . "').attr('value', '" . $this->Formato->format(null, array("type" => "2SAnterior")) . "');"));
								break;
							case "A":
								$after .= $this->link($this->image('a.gif', array('class' => 'periodo a')), null, array("title" => "Año Anterior", "onclick" => "jQuery('#" . $rnd . "').attr('value', '" . $this->Formato->format(null, array("type" => "anoAnterior")) . "');"));
								break;
                            case "F":
                                $after .= $this->link($this->image('f.gif', array('class' => 'periodo f')), null, array("title" => "Final", "onclick" => "jQuery('#" . $rnd . "').attr('value', '" . $this->Formato->format(null, array("type" => "final")) . "');"));
                                break;
							case "M":
								$after .= $m;
								break;
							case "soloAAAAMM":
								$after .= $this->link($this->image('m.gif', array("class" => "periodo")), null, array("title" => "Mensual", "onclick" => "jQuery('#" . $rnd . "').attr('value', '" . substr($this->Formato->format(null, array("type" => "mensualAnterior")), 0, 6) . "');"));
								break;
						}
					}
				}
				$options['after'] = $after . $options['after'];
			}
			
			elseif ($tipoCampo === "radio") {
				$options['type'] = "radio";
				$options['legend'] = false;
				$options['before'] = $this->label(null, $options['label'], array("for"=>false));
				
				/**
				* Pongo todas las opciones dentro de un div para poder asignarles estilos.
				*/
				$options['before'] .= "<div class='radio_opciones'>";
				$options['after'] = "</div>" . $options['after'];
				$options['label'] = false;
				$options['class'] = "radio";

				/**
				* Cuando esta vacio, cakePHP agrega un hidden para postear el vacio.
				* Yo agrego el hidden a mano, por lo cual le pongo siempre una valor para que cake no lo creen al hidden.
				*/
				if (empty($options['value'])) {
					$options['value'] = "/**VACIO**/";
					if (!empty($options['name'])) {
						$options['before'] .= parent::hidden($tagName, array("name"=>$options['name'], "value" => ""));
					}
					else {
						$options['before'] .= parent::hidden($tagName, array("value" => ""));
					}
				}
			}

			/**
			* El array parametros posteara (si los encuentra) via params->named los valores de los controles especificados.
			* $appForm->input('Banco.id', array("label" => "Cuenta", "type" => "relacionado", "valor" => "Banco.id", "relacion" => "Soporte.modo", "parametros"=>array("Soporte.empleador_id", "Soporte.grupo_id"), "url" => "pagos/cuentas_relacionado"));
			*/
			elseif ($tipoCampo === "relacionado") {

				$tmp = explode(".", $tagName);
				$id = Inflector::camelize($tmp[0]) . Inflector::camelize($tmp[1]);
				$tmp = explode(".", $options['relacion']);
				$idHiddenRelacionado = Inflector::camelize($tmp[0]) . Inflector::camelize($tmp[1]);
				$idHiddenRelacionadoTmp = $idHiddenRelacionado . "Tmp" . intval(rand());

				$value[0] = "Seleccione su opcion";
				/**
				* Busco el valor cuando sea un edit, o cuando es un add que no ha validado.
				*/
				if (!empty($options['valor']) && ($this->action != "add" ||
					($this->action == "add" && !empty($this->validationErrors) && !empty($this->data)))) {
					$valueId = $this->value($tagName);
					list($mRetorno, $cRetorno) = explode(".", $options['valor']);
					if (isset($mRetorno) && isset($cRetorno) && isset($this->data[$mRetorno][$cRetorno])) {
						$value[$valueId] = $this->data[$mRetorno][$cRetorno];
					}
					/**
					* No le pongo model ni campo, ya que es temporal, nunca debere guardar estos valores.
					*/
					$options['after'] .= $this->input("Bar.foo", array("value"=>$id . "|" . $valueId, 'id' => $idHiddenRelacionadoTmp, "type" => "hidden"));
				}
				
				$jsParametros = "";
				if (!empty($options['parametros'])) {
					foreach ($options['parametros'] as $parametro) {
						list($modelParametro, $fieldParametro) = explode(".", $parametro);
						$parametroRelacionado = $modelParametro . inflector::camelize($fieldParametro);
						$jsParametrosArray[] =
						$parametroRelacionado . ": jQuery('#" . $parametroRelacionado . "').val()";
					}
					$jsParametros ="parametros = {" . implode(", ", $jsParametrosArray) . "};";
				}
				
				$requestAjax = '
					jQuery("#' . $id . '").bind("click", function () {
						var valor = jQuery("#' . $idHiddenRelacionado . '").val();
						var parametros = {}; ' .
						$jsParametros . '
						var parametrosAdicionales = "";
						jQuery.each(parametros, function(key, value) {
   							parametrosAdicionales = parametrosAdicionales + key + ":" + value + "/";
 						});
 						
						var reg = new RegExp("^[0-9]+$");
						if (!reg.test(valor)) {
							alert("Antes de continuar debe seleccionar un valor para ' . str_replace(" Id", "", Inflector::humanize($tmp[1])) . '");
						}
						else {
							var elHidden = document.getElementById("' . $idHiddenRelacionadoTmp . '");

							if (elHidden != null) {
								var tmp = elHidden.value.split("|");
								/**
								* Si ya existe el hidden, comparo que no haya cambiado el valor.
								*/
								if (tmp[1] == valor) {
									return;
								}

								/**
								* Si cambio, lo asigno nuevamente.
								*/
								jQuery("#' . $idHiddenRelacionadoTmp . '").val(tmp[0] + "|" + valor);
							}
							else {
								/**
								* Si el hidden aun no esta creado, lo creo.
								*/
								jQuery("#form").append("<input id=\'' . $idHiddenRelacionadoTmp . '\' type=\'hidden\' value=\'" + jQuery(this).attr("id") + "|" + valor + "\' >");
							}
						

							/**
							* Hago el request via jSon.
							*/
							jQuery.getJSON("' . Router::url("/") . $options['url'] . '/" + valor + "/" + parametrosAdicionales,
								function(datos){
									var options = "";
									for (var i = 0; i < datos.length; i++) {
										options += "<option value=\"" + datos[i].optionValue + "\">" + datos[i].optionDisplay + "</option>";
									}
									jQuery("#' . $id . '").html(options);
								}
							);
						}
					})';
				
				$this->addScript($requestAjax);
				$options = am($options, array("type" => "select"), array("options"=>$value), array("maxlength"=>false));
				unset($options['url']);
				unset($options['relacion']);
				unset($options['valor']);
				return $this->input($tagName, $options);
			}

			/**
			* Manejo los tipos de datos numericos.
			*/
			elseif ($tipoCampo === "float" || $tipoCampo === "integer") {
				$options['class'] = "derecha";
			}

			elseif (isset($options['multiple']) && $options['multiple'] === 'checkbox') {
				$id = 'checkbox_' . mt_rand();
				$seleccion[] = $this->link('T', null, array('onclick' => 'jQuery("#' . $id . ' input:checkbox").checkbox("seleccionar");return false;'));
				$seleccion[] = $this->link('N', null, array('onclick' => 'jQuery("#' . $id . ' input:checkbox").checkbox("deseleccionar");return false;'));
				$seleccion[] = $this->link('I', null, array('onclick' => 'jQuery("#' . $id . ' input:checkbox").checkbox("invertir");return false;'));
				$seleccionString = $this->tag('div', implode(' / ', $seleccion), array('class' => 'seleccion'));
				if (empty($options['after'])) {
					unset($options['after']);
				}
                $options['before'] = $this->label(null, $options['label'], array("for"=>false));
                $options['label'] = false;
				
				if (!empty($this->data)) {
					$parent = array_shift(array_keys($this->data));
					if (!empty($this->data[$model])) {
						if (ClassRegistry::isKeySet($model)) {
							$modelClass =& ClassRegistry::getObject($model);
							if (!empty($modelClass->hasAndBelongsToMany[$parent]['with'])) {
								$options['value'] = Set::extract('/' . $modelClass->hasAndBelongsToMany[$parent]['with'] . '/' . $field, $this->data[$model]);
							} elseif (!empty($this->data[$model])) {
								$options['value'] = Set::extract('/' . $field, $this->data[$model]);
							}
						}
					}
				}
				
				/**
				 * Try to find bitwise.
				 */
				if (!empty($options['options']) && !empty($this->data[$model][$field]) && is_numeric($this->data[$model][$field])) {
					foreach ($options['options'] as $k => $v) {
						if ($this->data[$model][$field] & $k) {
							$options['value'] = array_merge((Array)$options['value'], (Array)$k);
						}
					}
				}
				
				$options = array_merge(array('after'=>$seleccionString, 'div'=>array('id' =>$id, 'class'=>'input'), 'type' => 'select', 'multiple' => 'checkbox'), $options);
			}
		
			elseif ($tipoCampo === 'lov'
					&& !empty($options['lov']['controller'])
                    && is_string($options['lov']['controller'])) {

				$rnd = intval(rand());
				$id = $this->domId($tagName);

				/**
				* Cargo nuevamente los valores.
				*/
				$value = array();
                foreach ($options['lov']['camposRetorno'] as $campoRetorno) {
                    list($mRetorno, $cRetorno) = explode(".", $campoRetorno);
                    if (isset($this->data[$mRetorno][$cRetorno])) {
                        $value[] = $this->data[$mRetorno][$cRetorno];
                    } else {
                        /**
                        * Si aun no lo encontre, puede que este en recursive = 2.
                        * Trato de buscarlo mas adentro en el array.
                        */
                        $modelParent = Inflector::classify($options['lov']['controller']);
                        if (isset($this->data[$modelParent][$mRetorno][$cRetorno])) {
                            $value[] = $this->data[$modelParent][$mRetorno][$cRetorno];
                        }
                    }
                }
				
				$options['lov'] = array_merge(array(  'action'    => 'index',
										              'retornarA' => $id), $options['lov']);
                if (empty($options['lov']['mask'])) {
                    $options['lov']['mask'] = trim(str_repeat('%s ', count($options['lov']['camposRetorno'])));
                }
				$options['lov']['camposRetorno'] = implode('|', $options['lov']['camposRetorno']);


                $lovOptions = null;
                foreach ($options['lov'] as $lovKey => $lovValue) {
                    $lovOptions[] = $lovKey . ':' . $lovValue;
                }
                $lupa = $this->image('search.gif', array(   'alt'       => __('Search', true),
                                                            'class'     => 'lupa_lov',
                                                            'longdesc'  => implode(';', $lovOptions)));

                /**
                * Si permite seleccion multiple, pongo un textarea, sino un text comun.
                */
                if (isset($options['lov']['seleccionMultiple']) && $options['lov']['seleccionMultiple'] == 0) {
                    $type = 'text';
                } else {
                    $type = 'textarea';
                    $lupa .= '<p class="expand_text_area">&nbsp;</p>';
                }

				/**
				* El control lov se abre en un popup o en un div, de acuerdo a las preferencias.
				*/
                $options['after'] = $lupa . $options['after'];

				unset($options['type']);
				unset($options['lov']);
				unset($options['maxlength']);

				/**
				* Creo la hidden que sera quien en definitiva, contenga el valor correto a actualizar, lo que
				* muestra la lov es solo una "pantalla" linda al usuario, esta input hidden tiene el valor que se actualizara.
				*/
				$options['after'] .= $requerido . $this->input($tagName, array_merge($options, array('id' => $id, "type" => "hidden")));

				/**
				* Busco el valor "descriptivo" para mostrarle al usuario.
				*/
				if (!empty($value)) {
					//foreach ($value as $k=>$v) {
						//if (preg_match(VALID_DATE_MYSQL, $v)) {
							//$value[$k] = $this->Formato->format($v, array("type" => "date"));
						//}
					//}
					if (isset($options['lov']['separadorRetorno']) && !empty($options['lov']['separadorRetorno'])) {
						$options['value'] = implode($options['lov']['separadorRetorno'], $value);
					} else {
						$options['value'] = implode(" - ", $value);
					}
					$options['title'] = $options['value'];
				}
				
				/**
				* Busco una etiqueta que vera el usuario.
				if (!isset($options['label'])) {
					if (isset($tmpName)) {
						$options['label'] = Inflector::humanize(array_pop(explode(".", str_replace("_id", "", $tmpName))));
					}
					else {
						$options['label'] = Inflector::humanize(array_pop(explode(".", str_replace("_id", "", $tagName))));
					}
				}
				*/

				$options = array_merge($options, array(	'id'		=> $id . '__',
														'readonly'	=> true,
														'type'		=> $type,
														'class'		=> 'izquierda'));

				list($model, $field) = explode('.', $tagName);
				if (!empty($this->data[$model][$field . '__'])) {
					$options['value'] = $this->data[$model][$field . '__'];
				}
				return $this->input($tagName . '__', $options);
			}
		}

		$aclaracion = '';
		if (!empty($options['aclaracion'])) {
			$aclaracion = $this->tag('span', $options['aclaracion'], array('class' => 'aclaracion'));
			unset($options['aclaracion']);
		}

		$options['after'] .= $aclaracion . $requerido;
		if (isset($options['maxlength']) && $options['maxlength'] === false) {
			unset($options['maxlength']);
		}
		return parent::input($tagName, $options);
	}

/**
 * Returns a formatted LABEL element for HTML FORMs.
 *
 * @param string $fieldName This should be "Modelname.fieldname", "Modelname/fieldname" is deprecated
 * @param string $text Text that will appear in the label field.
 * @return string The formatted LABEL element
 * TODO: Must deprecated this method and use span tag instead, in vews code
 */
	function label($fieldName = null, $text = null, $attributes = array()) {
		$return = parent::label($fieldName, $text, $attributes);
		if (isset($attributes['for']) && $attributes['for'] === false) {
			return str_replace(' for=""', "", $return);
		}
		return $return;
	}

	
/**
 * Creates a button tag.
 *
 * @param  mixed  $params  Array of params [content, type, options] or the
 *						   content of the button.
 * @param  string $type	   Type of the button (button, submit or reset).
 * @param  array  $options Array of options.
 * @return string A HTML button tag.
 * @access public
 */
	function button($caption = '', $options = array()) {
		$return = '<input type="button" value="' . $caption . '" ' . $this->_parseAttributes($options, null, '', '') . ' />';
		return $this->tag('div', $return, array('class' => 'submit'));
	}

	
/**
 * TODO: Change by jquery alternative (http://ui.jquery.com/demos/datepicker)
 */
	function __inputFecha($tagName, $options = array(), $type = 'date') {
		$this->setEntity($tagName);
		$id = $this->domId(implode('.', array_filter(array($this->model(), $this->field()))));
		$codigo_html = $this->image('calendar.gif', array('class'	=>'fecha', 'title' => __('Pick date', true)));


		if ($type == 'date') {
            $codigo_html = $this->link($codigo_html, null, array('id' => 'trigger' . $id));
            $this->addScript('
                Calendar.setup({
                    inputField      : "' . $id . '",
                    trigger         : "trigger' . $id . '",
                    weekNumbers     : true,
                    onSelect        : function(cal) {
                        var date = cal.selection.get();
                        if (date) {
                                date = Calendar.intToDate(date);
                                jQuery("#' . $id . '").val(Calendar.printDate(date, "%Y-%m-%d"));
                        }
                        this.hide()
                    }
                });'
            );
		} else {
            $codigo_html = $this->link($codigo_html, null, array('id' => 'trigger' . $id));
            $this->addScript('
                Calendar.setup({
                    inputField      : "' . $id . '",
                    trigger         : "trigger' . $id . '",
                    weekNumbers     : true,
                    showTime        : 24,
                    onSelect        : function(cal) {
                        var date = cal.selection.get();
                        if (date) {
                                var h = cal.getHours();
                                var m = cal.getMinutes();
                                if (h < 10) h = "0" + h;
                                if (m < 10) m = "0" + m;
                                date = Calendar.intToDate(date);
                                jQuery("#' . $id . '").val(Calendar.printDate(date, "%Y-%m-%d " + h + ":" + m));
                        }
                        this.hide();
                    }
                });'
            );
		}
        
		return $codigo_html;
	}



}
?>