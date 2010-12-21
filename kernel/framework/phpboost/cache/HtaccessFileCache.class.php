<?php
/*##################################################
 *                           HtaccessCache.class.php
 *                            -------------------
 *   begin                : October 22, 2009
 *   copyright            : (C) 2009 Benoit Sautel
 *   email                : ben.popeye@phpboost.com
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
 * This class contains the cache data of the .htaccess file which is located at the root of the site
 * and is used to change the Apache configuration only in the PHPBoost folder.
 * @author Benoit Sautel <ben.popeye@phpboost.com>
 *
 */
class HtaccessFileCache implements CacheData
{
	private $htaccess_file_content = '';
	private $general_config;

	/**
	 * {@inheritdoc}
	 */
	public function synchronize()
	{
		$this->htaccess_file_content = '';
		$this->general_config = GeneralConfig::load();
		
		if (ServerEnvironmentConfig::load()->is_url_rewriting_enabled())
		{
			$this->enable_rewrite_rules();

			$this->add_core_rules();
			$this->add_feeds_rules();
			$this->add_modules_rules();

			$this->add_bandwidth_protection();

			$this->add_robots_protection();
		}

		$this->add_404_error_redirection();

		$this->add_manual_content();

		$this->clean_file_content();
	}

	private function add_line($line)
	{
		$this->htaccess_file_content .= "\n" . $line;
	}

	private function add_empty_line()
	{
		$this->add_line('');
	}

	private function add_section($name)
	{
		$this->add_empty_line();
		$this->add_line('# ' . $name . ' #');
	}

	private function enable_rewrite_rules()
	{
		$this->add_section('Rewrite rules');
		$this->add_line('Options +FollowSymlinks');
		$this->add_line('RewriteEngine on');
	}

	private function add_core_rules()
	{
		$this->add_section('Core');

		$this->add_rewrite_rule('^member/member-([0-9]+)-?([0-9]*)\.php$', 'member/member.php?id=$2&p=$3');
		$this->add_rewrite_rule('^member/pm-?([0-9]+)-?([0-9]{0,})-?([0-9]{0,})-?([0-9]{0,})-?([a-z_]{0,})\.php$', 'member/pm.php?pm=$2&id=$3&p=$4&quote=$5');

        $eps = AppContext::get_extension_provider_service();
        $mappings = $eps->get_extension_point(UrlMappingsExtensionPoint::EXTENSION_POINT, array('kernel', 'install'));
		foreach ($mappings as $mapping_list)
		{
            $this->add_url_mapping($mapping_list);
		}
	}

	private function add_feeds_rules()
	{
		$this->add_section('Feeds');
		foreach (array('rss', 'atom') as $feed_type)
		{
	        $this->add_rewrite_rule('^' . $feed_type . '/?$', 'syndication.php?m=news&feed=' . $feed_type);
	        $this->add_rewrite_rule('^' . $feed_type . '/([a-zA-Z0-9-]+)/?$', 'syndication.php?m=$1&feed=' . $feed_type);
	        $this->add_rewrite_rule('^' . $feed_type . '/([a-zA-Z0-9-]+)/([0-9]+)/?$', 'syndication.php?m=$1&cat=$2&feed=' . $feed_type);
			$this->add_rewrite_rule('^' . $feed_type . '/([a-zA-Z0-9-]+)/([0-9]+)/([a-zA-Z0-9-]+)/?$', 'syndication.php?m=$1&cat=$2&name=$3&feed=' . $feed_type);
			$this->add_rewrite_rule('^' . $feed_type . '/([a-zA-Z0-9-]+)/([a-zA-Z0-9-]+)/([0-9]+)/?$', 'syndication.php?m=$1&cat=$3&name=$2&feed=' . $feed_type);
		}
	}

