<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2018 12 01
 * @since       PHPBoost 3.0 - 2011 03 21
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

class NewsletterArchivesController extends ModuleController
{
	private $lang;
	private $view;
	private $stream;

	private $nbr_archives_per_page = 25;

	public function execute(HTTPRequestCustom $request)
	{
		$this->stream = NewsletterStreamsCache::load()->get_stream($request->get_int('id_stream', 0));

		$this->init();
		$this->build_form($request);

		return $this->generate_response($request);
	}

	private function build_form(HTTPRequestCustom $request)
	{
		$field = $request->get_value('field', NewsletterUrlBuilder::DEFAULT_SORT_FIELD);
		$sort = $request->get_value('sort', NewsletterUrlBuilder::DEFAULT_SORT_MODE);
		$current_page = $request->get_int('page', 1);
		$mode = ($sort == 'top') ? 'ASC' : 'DESC';

		if (!NewsletterAuthorizationsService::id_stream($this->stream->get_id())->read_archives())
		{
			NewsletterAuthorizationsService::get_errors()->read_archives();
		}

		if (!NewsletterStreamsCache::load()->stream_exists($this->stream->get_id()))
		{
			$controller = new UserErrorController(LangLoader::get_message('error', 'status-messages-common'), LangLoader::get_message('admin.stream-not-existed', 'common', 'newsletter'));
			DispatchManager::redirect($controller);
		}

		switch ($field)
		{
			case 'stream' :
				$field_bdd = 'stream_id';
			break;
			case 'subject' :
				$field_bdd = 'subject';
			break;
			case 'date' :
				$field_bdd = 'timestamp';
			break;
			case 'subscribers' :
				$field_bdd = 'nbr_subscribers';
			break;
			default :
				$field_bdd = 'timestamp';
		}

		$stream_id = $this->stream->get_id();
		$stream_condition = $stream_id ? "WHERE stream_id = '" . $stream_id . "'" : "";
		$nbr_archives = PersistenceContext::get_querier()->count(NewsletterSetup::$newsletter_table_archives, $stream_condition);

		$pagination = $this->get_pagination($current_page, $nbr_archives, $field, $sort);

		$moderation_auth = NewsletterAuthorizationsService::id_stream($this->stream->get_id())->moderation_archives();

		$this->view->put_all(array(
			'C_MODERATE' => $moderation_auth,
			'C_ARCHIVES' => (float)$nbr_archives,
			'C_SPECIFIC_STREAM' => $stream_id,
			'C_PAGINATION' => $pagination->has_several_pages(),
			'NUMBER_COLUMN' => 3 + (int)(empty($stream_id) && !empty($nbr_archives)) + $moderation_auth,
			'SORT_STREAM_TOP' => NewsletterUrlBuilder::archives($this->stream->get_id(), $this->stream->get_rewrited_name(), 'stream', 'top', $current_page)->rel(),
			'SORT_STREAM_BOTTOM' => NewsletterUrlBuilder::archives($this->stream->get_id(), $this->stream->get_rewrited_name(), 'stream', 'bottom', $current_page)->rel(),
			'SORT_SUBJECT_TOP' => NewsletterUrlBuilder::archives($this->stream->get_id(), $this->stream->get_rewrited_name(), 'subject', 'top', $current_page)->rel(),
			'SORT_SUBJECT_BOTTOM' => NewsletterUrlBuilder::archives($this->stream->get_id(), $this->stream->get_rewrited_name(), 'subject', 'bottom', $current_page)->rel(),
			'SORT_DATE_TOP' => NewsletterUrlBuilder::archives($this->stream->get_id(), $this->stream->get_rewrited_name(), 'date', 'top', $current_page)->rel(),
			'SORT_DATE_BOTTOM' => NewsletterUrlBuilder::archives($this->stream->get_id(), $this->stream->get_rewrited_name(), 'date', 'bottom', $current_page)->rel(),
			'SORT_SUBSCRIBERS_TOP' => NewsletterUrlBuilder::archives($this->stream->get_id(), $this->stream->get_rewrited_name(), 'subscribers', 'top', $current_page)->rel(),
			'SORT_SUBSCRIBERS_BOTTOM' => NewsletterUrlBuilder::archives($this->stream->get_id(), $this->stream->get_rewrited_name(), 'subscribers', 'bottom', $current_page)->rel(),
			'PAGINATION' => $pagination->display()
		));

		$result = PersistenceContext::get_querier()->select("SELECT *
		FROM " . NewsletterSetup::$newsletter_table_archives . "
		". $stream_condition ."
		ORDER BY ". $field_bdd ." ". $mode ."
		LIMIT :number_items_per_page OFFSET :display_from",
			array(
				'number_items_per_page' => $pagination->get_number_items_per_page(),
				'display_from' => $pagination->get_display_from()
			)
		);

		while ($row = $result->fetch())
		{
			$stream = NewsletterStreamsCache::load()->get_stream($row['stream_id']);

			$date = new Date($row['timestamp'], Timezone::SERVER_TIMEZONE);

			$this->view->assign_block_vars('archives_list', array_merge(
				Date::get_array_tpl_vars($date, 'date'),
				array(
				'STREAM_NAME' => $stream->get_name(),
				'SUBJECT' => $row['subject'],
				'NBR_SUBSCRIBERS' => $row['nbr_subscribers'],
				'U_VIEW_STREAM' => NewsletterUrlBuilder::archives($stream->get_id(), $this->stream->get_rewrited_name())->rel(),
				'U_VIEW_ARCHIVE' => NewsletterUrlBuilder::archive($row['id'])->rel(),
				'U_DELETE_ARCHIVE' => NewsletterUrlBuilder::delete_archive($row['id'], $stream->get_id())->rel()
				)
			));
		}
		$result->dispose();
	}

