<?php

class PruebasController extends AppController {
	var $name = 'Pruebas';
	var $uses = null;

	function index() {
		set_include_path(get_include_path() . PATH_SEPARATOR . APP . 'vendors' . DS . 'rtf2htm');

@$fd=fopen('/tmp/test.rtf',"r");
if ($fd==NULL) {
echo "File ".$argv[1]." not found.\n\n";
exit;
}
$input = fread ($fd,filesize('/tmp/test.rtf'));
fclose ($fd);

		//$input = file_get_contents('/tmp/test.rtf');
		require("rtftohtm_prep.php");
		$input = $output;
		//d($input);
		require("rtfimages_correc.php");
		$input = $output;
		require("rtfimages.php");
		require("rtftohtm.php");
		$input=$output;
		require("htmlparser.php");
		d($output);
		$input=$output;
		require("small_meta.php");
		
$text="<!DOCTYPE html public \"-//w3c//dtd html 4.0 transitional//cs\">
<html>
<head>
<meta HTTP-Equiv=\"Content-Type\" CONTENT=\"text/html; charset=iso-8859-2\">
</head>
<body text=\"#000000\" bgcolor=\"#FFFFFF\">
<font face=\"Verdana, Helvetica CE, Arial CE, Helvetica, Arial\">
<font size=\"2\">

";

$text2="
</body>
</html>";
		$x = $text . $output . $text2;
		file_put_contents('/tmp/test1.html', $x);
		//include(ROOT. "/app/vendors/pragmatia/rtf2html.php");
		//App::import('Vendor', "rft2html", true, array(APP . "vendors" . DS . "pragmatia"), "rtf2html.php");
	}


	function xindex() {
		$xslFile = APP . "webroot" . DS . "files" . DS . "xhtml2rtf.xsl";
		$html = APP . "webroot" . DS . "files" . DS . "HelloWorld.html";
		//$html = APP . "webroot" . DS . "files" . DS . "Readme.htm";
		$out = APP . "webroot" . DS . "files" . DS . "Readme.rtf";

		// Allocate a new XSLT processor
		$xp = new XsltProcessor();
		// create a DOM document and load the XSL stylesheet
		$xsl = new DomDocument;
		$xsl->load($xslFile);
		// import the XSL styelsheet into the XSLT process
		$xp->importStylesheet($xsl);


		// create a DOM document and load the XML datat
		$xml_doc = new DomDocument;
		$xml_doc->load($html);

		// transform the XML into HTML using the XSL file
		if ($html = $xp->transformToXML($xml_doc)) {
			file_put_contents($out, $html);
		} else {
			trigger_error('XSL transformation failed.', E_USER_ERROR);
		} // if
		
	}

}
?>