	private function add_modules_rules()
	{
		$this->add_section('Modules rules');

		$modules = ModulesManager::get_installed_modules_map();
		$eps = AppContext::get_extension_provider_service();
		foreach ($modules as $module)
		{
			$id = $module->get_id();
			$configuration = $module->get_configuration();
			$this->add_section($id);
			foreach ($configuration->get_url_rewrite_rules() as $rule)
			{
				$this->add_line(str_replace('DIR', $this->general_config->get_site_path(), $rule));
			}
			if ($eps->provider_exists($id, UrlMappingsExtensionPoint::EXTENSION_POINT))
			{
				$provider = $eps->get_provider($id);
				$url_mappings = $provider->get_extension_point(UrlMappingsExtensionPoint::EXTENSION_POINT);
				$this->add_url_mapping($url_mappings);
			}
		}
	}

	private function add_rewrite_rule($match, $path, $options = 'L,QSA')
	{
        $this->add_line('RewriteRule ' . $match . ' ' . $this->general_config->get_site_path() . '/' . ltrim($path, '/') . ' [' . $options . ']');
	}

    private function add_url_mapping(UrlMappingsExtensionPoint $mapping_list)
    {
        foreach ($mapping_list->list_mappings() as $mapping)
        {
            $this->add_rewrite_rule($mapping->from(), $mapping->to());
        }
    }

	private function add_bandwidth_protection()
	{
		//Bandwidth protection. The /upload directory can be forbidden if the request comes from
		//out of PHPBoost
		if (FileUploadConfig::load()->get_enable_bandwidth_protect())
		{
			$this->add_section('Bandwith protection');
			$this->add_line('RewriteCond %{HTTP_REFERER} !^$');
			$this->add_line('RewriteCond %{HTTP_REFERER} !^' . $this->general_config->get_site_url());
			$this->add_line('ReWriteRule .*upload/.*$ - [F]');
		}
	}

	private function add_robots_protection()
	{
		//Robot protection
		$this->add_section('Avoid Hacking Attempt');
		$this->add_line('RewriteCond %{HTTP_USER_AGENT} libwww [NC]');
		$this->add_line('RewriteRule .* - [F,L]');
	}

	private function add_404_error_redirection()
	{
		//Error page
		$this->add_empty_line();
		$this->add_line('# Error page #');
		$this->add_line('ErrorDocument 404 ' . $this->general_config->get_site_path() . '/member/404.php');
	}

	private function add_manual_content()
	{
		$manual_content = ServerEnvironmentConfig::load()->get_htaccess_manual_content();
		if (!empty($manual_content))
		{
			$this->add_section('Manual content');
			$this->add_line($manual_content);
		}
	}

	private function clean_file_content()
	{
		$this->htaccess_file_content = trim($this->htaccess_file_content);
	}

	/**
	 * Returns the content of the .htaccess file
	 * @return string its content
	 */
	public function get_htaccess_file_content()
	{
		return $this->htaccess_file_content;
	}

	/**
	 * Loads and returns the groups cached data.
	 * @return HtaccessFileCache The cached data
	 */
	public static function load()
	{
		return CacheManager::load(__CLASS__, 'kernel', 'htaccess-file');
	}

	/**
	 * Invalidates the current groups cached data.
	 */
	public static function invalidate()
	{
		CacheManager::invalidate('kernel', 'htaccess-file');
	}

	/**
	 * Regenerates the .htaccess file
	 */
	public static function regenerate()
	{
		self::invalidate();
		self::update_htaccess_file();
	}

	private static function update_htaccess_file()
	{
		$file = new File(PATH_TO_ROOT . '/.htaccess');

		try
		{
			$file->write(self::get_file_content());
			$file->close();
		}
		catch(IOException $ex)
		{
			ErrorHandler::add_error_in_log('Couldn\'t write the .htaccess file. Please check the site root read authorizations.');
		}
	}

	/**
	 *
	 * @return string
	 */
	private static function get_file_content()
	{
		return self::load()->get_htaccess_file_content();
	}
}