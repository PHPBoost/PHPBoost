<?php
/**
 * @package     Builder
 * @subpackage  Table\filter\sql
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 04 16
 * @since       PHPBoost 6.0 - 2021 04 16
*/

class HTMLTableContainsTextSQLFilter extends HTMLTableEscapedLikeTextSQLFilter
{
	/**
	 * {@inheritdoc}
	 */
	protected function get_value()
	{
		return '%' . parent::get_value() . '%';
	}
}

?>
