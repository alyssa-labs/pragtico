<?php
/**
 * Util methods.
 *
 * PHP versions 5
 *
 * @filesource
 * @copyright       Copyright 2007-2008, Pragmatia de RPB S.A.
 * @link            http://www.pragmatia.com
 * @package         pragtico
 * @subpackage      app.vendors.pragmatia
 * @since           Pragtico v 1.0.0
 * @version         $Revision: 54 $
 * @modifiedby      $LastChangedBy: mradosta $
 * @lastmodified    $Date: 2008-10-23 23:14:28 -0300 (Thu, 23 Oct 2008) $
 * @author          Martin Radosta <mradosta@pragmatia.com>
 */
/**
 * Util methods.
 *
 * @package pragtico
 * @subpackage app.vendors.pragmatia
 */
class Utils {

/**
 * Replaces non-ascii characters by ascii ones.
 */
    function replaceNonAsciiCharacters($text) {
        return str_replace(
            array('á', 'é', 'í', 'ó', 'ú', 'Á', 'É', 'Í', 'Ó', 'Ú', 'ñ', 'Ñ', '°'),
            array('a', 'e', 'i', 'o', 'u', 'A', 'E', 'I', 'O', 'U', 'n', 'N', ''), $text);
    }


/**
 * Normalize text input to a given length and pad based on it's type.
 *
 *
*/
    function normalizeText($text, $long, $type = 'text', $options = array()) {
        if ($type == 'text') {
            $__default = array('pad' => STR_PAD_RIGHT, 'character' => ' ');
            $text = Utils::replaceNonAsciiCharacters($text);
        } elseif ($type == 'number') {
            $__default = array('pad' => STR_PAD_LEFT, 'character' => '0');
        } else {
            return $text;
        }

        $options = array_merge($__default, $options);
        return str_pad(substr($text, 0, $long), $long, $options['character'], $options['pad']);
    }
        
}

?>