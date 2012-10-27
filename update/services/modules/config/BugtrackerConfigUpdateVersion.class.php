<?php
/*##################################################
 *                       BugtrackerConfigUpdateVersion.class.php
 *                            -------------------
 *   begin                : October 09, 2012
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

class BugtrackerConfigUpdateVersion extends ConfigUpdateVersion
{
	public function __construct()
	{
		parent::__construct('bugtracker');
	}
	
	protected function build_new_config()
	{
		$lang = LangLoader::get('bugtracker_config', 'bugtracker');
		
		$config = $this->get_old_config();
		
		$severities = array(1 => array('name' => $lang['bugtracker.config.severities.minor'], 'color' => '#' . $config['severity_minor_color']), array('name' => $lang['bugtracker.config.severities.major'], 'color' => '#' . $config['severity_major_color']), array('name' => $lang['bugtracker.config.severities.critical'], 'color' => '#' . $config['severity_critical_color']));
		
		$types = array();
		foreach ($config['types'] as $type)
		{
			$nb_types = sizeof($types);
			$array_id = empty($nb_types) ? 1 : ($nb_types + 1);
			$types[$array_id] = $type;
		}
		
		$categories = array();
		foreach ($config['categories'] as $category)
		{
			$nb_categories = sizeof($categories);
			$array_id = empty($nb_categories) ? 1 : ($nb_categories + 1);
			$categories[$array_id] = $category;
		}
		
		$versions = array();
		foreach ($config['versions'] as $version)
		{
			$nb_versions = sizeof($versions);
			$array_id = empty($nb_versions) ? 1 : ($nb_versions + 1);
			$versions[$array_id] = array(
				'name' => $version['name'],
				'detected_in' => $version['detected_in']
			);
		}
		
		$bugtracker_config = BugtrackerConfig::load();
		$bugtracker_config->set_authorizations(unserialize($config['auth']));
		$bugtracker_config->set_items_per_page($config['items_per_page']);
		$bugtracker_config->set_rejected_bug_color('#' . $config['rejected_bug_color']);
		$bugtracker_config->set_fixed_bug_color('#' . $config['closed_bug_color']);
		$bugtracker_config->set_comments_activated($config['activ_com']);
		$bugtracker_config->set_versions($versions);
		$bugtracker_config->set_types($types);
		$bugtracker_config->set_categories($categories);
		$bugtracker_config->set_severities($severities);
		BugtrackerConfig::save();
        
		return true;
	}
}
?>