	private function init()
	{
		$this->lang = LangLoader::get('common', 'newsletter');
		$this->view = new FileTemplate('newsletter/NewsletterArchivesController.tpl');
		$this->view->add_lang($this->lang);
	}

	private function get_pagination($current_page, $nbr_archives, $field, $sort)
	{
		$pagination = new ModulePagination($current_page, $nbr_archives, $this->nbr_archives_per_page);
		$pagination->set_url(NewsletterUrlBuilder::archives($this->stream->get_id(), $this->stream->get_rewrited_name(), $field, $sort, '%d'));

		if ($pagination->current_page_is_empty() && $current_page > 1)
		{
			$error_controller = PHPBoostErrors::unexisting_page();
			DispatchManager::redirect($error_controller);
		}

		return $pagination;
	}

	private function generate_response(HTTPRequestCustom $request)
	{
		$sort_field = $request->get_getvalue('field', 'pseudo');
		$sort_mode = $request->get_getvalue('sort', 'top');
		$page = $request->get_getint('page', 1);

		$body_view = new FileTemplate('newsletter/NewsletterBody.tpl');
		$body_view->add_lang($this->lang);
		$body_view->put('TEMPLATE', $this->view);
		$response = new SiteDisplayResponse($body_view);
		$breadcrumb = $response->get_graphical_environment()->get_breadcrumb();
		$breadcrumb->add($this->lang['newsletter'], NewsletterUrlBuilder::home()->rel());
		$breadcrumb->add($this->lang['archives.list'], NewsletterUrlBuilder::archives()->rel());

		if ($this->stream->get_id() > 0)
		{
			$stream = NewsletterStreamsCache::load()->get_stream($this->stream->get_id());
			$breadcrumb->add($stream->get_name(), NewsletterUrlBuilder::archives($this->stream->get_id(), $this->stream->get_rewrited_name(), $sort_field, $sort_mode, $page)->rel());
		}

		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($this->lang['archives.list'], $this->lang['newsletter'], $page);
		$description = $this->stream->get_description();
		if (empty($description))
			$description = StringVars::replace_vars($this->lang['newsletter.seo.archives'], array('name' => $this->stream->get_name()));
		$graphical_environment->get_seo_meta_data()->set_description($description, $page);
		$graphical_environment->get_seo_meta_data()->set_canonical_url(NewsletterUrlBuilder::archives($this->stream->get_id(), $this->stream->get_rewrited_name(), $sort_field, $sort_mode, $page));

		return $response;
	}
}
?>
