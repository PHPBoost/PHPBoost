<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2018 06 14
 * @since       PHPBoost 2.0 - 2008 04 08
 * @contributor Loic ROUCHON <horn@phpboost.com>
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor mipel <mipel@phpboost.com>
*/

class DatabaseService
{
	/**
	 * @desc Indents a MySQL query.
	 * @param string $query Query to indent.
	 * @return string The indented SQL query.
	 */
	public static function indent_query($query)
	{
		$query = ' ' . TextHelper::strtolower($query) . ' ';

		//Suppression des espaces en trop.
		$query = preg_replace('`(\s){2,}(\s){2,}`u', '$1', $query);

		//Ajout d'un retour à la ligne devant les mots clés principaux.
		$query = preg_replace('`\b(' . implode('|', array('select', 'update', 'insert into', 'from', 'left join', 'right join', 'cross join', 'natural join', 'inner join', 'left outer join', 'right outer join', 'full outer join', 'full join', 'drop', 'truncate', 'where', 'order by', 'group by', 'limit', 'having', 'union')) . ')+`u', "\r\n" . '$1', $query);

		//Case des mots clés.
		$key_words = array('select', 'update', 'delete', 'insert into', 'truncate', 'alter', 'table', 'status', 'set', 'drop', 'from', 'values', 'count', 'distinct', 'having', 'left', 'right', 'join', 'natural', 'outer', 'inner', 'between', 'where', 'group by', 'order by', 'limit', 'union', 'or', 'and', 'not', 'in', 'as', 'on', 'all', 'any', 'like', 'concat', 'substring', 'collate', 'collation', 'primary', 'key', 'default', 'null', 'exists', 'status', 'show');
		$query = preg_replace_callback('`\b(' . implode('|', $key_words) . ')+\b`u', function($matches) {return TextHelper::strtoupper($matches[1]);}, $query);
		//Suppression des espaces en trop.
		$query = preg_replace('`(\s){2,}(\s){2,}`u', '$1', $query);

		return trim($query);
	}

	/**
	 * @desc Highlights a SQL query to be more readable by a human.
	 * @param string $query Query to highlight
	 * @return string HTML code corresponding to the highlighted query.
	 */
	public static function highlight_query($query)
	{
		$query = ' ' . TextHelper::strtolower($query) . ' ';

		//Suppression des espaces en trop.
		$query = preg_replace('`(\s){2,}(\s){2,}`u', '$1', $query);

		//Ajout d'un retour à la ligne devant les mots clés principaux.
		$query = preg_replace('`\b(' . implode('|', array('select', 'update', 'insert into', 'from', 'left join', 'right join', 'cross join', 'natural join', 'inner join', 'left outer join', 'right outer join', 'full outer join', 'full join', 'drop', 'truncate', 'where', 'order by', 'group by', 'limit', 'having', 'union')) . ')+`u', "\r\n" . '$1', $query);

		//Coloration des opérateurs.
		$query = preg_replace('`(' . implode('|', array_map('preg_quote', array('*', '=', ',', '!=', '<>', '>', '<', '.', '(', ')'))) . ')+`uU', '<span class="db-operator-color">$1</span>', $query);

		//Coloration des mots clés.
		$key_words = array('select', 'update', 'delete', 'insert into', 'truncate', 'alter', 'table', 'status', 'set', 'drop', 'from', 'values', 'count', 'distinct', 'having', 'left', 'right', 'join', 'natural', 'outer', 'inner', 'between', 'where', 'group by', 'order by', 'limit', 'union', 'or', 'and', 'not', 'in', 'as', 'on', 'all', 'any', 'like', 'concat', 'substring', 'collate', 'collation', 'primary', 'key', 'default', 'null', 'exists', 'status', 'show');
		$query = preg_replace_callback('`\b(' . implode('|', $key_words) . ')+\b`u', function($matches) {return '<span class="db-keywords-color">' . TextHelper::strtoupper($matches[1]) . '</span>';}, $query);
		//Coloration finale.
		$query = preg_replace('`\'(.+)\'`uU', '<span class="db-text-color">\'$1\'</span>', $query); //Coloration du texte échappé.
		$query = preg_replace('`(?<![\'#])\b([0-9]+)\b(?!\')`', '<span class="db-number-color">$1</span>', $query); //Coloration des chiffres.

		//Suppression des espaces en trop.
		$query = preg_replace('`(\s){2,}(\s){2,}`u', '$1', $query);

		return nl2br(trim($query));
	}
}
?>
