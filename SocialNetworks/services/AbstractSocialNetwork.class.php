<?php
/*##################################################
 *                        AbstractSocialNetwork.class.php
 *                            -------------------
 *   begin                : April 13, 2018
 *   copyright            : (C) 2018 Julien BRISWALTER
 *   email                : j1.seth@phpboost.com
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

abstract class AbstractSocialNetwork implements SocialNetwork
{
	const SOCIAL_NETWORK_ID = '';
	
	/**
	 * {@inheritdoc}
	 */
	public function get_name()
	{
		return '';
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function get_icon_name()
	{
		return static::SOCIAL_NETWORK_ID;
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function get_css_class()
	{
		return static::SOCIAL_NETWORK_ID;
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function has_content_sharing_url()
	{
		return $this->get_content_sharing_url() != '';
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function get_content_sharing_url()
	{
		return '';
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function get_share_image_renderer_html()
	{
		$tpl = new FileTemplate('SocialNetworks/share_image_render.tpl');
		$tpl->put('ICON_NAME', $this->get_icon_name());
		return $tpl->render();
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function has_authentication()
	{
		return $this->get_external_authentication() !== false && $this->get_external_authentication() instanceof ExternalAuthentication;
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function get_external_authentication()
	{
		return false;
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function authentication_identifiers_needed()
	{
		return true;
	}

	/**
	 * {@inheritdoc}
	 */
	public function authentication_client_secret_needed()
	{
		return true;
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function get_identifiers_creation_url()
	{
		return '';
	}
}
?>
