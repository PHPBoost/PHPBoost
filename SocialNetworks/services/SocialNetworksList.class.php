<?php
/*##################################################
 *		                        SocialNetworksList.class.class.php
 *                            -------------------
 *   begin                : April 10, 2018
 *   copyright            : (C) 2018 Julien BRISWALTER
 *   email                : j1.seth@phpboost.com
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

/**
 * @author Julien BRISWALTER <j1.seth@phpboost.com>
 */
class SocialNetworksList
{
	/**
	 * @return string[] List of the classes implementing interface
	 */
	function get_implementing_classes($interface_name)
	{
		$folder = new Folder(PATH_TO_ROOT . '/SocialNetworks/services');
		$classes_files = $folder->get_files();
		$classes = array();
		foreach ($classes_files as $class)
		{
			$name = str_replace('.class.php', '', $class->get_name());
			if (in_array($interface_name, class_implements($name)))
				$classes[] = $name;
		}
		
		return $classes;
	}

	/**
	 * @return string[] List of social networks
	 */
	function get_social_networks_list()
	{
		$social_networks = array();
		
		foreach ($this->get_implementing_classes('SocialNetwork') as $social_network)
		{
			$social_networks[$social_network::SOCIAL_NETWORK_ID] = $social_network;
		}
		
		return $social_networks;
	}

	/**
	 * @return string[] List of social networks ids
	 */
	function get_social_networks_ids()
	{
		return array_keys($this->get_social_networks_list());
	}

	/**
	 * @return string[] Sorted list of social networks
	 */
	function get_sorted_social_networks_list()
	{
		$social_networks = $this->get_social_networks_list();
		$sorted_social_networks = array();
		
		foreach (SocialNetworksConfig::load()->get_social_networks_order() as $social_network_id)
		{
			$sorted_social_networks[$social_network_id] = $social_networks[$social_network_id];
			unset($social_networks[$social_network_id]);
		}
		
		return array_merge($sorted_social_networks, $social_networks);
	}

	/**
	 * @return string[] List of social networks authentifications
	 */
	function get_external_authentications_list()
	{
		$get_enabled_authentications = SocialNetworksConfig::load()->get_enabled_authentications();
		$external_authentications = array();
		
		foreach ($this->get_sorted_social_networks_list() as $id => $social_network)
		{
			$sn = new $social_network();
			if ($sn->has_authentication() && in_array($id, $get_enabled_authentications))
				$external_authentications[] = $sn->get_external_authentication();
		}
		
		return $external_authentications;
	}

	/**
	 * @return string[] List of social networks sharing links
	 */
	function get_sharing_links_list()
	{
		$enabled_content_sharing = SocialNetworksConfig::load()->get_enabled_content_sharing();
		$sharing_links = array();
		
		foreach ($this->get_sorted_social_networks_list() as $id => $social_network)
		{
			if (in_array($id, $enabled_content_sharing))
			{
				$sn = new $social_network();
				$sharing_links[] = new ContentSharingActionsMenuLink($id, $sn->get_name(), new Url($sn->get_content_sharing_url()), $sn->get_share_image_renderer_html());
			}
		}
		
		return $sharing_links;
	}
}
?>