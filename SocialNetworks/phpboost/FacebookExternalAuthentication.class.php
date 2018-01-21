<?php
/*##################################################
 *		                        FacebookExternalAuthentication.class.php
 *                            -------------------
 *   begin                : January 08, 2018
 *   copyright            : (C) 2018 Kévin MASSY
 *   email                : kevin.massy@phpboost.com
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

/**
 * @author Kevin MASSY <kevin.massy@phpboost.com>
 */
class FacebookExternalAuthentication implements ExternalAuthentication
{
	public function get_authentication_id()
	{
		return FacebookAuthenticationMethod::AUTHENTICATION_METHOD;
	}

	public function get_authentication_name()
	{
		return LangLoader::get_message('facebook-connect', 'user-common');
	}

	public function authentication_actived()
	{
		return SocialNetworksConfig::load()->is_fb_auth_available();
	}

	public function get_image_renderer_html()
	{
		return (new FileTemplate('SocialNetworks/facebook_image_render.tpl'))->render();
	}

	public function get_authentication()
	{
		return new FacebookAuthenticationMethod();
	}
}
?>