<?php
/*##################################################
 *		                    SearchConfig.class.php
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
 * @desc This class represents the sitemap module's configuration.
 * @author Benoit Sautel <ben.popeye@phpboost.com>
 */
class SearchConfig extends AbstractConfigData
{
    const weightings = 'weightings';
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
            self::unauthorized_providers => array()
		);
	}

	/**
	 * Returns the configuration.
	 * @return SearchConfig
	 */
	public static function load()
	{
		return ConfigManager::load(__CLASS__, 'search', 'config');
	}

	/**
	 * Saves the configuration in the database. Has it become persistent.
	 * @param SearchConfig $config The configuration to push in the database.
	 */
	public static function save(SearchConfig $config)
	{
		ConfigManager::save('search', $config, 'config');
	}
}
?>