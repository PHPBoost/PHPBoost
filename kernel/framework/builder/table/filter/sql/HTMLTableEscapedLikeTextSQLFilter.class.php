<?php
/**
 * @package     Builder
 * @subpackage  Table\filter\sql
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2010 03 02
*/

abstract class HTMLTableEscapedLikeTextSQLFilter extends HTMLTableLikeTextSQLFilter
{
	/**
	 * {@inheritdoc}
	 */
	protected function set_value($value)
	{
		parent::set_value(str_replace('%', '', $value));
	}
}

?>
