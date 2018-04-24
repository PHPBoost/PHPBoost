<?php
/*##################################################
 *                        AbstractSocialNetworkExternalAuthentication.class.php
 *                            -------------------
 *   begin                : April 16, 2018
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

abstract class AbstractSocialNetworkExternalAuthentication implements ExternalAuthentication
{
	/**
	 * {@inheritdoc}
	 */
	abstract public function get_authentication_id();
	
	/**
	 * {@inheritdoc}
	 */
	public function get_authentication_name()
	{
		return StringVars::replace_vars(LangLoader::get_message('sign-in-label', 'common', 'SocialNetworks'), array('name' => $this->get_social_network()->get_name()));
	}
	
	/**
	 * @return SocialNetwork class
	 */
	abstract protected function get_social_network();
	
	/**
	 * {@inheritdoc}
	 */
	public function authentication_actived()
	{
		return SocialNetworksConfig::load()->is_authentication_available($this->get_authentication_id());
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function get_image_renderer_html()
	{
		$tpl = new FileTemplate('SocialNetworks/auth_image_render.tpl');
		$tpl->put_all(array(
			'C_DISPLAY_NAME' => strpos(REWRITED_SCRIPT, 'user') !== false && strpos(REWRITED_SCRIPT, 'login') !== false,
			'ICON_NAME' => $this->get_social_network()->get_icon_name(),
			'NAME' => $this->get_authentication_name()
		));
		return $tpl->render();
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function get_css_class()
	{
		return 'social-connect ' . $this->get_social_network()->get_css_class();
	}
	
	/**
	 * {@inheritdoc}
	 */
	abstract public function get_authentication();
	
	/**
	 * @desc Delete the Social Network session token
	 */
	public function delete_session_token()
	{
		if (isset($_SESSION[$this->get_authentication_id() . '_token']))
			unset($_SESSION[$this->get_authentication_id() . '_token']);
	}
}
?>
