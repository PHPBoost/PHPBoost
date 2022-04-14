<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 04 14
 * @since       PHPBoost 4.0 - 2014 11 27
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class FaqAjaxDeleteItemController extends AbstractController
{
	private $item;

	public function execute(HTTPRequestCustom $request)
	{
		$this->get_item($request);

		if ($this->item !== null && $this->item->is_authorized_to_delete())
		{
			$this->delete_item();
			$deleted_id = $this->item->get_id();
		}
		else
			$deleted_id = 0;

		return new JSONResponse(array('deleted_id' => $deleted_id));
	}

	private function delete_item()
	{
		AppContext::get_session()->csrf_post_protect();

		FaqService::delete($this->item->get_id());
		
        if (!CategoriesAuthorizationsService::check_authorizations()->write() && CategoriesAuthorizationsService::check_authorizations()->contribution())
			ContributionService::generate_cache();

		FaqService::clear_cache();
		HooksService::execute_hook_action('delete', self::$module_id, $item->get_properties());
	}

	private function get_item(HTTPRequestCustom $request)
	{
		$id = $request->get_getint('id', 0);

		if (!empty($id))
		{
			try {
				$this->item = FaqService::get_item($id);
			} catch (RowNotFoundException $e) {}
		}
	}
}
?>
