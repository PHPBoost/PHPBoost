<?php
/*##################################################
 *                      BugtrackerStatsListController.class.php
 *                            -------------------
 *   begin                : November 13, 2012
 *   copyright            : (C) 2012 Julien BRISWALTER
 *   email                : julien.briswalter@gmail.com
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
		//Récupération des paramètres de configuration
		$config = BugtrackerConfig::load();
		$versions = $config->get_versions();
		
		$display_versions = sizeof($versions) ? true : false;
		
		//Nombre de bugs
		$nbr_bugs = BugtrackerService::count();
		$nbr_bugs_not_rejected = BugtrackerService::count("WHERE status <> 'rejected'");
		$nbr_fixed_bugs = BugtrackerService::count("WHERE fixed_in <> ''");
		
		$result = PersistenceContext::get_querier()->select("SELECT status, COUNT(*) as nb_bugs
		FROM " . BugtrackerSetup::$bugtracker_table . "
		GROUP BY status
		ORDER BY status ASC", array(), SelectQueryResult::FETCH_ASSOC);
		
		while ($row = $result->fetch())
		{
			$this->view->assign_block_vars('status', array(
				'NAME'	=> $this->lang['bugs.status.' . $row['status']],
				'NUMBER'=> $row['nb_bugs']
			));
		}
		
		$this->view->put_all(array(
			'C_BUGS' 				=> (float)$nbr_bugs,
			'C_FIXED_BUGS' 			=> (float)$nbr_fixed_bugs,
			'C_BUGS_NOT_REJECTED' 	=> (float)$nbr_bugs_not_rejected,
			'C_DISPLAY_VERSIONS'	=> $display_versions,
			'C_DISPLAY_TOP_POSTERS'	=> $config->get_stats_top_posters_activated(),
			'L_GUEST'				=> LangLoader::get_message('guest', 'main')
		));
		
		if (!empty($nbr_fixed_bugs))
		{
			$result = PersistenceContext::get_querier()->select("SELECT fixed_in, COUNT(*) as nb_bugs
			FROM " . BugtrackerSetup::$bugtracker_table . "
			GROUP BY fixed_in
			ORDER BY fixed_in ASC", array(), SelectQueryResult::FETCH_ASSOC);
			
			while ($row = $result->fetch())
			{
				if (!empty($row['fixed_in']) && isset($versions[$row['fixed_in']]))
					$this->view->assign_block_vars('fixed_version', array(
						'NAME'					=> stripslashes($versions[$row['fixed_in']]['name']),
						'LINK_VERSION_ROADMAP'	=> BugtrackerUrlBuilder::roadmap(Url::encode_rewrite($versions[$row['fixed_in']]['name']))->absolute(),
						'NUMBER'				=> $row['nb_bugs']
					));
			}
		}
		
		$i = 1;
		$result = PersistenceContext::get_querier()->select("SELECT member.*, COUNT(*) as nb_bugs
		FROM " . BugtrackerSetup::$bugtracker_table . " b
		JOIN " . DB_TABLE_MEMBER . " member ON (member.user_id = b.author_id)
		WHERE status <> 'rejected'
		GROUP BY author_id
		ORDER BY nb_bugs DESC
		LIMIT " . $config->get_stats_top_posters_number() . " OFFSET 0", array(), SelectQueryResult::FETCH_ASSOC);
		
		while ($row = $result->fetch())
		{
			//Author
			$author = new User();
			$author->set_properties($row);
			$author_group_color = User::get_group_color($author->get_groups(), $author->get_level(), true);
			
			$this->view->assign_block_vars('top_poster', array(
				'C_AUTHOR_GROUP_COLOR'	=> !empty($author_group_color),
				'ID' 					=> $i,
				'AUTHOR'				=> $author->get_pseudo(),
				'AUTHOR_LEVEL_CLASS'	=> UserService::get_level_class($author->get_level()),
				'AUTHOR_GROUP_COLOR'	=> $author_group_color,
				'LINK_AUTHOR_PROFILE'	=> UserUrlBuilder::profile($author->get_id())->absolute(),
				'USER_BUGS' 			=> $row['nb_bugs']
			));
			
			$i++;
		}
		
		return $this->view;
	}
	
	private function init()
	{
		$this->lang = LangLoader::get('bugtracker_common', 'bugtracker');
		$this->view = new FileTemplate('bugtracker/BugtrackerStatsListController.tpl');
		$this->view->add_lang($this->lang);
	}
	
	private function check_page_exists()
	{
		if (BugtrackerConfig::load()->get_stats_activated() == false)
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
	
	public static function get_view()
	{
		$object = new self();
		$object->init();
		$object->check_authorizations();
		$object->build_view(AppContext::get_request());
		return BugtrackerViews::build_body_view($object->view, 'stats');
	}
	
	private function build_response(View $view)
	{
		$request = AppContext::get_request();
		$success = $request->get_value('success', '');
		$bug_id = $request->get_int('id', 0);
		
		$body_view = BugtrackerViews::build_body_view($view, 'stats');
		
		//Gestion des messages
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
