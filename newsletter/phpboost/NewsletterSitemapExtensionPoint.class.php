<?php
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
		
		$link = new SitemapLink($lang['newsletter'], NewsletterUrlBuilder::home(), Sitemap::FREQ_DAILY, Sitemap::PRIORITY_MAX);
		$module_map = new ModuleMap($link, 'newsletter');

		$streams = NewsletterStreamsCache::load()->get_streams();
		$config = NewsletterConfig::load();
		$user = AppContext::get_current_user();
		foreach ($streams as $id => $properties)
		{			
			if ($auth_mode == Sitemap::AUTH_PUBLIC)
			{
				$is_authorized = is_array($properties['authorizations']) ? Authorizations::check_auth(RANK_TYPE, GUEST_LEVEL, $properties['auth'], NewsletterAuthorizationsService::AUTH_READ) : Authorizations::check_auth(RANK_TYPE, GUEST_LEVEL, $config->get_authorizations(), NewsletterAuthorizationsService::AUTH_READ);
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