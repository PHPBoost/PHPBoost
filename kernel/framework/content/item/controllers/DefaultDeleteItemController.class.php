<?php
/**
 * @package     Content
 * @subpackage  Item\controllers
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2020 02 12
 * @since       PHPBoost 5.3 - 2019 12 20
*/

class DefaultDeleteItemController extends AbstractItemController
{
	protected $item;

	public function execute(HTTPRequestCustom $request)
	{
		$this->item = $this->get_item($request);

		if ($this->item !== null && $this->check_authorizations())
		{
			AppContext::get_session()->csrf_get_protect();
			
			self::get_items_manager()->delete($this->item->get_id());
			self::get_items_manager()->clear_cache();
		}
		else
			$this->display_user_not_authorized_page();
		
		$this->display_response($request);
	}

	protected function get_item(HTTPRequestCustom $request)
	{
		$id = $request->get_getint('id', 0);
		try {
			return self::get_items_manager()->get_item($id);
		} catch (RowNotFoundException $e) {
			$this->display_unexisting_page();
		}
	}

	protected function check_authorizations()
	{
		return ($this->item->is_authorized_to_manage() && !AppContext::get_current_user()->is_readonly());
	}

	protected function display_response(HTTPRequestCustom $request)
	{
		AppContext::get_response()->redirect(($request->get_url_referrer() && !TextHelper::strstr($request->get_url_referrer(), $this->get_display_item_url()) ? $request->get_url_referrer() : ModulesUrlBuilder::home()), StringVars::replace_vars($this->items_lang['items.message.success.delete'], array('title' => $this->item->get_title())));
	}

	/**
	 * @return Url url to display item
	 */
	protected function get_display_item_url()
	{
		return self::get_module()->get_configuration()->has_categories() ? ItemsUrlBuilder::display($this->item->get_category()->get_id(), $this->item->get_category()->get_rewrited_name(), $this->item->get_id(), $this->item->get_rewrited_title())->rel() : ItemsUrlBuilder::display_item($this->item->get_id(), $this->item->get_rewrited_title())->rel();
	}
}
?>
