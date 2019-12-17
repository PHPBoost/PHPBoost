<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Patrick DUBEAU <daaxwizeman@gmail.com>
 * @version     PHPBoost 5.3 - last update: 2019 12 16
 * @since       PHPBoost 4.0 - 2013 03 05
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

class ArticlesDeleteController extends ModuleController
{
	public function execute(HTTPRequestCustom $request)
	{
		AppContext::get_session()->csrf_get_protect();

		$article = $this->get_article($request);

		if (!$article->is_authorized_to_delete())
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}

		ArticlesService::delete($article->get_id());
		ArticlesService::clear_cache();

		AppContext::get_response()->redirect(($request->get_url_referrer() && !TextHelper::strstr($request->get_url_referrer(), ArticlesUrlBuilder::display_article($article->get_category()->get_id(), $article->get_category()->get_rewrited_name(), $article->get_id(), $article->get_rewrited_title())->rel()) ? $request->get_url_referrer() : ArticlesUrlBuilder::home()), StringVars::replace_vars(LangLoader::get_message('articles.message.success.delete', 'common', 'articles'), array('title' => $article->get_title())));
	}

	private function get_article(HTTPRequestCustom $request)
	{
		$id = $request->get_getint('id', 0);
		if (!empty($id))
		{
			try {
				return ArticlesService::get_article('WHERE articles.id=:id', array('id' => $id));
			} catch (RowNotFoundException $e) {
				$error_controller = PHPBoostErrors::unexisting_page();
				DispatchManager::redirect($error_controller);
			}
		}
	}
}
?>
