<?php
/*##################################################
 *                           sql2mysql_query_translator.class.php
 *                            -------------------
 *   begin                : October 2, 2009
 *   copyright            : (C) 2009 Loic Rouchon
 *   email                : horn@phpboost.com
 *
 *
 ###################################################
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

/**
 * @author loic rouchon <loic.rouchon@phpboost.com>
 * @package sql
 * @subpackage mysql
 * @desc translates the generic query <code>$query</code> into the mysql specific dialect
 */
class SQL2MySQLQueryTranslator
{
    /**
     * @var string
     */
    private static $query;

    /**
     * @desc translates the generic query <code>$query</code> into the mysql specific dialect
     * @param string $query the query to translate
     * @return string the translated query
     */
    public static function translate($query)
    {
        self::$query = $query;
         
        echo '<hr />' . self::$query . '<hr />';
        self::translate_operators();
        self::translate_functions();
        echo '<hr />' . self::$query . '<hr />';
        return self::$query;
    }

    private static function translate_operators()
    {
        self::$query = preg_replace_callback('`[\w:_\']+(?:\s*\|\|\s*[\w:_\']+)+`',
        array('SQL2MySQLQueryTranslator', 'concat_callback'), self::$query);
    }
    
    private static function translate_functions()
    {
        self::$query = preg_replace('`ft_search\(\s*(.+)\s*,\s*(.+)\s*\)`iU',
        'match($1) against($2)', self::$query);
        self::$query = preg_replace('`ft_search_relevance\(\s*(.+)\s*,\s*(.+)\s*\)`iU',
        'match($1) against($2)', self::$query);
    }
    
    private static function concat_callback($matches)
    {
        $parameters = explode('||', $matches[0]);
        return 'concat(' . implode(',', $parameters) .')';
    }
}

?>