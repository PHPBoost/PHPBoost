<?php
// TODO add this in /kernel/framework/functions.inc.php

/**
 * @desc Returns true if the object $object implements the interface named $interface_name
 * @param object $object the object
 * @param string $interface_name the interface
 * @return boolean true if the object $object implements the interface named $interface_name
 */
function implements_interface($object, $interface_name)
{
	return in_array($interface_name, class_implements($object));
}

define('INTERFACE_IMPORT', '.int.php');
/**
 * @desc Umports a class or a lib from the web site root
 * @param string $path Path of the file to load without .class.php or .inc.php (INC_IMPORT)
 * .lib.php (LIB_IMPORT) or .int.php (INTERFACE_IMPORT) extension (for instance util/date)
 * @param string $import_type the import type. Default is CLASS_IMPORT,
 * but you could also import a library by using LIB_IMPORT (file whose extension is .inc.php)
 * or INC_IMPORT to include a .inc.php file (for example the current file, functions.inc.php).
 */
function mimport($path, $import_type = CLASS_IMPORT)
{
	require_once(PATH_TO_ROOT . '/' . $path . $import_type);
}

function mvcimport($path, $import_type = CLASS_IMPORT)
{
	mimport('blog/' . $path, $import_type);
}
?>