<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 01 13
 * @since       PHPBoost 3.0 - 2011 03 21
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class NewsletterArchivesController extends DefaultModuleController
{
	private $stream;

	private $elements_number = 0;
	private $ids = array();

	public function execute(HTTPRequestCustom $request)
	{
		$this->stream = NewsletterStreamsCache::load()->get_stream($request->get_int('id_stream', 0));

		if (!NewsletterStreamsCache::load()->stream_exists($this->stream->get_id()))
		{
			$controller = new UserErrorController($this->lang['warning.error'], $this->lang['newsletter.stream.not.exists']);
			DispatchManager::redirect($controller);
		}

		$this->check_authorizations();

		$current_page = $this->build_table();

		$this->execute_multiple_delete_if_needed($request);

		return $this->generate_response($current_page);
	}

	private function check_authorizations()
	{
		if (!NewsletterAuthorizationsService::id_stream($this->stream->get_id())->read_archives())
		{
			NewsletterAuthorizationsService::get_errors()->read_archives();
		}
	}

	private function build_table()
	{
		$moderation_authorization = NewsletterAuthorizationsService::id_stream($this->stream->get_id())->moderation_archives();

		$columns = array(
			new HTMLTableColumn($this->lang['newsletter.stream.name'], 'stream_id'),
			new HTMLTableColumn($this->lang['newsletter.item.name'], 'subject'),
			new HTMLTableColumn($this->lang['newsletter.archives.date'], 'timestamp'),
			new HTMLTableColumn($this->lang['newsletter.subscribers.number'], 'nbr_subscribers')
		);

		if ($this->stream->get_id())
			unset($columns[0]);

		if ($moderation_authorization)
			$columns[] = new HTMLTableColumn($this->lang['common.moderation'], '', array('sr-only' => true));

		$table_model = new SQLHTMLTableModel(NewsletterSetup::$newsletter_table_archives, 'items-manager', $columns, new HTMLTableSortingRule('timestamp', HTMLTableSortingRule::DESC));

		if ($this->stream->get_id())
			$table_model->add_permanent_filter('stream_id = ' . $this->stream->get_id());

		$table = new HTMLTable($table_model);

		$results = array();
		$result = $table_model->get_sql_results();
		foreach ($result as $row)
		{
			$stream = NewsletterStreamsCache::load()->get_stream($row['stream_id']);

			$stream_date = new Date($row['timestamp'], Timezone::SERVER_TIMEZONE);

			$this->elements_number++;
			$this->ids[$this->elements_number] = $row['id'];

			$stream_link = new LinkHTMLElement(NewsletterUrlBuilder::archives($stream->get_id(), $stream->get_rewrited_name()), $stream->get_name());
			$archive_link = new LinkHTMLElement(NewsletterUrlBuilder::archive($row['id']), $row['subject']);

			$table_row = array(
				new HTMLTableRowCell($stream_link->display()),
				new HTMLTableRowCell($archive_link->display()),
				new HTMLTableRowCell($stream_date->format(Date::FORMAT_DAY_MONTH_YEAR)),
				new HTMLTableRowCell($row['nbr_subscribers'])
			);

			if ($this->stream->get_id())
				unset($table_row[0]);

			if ($moderation_authorization)
			{
				$delete_link = new DeleteLinkHTMLElement(NewsletterUrlBuilder::delete_archive($row['id'], $this->stream->get_id()));
				$table_row[] = new HTMLTableRowCell($delete_link->display());
			}

			$results[] = new HTMLTableRow($table_row);
		}
		$table->set_rows($table_model->get_number_of_matching_rows(), $results);

		$this->view->put('CONTENT', $table->display());

		return $table->get_page_number();
	}

	private function execute_multiple_delete_if_needed(HTTPRequestCustom $request)
	{
		if ($request->get_string('delete-selected-elements', false))
		{
			for ($i = 1 ; $i <= $this->elements_number ; $i++)
			{
				if ($request->get_value('delete-checkbox-' . $i, 'off') == 'on')
				{
					if (isset($this->ids[$i]) && $this->ids[$i] && NewsletterAuthorizationsService::id_stream($this->stream->get_id())->moderation_archives())
					{
						$row = PersistenceContext::get_querier()->select_single_row(NewsletterSetup::$newsletter_table_archives, array('*'), "WHERE id = '". $this->ids[$i] ."'");
						NewsletterService::delete_archive($this->ids[$i]);
						HooksService::execute_hook_action('delete', self::$module_id, array('id' => $this->ids[$i], 'title' => $row['subject']));
					}
				}
			}

			NewsletterStreamsCache::invalidate();

			AppContext::get_response()->redirect(NewsletterUrlBuilder::archives($this->stream->get_id(), $this->stream->get_rewrited_name()), $this->lang['warning.process.success']);
		}
	}

	private function generate_response($page = 1)
	{
		$body_view = new FileTemplate('newsletter/NewsletterBody.tpl');
		$body_view->add_lang($this->lang);
		$body_view->put_all(array(
			'C_SUBTITLE' => true,
			'C_STREAM_TITLE' => $this->stream->get_id() != Category::ROOT_CATEGORY,
			'L_SUBTITLE' => $this->lang['newsletter.archives'],
			'STREAM_TITLE' => $this->stream->get_name(),
			'TEMPLATE'   => $this->view
		));
		$response = new SiteDisplayResponse($body_view);

		$graphical_environment = $response->get_graphical_environment();

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['newsletter.module.title'], NewsletterUrlBuilder::home()->rel());
		$breadcrumb->add($this->lang['newsletter.archives.list'], NewsletterUrlBuilder::archives()->rel());

		if ($this->stream->get_id() > 0)
		{
			$stream = NewsletterStreamsCache::load()->get_stream($this->stream->get_id());
			$breadcrumb->add($stream->get_name(), NewsletterUrlBuilder::archives($this->stream->get_id(), $this->stream->get_rewrited_name())->rel());
		}

		$graphical_environment->set_page_title($this->lang['newsletter.archives.list'], $this->lang['newsletter.module.title'], $page);
		$description = $this->stream->get_description();
		if (empty($description))
			$description = StringVars::replace_vars($this->lang['newsletter.seo.archives'], array('name' => $this->stream->get_name()));
		$graphical_environment->get_seo_meta_data()->set_description($description, $page);
		$graphical_environment->get_seo_meta_data()->set_canonical_url(NewsletterUrlBuilder::archives($this->stream->get_id(), $this->stream->get_rewrited_name()));

		return $response;
	}
}
?>
