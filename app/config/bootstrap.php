<?php
/* SVN FILE: $Id: bootstrap.php 4407 2007-02-02 10:39:45Z phpnut $ */
/**
 * Short description for file.
 *
 * Long description for file
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) :  Rapid Development Framework <http://www.cakephp.org/>
 * Copyright 2005-2007, Cake Software Foundation, Inc.
 *								1785 E. Sahara Avenue, Suite 490-204
 *								Las Vegas, Nevada 89104
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @filesource
 * @copyright		Copyright 2005-2007, Cake Software Foundation, Inc.
 * @link				http://www.cakefoundation.org/projects/info/cakephp CakePHP(tm) Project
 * @package			cake
 * @subpackage		cake.app.config
 * @since			CakePHP(tm) v 0.10.8.2117
 * @version         $Revision: 4407 $
 * @modifiedby      $LastChangedBy: phpnut $
 * @lastmodified    $Date: 2007-02-02 04:39:45 -0600 (Fri, 02 Feb 2007) $
 * @license			http://www.opensource.org/licenses/mit-license.php The MIT License
 */
/**
 *
 * This file is loaded automatically by the app/webroot/index.php file after the core bootstrap.php is loaded
 * This is an application wide file to load any function that is not used within a class define.
 * You can also use this to include or require any files in your application.
 *
 */
/**
 * The settings below can be used to set additional paths to models, views and controllers.
 * This is related to Ticket #470 (https://trac.cakephp.org/ticket/470)
 *
 * $modelPaths = array('full path to models', 'second full path to models', 'etc...');
 * $viewPaths = array('this path to views', 'second full path to views', 'etc...');
 * $controllerPaths = array('this path to controllers', 'second full path to controllers', 'etc...');
 *
 */

//define('VALID_DATE', '/(0[1-9]|[12][0-9]|3[01])[\/.](0[1-9]|1[012])[\/.](19\d\d|20\d\d)/');
define('VALID_DATE_NULO', '/(0[1-9]|[12][0-9]|3[01])[\/.](0[1-9]|1[012])[\/.](19\d\d|20\d\d)|^$/');
define('VALID_DATE', '/(19\d\d|20\d\d)[\-](0[1-9]|1[012])[\-](0[1-9]|[12][0-9]|3[01])|^$/');
define('VALID_DATE_MYSQL', '/(19\d\d|20\d\d)[\-](0[1-9]|1[012])[\-](0[1-9]|[12][0-9]|3[01])|^$/');
define('VALID_DATETIME_MYSQL', '/(19\d\d|20\d\d)[\-](0[1-9]|1[012])[\-](0[1-9]|[12][0-9]|3[01])\s{1}([0-1][0-9]|[2][0-3]):([0-5][0-9]):{0,1}([0-5][0-9]){0,1}|^$/');
define('VALID_DATETIME', '/(0[1-9]|[12][0-9]|3[01])[\/.](0[1-9]|1[012])[\/.](19\d\d|20)\d\d\s{1}([0-1][0-9]|[2][0-3]):([0-5][0-9])|^$/');
define('VALID_MAIL', '/\A(?:^([a-z0-9][a-z0-9_\-\.\+]*)@([a-z0-9][a-z0-9\.\-]{0,63}\.(com|org|net|biz|info|name|net|pro|aero|coop|museum|[a-z]{2,4}))$)\z|^$/i');
define('VALID_NUMBER_MAYOR_A_CERO', '/^[1-9]+/');
define('VALID_NUMBER_NULO', '/[0-9]*/');
define('VALID_PERIODO', '/^(20\d\d)(0[1-9]|1[012])([12][qQ]|[12][sS]|[mM]|[aA]|[fF])$|^$/'); //200804M, 2007111Q, 2007092Q

define('VALID_NOT_EMPTY', '/.+/');
define('VALID_NUMBER', '/^[-+]?\\b[0-9]*\\.?[0-9]+\\b$/');
define('VALID_EMAIL', "/^[a-z0-9!#$%&'*+\/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+\/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+(?:[a-z]{2,4}|museum|travel)$/i");



