<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Patrick DUBEAU <daaxwizeman@gmail.com>
 * @version     PHPBoost 5.3 - last update: 2020 02 04
 * @since       PHPBoost 4.0 - 2013 06 03
 * @contributor mipel <mipel@phpboost.com>
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class ArticlesPrintArticlesController extends AbstractItemController
{
	private $article;

	public function execute(HTTPRequestCustom $request)
	{
		$this->check_authorizations();

		$this->build_view($request);

		return new SiteNodisplayResponse($this->view);
	}

	private function get_article()
	{
		if ($this->article === null)
		{
			$id = AppContext::get_request()->get_getint('id', 0);
			if (!empty($id))
			{
				try
				{
					$this->article = self::get_items_manager()->get_item($id);
				}
				catch (RowNotFoundException $e)
				{
					$error_controller = PHPBoostErrors::unexisting_page();
					DispatchManager::redirect($error_controller);
				}
			}
			else
				$this->article = new Article();
		}
		return $this->article;
	}

	private function build_view()
	{
		$content = preg_replace('`\[page\](.*)\[/page\]`u', '<h2>$1</h2>', $this->article->get_content());
		$this->view->put_all(array(
			'PAGE_TITLE' => $this->lang['articles.print.item'] . ' - ' . $this->article->get_title() . ' - ' . GeneralConfig::load()->get_site_name(),
			'TITLE' => $this->article->get_title(),
			'CONTENT' => FormatingHelper::second_parse($content)
		));
	}

	protected function get_template_to_use()
	{
		return new FileTemplate('framework/content/print.tpl');
	}

	protected function check_authorizations()
	{
		$article = $this->get_article();

		$not_authorized = !CategoriesAuthorizationsService::check_authorizations($article->get_id_category())->write() && (!CategoriesAuthorizationsService::check_authorizations($article->get_id_category())->moderation() && $article->get_author_user()->get_id() != AppContext::get_current_user()->get_id());

		switch ($article->get_publishing_state())
		{
			case Article::PUBLISHED:
				if (!CategoriesAuthorizationsService::check_authorizations()->read() && $not_authorized)
				{
					$error_controller = PHPBoostErrors::user_not_authorized();
					DispatchManager::redirect($error_controller);
				}
			break;
			case Article::NOT_PUBLISHED:
				if ($not_authorized)
				{
					$error_controller = PHPBoostErrors::user_not_authorized();
					DispatchManager::redirect($error_controller);
				}
			break;
			case Article::DEFERRED_PUBLICATION:
				if (!$article->is_published() && $not_authorized)
				{
					$error_controller = PHPBoostErrors::user_not_authorized();
					DispatchManager::redirect($error_controller);
				}
			break;
			default:
				$error_controller = PHPBoostErrors::unexisting_page();
				DispatchManager::redirect($error_controller);
			break;
		}
	}
}
?>
