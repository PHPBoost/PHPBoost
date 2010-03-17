<?php
/**
 * xmlhttprequest.php
 *
 */

$fname = $_SERVER['PHP_SELF'];
if( strpos($fname, '/online/xmlhttprequest.php') !== FALSE)
{
	$fname = dirname($fname).'/online.php';
	$_SERVER['PHP_SELF'] = $fname;
	$_SERVER['QUERY_STRING'] = '';
}

require_once('../kernel/begin.php');
require_once('../online/online_begin.php');
require_once('../kernel/header_no_display.php');
require_once('../online/online_mini.php');

if (!empty($_GET['mini']))
{
	$tmp = @file_get_contents('../cache/menus.php');
	if ($tmp === FALSE)
		die ('Erreur');
		
	$matches = array();
	$i = @preg_match('/online_mini\((\d+),(\d+)\)/', $tmp, $matches);
	if ($i === FALSE || $i == 0)
		die ('Erreur');

	echo( online_mini($matches[1], $matches[2], TRUE) );
}
elseif (!empty($_GET['page']))
{
	echo( get_online(TRUE) );
}
elseif( !empty($_GET['display_desc']) )
{
	$toggle = switch_display(intval($_GET['display_desc']));
	echo $toggle;
}

?>