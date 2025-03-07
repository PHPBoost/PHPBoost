<?php
/**
 * @package     PHPBoost
 * @subpackage  Module
 * @copyright   &copy; 2005-2025 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2024 05 31
 * @since       PHPBoost 3.0 - 2009 12 12
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class Module
{
    private $module_id;
    private $activated;
	private $installed_version;

    // band-aid TODO in update script : remove 'authorizations' from db /configs/kernel-modules in serialized 'value'
    // remains of 4.0 version - Thx to SchyzoO
	private $authorizations;

	public function __construct($module_id, $activated = false)
	{
        $this->module_id = $module_id;
		$this->installed_version = $this->get_configuration()->get_version();
        $this->activated = $activated;
	}

    public function get_id()
    {
        return $this->module_id;
    }

    public function is_activated()
    {
        return $this->activated;
    }

	public function get_installed_version()
	{
		return $this->installed_version;
	}

    public function set_activated($activated)
    {
        $this->activated = $activated;
    }

	public function set_installed_version($installed_version)
    {
        $this->installed_version = $installed_version;
    }

    /**
     * @return ModuleConfiguration
     */
    public function get_configuration()
    {
        return ModuleConfigurationManager::get($this->module_id);
    }

    /**
     * Return values for serialize Module in DB
     * Force to remove autorizations from Module to remove them in kernel-modules table DB
     * @return array
     */
    public function __serialize()
    {
        return [
            'module_id' => $this->module_id,
            'activated' => $this->activated,
            'installed_version' => $this->installed_version
        ];
    }
}
?>
