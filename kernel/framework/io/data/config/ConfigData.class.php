<?php
/**
 * This interface represents configuration data which are stored automatically by the
 * config manager. The storage mode is very powerful, it uses a two-level cache and the database.
 * <p>They are stored in a map associating a value to a property</p>
 * @package     IO
 * @subpackage  Data\config
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 04 06
 * @since       PHPBoost 3.0 - 2009 09 16
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

interface ConfigData extends CacheData
{
	/**
	 * Check if the config has the requested property.
	 * @param string $name Name of the property to read
	 * @return bool true if the property exists, false otherwise
	 */
	function has_property($name);

	/**
	 * Reads a property's value.
	 * @param string $name Name of the property to read
	 * @return string the read value
	 * @throws PropertyNotFoundException If the property if not found
	 */
	function get_property($name);

	/**
	 * Sets a property value. If the property exists, it overrides its value,
	 * otherwise, it creates an entry for this property.
	 * @param string $name Name of the property
	 * @param string $value Value of the property
	 */
	function set_property($name, $value);

	/**
	 * Sets the default value to avoid having unexisting values when we use it.
	 * If some entries doesn't exist, they can be created here.
	 */
	function set_default_values();

	/**
	 * Remove a property from the list of properties.
	 * Usefull to remove an old property when the config evolves between two versions.
	 * @param string $name Name of the property
	 */
	function delete_property($name);
}
?>
