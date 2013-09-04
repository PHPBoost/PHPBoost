<?php
/*##################################################
 *                      BugtrackerStatsListController.class.php
 *                            -------------------
 *   begin                : November 13, 2012
 *   copyright            : (C) 2012 Julien BRISWALTER
 *   email                : julienseth78@phpboost.com
 *
 *
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

class BugtrackerStatsListController extends ModuleController
{
	private $lang;
	private $view;
	
	public function execute(HTTPRequestCustom $request)
	{
		$this->init();
		
		$this->check_page_exists();
		
		$this->check_authorizations();
		
		$this->build_view($request);
		
		return $this->build_response($this->view);
	}
	
	private function build_view($request)
	{
		$config = BugtrackerConfig::load();
		$versions = $config->get_versions();
		$display_versions = sizeof($versions);
		
		$stats_cache = BugtrackerStatsCache::load();
		$bugs_number_per_version = $stats_cache->get_bugs_number_per_version_list();
		$top_posters = $stats_cache->get_top_posters_list();
		
		$this->view->put_all(array(
			'C_BUGS'				=> $stats_cache->get_bugs_number('total'),
			'C_FIXED_BUGS'			=> !empty($bugs_number_per_version),
			'C_POSTERS'				=> !empty($top_posters),
			'C_DISPLAY_VERSIONS'	=> $display_versions,
			'C_DISPLAY_TOP_POSTERS'	=> $config->are_stats_top_posters_enabled(),
			'L_GUEST'				=> LangLoader::get_message('guest', 'main')
		));
		
		foreach ($stats_cache->get_bugs_number_list() as $status => $bugs_number)
		{
			if ($status != 'total')
				$this->view->assign_block_vars('status', array(
					'NAME'	=> $this->lang['bugs.status.' . $status],
					'NUMBER'=> $bugs_number
				));
		}
		
		foreach ($bugs_number_per_version as $version_id => $bugs_number)
		{
			$this->view->assign_block_vars('fixed_version', array(
				'NAME'					=> stripslashes($versions[$version_id]['name']),
				'LINK_VERSION_ROADMAP'	=> BugtrackerUrlBuilder::roadmap(Url::encode_rewrite($versions[$version_id]['name']))->absolute(),
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
					'AUTHOR'				=> $poster['user']->get_pseudo(),
					'AUTHOR_LEVEL_CLASS'	=> UserService::get_level_class($poster['user']->get_level()),
					'AUTHOR_GROUP_COLOR'	=> $author_group_color,
					'LINK_AUTHOR_PROFILE'	=> UserUrlBuilder::profile($poster['user']->get_id())->absolute(),
					'USER_BUGS' 			=> $poster['bugs_number']
				));
			}
		}
		
		return $this->view;
	}
	
	private function init()
	{
		$this->lang = LangLoader::get('common', 'bugtracker');
		$this->view = new FileTemplate('bugtracker/BugtrackerStatsListController.tpl');
		$this->view->add_lang($this->lang);
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
		$request = AppContext::get_request();
		$success = $request->get_value('success', '');
		$bug_id = $request->get_int('id', 0);
		
		$body_view = BugtrackerViews::build_body_view($view, 'stats');
		
		switch ($success)
		{
			case 'add':
				$errstr = StringVars::replace_vars($this->lang['bugs.success.add'], array('id' => $bug_id));
				break;
			default:
				$errstr = '';
		}
		if (!empty($errstr))
			$body_view->put('MSG', MessageHelper::display($errstr, E_USER_SUCCESS, 5));
		
		$response = new BugtrackerDisplayResponse();
		$response->add_breadcrumb_link($this->lang['bugs.module_title'], BugtrackerUrlBuilder::home());
		$response->add_breadcrumb_link($this->lang['bugs.titles.bugs_stats'], BugtrackerUrlBuilder::stats());
		$response->set_page_title($this->lang['bugs.titles.bugs_stats']);
		
		return $response->display($body_view);
	}
}
?>
