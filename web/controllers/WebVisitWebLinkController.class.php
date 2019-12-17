<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 11 09
 * @since       PHPBoost 4.1 - 2014 08 21
*/

class WebVisitWebLinkController extends AbstractController
{
	private $weblink;

	public function execute(HTTPRequestCustom $request)
	{
		$id = $request->get_getint('id', 0);

		if (!empty($id))
		{
			try {
				$this->weblink = WebService::get_weblink('WHERE web.id = :id', array('id' => $id));
			} catch (RowNotFoundException $e) {
				$error_controller = PHPBoostErrors::unexisting_page();
				DispatchManager::redirect($error_controller);
			}
		}

		if ($this->weblink !== null && !CategoriesAuthorizationsService::check_authorizations($this->weblink->get_id_category())->read())
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
		else if ($this->weblink !== null && $this->weblink->is_visible())
		{
			$this->weblink->set_number_views($this->weblink->get_number_views() + 1);
			WebService::update_number_views($this->weblink);
			WebCache::invalidate();

			AppContext::get_response()->redirect($this->weblink->get_url()->absolute());
		}
		else
		{
			$error_controller = PHPBoostErrors::unexisting_page();
			DispatchManager::redirect($error_controller);
		}
	}
}
?>
