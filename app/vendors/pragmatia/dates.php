<?php
/**
 * Date operations.
 *
 * PHP versions 5
 *
 * @filesource
 * @copyright		Copyright 2007-2008, Pragmatia de RPB S.A.
 * @link			http://www.pragmatia.com
 * @package			pragtico
 * @subpackage		app.vendors.pragmatia
 * @since			Pragtico v 1.0.0
 * @version			$Revision: 54 $
 * @modifiedby		$LastChangedBy: mradosta $
 * @lastmodified	$Date: 2008-10-23 23:14:28 -0300 (Thu, 23 Oct 2008) $
 * @author      	Martin Radosta <mradosta@pragmatia.com>
 */
/**
 * Date operations Class.
 *
 * @package pragtico
 * @subpackage app.vendors.pragmatia
 */
class Dates {

/**
 * Constructor de la clase.
 * Me aseguro que las constantes existan (normalmente definidas en bootstrap.php.
 *
 * @return void
 * @access public
 */
	function __construct() {
        
        if (!defined('DAY_IN_SECONDS')) {
            define('DAY_IN_SECONDS', 86400);
        }
        
		if (!defined('VALID_DATE_MYSQL')) {
			define('VALID_DATE_MYSQL', '/(19\d\d|20\d\d)[\-](0[1-9]|1[012])[\-](0[1-9]|[12][0-9]|3[01])|^$/');
		}
		if (!defined('VALID_DATETIME_MYSQL')) {
			define('VALID_DATETIME_MYSQL', '/(19\d\d|20\d\d)[\-](0[1-9]|1[012])[\-](0[1-9]|[12][0-9]|3[01])\s{1}([0-1][0-9]|[2][0-3]):([0-5][0-9]):{0,1}([0-5][0-9]){0,1}|^$/');
		}
	}

	

	function daysInMonth($year, $month) {
		return( date( "t", mktime( 0, 0, 0, $month, 1, $year) ) );
	}


    function getDay($date) {
        return array_pop(explode('-', $date));
    }
    
    function getMonth($date) {
        list(,$month) = explode('-', $date);
        return $month;
    }
    
