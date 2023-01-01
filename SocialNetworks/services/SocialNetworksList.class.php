<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2018 11 21
 * @since       PHPBoost 5.1 - 2018 04 10
*/

class SocialNetworksList
{
	/**
	 * @return string[] List of the classes implementing interface
	 */
	function get_implementing_classes($interface_name)
	{
		$folder = new Folder(PATH_TO_ROOT . '/SocialNetworks/social_networks');
		$classes = array();

		foreach ($folder->get_files() as $class)
		{
			$name = str_replace('.class.php', '', $class->get_name());
			if (ClassLoader::is_class_registered_and_valid($name) && in_array($interface_name, class_implements($name)))
				$classes[] = $name;
		}

		$additional_social_networks = SocialNetworksConfig::load()->get_additional_social_networks();
		if (is_array($additional_social_networks))
		{
			foreach (SocialNetworksConfig::load()->get_additional_social_networks() as $class)
			{
				if (ClassLoader::is_class_registered_and_valid($class) && in_array($interface_name, class_implements($class)))
					$classes[] = $class;
			}
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
			if (isset($social_networks[$social_network_id]))
			{
				$sorted_social_networks[$social_network_id] = $social_networks[$social_network_id];
				unset($social_networks[$social_network_id]);
			}
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
		$request = AppContext::get_request();
		$enabled_content_sharing = SocialNetworksConfig::load()->get_enabled_content_sharing();
		$sharing_links = array();

		foreach ($this->get_sorted_social_networks_list() as $id => $social_network)
		{
			if (in_array($id, $enabled_content_sharing))
			{
				$sn = new $social_network();

				$display = false;
				if ($sn->is_desktop_only() && !$request->is_mobile_device())
				{
					$content_sharing_url = $sn->get_content_sharing_url();
					$display = true;
				}
				else if ($sn->is_mobile_only() && $request->is_mobile_device())
				{
					$content_sharing_url = $sn->has_mobile_content_sharing_url() ? $sn->get_mobile_content_sharing_url() : $sn->get_content_sharing_url();
					$display = true;
				}
				else if (!$sn->is_desktop_only() && !$sn->is_mobile_only())
				{
					if ($request->is_mobile_device() && $sn->has_mobile_content_sharing_url())
						$content_sharing_url = $sn->get_mobile_content_sharing_url();
					else
						$content_sharing_url = $sn->get_content_sharing_url();

					$display = true;
				}

				if ($display && $content_sharing_url)
					$sharing_links[] = new ContentSharingActionsMenuLink($sn->get_css_class(), $sn->get_name(), new Url($content_sharing_url), $sn->get_share_image_renderer_html());
			}
		}

		return $sharing_links;
	}
}
?>
