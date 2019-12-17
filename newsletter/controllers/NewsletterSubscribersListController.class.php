<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 10 11
 * @since       PHPBoost 3.0 - 2011 03 11
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class NewsletterSubscribersListController extends ModuleController
{
	private $lang;
	private $view;
	private $user;
	private $stream;

	private $nbr_subscribers_per_page = 25;

	public function execute(HTTPRequestCustom $request)
	{
		$this->stream = NewsletterStreamsCache::load()->get_stream($request->get_int('id_stream', 0));

		if ($this->stream->get_id() == 0)
		{
			AppContext::get_response()->redirect(NewsletterUrlBuilder::home());
		}

		$this->init();
		$this->build_form($request);

		return $this->generate_response($request);
	}

	private function build_form(HTTPRequestCustom $request)
	{
		$field = $request->get_value('field', 'name');
		$sort = $request->get_value('sort', 'top');
		$current_page = $request->get_int('page', 1);

		if (!NewsletterAuthorizationsService::id_stream($this->stream->get_id())->read_subscribers())
		{
			NewsletterAuthorizationsService::get_errors()->read_subscribers();
		}

		$mode = ($sort == 'top') ? 'ASC' : 'DESC';
		switch ($field)
		{
			case 'mail' :
				$field_bdd = 'user_mail';
			break;
			default :
				$field_bdd = 'name';
		}

		$subscribers_list = NewsletterService::list_subscribers_by_stream($this->stream->get_id());

		$nbr_subscribers = count($subscribers_list);

		$pagination = new ModulePagination($current_page, $nbr_subscribers, $this->nbr_subscribers_per_page);
		$pagination->set_url(NewsletterUrlBuilder::subscribers($this->stream->get_id(), $this->stream->get_rewrited_name(), $field, $sort, '%d'));

		if ($pagination->current_page_is_empty() && $current_page > 1)
		{
			$error_controller = PHPBoostErrors::unexisting_page();
			DispatchManager::redirect($error_controller);
		}

		$this->view->put_all(array(
			'C_SUBSCRIBERS' => (int)$nbr_subscribers,
			'C_SUBSCRIPTION' => NewsletterUrlBuilder::subscribe()->rel(),
			'C_PAGINATION' => $pagination->has_several_pages(),
			'SORT_NAME_TOP' => NewsletterUrlBuilder::subscribers($this->stream->get_id(), $this->stream->get_rewrited_name(), 'name', 'top', $current_page)->rel(),
			'SORT_NAME_BOTTOM' => NewsletterUrlBuilder::subscribers($this->stream->get_id(), $this->stream->get_rewrited_name(), 'nae', 'bottom', $current_page)->rel(),
			'SORT_MAIL_TOP' => NewsletterUrlBuilder::subscribers($this->stream->get_id(), $this->stream->get_rewrited_name(), 'mail', 'top', $current_page)->rel(),
			'SORT_MAIL_BOTTOM' => NewsletterUrlBuilder::subscribers($this->stream->get_id(), $this->stream->get_rewrited_name(), 'mail', 'bottom', $current_page)->rel(),
			'PAGINATION' => $pagination->display()
		));

		if (!empty($nbr_subscribers))
		{
			$result = PersistenceContext::get_querier()->select("SELECT subscribers.id, subscribers.user_id, COALESCE(NULLIF(subscribers.mail, ''), member.email) AS user_mail, COALESCE(NULLIF(member.display_name, ''), '" . LangLoader::get_message('visitor', 'user-common') . "') AS name
			FROM " . NewsletterSetup::$newsletter_table_subscribers . " subscribers
			LEFT JOIN " . DB_TABLE_MEMBER . " member ON subscribers.user_id = member.user_id
			WHERE subscribers.id IN :ids_list
			ORDER BY ". $field_bdd ." ". $mode ."
			LIMIT :number_items_per_page OFFSET :display_from",
				array(
					'ids_list' => array_keys($subscribers_list),
					'number_items_per_page' => $pagination->get_number_items_per_page(),
					'display_from' => $pagination->get_display_from()
				)
			);

			while ($row = $result->fetch())
			{
				if ($row['user_mail'])
				{
					$this->view->assign_block_vars('subscribers_list', array(
						'C_AUTH_MODO' => NewsletterAuthorizationsService::id_stream($this->stream->get_id())->moderation_subscribers(),
						'C_MEMBER' => $row['user_id'] > 0,
						'C_EDIT' => $row['user_id'] == User::VISITOR_LEVEL,
						'U_EDIT' => $row['user_id'] == User::VISITOR_LEVEL ? NewsletterUrlBuilder::edit_subscriber($row['id'])->rel() : '',
						'U_DELETE' => NewsletterUrlBuilder::delete_subscriber($row['id'], $this->stream->get_id())->rel(),
						'NAME' => $row['name'],
						'MAIL' => $row['user_mail'],
						'U_USER_PROFILE' => UserUrlBuilder::profile($row['user_id'])->rel()
					));
				}
			}
			$result->dispose();
		}
	}

	private function init()
	{
		$this->lang = LangLoader::get('common', 'newsletter');
		$this->view = new FileTemplate('newsletter/NewsletterSubscribersListController.tpl');
		$this->view->add_lang($this->lang);
		$this->user = AppContext::get_current_user();
	}

	private function generate_response(HTTPRequestCustom $request)
	{
		$sort_field = $request->get_getvalue('field', 'name');
		$sort_mode = $request->get_getvalue('sort', 'top');
		$page = $request->get_getint('page', 1);

		$body_view = new FileTemplate('newsletter/NewsletterBody.tpl');
		$body_view->add_lang($this->lang);
		$body_view->put('TEMPLATE', $this->view);
		$response = new SiteDisplayResponse($body_view);
		$breadcrumb = $response->get_graphical_environment()->get_breadcrumb();
		$breadcrumb->add($this->lang['newsletter'], NewsletterUrlBuilder::home()->rel());
		$name_page = $this->lang['newsletter.subscribers'] . ' : ' . $this->stream->get_name();
		$breadcrumb->add($name_page, NewsletterUrlBuilder::subscribers($this->stream->get_id(), $this->stream->get_rewrited_name(), $sort_field, $sort_mode, $page)->rel());

		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($name_page, $this->lang['newsletter'], $page);
		$graphical_environment->get_seo_meta_data()->set_description(StringVars::replace_vars($this->lang['newsletter.seo.suscribers.list'], array('name' => $this->stream->get_name())), $page);
		$graphical_environment->get_seo_meta_data()->set_canonical_url(NewsletterUrlBuilder::subscribers($this->stream->get_id(), $this->stream->get_rewrited_name(), $sort_field, $sort_mode, $page));

		return $response;
	}
}
?>
