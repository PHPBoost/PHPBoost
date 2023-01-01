<?php
/**
 * @package     Content
 * @subpackage  Item\controllers
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 01 12
 * @since       PHPBoost 6.0 - 2019 12 20
*/

class DefaultDeleteItemController extends AbstractItemController
{
	/**
	 * @var Item
	 */
	protected $item;

	public function execute(HTTPRequestCustom $request)
	{
		$this->item = $this->get_item();

		if ($this->item !== null && $this->check_authorizations())
		{
			AppContext::get_session()->csrf_get_protect();
			
			self::get_items_manager()->delete($this->item);
			self::get_items_manager()->clear_cache();
			HooksService::execute_hook_action('delete', self::$module_id, $this->item->get_properties());
			
			if (self::get_module_configuration()->has_contribution() && ((self::get_module_configuration()->has_categories() && !CategoriesAuthorizationsService::check_authorizations(Category::ROOT_CATEGORY, self::$module_id)->write() && CategoriesAuthorizationsService::check_authorizations(Category::ROOT_CATEGORY, self::$module_id)->contribution()) || (!self::get_module_configuration()->has_categories() && !ItemsAuthorizationsService::check_authorizations(self::$module_id)->write() && ItemsAuthorizationsService::check_authorizations(self::$module_id)->contribution())))
				ContributionService::generate_cache();
		}
		else
			$this->display_user_not_authorized_page();
		
		$this->display_response();
	}

	protected function get_item()
	{
		$id = $this->request->get_getint('id', 0);
		try {
			return self::get_items_manager()->get_item($id);
		} catch (RowNotFoundException $e) {
			$this->display_unexisting_page();
		}
	}

	protected function check_authorizations()
	{
		return ($this->item->is_authorized_to_delete() && !AppContext::get_current_user()->is_readonly());
	}

	protected function display_response()
	{
		AppContext::get_response()->redirect(($this->request->get_url_referrer() && !TextHelper::strstr($this->request->get_url_referrer(), $this->get_display_item_url()) ? $this->request->get_url_referrer() : ModulesUrlBuilder::home()), StringVars::replace_vars($this->lang['items.message.success.delete'], array('title' => $this->item->get_title())));
	}

	/**
	 * @return Url url to display item
	 */
	protected function get_display_item_url()
	{
		return self::get_module_configuration()->has_categories() ? ItemsUrlBuilder::display($this->item->get_category()->get_id(), $this->item->get_category()->get_rewrited_name(), $this->item->get_id(), $this->item->get_rewrited_title())->rel() : ItemsUrlBuilder::display_item($this->item->get_id(), $this->item->get_rewrited_title())->rel();
	}
}
?>
