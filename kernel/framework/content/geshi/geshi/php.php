<?php
/*************************************************************************************
 * php-brief.php
 * -------------
 * Author: Nigel McNie (nigel@geshi.org)
 * Copyright: (c) 2004 Nigel McNie (http://qbnz.com/highlighter/)
 * Release Version: 1.0.8.3
 * Date Started: 2004/06/02
 *
 * PHP (brief version) language file for GeSHi.
 *
 * CHANGES
 * -------
 * 2008/05/23 (1.0.7.22)
 *  -  Added description of extra language features (SF#1970248)
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
    'QUOTEMARKS' => array("'", '"'),
    'ESCAPE_CHAR' => '\\',
    'HARDQUOTE' => array("'", "'"),
    'HARDESCAPE' => array("\'"),
    'NUMBERS' =>
        GESHI_NUMBER_INT_BASIC |  GESHI_NUMBER_OCT_PREFIX | GESHI_NUMBER_HEX_PREFIX |
        GESHI_NUMBER_FLT_SCI_ZERO,
    'KEYWORDS' => array(
        1 => array(
            'include', 'require', 'include_once', 'require_once',
            'for', 'as', 'foreach', 'if', 'elseif', 'else', 'while', 'do', 'endwhile', 'endif', 'switch', 'case', 'endswitch',
            'return', 'break'
            ),
        2 => array(
            'null', '__LINE__', '__FILE__',
            'false', '&lt;?php',
            'true', 'var', 'default',
            'function', 'class', 'new', '&amp;new', 'public', 'private', 'interface', 'extends',
            'const', 'self'
            ),
        3 => array(
            '&lt;?php', '?&gt;'		
			)
        ),
    'SYMBOLS' => array(
        1 => array(
            '<%', '<%=', '%>', '<?', '<?=', '?>'
            ),
        0 => array(
            '(', ')', '[', ']', '{', '}',
            '!', '@', '%', '&', '|', '/',
            '<', '>',
            '=', '-', '+', '*',
            '.', ':', ',', ';'
            )
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
            3 => 'color: #008000; font-style: italic;',
            'MULTI' => 'color: #008000; font-style: italic;'
            ),
        'ESCAPE_CHAR' => array(
            0 => 'color: #000099; font-weight: bold;',
            'HARD' => 'color: #000099; font-weight: bold;'
            ),
        'BRACKETS' => array(
            0 => 'color: #8000FF;'
            ),
        'STRINGS' => array(
            0 => 'color: #808080;',
            'HARD' => 'color: #808080;'
            ),
        'NUMBERS' => array(
            0 => 'color: #FF8000;',
            GESHI_NUMBER_OCT_PREFIX => 'color: #208080;',
            GESHI_NUMBER_HEX_PREFIX => 'color: #208080;',
            GESHI_NUMBER_FLT_SCI_ZERO => 'color:#800080;',
            ),
        'METHODS' => array(
            1 => 'color: #004000;',
            2 => 'color: #004000;'
            ),
        'SYMBOLS' => array(
            0 => 'color: #8000FF;',
            1 => 'color: #000000; font-weight: bold;'
            ),
        'REGEXPS' => array(
            0 => 'color: #000080;'
            ),
        'SCRIPT' => array(
            0 => '',
            1 => '',
            2 => '',
            3 => '',
            4 => '',
            5 => ''
            )
        ),
    'URLS' => array(
        1 => '',
        2 => '',
        3 => 'http://www.php.net/{FNAMEL}'
        ),
    'OOLANG' => true,
    'OBJECT_SPLITTERS' => array(
        1 => '-&gt;',
        2 => '::'
        ),
    'REGEXPS' => array(
        //Variables
        0 => "[\\$]{1,2}[a-zA-Z_][a-zA-Z0-9_]*"
        ),
    'STRICT_MODE_APPLIES' => GESHI_MAYBE,
    'SCRIPT_DELIMITERS' => array(
        0 => array(
            '<?php' => '?>'
            ),
        1 => array(
            '<?' => '?>'
            ),
        2 => array(
            '<%' => '%>'
            ),
        3 => array(
            '<script language="php">' => '</script>'
            ),
        4 => "/(<\?(?:php)?)(?:'(?:[^'\\\\]|\\\\.)*?'|\"(?:[^\"\\\\]|\\\\.)*?\"|\/\*(?!\*\/).*?\*\/|.)*?(\?>|\Z)/sm",
        5 => "/(<%)(?:'(?:[^'\\\\]|\\\\.)*?'|\"(?:[^\"\\\\]|\\\\.)*?\"|\/\*(?!\*\/).*?\*\/|.)*?(%>|\Z)/sm"
        ),
    'HIGHLIGHT_STRICT_BLOCK' => array(
        0 => true,
        1 => true,
        2 => true,
        3 => true,
        4 => true,
        5 => true
        ),
    'TAB_WIDTH' => 4
);

?>