Inflector::rules(
	'singular', array(
		'rules' 			=> array(
			'/des$/i' => 'd',
			'/res$/i' => 'r',
			'/nes$/i' => 'n',
			'/les$/i' => 'l'),
		'irregular' 		=> array(
			'conceptos_familia'			=> 'conceptos_familia')
		)
);

Inflector::rules(
'plural', array(
	'rules' 			=> array(
		'/(.d)$/i' => '\1\2es',
		'/(.n)$/i' => '\1\2es',
		'/(.l)$/i' => '\1\2es',
		'/(.r)$/i' => '\1\2es'),
	'uninflected' 		=> array('suss'),
    'irregular' 		=> array(
		'zone'                      => 'zones',
		'familiar'                  => 'familiares',
		'actividad'					=> 'actividades',
		'novedad'					=> 'novedades',
		'empleador'					=> 'empleadores',
		'localidad'					=> 'localidades',
		'trabajador'				=> 'trabajadores',
		'condicion'					=> 'condiciones',
		'controlador'				=> 'controladores',
		'variable'					=> 'variables',
		'coeficiente'				=> 'coeficientes',
		'relacion'					=> 'relaciones',
		'rol'						=> 'roles',
		'vacacion'					=> 'vacaciones',
		'liquidacion'				=> 'liquidaciones',
		'modalidad'					=> 'modalidades',
		'situacion'					=> 'situaciones',
		'sucursal'					=> 'sucursales',
		'accion'					=> 'acciones')
));

/**
 * Debug and show SQL
 */
function ds($var = 'x') {
	d($var, false, true);
}

function d($var = 'x', $skipDebugMode = false, $showSql = false) {
	if (Configure::read() > 0 || $skipDebugMode === true) {
		$calledFrom = debug_backtrace();
		echo '<strong>' . substr(str_replace(ROOT, '', $calledFrom[0]['file']), 1) . '</strong>';
		echo ' (line <strong>' . $calledFrom[0]['line'] . '</strong>)';
		echo "\n<pre class=\"cake-debug\">\n";
		$var = print_r($var, true);
		if ($showSql) {
			dsql();
		}
		echo $var . "\n</pre>\n";
		die;
	}
}

function dsql() {
    $sources = ConnectionManager::sourceList();
    if (!isset($logs)):
        $logs = array();
        foreach ($sources as $source):
            $db =& ConnectionManager::getDataSource($source);
            if (!$db->isInterfaceSupported('getLog')):
                continue;
            endif;
            $logs[$source] = $db->getLog();
        endforeach;
    endif;

	App::import('Vendor', 'Geshi');
	$geshi = new GeSHi('', 'mysql');
	$geshi->set_header_type(GESHI_HEADER_DIV);
	$geshi->enable_keyword_links(false);

    foreach ($logs as $source => $logInfo) {
        $text = $logInfo['count'] > 1 ? 'queries' : 'query';
        printf(
            '<table class="cake-sql-log" id="cakeSqlLog_%s" summary="Cake SQL Log" cellspacing="0" border = "0">',
            preg_replace('/[^A-Za-z0-9_]/', '_', uniqid(time(), true))
        );
        printf('<caption>(%s) %s %s took %s ms</caption>', $source, $logInfo['count'], $text, $logInfo['time']);
        echo '
            <thead>
                <tr><th>Nr</th><th>Query</th><th>Error</th><th>Affected</th><th>Num. rows</th><th>Took (ms)</th></tr>
            </thead>
            <tbody>
            ';
        foreach ($logInfo['log'] as $k => $i) {
			$geshi->set_source($i['query']);
            echo "<tr><td>" . ($k + 1) . "</td><td>" . $geshi->parse_code() . "</td><td>{$i['error']}</td><td style = \"text-align: right\">{$i['affected']}</td><td style = \"text-align: right\">{$i['numRows']}</td><td style = \"text-align: right\">{$i['took']}</td></tr>\n";
        }
        echo '</tbody></table>';
	}
}


?>