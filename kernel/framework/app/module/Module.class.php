<?php
/**
 * @package     PHPBoost
 * @subpackage  Module
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 05 19
 * @since       PHPBoost 3.0 - 2009 12 12
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class Module
{
    private string $module_id;
    private bool $activated;
	private string $installed_version;

    // band-aid TODO in update script : remove 'authorizations' from db /configs/kernel-modules in serialized 'value'
    // remains of 4.0 version - Thx to SchyzoO
	private $authorizations;

	public function __construct(string $module_id, bool $activated = false)
	{
        $this->module_id = $module_id;
		$this->installed_version = $this->get_configuration()->get_version();
        $this->activated = $activated;
	}

    public function get_id(): string
    {
        return $this->module_id;
    }

    public function is_activated(): bool
    {
        return $this->activated;
    }

	public function get_installed_version(): string
	{
		return $this->installed_version;
	}

    public function set_activated(bool $activated): void
    {
        $this->activated = $activated;
    }

	public function set_installed_version(string $installed_version): void
    {
        $this->installed_version = $installed_version;
    }

    
    public function get_configuration(): ModuleConfiguration
    {
        return ModuleConfigurationManager::get($this->module_id);
    }

    /**
     * Return values for serialize Module in DB
     * Force to remove autorizations from Module to remove them in kernel-modules table DB
     */
    public function __serialize(): array
    {
        return [
            'module_id' => $this->module_id,
            'activated' => $this->activated,
            'installed_version' => $this->installed_version
        ];
    }
}
?>