    function getYear($date) {
        return array_shift(explode('-', $date));
    }


/**
 * Creates periods based in dates.
 *
 * if optional parameter month is specified, can create periods based in a date +/- months.
 *
 */
    function getPeriods($fromDate = null, $toDate = null, $options = array()) {
        
        $defaults = array(  'fromInclusive' => true,
                            'toInclusive'   => true);
        $options = array_merge($defaults, $options);

        if (!empty($options['month'])) {
            $fromDate = Dates::dateAdd($toDate, $options['month'], 'm', array('fromInclusive' => false));
        }
        
        if ($options['fromInclusive'] === false) {
            $fromDate = Dates::dateAdd($fromDate, 1, 'd', array('fromInclusive' => false));
        }
        if ($options['toInclusive'] === false) {
            $toDate = Dates::dateAdd($toDate, -1, 'd', array('fromInclusive' => false));
        }

        $periods = array();
        while ($fromDate <= $toDate) {
            $day = Dates::getDay($fromDate);
            $month = Dates::getMonth($fromDate);
            $year = Dates::getYear($fromDate);
            if ($day <= 15) {
                $periods[] = $year . $month . '1Q';
                $fromDate = Dates::dateAdd($fromDate, 16);
            } else {
                $periods[] = $year . $month . '2Q';
                $periods[] = $year . $month . 'M';
                $fromDate = Dates::dateAdd($fromDate, Dates::daysInMonth($year, $month) - 14);
            }
        }
        return $periods;
    }

/**
 * Calculates non working days between two dates.
 *
 * @param string $fromDate Starting date.
 * @param string $toDate End date. If empty, current date will be used instead.
 * @param array $options
 *          fromInclusive: (default: true). Means that lower limit will be included in calculations.         
 *          toInclusive: (default: true). Means that upper limit will be included in calculations.
 *
 * @return int Number of non working days.
 * @access public
 */
    function getNonWorkingDays($fromDate, $toDate = null, $options = array()) {

        $defaults = array(  'fromInclusive' => true,
                            'toInclusive'   => true);
        $options = array_merge($defaults, $options);
        
        if (empty($toDate)) {
            $toDate = date('Y-m-d');
        }

        $count = 0;
        while ($fromDate <= $toDate) {
            $dateInSeconds = strtotime($fromDate);
            if (in_array(date('D', $dateInSeconds), array('Sat', 'Sun'))) {
                $count++;
            }
            $fromDate = date('Y-m-d', strtotime($fromDate) + 86400);
        }
        return $count;
    }

	
/**
 * Calculates difference between two dates.
 *
 * Las fechas deben estar en formato mysql (yyyy-mm-dd hh:mm:ss) aunque no completa (@see __getValidDateTime)
 *
 * @param string $fromDate La fecha desde la cual se tomara la diferencia.
 * @param string $toDate La fecha hasta la cual se tomara la diferencia. Si no se pasa la fecha hasta,
 * @param array $options
 * se tomara la fecha actual como segunda fecha.
 *
 * @return mixed 	array con dias, horas, minutos y segundos en caso de que las fechas sean validas.
 * 					False en caso de que las fechas sean invalidas.
 * @access public
 */
	function dateDiff($fromDate, $toDate = null, $options = array()) {

		$defaults = array(	'fromInclusive' => true,
						 	'toInclusive' 	=> true,
						 	'2007Bug' 		=> false);
		$options = array_merge($defaults, $options);

		/** http://www.usenet-forums.com/php-language/373199-last-day-year-date-bug-2.html */
		if (substr($fromDate, 0, 10) <= '2007-12-31' && substr($toDate, 0, 10) >= '2007-12-31') {
			$options['2007Bug'] = true;
		}
		
		if ($fromDate = Dates::__getValidDateTime($fromDate)) {
			$fromDate = strtotime($fromDate);
		} else {
			return false;
		}
		
		if ($toDate = Dates::__getValidDateTime($toDate)) {
			$toDate = strtotime($toDate);
		} else {
			return false;
		}

		if ($options['fromInclusive'] === true && $options['toInclusive'] === true) {
			$toDate += 86400;
		}

		if ($options['2007Bug'] === true) {
			$toDate += 3600;
		}


		$diff = $toDate-$fromDate;
		$daysDiff = floor($diff/60/60/24);
		$diff -= $daysDiff*86400;
		$hrsDiff = floor($diff/60/60);
		$diff -= $hrsDiff*3600;
		$minsDiff = floor($diff/60);
		$diff -= $minsDiff*60;
		$secsDiff = $diff;

		$diferencia=false;
		$diferencia['dias']=$daysDiff;
		$diferencia['horas']=$hrsDiff;
		$diferencia['minutos']=$minsDiff;
		$diferencia['segundos']=$secsDiff;
		return $diferencia;
	}


/**
 * Suma una cantidad de 'intervalo' a una fecha.
 *
 * @param string $fecha La fecha a la cual se le debe sumar el intervalo.
 * @param string $intervalo El intervalo de tiempo.
 * El intervalo puede ser:
 *		y Year
 *		q Quarter
 *		m Month
 * 		w Week
 * 		d Day
 * 		h Hour
 * 		n minute
 * 		s second
 * @param integer $cantidad La cantidad de intervalo a sumar a la fecha.
 * @return mixed La fecha en formato yyyy-mm-dd hh:mm:ss con el intervalo agregado, false si no fue posible realizar la operacion.
 * @access public
 */
	function dateAdd($fecha = null, $cantidad = 1, $intervalo = 'd', $options = array()) {

        $defaults = array('fromInclusive' => true);
        $options = array_merge($defaults, $options);

        if ($options['fromInclusive'] === true) {
            $cantidad--;
        }
        
		$validIntervalo = array('y', 'q', 'm', 'w', 'd', 'h', 'n', 's');
		if (!in_array($intervalo, $validIntervalo) || !is_numeric($cantidad)) {
			return false;
		}
		
		if ($fecha = Dates::__getValidDateTime($fecha)) {
			$fecha = strtotime($fecha);
		} else {
			return false;
		}
		$ds = getdate($fecha);

		$h = $ds['hours'];
		$n = $ds['minutes'];
		$s = $ds['seconds'];
		$m = $ds['mon'];
		$d = $ds['mday'];
		$y = $ds['year'];

		switch ($intervalo) {
			case 'y':
				$y += $cantidad;
				break;
			case 'q':
				$m +=($cantidad * 3);
				break;
			case 'm':
				$m += $cantidad;
				break;
			case 'w':
				$d +=($cantidad * 7);
				break;
			case 'd':
				$d += $cantidad;
				break;
			case 'h':
				$h += $cantidad;
				break;
			case 'n':
				$n += $cantidad;
				break;
			case 's':
				$s += $cantidad;
				break;
		}
		
		//return date('Y-m-d h:i:s', mktime($h ,$n, $s, $m ,$d, $y));
		return date('Y-m-d', mktime($h ,$n, $s, $m ,$d, $y));
	}
	
	
/**
 * Creates a date based on a starting date adding n working days.
 *
 * @param date $startDate The starting date.
 * @param integer $workingDays The number of days to add.
 * @param mixed $nonWorkingDays If string default, Argentina non working days will be used.
 *								If array of dates is specified, they'll used instead.
 * @return date 
 **/
	function dateAddWorkingDays($startDate, $workingDays = 1, $nonWorkingDays = 'default') {
 
        /** Separo la fecha en dia month y año */
        $day = date('d',strtotime($startDate));
        $month = date('m',strtotime($startDate));
        $year = date('Y',strtotime($startDate));
        
		/** Arreglo con los feriados de Argentina Ver http://www.mininterior.gov.ar/servicios/feriados2008.asp */
		$feriados = array();
		if ($nonWorkingDays === 'default') {
			$feriados = array(
				'01-01-' . $year,    // Año Nuevo
				'24-03-' . $year,    // Día Nacional de la Memoria por la Verdad y la Justicia
				'21-03-' . $year,    // Viernes Santo Festividad Cristiana
				'02-04-' . $year,    // Día del Veterano y de los Caídos en la Guerra de Malvinas (ley 26.110)
				'01-05-' . $year,    // Día del Trabajador
				'25-05-' . $year,    // Primer Gobierno Patrio
				'16-06-' . $year,    // Paso a la Inmortalidad del General Manuel Belgraon
				'09-07-' . $year,    // Día de la Independencia
				'18-08-' . $year,    // Paso a la Inmortalidad del General José de San Martín
				'12-10-' . $year,    // Día de la Raza
				'08-12-' . $year,    // Inmaculada Concepción de María
				'25-12-' . $year,    // Navidad
			);
		} elseif (is_array($nonWorkingDays)) {
			$feriados = $nonWorkingDays;
		}
        
		/** calculo el timonthtamp de la fechainicial ($desde) */
        $mkDesde    = mktime(0, 0, 0,$month, $day, $year);

		/** Calculo el timonthtamp de la fechainicial ($desde) + los dias que tiene que correrse */
        $mkResult    = mktime(0, 0, 0, $month, $day + $workingDays, $year);
 
        /** Realizo la correccion correspondiente por el fin de semana */
        switch (date('N', $mkResult)) {
            case 1: //Lunes
            $mkResult += (86400*2);//le agrego 2 dias
            break;
            case 2: //Martes
            $mkResult += (86400);//le agrego 1 dia
            break;
            case 3: //Miercoles
            case 4: //Jueves
            case 5: //Viernes
            break;
            case 6: //Sabado
            $mkResult += (86400*2);//le agrego 2 dias
            break;
            case 7:    //Domingo
            $mkResult += (86400*2);//le agrego 2 dias
            break;
        }
 
        /** Convierto las fechas en timonthtap para poder compararlos */
        $mkFeriados = array_map('strtotime', $feriados);
 
        /** Recorro los feriados para ver si mis fechas coinciden con alguno y si lo es hago la correccion necesaria. */
        foreach ($mkFeriados as $mkFecha){
            if(($mkDesde <= $mkFecha) and ($mkResult >= $mkFecha)){
                $mkResult += 86400;//le agrego 1 dia
            }
        }
 
        /** devuelvo el resultado en el formato deseado
        * $resultado = strftime('%A %e %B %Y',$mkResult);
		*/
        $resultado = date('Y-m-d', $mkResult);
        return $resultado;
    }


/**
 * Dada una fecha en alguno de los formatos admitidos, retorna una fechaHora MySql valida y completa.
 *
 * @param  string $fecha Una fecha.
 * 	Formatos Admitidos de entrada:
 *			yyyy-mm-dd hh:mm:ss
 *			yyyy-mm-dd hh:mm
 *			yyyy-mm-dd
 * @return mixed FechaHora MYSQL valida y completa (yyyy-mm-dd hh:mm:ss) en caso haber ingresado una fecha valida,
 * false en otro caso.
 * @access private
 */
	function __getValidDateTime($fecha) {
		if (empty($fecha)) {
			$fecha = date('Y-m-d H:i:s');
		} else {
			$fecha = trim($fecha);
		}
		
		if(preg_match(VALID_DATETIME_MYSQL, $fecha, $matches) || preg_match(VALID_DATE_MYSQL, $fecha, $matches)) {
			if(!isset($matches[4])) {
				$matches[4] = '00';
			}
			if(!isset($matches[5])) {
				$matches[5] = '00';
			}
			if(!isset($matches[6])) {
				$matches[6] = '00';
			}
			return $matches[1] . '-' . $matches[2] . '-' . $matches[3] . ' ' . $matches[4] . ':' . $matches[5] . ':' . $matches[6];
		} else {
			return false;
		}
	}
}

?>