<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 12 06
 * @since       PHPBoost 4.0 - 2014 09 02
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class FaqPendingItemsController extends DefaultModuleController
{
	protected function get_template_to_use()
	{
		return new FileTemplate('faq/FaqSeveralItemsController.tpl');
	}

	public function execute(HTTPRequestCustom $request)
	{
		$this->check_authorizations();

		$this->build_view($request);

		return $this->generate_response($request);
	}

	public function build_view(HTTPRequestCustom $request)
	{
		$authorized_categories = CategoriesService::get_authorized_categories();

		$result = PersistenceContext::get_querier()->select('SELECT *
		FROM '. FaqSetup::$faq_table .' faq
		LEFT JOIN '. DB_TABLE_MEMBER .' member ON member.user_id = faq.author_user_id
		WHERE approved = 0
		AND faq.id_category IN :authorized_categories
		' . (!CategoriesAuthorizationsService::check_authorizations()->moderation() ? ' AND faq.author_user_id = :user_id' : '') . '
		ORDER BY q_order ASC', array(
			'authorized_categories' => $authorized_categories,
			'user_id' => AppContext::get_current_user()->get_id()
		));

		$this->view->put_all(array(
			'C_ITEMS'            => $result->get_rows_count() > 0,
			'C_PENDING_ITEMS'    => true,
			'C_SEVERAL_ITEMS'    => $result->get_rows_count() > 1,
			'C_SINGLE_VIEW'              => $this->config->get_display_type() == FaqConfig::SIBLINGS_VIEW,
			'C_DISPLAY_CONTROLS' => $this->config->are_control_buttons_displayed(),

			'ITEMS_NUMBER' => $result->get_rows_count()
		));

		while ($row = $result->fetch())
		{
			$item = new FaqItem();
			$item->set_properties($row);

			$this->view->assign_block_vars('items', $item->get_template_vars());
		}
		$result->dispose();
	}

	private function check_authorizations()
	{
		if (!(CategoriesAuthorizationsService::check_authorizations()->write() || CategoriesAuthorizationsService::check_authorizations()->contribution() || CategoriesAuthorizationsService::check_authorizations()->moderation()))
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
	}

	private function generate_response(HTTPRequestCustom $request)
	{
		$response = new SiteDisplayResponse($this->view);

		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($this->lang['faq.pending.items'], $this->lang['faq.module.title']);
		$graphical_environment->get_seo_meta_data()->set_description($this->lang['faq.seo.description.pending']);
		$graphical_environment->get_seo_meta_data()->set_canonical_url(FaqUrlBuilder::display_pending_items());

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['faq.module.title'], FaqUrlBuilder::home());
		$breadcrumb->add($this->lang['faq.pending.items'], FaqUrlBuilder::display_pending_items());

		return $response;
	}
}
?>
