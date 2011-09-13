<?php
/*##################################################
 *		                    SearchModuleConfig.class.php
 *                            -------------------
 *   begin                : April 10, 2010
 *   copyright            : (C) 2010 Loic Rouchon
 *   email                : loic.rouchon@phpboost.com
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
 * @desc This class represents the search module's configuration.
 * @author Loic Rouchon <loic.rouchon@phpboost.com>
 */
class SearchModuleConfig extends AbstractConfigData
{
	const weightings = 'weightings';
	const nb_results_per_page = '_nb_results_per_page';
	const cache_lifetime = 'cache_lifetime';
	const cache_max_uses = 'cache_max_uses';
	const unauthorized_providers = 'unauthorized_providers';

	/**
	 * Returns the search providers weighting
	 * @return int[string] the search providers weighting
	 */
	public function get_weightings()
	{
		return $this->get_property(self::weightings);
	}

	/**
	 * Sets the search providers weighting
	 * @param int[string] $weightings the search providers weighting
	 */
	public function set_weightings(array $weightings)
	{
		$this->set_property(self::weightings, $weightings);
	}

	/**
	 * Returns the number of result per page to display
	 * @return int the number of result per page to display
	 */
	public function get_nb_results_per_page()
	{
		return $this->get_property(self::nb_results_per_page);
	}

	/**
	 * Sets the number of result per page to display
	 * @param int $nb_results_per_page the number of result per page to display
	 */
	public function set_nb_results_per_page($nb_results_per_page)
	{
		$this->set_property(self::nb_results_per_page, $nb_results_per_page);
	}

	/**
	 * Returns the cache lifetime in minutes
	 * @return int the cache lifetime in minutes
	 */
	public function get_cache_lifetime()
	{
		return $this->get_property(self::cache_lifetime);
	}

	/**
	 * Sets the cache lifetime in minutes
	 * @param int $cache_lifetime the cache lifetime in minutes
	 */
	public function set_cache_lifetime($cache_lifetime)
	{
		$this->set_property(self::cache_lifetime, $cache_lifetime);
	}

	/**
	 * Returns the cache lifetime in minutes
	 * @return int the cache lifetime in minutes
	 */
	public function get_cache_max_uses()
	{
		return $this->get_property(self::cache_max_uses);
	}

	/**
	 * Sets the cache max uses
	 * @param int $cache_max_uses the cache max uses
	 */
	public function set_cache_max_uses($cache_max_uses)
	{
		$this->set_property(self::cache_max_uses, $cache_max_uses);
	}

	/**
	 * Returns the unauthorized search providers ids
	 * @return string[] The unauthorized search providers ids
	 */
	public function get_unauthorized_providers()
	{
		return $this->get_property(self::unauthorized_providers);
	}

	/**
	 * Sets the unauthorized search providers
	 * @param string[] $unauthorized_providers The unauthorized search providers ids
	 */
	public function set_unauthorized_providers(array $unauthorized_providers)
	{
		$this->set_property(self::unauthorized_providers, $unauthorized_providers);
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_default_values()
	{
		return array(
		self::weightings => array(),
		self::nb_results_per_page => 15,
		self::cache_lifetime => 30,
		self::cache_max_uses => 200,
		self::unauthorized_providers => array()
		);
	}

	/**
	 * Returns the configuration.
	 * @return SearchModuleConfig
	 */
	public static function load()
	{
		return ConfigManager::load(__CLASS__, 'search', 'config');
	}

	/**
	 * Saves the configuration in the database. Has it become persistent.
	 * @param SearchModuleConfig $config The configuration to push in the database.
	 */
	public static function save(SearchModuleConfig $config)
	{
		ConfigManager::save('search', $config, 'config');
	}
}
?>