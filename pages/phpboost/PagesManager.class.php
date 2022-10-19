<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 03 16
 * @since       PHPBoost 6.0 - 2021 02 20
*/

class PagesManager extends ItemsManager
{
	/**
	 * @desc Initialize the additional parameters to custom the request to get items in the order set on the reorder page.
	 */
	protected function init_get_items_additional_parameters()
	{
		$this->get_items_static_sort_field = 'i_order';
	}
	
	/**
	 * @desc Update the position of an item.
	 * @param string[] $id_item : id of the item to update
	 * @param string[] $position : new item position
	 */
	public function update_position($id_item, $position)
	{
		self::$db_querier->update(self::$items_table, array('i_order' => $position), 'WHERE id=:id', array('id' => $id_item));
	}
}
?>
