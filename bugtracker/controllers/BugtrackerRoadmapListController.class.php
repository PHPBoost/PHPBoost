<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 12 05
 * @since       PHPBoost 3.0 - 2012 11 13
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class BugtrackerRoadmapListController extends DefaultModuleController
{
	protected function get_template_to_use()
	{
		return new FileTemplate('bugtracker/BugtrackerRoadmapListController.tpl');
	}

	public function execute(HTTPRequestCustom $request)
	{
		$this->check_page_exists();

		$this->check_authorizations();

		$this->build_view($request);

		return $this->build_response($this->view);
	}

	private function check_page_exists()
	{
		if (!$this->config->is_roadmap_enabled())
		{
			$error_controller = PHPBoostErrors::unexisting_page();
			DispatchManager::redirect($error_controller);
		}
	}

	private function check_authorizations()
	{
		if (!BugtrackerAuthorizationsService::check_authorizations()->read())
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
	}

	private function build_view($request)
	{
		$severities = $this->config->get_severities();
		$versions = array_reverse($this->config->get_versions(), true);

		if (!empty($versions))
		{
			$roadmap_version = $request->get_int('id_version', 0);
			$roadmap_version_name = $request->get_value('version', '');
			$roadmap_status = $request->get_value('status', 'all');

			if ($roadmap_status !== 'all' && $roadmap_status !== BugtrackerItem::FIXED && $roadmap_status !== BugtrackerItem::IN_PROGRESS)
			{
				$error_controller = PHPBoostErrors::unexisting_page();
				DispatchManager::redirect($error_controller);
			}

			if (empty($roadmap_version) && empty($roadmap_version_name))
			{
				$roadmap_version = key($versions);
				$roadmap_version_name = Url::encode_rewrite($versions[key($versions)]['name']);
			}
			else if (!isset($versions[$roadmap_version]))
			{
				$error_controller = PHPBoostErrors::unexisting_page();
				DispatchManager::redirect($error_controller);
			}

			$field = $request->get_value('field', BugtrackerUrlBuilder::DEFAULT_SORT_FIELD);
			$sort = $request->get_value('sort', BugtrackerUrlBuilder::DEFAULT_SORT_MODE);
			$current_page = $request->get_int('page', 1);

			$mode = ($sort == 'top') ? 'ASC' : 'DESC';

			switch ($field)
			{
				case 'id' :
					$field_bdd = 'id';
					break;
				case 'title' :
					$field_bdd = 'title';
					break;
				case 'severity' :
					$field_bdd = 'severity';
					break;
				case 'status' :
					$field_bdd = 'status';
					break;
				default :
					$field_bdd = 'fix_date';
					break;
			}

			$stats_cache = BugtrackerStatsCache::load();
			$bugs_number = $stats_cache->get_bugs_number_per_version($roadmap_version);
			$roadmap_status_bugs_number = ($bugs_number && isset($bugs_number[$roadmap_status]) ? $bugs_number[$roadmap_status] : 0);

			$pagination = $this->get_pagination($roadmap_status_bugs_number, $current_page, $roadmap_version, $roadmap_version_name, $roadmap_status, $field, $sort);

			$result = PersistenceContext::get_querier()->select("SELECT b.*, member.*
			FROM " . BugtrackerSetup::$bugtracker_table . " b
			LEFT JOIN " . DB_TABLE_MEMBER . " member ON member.user_id = b.author_id
			WHERE fixed_in = " . $roadmap_version . "
			" . ($roadmap_status != 'all' ? ($roadmap_status == BugtrackerItem::IN_PROGRESS ? "AND (b.status = '" . BugtrackerItem::IN_PROGRESS . "' OR b.status = '" . BugtrackerItem::REOPEN . "')" : "AND b.status = '" . BugtrackerItem::FIXED . "'") : "") . "
			ORDER BY " . $field_bdd . " " . $mode . "
			LIMIT :number_items_per_page OFFSET :display_from",
				array(
					'number_items_per_page' => $pagination->get_number_items_per_page(),
					'display_from' => $pagination->get_display_from()
				)
			);

			$displayed_severities = array();

			while ($row = $result->fetch())
			{
				$bug = new BugtrackerItem();
				$bug->set_properties($row);

				if (!in_array($bug->get_severity(), $displayed_severities)) $displayed_severities[] = $bug->get_severity();

				$this->view->assign_block_vars('bug', array_merge($bug->get_template_vars(), array(
					'C_LINE_COLOR'	=> !empty($row['severity']) && isset($severities[$row['severity']]),
					'LINE_COLOR' 	=> stripslashes($severities[$row['severity']]['color'])
				)));
			}
			$result->dispose();

			$bugs_colspan = 4;
			if ($this->config->is_type_column_displayed()) $bugs_colspan++;
			if ($this->config->is_category_column_displayed()) $bugs_colspan++;
			if ($this->config->is_priority_column_displayed()) $bugs_colspan++;
			if ($this->config->is_detected_in_column_displayed()) $bugs_colspan++;

			$this->view->put_all(array(
				'C_VERSIONS_AVAILABLE'			=> true,
				'C_BUGS'						=> $roadmap_status_bugs_number,
				'C_STATUS_IN_PROGRESS'			=> $roadmap_status == BugtrackerItem::IN_PROGRESS,
				'C_DISPLAY_TYPE_COLUMN'			=> $this->config->is_type_column_displayed(),
				'C_DISPLAY_CATEGORY_COLUMN'		=> $this->config->is_category_column_displayed(),
				'C_DISPLAY_PRIORITY_COLUMN'		=> $this->config->is_priority_column_displayed(),
				'C_DISPLAY_DETECTED_IN_COLUMN'	=> $this->config->is_detected_in_column_displayed(),
				'C_PAGINATION'					=> $pagination->has_several_pages(),
				'PAGINATION' 					=> $pagination->display(),
				'BUGS_COLSPAN' 					=> $bugs_colspan,
				'SELECT_VERSION'				=> $this->build_form(isset($versions[$roadmap_version]) ? $roadmap_version : 0, isset($versions[$roadmap_version]) ? Url::encode_rewrite($versions[$roadmap_version]['name']) : '', $roadmap_status, (int)$roadmap_status_bugs_number, isset($versions[$roadmap_version]) ? $versions[$roadmap_version]['release_date'] : 0)->display(),
				'LEGEND'						=> BugtrackerViews::build_legend($displayed_severities, 'roadmap'),
				'LINK_BUG_ID_TOP' 				=> BugtrackerUrlBuilder::roadmap($roadmap_version, $roadmap_version_name, $roadmap_status, 'id', 'top', $current_page)->rel(),
				'LINK_BUG_ID_BOTTOM' 			=> BugtrackerUrlBuilder::roadmap($roadmap_version, $roadmap_version_name, $roadmap_status, 'id', 'bottom', $current_page)->rel(),
				'LINK_BUG_TITLE_TOP' 			=> BugtrackerUrlBuilder::roadmap($roadmap_version, $roadmap_version_name, $roadmap_status, 'title', 'top', $current_page)->rel(),
				'LINK_BUG_TITLE_BOTTOM' 		=> BugtrackerUrlBuilder::roadmap($roadmap_version, $roadmap_version_name, $roadmap_status, 'title', 'bottom', $current_page)->rel(),
				'LINK_BUG_SEVERITY_TOP' 		=> BugtrackerUrlBuilder::roadmap($roadmap_version, $roadmap_version_name, $roadmap_status, 'severity', 'top', $current_page)->rel(),
				'LINK_BUG_SEVERITY_BOTTOM'		=> BugtrackerUrlBuilder::roadmap($roadmap_version, $roadmap_version_name, $roadmap_status, 'severity', 'bottom', $current_page)->rel(),
				'LINK_BUG_STATUS_TOP'			=> BugtrackerUrlBuilder::roadmap($roadmap_version, $roadmap_version_name, $roadmap_status, 'status', 'top', $current_page)->rel(),
				'LINK_BUG_STATUS_BOTTOM'		=> BugtrackerUrlBuilder::roadmap($roadmap_version, $roadmap_version_name, $roadmap_status, 'status', 'bottom', $current_page)->rel(),
				'LINK_BUG_DATE_TOP' 			=> BugtrackerUrlBuilder::roadmap($roadmap_version, $roadmap_version_name, $roadmap_status, 'date', 'top', $current_page)->rel(),
				'LINK_BUG_DATE_BOTTOM'			=> BugtrackerUrlBuilder::roadmap($roadmap_version, $roadmap_version_name, $roadmap_status, 'date', 'bottom', $current_page)->rel()
			));
		}

		return $this->view;
	}

	private function build_form($requested_version_id, $requested_version_name, $requested_status, $nbr_bugs, $requested_version_release_date = 0)
	{
		$form = new HTMLForm('version', '', false);

		$fieldset = new FormFieldsetHorizontal('choose-version');
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldSimpleSelectChoice('roadmap_version', $this->lang['titles.choose_version'], $requested_version_id . '-' . $requested_version_name, $this->build_select_versions(),
			array('events' => array('change' => 'document.location = "'. BugtrackerUrlBuilder::roadmap()->rel() .'" + HTMLForms.getField("roadmap_version").getValue()' . ($requested_status != 'all' ? ' + "/" + HTMLForms.getField("status").getValue()' : '') . ';')
		)));

		$fieldset->add_field(new FormFieldSimpleSelectChoice('status', $this->lang['labels.fields.status'], $requested_status, $this->build_select_status(),
			array('events' => array('change' => 'document.location = "'. BugtrackerUrlBuilder::roadmap($requested_version_id, $requested_version_name)->rel() .'" + "/" + HTMLForms.getField("status").getValue();')
		)));

		$release_date = !empty($requested_version_release_date) && is_numeric($requested_version_release_date) ? new Date($requested_version_release_date, Timezone::SERVER_TIMEZONE) : null;

		// $fieldset = new FormFieldsetHorizontal('informations');
		// $form->add_fieldset($fieldset);

		$fieldset_infos =  new FormFieldsetHorizontal('informations', array('css_class' => 'flex-between'));
		$form->add_fieldset($fieldset_infos);
		$fieldset_infos->add_field(new FormFieldHTML('informations_date', !empty($release_date) ? $this->lang['labels.fields.version_release_date'] . ': ' . $release_date->format(Date::FORMAT_DAY_MONTH_YEAR) : ''));

		$fieldset_infos->add_field(new FormFieldHTML('informations_fixed_number', ($this->lang['bugtracker.items.number'] . ': ' . $nbr_bugs)));

		return $form;
	}

	private function build_select_versions()
	{
		$versions = array_reverse($this->config->get_versions(), true);

		$array_versions = array();
		//Versions
		foreach ($versions as $key => $version)
		{
			$array_versions[] = new FormFieldSelectChoiceOption(stripslashes($version['name']), $key . '-' . Url::encode_rewrite($version['name']));
		}
		return $array_versions;
	}

	private function build_select_status()
	{
		$array_status[] = new FormFieldSelectChoiceOption($this->lang['status.' . BugtrackerItem::FIXED] . ' / ' . $this->lang['status.' . BugtrackerItem::IN_PROGRESS], 'all');
		$array_status[] = new FormFieldSelectChoiceOption($this->lang['status.' . BugtrackerItem::FIXED], BugtrackerItem::FIXED);
		$array_status[] = new FormFieldSelectChoiceOption($this->lang['status.' . BugtrackerItem::IN_PROGRESS], BugtrackerItem::IN_PROGRESS);

		return $array_status;
	}

	private function get_pagination($bugs_number, $page, $roadmap_version, $roadmap_version_name, $roadmap_status, $field, $sort)
	{
		$pagination = new ModulePagination($page, $bugs_number, (int)$this->config->get_items_per_page());
		$pagination->set_url(BugtrackerUrlBuilder::roadmap($roadmap_version, $roadmap_version_name, $roadmap_status, $field, $sort, '%d'));

		if ($pagination->current_page_is_empty() && $page > 1)
		{
			$error_controller = PHPBoostErrors::unexisting_page();
			DispatchManager::redirect($error_controller);
		}

		return $pagination;
	}

	private function build_response(View $view)
	{
		$versions = array_reverse($this->config->get_versions(), true);

		$request = AppContext::get_request();
		$roadmap_id_version = $request->get_value('id_version', key($versions));
		$roadmap_version = $request->get_value('version', Url::encode_rewrite($versions[key($versions)]['name']));
		$roadmap_status = $request->get_value('status', 'all');

		$field = $request->get_value('field', 'date');
		$sort = $request->get_value('sort', 'desc');
		$page = $request->get_int('page', 1);

		$body_view = BugtrackerViews::build_body_view($view, 'roadmap');

		$response = new SiteDisplayResponse($body_view);
		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title(StringVars::replace_vars($this->lang['titles.roadmap.version'], array('version' => $versions[key($versions)]['name'])), $this->lang['bugtracker.module.title'], $page);
		$graphical_environment->get_seo_meta_data()->set_description(StringVars::replace_vars($this->lang['seo.roadmap'], array('version' => $versions[key($versions)]['name'])), $page);
		$graphical_environment->get_seo_meta_data()->set_canonical_url(BugtrackerUrlBuilder::roadmap($roadmap_id_version, $roadmap_version, $roadmap_status, $field, $sort, $page));

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['bugtracker.module.title'], BugtrackerUrlBuilder::home());
		$breadcrumb->add($this->lang['titles.roadmap'], BugtrackerUrlBuilder::roadmap($roadmap_id_version, $roadmap_version, $roadmap_status, $field, $sort, $page));

		return $response;
	}
}
?>
