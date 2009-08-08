<?php

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

?>