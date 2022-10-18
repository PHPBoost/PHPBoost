<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 12 16
 * @since       PHPBoost 3.0 - 2011 03 13
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class NewsletterHomeController extends DefaultModuleController
{
	private $full_view;

	protected function get_template_to_use()
	{
		return new FileTemplate('newsletter/NewsletterHomeController.tpl');
	}

	public function execute(HTTPRequestCustom $request)
	{
		$this->check_authorizations();

		$this->init();

		$this->build_form($request);

		return $this->generate_response($request);
	}

	private function init()
	{
		$this->full_view = new FileTemplate('newsletter/NewsletterBody.tpl');
		$this->full_view->add_lang($this->lang);
	}

	private function build_form(HTTPRequestCustom $request)
	{
		$pagination = $this->get_pagination();

		$result = PersistenceContext::get_querier()->select('SELECT @id_stream:= id, newsletter_streams.*,
			(SELECT COUNT(*)
			FROM ' . NewsletterSetup::$newsletter_table_subscriptions . ' subscriptions
			LEFT JOIN ' . NewsletterSetup::$newsletter_table_subscribers . ' subscribers ON subscriptions.subscriber_id = subscribers.id
			LEFT JOIN ' . DB_TABLE_MEMBER . ' member ON subscribers.user_id = member.user_id
			WHERE (subscribers.mail <> \'\' OR member.email <> \'\') AND subscriptions.stream_id = @id_stream
			) AS subscribers_number
		FROM ' . NewsletterSetup::$newsletter_table_streams . ' newsletter_streams
		LIMIT :number_items_per_page OFFSET :display_from',
			array(
				'number_items_per_page' => $pagination->get_number_items_per_page(),
				'display_from' => $pagination->get_display_from()
		));

		while ($row = $result->fetch())
		{
			if (NewsletterAuthorizationsService::id_stream($row['id'])->read())
			{
				$category = new RichCategory();
				$category->set_properties($row);
				$category_thumbnail = $category->get_thumbnail()->rel();

				$this->view->assign_block_vars('streams_list', array(
					'C_THUMBNAIL' => !empty($category_thumbnail),
					'C_VIEW_ARCHIVES' => NewsletterAuthorizationsService::id_stream($row['id'])->read_archives(),
					'C_VIEW_SUBSCRIBERS' => NewsletterAuthorizationsService::id_stream($row['id'])->read_subscribers(),
					'U_THUMBNAIL' => $category_thumbnail,
					'NAME' => $category->get_name(),
					'DESCRIPTION' => $category->get_description(),
					'SUBSCRIBERS_NUMBER' => $row['subscribers_number'],
					'U_VIEW_ARCHIVES' => NewsletterUrlBuilder::archives($row['id'], $row['rewrited_name'])->absolute(),
					'U_VIEW_SUBSCRIBERS' => NewsletterUrlBuilder::subscribers($row['id'], $row['rewrited_name'])->absolute(),
				));
			}
		}

		$this->view->put_all(array(
			'C_SUSCRIBE' => NewsletterAuthorizationsService::check_authorizations()->subscribe(),
			'C_STREAMS' => $result->get_rows_count() != 0,
			'C_PAGINATION' => $pagination->has_several_pages(),
			'PAGINATION' => $pagination->display()
		));

		$result->dispose();

		$this->full_view->put('TEMPLATE', $this->view);
	}

	private function check_authorizations()
	{
		if (!NewsletterAuthorizationsService::default_authorizations()->read())
		{
			NewsletterAuthorizationsService::get_errors()->read();
		}
	}

	private function get_pagination()
	{
		$nbr_streams = PersistenceContext::get_querier()->count(NewsletterSetup::$newsletter_table_streams);

		$page = AppContext::get_request()->get_getint('page', 1);
		$pagination = new ModulePagination($page, $nbr_streams, $this->config->get_streams_number_per_page());
		$pagination->set_url(NewsletterUrlBuilder::home('%d'));

		if ($pagination->current_page_is_empty() && $page > 1)
		{
			$error_controller = PHPBoostErrors::unexisting_page();
			DispatchManager::redirect($error_controller);
		}

		return $pagination;
	}

	private function generate_response(HTTPRequestCustom $request)
	{
		$page = $request->get_getint('page', 1);

		$response = new SiteDisplayResponse($this->full_view);
		$breadcrumb = $response->get_graphical_environment()->get_breadcrumb();
		$breadcrumb->add($this->lang['newsletter.module.title'], NewsletterUrlBuilder::home()->absolute());
		$breadcrumb->add($this->lang['newsletter.items.list'], NewsletterUrlBuilder::home($page)->absolute());

		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($this->lang['newsletter.items.list'], $this->lang['newsletter.module.title'], $page);
		$graphical_environment->get_seo_meta_data()->set_description(StringVars::replace_vars($this->lang['newsletter.seo.home'], array('site' => GeneralConfig::load()->get_site_name())));
		$graphical_environment->get_seo_meta_data()->set_canonical_url(NewsletterUrlBuilder::home($page));

		return $response;
	}

	public static function get_view()
	{
		$object = new self();
		$object->check_authorizations();
		$object->init();
		$object->build_form(AppContext::get_request());
		return $object->full_view;
	}
}
?>
