<?php
/*************************************************************************************
 * php-brief.php
 * -------------
 * Author: Nigel McNie (nigel@geshi.org)
 * Copyright: (c) 2004 Nigel McNie (http://qbnz.com/highlighter/)
 * Release Version: 1.0.7.20
 * Date Started: 2004/06/02
 *
 * PHP language file for GeSHi (brief version).
 *
 * CHANGES
 * -------
 * 2004/11/27 (1.0.3)
 *  -  Added support for multiple object splitters
 *  -  Fixed &new problem
 * 2004/10/27 (1.0.2)
 *   -  Added support for URLs
 * 2004/08/05 (1.0.1)
 *   -  Added support for symbols
 * 2004/07/14 (1.0.0)
 *   -  First Release
 *
 * TODO (updated 2004/07/14)
 * -------------------------
 * * Remove more functions that are hardly used
 *
 *************************************************************************************
 *
 *     This file is part of GeSHi.
 *
 *   GeSHi is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 *   GeSHi is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *   GNU General Public License for more details.
 *
 *   You should have received a copy of the GNU General Public License
 *   along with GeSHi; if not, write to the Free Software
 *   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 *
 ************************************************************************************/

$language_data = array (
	'LANG_NAME' => 'PHP',
	'COMMENT_SINGLE' => array(1 => '//', 2 => '#'),
	'COMMENT_MULTI' => array('/*' => '*/'),
	//Heredoc and Nowdoc syntax
	'COMMENT_REGEXP' => array(3 => '/<<<\s*?(\'?)([a-zA-Z0-9]+)\1[^\n]*?\\n.*\\n\\2(?![a-zA-Z0-9])/siU'),
    'CASE_KEYWORDS' => GESHI_CAPS_NO_CHANGE,
	'CASE_KEYWORDS' => GESHI_CAPS_NO_CHANGE,
	'QUOTEMARKS' => array("'", '"'),
	'ESCAPE_CHAR' => '\\',
	'KEYWORDS' => array(
		1 => array(
			'include', 'require', 'include_once', 'require_once',
			'for', 'as', 'foreach', 'if', 'elseif', 'else', 'while', 'do', 'endwhile', 'endif', 'switch', 'case', 'endswitch', 'endfor', 'endforeach',
			'return', 'break', 'continue'
			),
		2 => array(
			'null', '__LINE__', '__FILE__',
			 '&lt;script language', '&lt;/script&gt;',
            'void', 'int', 'string', 'bool', 'float', 'object', 'resource', 'false', 'true', 'var', 'default', 'list',
			'echo', 'print', 'global', 'static', 'exit', 'array', 'empty', 'eval', 'isset', 'unset', 'die',
			'function', 'class', 'new', '&amp;new', 'public', 'private', 'interface', 'extends',
			'__FUNCTION__', '__CLASS__', '__METHOD__', 'PHP_VERSION',
            'PHP_OS', 'DEFAULT_INCLUDE_PATH', 'PEAR_INSTALL_DIR', 'PEAR_EXTENSION_DIR',
            'PHP_EXTENSION_DIR', 'PHP_BINDIR', 'PHP_LIBDIR', 'PHP_DATADIR', 'PHP_SYSCONFDIR',
            'PHP_LOCALSTATEDIR', 'PHP_CONFIG_FILE_PATH', 'PHP_OUTPUT_HANDLER_START', 'PHP_OUTPUT_HANDLER_CONT',
            'PHP_OUTPUT_HANDLER_END', 'E_ERROR', 'E_WARNING', 'E_PARSE', 'E_NOTICE',
            'E_CORE_ERROR', 'E_CORE_WARNING', 'E_COMPILE_ERROR', 'E_COMPILE_WARNING', 'E_USER_ERROR',
            'E_USER_WARNING', 'E_USER_NOTICE', 'E_ALL'
			),
		3 => array(	
			'&lt;?php', '?&gt;'		
			)
		),
	'SYMBOLS' => array(
		),
	'CASE_SENSITIVE' => array(
		GESHI_COMMENTS => false,
		1 => false,
		2 => false,
		3 => false
		),
	'STYLES' => array(
		'KEYWORDS' => array(
            1 => 'color: #0000FF; font-weight: bold;',
            2 => 'color: #0000FF; font-weight: bold;',
            3 => 'color: #FF0000; font-weight: normal;'
            ),
        'COMMENTS' => array(
            1 => 'color: #008000; font-style: italic;',
            2 => 'color: #008000; font-style: italic;',
            'MULTI' => 'color: #008000; font-style: italic;'
            ),
        'ESCAPE_CHAR' => array(
            0 => 'color: #000099; font-weight: bold;'
            ),
        'BRACKETS' => array(
            0 => 'color: #8000FF;'
            ),
        'STRINGS' => array(
            0 => 'color: #808080;'
            ),
        'NUMBERS' => array(
            0 => 'color: #FF8000;'
            ),
        'METHODS' => array(
            1 => 'color: #000000;',
            2 => 'color: #000000;'
            ),
        'SYMBOLS' => array(
            0 => 'color: #8000FF;'
            ),
        'REGEXPS' => array(
            0 => 'color: #000080;',
            1 => 'color: #ff0000'
            ),
		'SCRIPT' => array(
			0 => '',
			1 => '',
			2 => '',
			3 => ''
			)
		),
	'URLS' => array(
		1 => '',
		2 => '',
		3 => '',
		4 => ''
		),
	'OOLANG' => true,
	'OBJECT_SPLITTERS' => array(
		1 => '-&gt;',
		2 => '::'
		),
	'REGEXPS' => array(
		0 => "[\\$]{1,2}[a-zA-Z_][a-zA-Z0-9_]*"
		),
	'STRICT_MODE_APPLIES' => GESHI_MAYBE,
	'SCRIPT_DELIMITERS' => array(
		'<?php' => '?>',
		'<?' => '?>',
		'<%' => '%>',
		'<script language="php">' => '</script>'
		),
	'HIGHLIGHT_STRICT_BLOCK' => array(
		0 => true,
		1 => true,
		2 => true,
		3 => true
        ),
    'TAB_WIDTH' => 4
);

?>
