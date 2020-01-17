<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Patrick DUBEAU <daaxwizeman@gmail.com>
 * @version     PHPBoost 5.3 - last update: 2020 01 17
 * @since       PHPBoost 4.0 - 2013 02 27
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class ArticlesService extends ItemsManager
{
	public static function update_number_view(Article $article)
	{
		self::$db_querier->update(self::$items_table, array('number_view' => $article->get_number_view()), 'WHERE id=:id', array('id' => $article->get_id()));
	}
}
?>
