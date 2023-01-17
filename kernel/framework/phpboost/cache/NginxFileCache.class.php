<?php
/**
 * This class contains the cache data of the nginx.conf file which is located at the root of the site
 * and is used to change the Nginx configuration only in the PHPBoost folder.
 * @package     PHPBoost
 * @subpackage  Cache
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2023 01 17
 * @since       PHPBoost 5.2 - 2019 10 26
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class NginxFileCache implements CacheData
{
	private $nginx_file_content = '';
	private $general_config;
	private $server_environment_config;

	/**
	 * {@inheritdoc}
	 */
	public function synchronize()
	{
		$this->nginx_file_content = '';
		$this->general_config = GeneralConfig::load();
		$this->server_environment_config = ServerEnvironmentConfig::load();

		if ($this->server_environment_config->is_url_rewriting_enabled())
		{
			$this->force_redirection_if_available();

			$this->add_core_rules();

			$this->add_modules_rules();

			$this->add_user_rules();

			$this->add_bandwidth_protection();
		}

		$this->set_default_charset();

		$this->add_error_redirection();

		$this->add_expires_headers();

		$this->add_http_headers();

		$this->disable_file_etags();

		$this->add_manual_content();

		$this->clean_file_content();
	}

	private function add_line($line)
	{
		$this->nginx_file_content .= "\n" . $line;
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

	private function set_default_charset()
	{
		$this->add_section('Charset');
		$this->add_line('charset utf-8;');
	}

	private function add_core_rules()
	{
		$this->add_section('Core');

		$eps = AppContext::get_extension_provider_service();
		$mappings = $eps->get_extension_point(UrlMappingsExtensionPoint::EXTENSION_POINT);
		$this->add_url_mapping($mappings['kernel']);
	}

	private function add_modules_rules()
	{
		$modules = ModulesManager::get_activated_modules_map();
		$eps = AppContext::get_extension_provider_service();

		// Generate high priority rewriting rules
		$first_high_priority_mapping = true;
		foreach ($modules as $module)
		{
			$id = $module->get_id();
			if ($eps->provider_exists($id, UrlMappingsExtensionPoint::EXTENSION_POINT))
			{
				$provider = $eps->get_provider($id);
				$mappings_high_priority = array();
				foreach ($provider->get_extension_point(UrlMappingsExtensionPoint::EXTENSION_POINT)->list_mappings() as $mapping)
				{
					if ($mapping instanceof DispatcherUrlMapping && $mapping->is_high_priority())
						$mappings_high_priority[] = $mapping;
				}

				if (!empty($mappings_high_priority))
				{
					if ($first_high_priority_mapping)
					{
						$this->add_section('High Priority Modules rules');
						$first_high_priority_mapping = false;
					}

					$this->add_section($id);
					$this->add_url_mapping($mappings_high_priority);
				}
			}
		}

		$this->add_section('Modules rules');

		foreach ($modules as $module)
		{
			$id = $module->get_id();
			if ($eps->provider_exists($id, UrlMappingsExtensionPoint::EXTENSION_POINT))
			{
				$provider = $eps->get_provider($id);
				$mappings_normal_priority = array();
				foreach ($provider->get_extension_point(UrlMappingsExtensionPoint::EXTENSION_POINT)->list_mappings() as $mapping)
				{
					if ($mapping instanceof DispatcherUrlMapping && (!$mapping->is_high_priority() && !$mapping->is_low_priority()))
						$mappings_normal_priority[] = $mapping;
				}

				if (!empty($mappings_normal_priority))
				{
					$this->add_section($id);
					$this->add_url_mapping($mappings_normal_priority, $module->get_configuration()->get_url_rewrite_rules());
				}
			}
		}

		// Generate low priority rewriting rules
		$first_low_priority_mapping = true;
		foreach ($modules as $module)
		{
			$id = $module->get_id();
			if ($eps->provider_exists($id, UrlMappingsExtensionPoint::EXTENSION_POINT))
			{
				$provider = $eps->get_provider($id);
				$mappings_low_priority = array();
				foreach ($provider->get_extension_point(UrlMappingsExtensionPoint::EXTENSION_POINT)->list_mappings() as $mapping)
				{
					if ($mapping instanceof DispatcherUrlMapping && $mapping->is_low_priority())
						$mappings_low_priority[] = $mapping;
				}

				if (!empty($mappings_low_priority))
				{
					if ($first_low_priority_mapping)
					{
						$this->add_section('Low Priority Modules rules');
						$first_low_priority_mapping = false;
					}

					$this->add_section($id);
					$this->add_url_mapping($mappings_low_priority);
				}
			}
		}
	}

	private function add_user_rules()
	{
		$this->add_section('User');

		$rules = array('RewriteRule ^user/pm-?([0-9]+)-?([0-9]{0,})-?([0-9]{0,})-?([0-9]{0,})-?([a-z_]{0,})$ /user/pm.php?pm=$1&id=$2&p=$3&quote=$4 [L,QSA]');

		$eps = AppContext::get_extension_provider_service();
		$mappings = $eps->get_extension_point(UrlMappingsExtensionPoint::EXTENSION_POINT);
		$this->add_url_mapping($mappings['user'], $rules);
	}

	private function add_rewrite_rule($match, $path)
	{
		$this->add_line('	rewrite ' . str_replace('^', '^/', $match) . ' ' . $this->general_config->get_site_path() . '/' . ltrim($path, '/') . ' break;');
	}

	private function add_url_mapping($mapping_list, $rules = array())
	{
		if ($mapping_list instanceof UrlMappingsExtensionPoint)
			$mapping_list = $mapping_list->list_mappings();

		$locations = array();
		foreach ($mapping_list as $mapping)
		{
			preg_match('#([\w_-]*)/#', $mapping->from(), $matches);
			$locations[$matches[0]][] = array('from' => $mapping->from(), 'to' => $mapping->to());
		}

		$first_location = true;
		foreach ($locations as $location => $element)
		{
			$this->add_line('location /' . substr($location, 0, -1) . ' {');
			if ($first_location && !empty($rules))
			{
				foreach ($rules as $rule)
				{
					$this->add_line(str_replace(array('DIR', 'RewriteRule', '^', '[L,QSA]', '[L]'), array($this->general_config->get_site_path(), '	rewrite', '^/', 'break;', 'break;'), $rule));
				}
			}
			$first_location = false;

			foreach ($element as $parameters)
			{
				$this->add_rewrite_rule($parameters['from'], $parameters['to']);
			}
			$this->add_line('}');
			next($locations) !== false ? $this->add_empty_line() : '';
		}
	}

	private function force_redirection_if_available()
	{
		$domain = AppContext::get_request()->get_domain_name();

		if (!$this->server_environment_config->is_redirection_https_enabled() && $this->server_environment_config->is_redirection_www_enabled() && $this->server_environment_config->is_redirection_www_mode_with_www())
		{
			$this->add_section('Site redirection to www');
			$this->add_line('if ($host !~ "^www\.") {');
			$this->add_line('	rewrite ^(.*)$ http://www.$http_host/$1 permanent;');
			$this->add_line('}');
		}

		if (!$this->server_environment_config->is_redirection_https_enabled() && $this->server_environment_config->is_redirection_www_enabled() && !$this->server_environment_config->is_redirection_www_mode_with_www())
		{
			$this->add_section('Site redirection to NON-www');
			$this->add_line('if ($host ~ "^www\.") {');
			$this->add_line('	rewrite ^www\.(.*)$ http://%1/$1 permanent;');
			$this->add_line('}');
		}

		if ($this->server_environment_config->is_redirection_https_enabled() && $this->server_environment_config->is_redirection_www_enabled() && $this->server_environment_config->is_redirection_www_mode_with_www())
		{
			$this->add_section('Force to use HTTPS AND ...');
			$this->add_line('if ($https != "on") {');
			$this->add_line('	rewrite .* https://$http_host$request_uri permanent;');
			$this->add_line('}');
			$this->add_line('if ($server_port = 80) {');
			$this->add_line('	rewrite .* https://$http_host$request_uri permanent;');
			$this->add_line('}');
			$this->add_line('if ($http_x_forwarded_proto != https) {');
			$this->add_line('	rewrite .* https://$http_host$request_uri permanent;');
			$this->add_line('}');
			$this->add_section('... WWW');
			$this->add_line('if ($host !~ "^www\.") {');
			$this->add_line('	rewrite ^(.*)$ https://www.$http_host/$1 permanent;');
			$this->add_line('}');
		}

		if ($this->server_environment_config->is_redirection_https_enabled() && $this->server_environment_config->is_redirection_www_enabled() && !$this->server_environment_config->is_redirection_www_mode_with_www())
		{
			$this->add_section('Force to use HTTPS AND ...');
			$this->add_line('if ($https != "on") {');
			$this->add_line('	rewrite .* https://$http_host$request_uri permanent;');
			$this->add_line('}');
			$this->add_line('if ($server_port = 80) {');
			$this->add_line('	rewrite .* https://$http_host$request_uri permanent;');
			$this->add_line('}');
			$this->add_line('if ($http_x_forwarded_proto != https) {');
			$this->add_line('	rewrite .* https://$http_host$request_uri permanent;');
			$this->add_line('}');
			$this->add_section('... NON-WWW');
			$this->add_line('if ($host ~ "^www\.") {');
			$this->add_line('	rewrite ^www\.(.*)$ http://%1/$1 permanent;');
			$this->add_line('}');
		}

		if ($this->server_environment_config->is_redirection_https_enabled() && !$this->server_environment_config->is_redirection_www_enabled())
		{
			$this->add_section('Force to use HTTPS WITH SUBDOMAIN');
			$this->add_section('Force to use HTTPS AND ...');
			$this->add_line('if ($https != "on") {');
			$this->add_line('	rewrite .* https://$http_host$request_uri permanent;');
			$this->add_line('}');
			$this->add_line('if ($server_port = 80) {');
			$this->add_line('	rewrite .* https://$http_host$request_uri permanent;');
			$this->add_line('}');
			$this->add_line('if ($http_x_forwarded_proto != https) {');
			$this->add_line('	rewrite .* https://$http_host$request_uri permanent;');
			$this->add_line('}');
		}
	}

	private function add_bandwidth_protection()
	{
		//Bandwidth protection. The /upload directory can be forbidden if the request comes from out of PHPBoost
		if (FileUploadConfig::load()->get_enable_bandwidth_protect())
		{
			$this->add_section('Bandwith protection');
			$this->add_line('location /upload {');
			$this->add_line('	if ($http_referer !~ "^' . $this->general_config->get_site_url() . '"){');
			$this->add_line('		return 403;');
			$this->add_line('	}');
			$this->add_line('}');
			$this->add_empty_line();
			$this->add_section('Stop hotlinking');
			$this->add_line('location ~ ^/.+\.(?:ico|css|js|bmp|gif|webp|jpe?g|png)$ {');
			$this->add_line('	if ($http_referer !~ "^' . $this->general_config->get_site_url() . '"){');
			$this->add_line('		return 403;');
			$this->add_line('	}');
			$this->add_line('}');
		}
	}

	private function add_error_redirection()
	{
		//Error page
		$this->add_section('Error pages');
		$this->add_line('error_page 403 ' . $this->general_config->get_site_path() . UserUrlBuilder::error_403()->relative());
		$this->add_line('error_page 404 ' . $this->general_config->get_site_path() . UserUrlBuilder::error_404()->relative());
	}

	private function add_expires_headers()
	{
		$this->add_section('Expires Headers');
		$this->add_line('map $sent_http_content_type $expires {');
		$this->add_line('	# Default expiration: 1 week after request');
		$this->add_line('	default                   7d;');
		$this->add_empty_line();
		$this->add_line('	# CSS and JS expiration: 1 week after request');
		$this->add_line('	text/css                  7d;');
		$this->add_line('	text/javascript           7d;');
		$this->add_line('	text/x-javascript         7d;');
		$this->add_line('	application/javascript    7d;');
		$this->add_line('	application/x-javascript  7d;');
		$this->add_empty_line();
		$this->add_line('	# Fonts expiration: 1 month after request');
		$this->add_line('	application/font-woff     30d;');
		$this->add_line('	application/font-woff2    30d;');
		$this->add_empty_line();
		$this->add_line('	# Image files expiration: 1 month after request');
		$this->add_line('	image/bmp                 30d;');
		$this->add_line('	image/gif                 30d;');
		$this->add_line('	image/jpeg                30d;');
		$this->add_line('	image/jp2                 30d;');
		$this->add_line('	image/pipeg               30d;');
		$this->add_line('	image/png                 30d;');
		$this->add_line('	image/webp                30d;');
		$this->add_line('	image/svg+xml             30d;');
		$this->add_line('	image/tiff                30d;');
		$this->add_line('	image/vnd.microsoft.icon  30d;');
		$this->add_line('	image/x-icon              30d;');
		$this->add_line('	image/ico                 30d;');
		$this->add_line('	image/icon                30d;');
		$this->add_line('	text/ico                  30d;');
		$this->add_line('	application/ico           30d;');
		$this->add_line('	image/vnd.wap.wbmp        30d;');
		$this->add_line('	application/vnd.wap.wbxml 30d;');
		$this->add_line('	application/smil          30d;');
		$this->add_empty_line();
		$this->add_line('	# Audio files expiration: 1 month after request');
		$this->add_line('	audio/basic               30d;');
		$this->add_line('	audio/mid                 30d;');
		$this->add_line('	audio/midi                30d;');
		$this->add_line('	audio/mpeg                30d;');
		$this->add_line('	audio/x-aiff              30d;');
		$this->add_line('	audio/x-mpegurl           30d;');
		$this->add_line('	audio/x-pn-realaudio      30d;');
		$this->add_line('	audio/x-wav               30d;');
		$this->add_empty_line();
		$this->add_line('	# Movie files expiration: 1 month after request');
		$this->add_line('	application/x-shockwave-flash 30d;');
		$this->add_line('	x-world/x-vrml            30d;');
		$this->add_line('	video/x-msvideo           30d;');
		$this->add_line('	video/mpeg                30d;');
		$this->add_line('	video/mp4                 30d;');
		$this->add_line('	video/quicktime           30d;');
		$this->add_line('	video/x-la-asf            30d;');
		$this->add_line('	video/x-ms-asf            30d;');
		$this->add_line('}');
		$this->add_empty_line();
		$this->add_line('expires $expires;');
	}

	private function add_http_headers()
	{
		$this->add_section('HTTP Headers');

		if ($this->server_environment_config->is_redirection_https_enabled() && $this->server_environment_config->is_hsts_security_enabled())
		{
			$this->add_line('# Tell the browser to attempt the HTTPS version first');
			$this->add_line('add_header Strict-Transport-Security "max-age=' . $this->server_environment_config->get_hsts_security_duration() . '; ' . ($this->server_environment_config->is_hsts_security_subdomain_enabled() ? 'includeSubDomains;' : '') . '"');
		}

		$this->add_line('# Don\'t allow any pages to be framed externally - Defends against CSRF');
		$this->add_line('add_header X-Frame-Options SAMEORIGIN');
		$this->add_line('# Control Cross-Domain Policies');
		$this->add_line('add_header X-Permitted-Cross-Domain-Policies "master-only"');
		$this->add_line('# Prevent mime based attacks');
		$this->add_line('add_header X-Content-Type-Options "nosniff"');
	}

	private function disable_file_etags()
	{
		$this->add_section('Disable file etags');
		$this->add_line('etag off;');
	}

	private function add_manual_content()
	{
		$manual_content = $this->server_environment_config->get_nginx_manual_content();
		if (!empty($manual_content))
		{
			$this->add_section('Manual content');
			$this->add_line($manual_content);
		}
	}

	private function clean_file_content()
	{
		$this->nginx_file_content = trim($this->nginx_file_content);
	}

	/**
	 * Returns the content of the nginx.conf file
	 * @return string its content
	 */
	public function get_nginx_file_content()
	{
		return $this->nginx_file_content;
	}

	/**
	 * Loads and returns the groups cached data.
	 * @return NginxFileCache The cached data
	 */
	public static function load()
	{
		return CacheManager::load(__CLASS__, 'kernel', 'nginx-file');
	}

	/**
	 * Invalidates the current groups cached data.
	 */
	public static function invalidate()
	{
		CacheManager::invalidate('kernel', 'nginx-file');
	}

	/**
	 * Regenerates the nginx.conf file
	 */
	public static function regenerate()
	{
		self::invalidate();
		self::update_nginx_file();
	}

	private static function update_nginx_file()
	{
		if (isset($_SERVER["SERVER_SOFTWARE"]) && !preg_match('/apache/i', $_SERVER["SERVER_SOFTWARE"]))
		{
			$file = new File(PATH_TO_ROOT . '/nginx.conf');

			try
			{
				$file->write(self::get_file_content());
				$file->close();
			}
			catch(IOException $ex)
			{
				ErrorHandler::add_error_in_log('Couldn\'t write the nginx.conf file. Please check the site root read authorizations.', '');
			}
		}
	}

	/**
	 *
	 * @return string
	 */
	private static function get_file_content()
	{
		return self::load()->get_nginx_file_content();
	}
}
?>
