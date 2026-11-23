<?php
/**
 * @package     PHPBoost
 * @subpackage  Theme
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 23
 * @since       PHPBoost 3.0 - 2011 04 10
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class Theme
{
    private string $theme_id;
    private bool $activated;
	private ColumnsDisabled $columns_disabled;
	private CustomizeInterface $customize_interface;
    private array $authorizations;
	const ACCES_THEME = 1;

	public function __construct(string $theme_id, array $authorizations = [], $activated = false)
	{
        $this->theme_id = $theme_id;
        $this->activated = $activated;
        $this->authorizations = $authorizations;
        $this->customize_interface = new CustomizeInterface();
	}

    public function get_id(): string
    {
        return $this->theme_id;
    }

    public function is_activated(): bool
    {
        return $this->activated;
    }

    public function get_authorizations(): array
    {
        return $this->authorizations;
    }

    public function set_activated(bool $activated): void
    {
        $this->activated = $activated;
    }

	public function set_columns_disabled(ColumnsDisabled $columns_disabled): void
	{
		$this->columns_disabled = $columns_disabled;
	}

	public function get_columns_disabled(): ColumnsDisabled
	{
		return $this->columns_disabled;
	}

	public function set_customize_interface(CustomizeInterface $customize_interface): void
	{
		$this->customize_interface = $customize_interface;
	}

	public function get_customize_interface(): CustomizeInterface
	{
		return $this->customize_interface;
	}

    public function set_authorizations(array $authorizations): void
    {
        $this->authorizations = $authorizations;
    }

    /**
     * @return ThemeConfiguration
     */
    public function get_configuration(): ThemeConfiguration
    {
        return ThemeConfigurationManager::get($this->theme_id);
    }

    public function check_auth(): bool
    {
        if ($this->theme_id == UserAccountsConfig::load()->get_default_theme())
        {
            return true;
        }
        return AppContext::get_current_user()->check_auth($this->authorizations, self::ACCES_THEME);
    }
}
?>
