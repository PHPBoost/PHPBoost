<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Patrick DUBEAU <daaxwizeman@gmail.com>
 * @version     PHPBoost 6.1 - last update: 2025 02 19
 * @since       PHPBoost 4.0 - 2013 06 03
 * @contributor mipel <mipel@phpboost.com>
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class ArticlesPrintItemController extends AbstractItemController
{
	public function execute(HTTPRequestCustom $request)
	{
		$this->get_item();
		$this->check_authorizations();

		$this->build_view();

		return new SiteNodisplayResponse($this->view);
	}

	private function get_item()
	{
		if ($this->item === null)
		{
			try
			{
				$this->item = self::get_items_manager()->get_item($this->request->get_getint('id', 0));
			}
			catch (RowNotFoundException $e)
			{
				$this->display_unexisting_page();
			}
		}
		return $this->item;
	}

	private function build_view()
	{
		$content = preg_replace('`\[page\](.*)\[/page\]`u', '<h2>$1</h2>', $this->item->get_content());
		$this->view->put_all(array(
			'PAGE_TITLE' => $this->lang['articles.print.item'] . ' - ' . $this->item->get_title() . ' - ' . GeneralConfig::load()->get_site_name(),
			'TITLE' 	 => $this->item->get_title(),
			'CONTENT' 	 => FormatingHelper::second_parse($content)
        ));
	}

	protected function get_template_to_use()
	{
		return new FileTemplate('framework/content/print.tpl');
	}

	protected function check_authorizations()
	{
		$not_authorized = !CategoriesAuthorizationsService::check_authorizations($this->item->get_id_category())->write() && (!CategoriesAuthorizationsService::check_authorizations($this->item->get_id_category())->moderation() && $this->item->get_author_user()->get_id() != AppContext::get_current_user()->get_id());

		switch ($this->item->get_publishing_state())
		{
			case ArticlesItem::PUBLISHED:
				if (!CategoriesAuthorizationsService::check_authorizations()->read() || $not_authorized)
				{
					$this->display_user_not_authorized_page();
				}
			break;
			case ArticlesItem::NOT_PUBLISHED:
				if ($not_authorized)
				{
					$this->display_user_not_authorized_page();
				}
			break;
			case ArticlesItem::DEFERRED_PUBLICATION:
				if (!$this->item->is_published() && $not_authorized)
				{
					$this->display_user_not_authorized_page();
				}
			break;
			default:
				$this->display_unexisting_page();
			break;
		}
	}
}
?>
