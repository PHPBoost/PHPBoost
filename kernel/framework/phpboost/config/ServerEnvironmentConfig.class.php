<?php
/*##################################################
 *		             ServerEnvironmentConfig.class.php
 *                            -------------------
 *   begin                : July 8, 2010
 *   copyright            : (C) 2010 Benoit Sautel
 *   email                : ben.popeye@phpboost.com
 *
 *
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU ServerEnvironment Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU ServerEnvironment Public License for more details.
 *
 * You should have received a copy of the GNU ServerEnvironment Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

/**
 * @author Benoit Sautel <ben.popeye@phpboost.com>
 */
class ServerEnvironmentConfig extends AbstractConfigData
{
	const URL_REWRITING_ENABLED = 'url_rewriting_enabled';
	const HTACCESS_MANUAL_CONTENT = 'htaccess_manual_content';
	const OUTPUT_GZIPING_ENABLED = 'output_gziping_enabled';

	public function is_url_rewriting_enabled()
	{
		return $this->get_property(self::URL_REWRITING_ENABLED);
	}
	
	public function set_url_rewriting_enabled($enabled)
	{
		$this->set_property(self::URL_REWRITING_ENABLED, $enabled);
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
			self::URL_REWRITING_ENABLED => false,
			self::HTACCESS_MANUAL_CONTENT => '',
			self::OUTPUT_GZIPING_ENABLED => false
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