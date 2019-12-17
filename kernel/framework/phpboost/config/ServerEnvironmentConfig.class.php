<?php
/**
 * @package     PHPBoost
 * @subpackage  Config
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 10 26
 * @since       PHPBoost 3.0 - 2010 07 08
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor janus57 <janus57@janus57.fr>
*/

class ServerEnvironmentConfig extends AbstractConfigData
{
	const URL_REWRITING_ENABLED       = 'url_rewriting_enabled';
	const REDIRECTION_WWW_ENABLED     = 'redirection_www_enabled';
	const REDIRECTION_WWW_MODE        = 'redirection_www_mode';
	const REDIRECTION_WWW_WITH_WWW    = 'with_www';
	const REDIRECTION_WWW_WITHOUT_WWW = 'without_www';
	const REDIRECTION_HTTPS_ENABLED   = 'redirection_https_enabled';
	const HSTS_SECURITY_ENABLED       = 'hsts_security_enabled';
	const HSTS_SECURITY_SUBDOMAIN_ENABLED	= 'hsts_security_subdomain_enabled';
	const HSTS_SECURITY_DURATION      = 'hsts_security_duration';
	const HTACCESS_MANUAL_CONTENT     = 'htaccess_manual_content';
	const NGINX_MANUAL_CONTENT        = 'nginx_manual_content';
	const OUTPUT_GZIPING_ENABLED      = 'output_gziping_enabled';

	public function is_url_rewriting_enabled()
	{
		return $this->get_property(self::URL_REWRITING_ENABLED);
	}

	public function set_url_rewriting_enabled($enabled)
	{
		$this->set_property(self::URL_REWRITING_ENABLED, $enabled);
	}

	public function is_redirection_www_enabled()
	{
		return $this->get_property(self::REDIRECTION_WWW_ENABLED);
	}

	public function enable_redirection_www()
	{
		return $this->set_property(self::REDIRECTION_WWW_ENABLED, true);
	}

	public function disable_redirection_www()
	{
		return $this->set_property(self::REDIRECTION_WWW_ENABLED, false);
	}

	public function get_redirection_www_mode()
	{
		return $this->get_property(self::REDIRECTION_WWW_MODE);
	}

	public function is_redirection_www_mode_with_www()
	{
		return $this->get_property(self::REDIRECTION_WWW_MODE) == self::REDIRECTION_WWW_WITH_WWW;
	}

	public function set_redirection_www_mode($value)
	{
		return $this->set_property(self::REDIRECTION_WWW_MODE, $value);
	}

	public function is_redirection_https_enabled()
	{
		return $this->get_property(self::REDIRECTION_HTTPS_ENABLED);
	}

	public function enable_redirection_https()
	{
		return $this->set_property(self::REDIRECTION_HTTPS_ENABLED, true);
	}

	public function disable_redirection_https()
	{
		return $this->set_property(self::REDIRECTION_HTTPS_ENABLED, false);
	}

	public function is_hsts_security_enabled()
	{
		return $this->get_property(self::HSTS_SECURITY_ENABLED);
	}

	public function enable_hsts_security()
	{
		return $this->set_property(self::HSTS_SECURITY_ENABLED, true);
	}

	public function disable_hsts_security()
	{
		return $this->set_property(self::HSTS_SECURITY_ENABLED, false);
	}

	public function get_hsts_security_duration()
	{
		return $this->get_property(self::HSTS_SECURITY_DURATION);
	}

	public function set_hsts_security_duration($value)
	{
		return $this->set_property(self::HSTS_SECURITY_DURATION, ($value * (24 * 60 * 60))); // hour, minute, second
	}

	public function get_config_hsts_security_duration()
	{
		return ($this->get_property(self::HSTS_SECURITY_DURATION) / (24 * 60 * 60));
	}

	public function is_hsts_security_subdomain_enabled()
	{
		return $this->get_property(self::HSTS_SECURITY_SUBDOMAIN_ENABLED);
	}

	public function enable_hsts_subdomain_security()
	{
		return $this->set_property(self::HSTS_SECURITY_SUBDOMAIN_ENABLED, true);
	}

	public function disable_hsts_subdomain_security()
	{
		return $this->set_property(self::HSTS_SECURITY_SUBDOMAIN_ENABLED, false);
	}

	private function htaccess_exists()
	{
		$file = new File(PATH_TO_ROOT . '/.htaccess');
		return $file->exists();
	}

	public function get_htaccess_manual_content()
	{
		return $this->get_property(self::HTACCESS_MANUAL_CONTENT);
	}

	public function set_htaccess_manual_content($content)
	{
		$this->set_property(self::HTACCESS_MANUAL_CONTENT, $content);
	}

	private function nginx_conf_exists()
	{
		$file = new File(PATH_TO_ROOT . '/nginx.conf');
		return $file->exists();
	}

	public function get_nginx_manual_content()
	{
		return $this->get_property(self::NGINX_MANUAL_CONTENT);
	}

	public function set_nginx_manual_content($content)
	{
		$this->set_property(self::NGINX_MANUAL_CONTENT, $content);
	}

	public function is_output_gziping_enabled()
	{
		return $this->get_property(self::OUTPUT_GZIPING_ENABLED);
	}

	public function set_output_gziping_enabled($enabled)
	{
		$this->set_property(self::OUTPUT_GZIPING_ENABLED, $enabled);
	}

	public function get_default_values()
	{
		return array(
			self::URL_REWRITING_ENABLED     => false,
			self::REDIRECTION_WWW_ENABLED   => false,
			self::REDIRECTION_WWW_MODE      => self::REDIRECTION_WWW_WITH_WWW,
			self::REDIRECTION_HTTPS_ENABLED => false,
			self::HSTS_SECURITY_ENABLED     => false,
			self::HSTS_SECURITY_SUBDOMAIN_ENABLED	=> false,
			self::HSTS_SECURITY_DURATION    => 2592000, // 30 days per default
			self::HTACCESS_MANUAL_CONTENT   => '',
			self::NGINX_MANUAL_CONTENT      => '',
			self::OUTPUT_GZIPING_ENABLED    => false
		);
	}

	/**
	 * Returns the configuration.
	 * @return ServerEnvironmentConfig
	 */
	public static function load()
	{
		return ConfigManager::load(__CLASS__, 'kernel', 'server-environment-config');
	}

	/**
	 * Saves the configuration in the database. Has it become persistent.
	 */
	public static function save()
	{
		ConfigManager::save('kernel', self::load(), 'server-environment-config');
	}
}
?>
