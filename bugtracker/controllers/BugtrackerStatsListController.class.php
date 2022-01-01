<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 12 05
 * @since       PHPBoost 3.0 - 2012 11 13
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class BugtrackerStatsListController extends DefaultModuleController
{
	protected function get_template_to_use()
	{
		return new FileTemplate('bugtracker/BugtrackerStatsListController.tpl');
	}

	public function execute(HTTPRequestCustom $request)
	{
		$this->check_page_exists();

		$this->check_authorizations();

		$this->build_view($request);

		return $this->build_response($this->view);
	}

	private function build_view($request)
	{
		$versions = $this->config->get_versions();
		$display_versions = count($versions);

		$stats_cache = BugtrackerStatsCache::load();
		$bugs_number_per_version = $stats_cache->get_bugs_number_per_version_list();
		$top_posters = $stats_cache->get_top_posters_list();

		$this->view->put_all(array(
			'C_BUGS'				=> $stats_cache->get_bugs_number('total'),
			'C_FIXED_BUGS'			=> !empty($bugs_number_per_version),
			'C_POSTERS'				=> !empty($top_posters),
			'C_DISPLAY_VERSIONS'	=> $display_versions,
			'C_DISPLAY_TOP_POSTERS'	=> $this->config->are_stats_top_posters_enabled(),
			'C_ROADMAP_ENABLED'		=> $this->config->is_roadmap_enabled()
		));

		foreach ($stats_cache->get_bugs_number_list() as $status => $bugs_number)
		{
			if ($status != 'total')
				$this->view->assign_block_vars('status', array(
					'NAME'	=> $this->lang['status.' . $status],
					'NUMBER'=> $bugs_number
				));
		}

		foreach ($bugs_number_per_version as $version_id => $bugs_number)
		{
			$release_date = !empty($versions[$version_id]['release_date']) && is_numeric($versions[$version_id]['release_date']) ? new Date($versions[$version_id]['release_date'], Timezone::SERVER_TIMEZONE) : null;

			$this->view->assign_block_vars('fixed_version', array(
				'NAME'					=> stripslashes($versions[$version_id]['name']),
				'RELEASE_DATE'			=> !empty($release_date) ? $release_date->format(Date::FORMAT_DAY_MONTH_YEAR) : $this->lang['notice.not_defined_e_date'],
				'LINK_VERSION_ROADMAP'	=> BugtrackerUrlBuilder::roadmap($version_id, Url::encode_rewrite($versions[$version_id]['name']))->rel(),
				'NUMBER'				=> $bugs_number['all']
			));
		}

		foreach ($top_posters as $id => $poster)
		{
			if (isset($poster['user']))
			{
				$author_group_color = User::get_group_color($poster['user']->get_groups(), $poster['user']->get_level(), true);

				$this->view->assign_block_vars('top_poster', array(
					'C_AUTHOR_GROUP_COLOR'	=> !empty($author_group_color),
					'ID' 					=> $id,
					'AUTHOR'				=> $poster['user']->get_display_name(),
					'AUTHOR_LEVEL_CLASS'	=> UserService::get_level_class($poster['user']->get_level()),
					'AUTHOR_GROUP_COLOR'	=> $author_group_color,
					'LINK_AUTHOR_PROFILE'	=> UserUrlBuilder::profile($poster['user']->get_id())->rel(),
					'USER_BUGS' 			=> $poster['bugs_number']
				));
			}
		}

		return $this->view;
	}

	private function check_page_exists()
	{
		if (!BugtrackerConfig::load()->are_stats_enabled())
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

	private function build_response(View $view)
	{
		$body_view = BugtrackerViews::build_body_view($view, 'stats');

		$response = new SiteDisplayResponse($body_view);
		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($this->lang['titles.stats'], $this->lang['bugtracker.module.title']);
		$graphical_environment->get_seo_meta_data()->set_description($this->lang['seo.stats']);
		$graphical_environment->get_seo_meta_data()->set_canonical_url(BugtrackerUrlBuilder::stats());

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['bugtracker.module.title'], BugtrackerUrlBuilder::home());
		$breadcrumb->add($this->lang['titles.stats'], BugtrackerUrlBuilder::stats());

		return $response;
	}
}
?>
