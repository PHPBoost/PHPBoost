<?php
/*##################################################
 *                       NewsletterSitemapExtensionPoint.class.php
*                            -------------------
*   begin                : May 30, 2012
*   copyright            : (C) 2012 Kevin MASSY
*   email                : soldier.weasel@gmail.com
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

class NewsletterSitemapExtensionPoint implements SitemapExtensionPoint
{
	public function get_public_sitemap()
	{
		return $this->get_module_map(Sitemap::AUTH_PUBLIC);
	}

	public function get_user_sitemap()
	{
		return $this->get_module_map(Sitemap::AUTH_USER);
	}

	private function get_module_map($auth_mode)
	{
		$lang = LangLoader::get('newsletter_common', 'newsletter');
		
		$link = new SitemapLink($lang['newsletter'], NewsletterUrlBuilder::home(), Sitemap::FREQ_MONTHLY, Sitemap::PRIORITY_MAX);
		$module_map = new ModuleMap($link, 'newsletter');

		$streams = NewsletterStreamsCache::load()->get_streams();
		$config = NewsletterConfig::load();
		$user = AppContext::get_current_user();
		foreach ($streams as $id => $properties)
		{			
			if ($auth_mode == Sitemap::AUTH_PUBLIC)
			{
				$is_authorized = is_array($properties['authorizations']) ? Authorizations::check_auth(RANK_TYPE, GUEST_LEVEL, $properties['authorizations'], NewsletterAuthorizationsService::AUTH_READ) : Authorizations::check_auth(RANK_TYPE, GUEST_LEVEL, $config->get_authorizations(), NewsletterAuthorizationsService::AUTH_READ);
			}
			else
			{
				$is_authorized = is_array($properties['authorizations']) ? $user->check_auth($properties['authorizations'], NewsletterAuthorizationsService::AUTH_READ) : $user->check_auth($config->get_authorizations(), NewsletterAuthorizationsService::AUTH_READ);
			}
			
			if ($is_authorized && $properties['visible'])
			{
				$link = new SitemapLink($properties['name'], NewsletterUrlBuilder::archives($id));
				$section = new SitemapSection($link);
				$module_map->add($section);
			}
		}

		return $module_map;
	}
}
?>