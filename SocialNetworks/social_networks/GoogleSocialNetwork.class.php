<?php
/*##################################################
 *                        GoogleSocialNetwork.class.php
 *                            -------------------
 *   begin                : April 10, 2018
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

class GoogleSocialNetwork extends AbstractSocialNetwork
{
	const SOCIAL_NETWORK_ID = 'google';
	
	public function get_name()
	{
		return 'Google+';
	}
	
	public function get_icon_name()
	{
		return self::SOCIAL_NETWORK_ID . '-plus-g';
	}
	
	public function get_css_class()
	{
		return self::SOCIAL_NETWORK_ID . 'plus';
	}
	
	public function get_content_sharing_url()
	{
		return 'https://plus.google.com/share?url=' . HOST . REWRITED_SCRIPT;
	}
	
	public function get_external_authentication()
	{
		return new GoogleExternalAuthentication();
	}
	
	public function get_identifiers_creation_url()
	{
		return 'https://console.developers.google.com/project';
	}
}
?>
