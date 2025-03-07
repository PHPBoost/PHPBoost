<?php
/**
 * @package     Content
 * @subpackage  Item\controllers
 * @copyright   &copy; 2005-2025 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2020 11 28
 * @since       PHPBoost 6.0 - 2019 12 23
*/

class DefaultAjaxDeleteItemController extends DefaultDeleteItemController
{
	protected function display_response()
	{
		$deleted_id = ($this->item !== null && $this->check_authorizations()) ? $this->item->get_id() : 0;
		return new JSONResponse(array('deleted_id' => $deleted_id, 'elements_number' => ($deleted_id > 0 ? ItemsService::get_items_manager(self::get_module()->get_id())->count() : 0)));
	}

	protected function display_unexisting_page() {}

	protected function display_user_not_authorized_page() {}
}
?>
