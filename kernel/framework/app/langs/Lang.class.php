<?php
/**
 * This class represents a lang
 * @package     PHPBoost
 * @subpackage  Langs
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 23
 * @since       PHPBoost 3.0 - 2012 01 19
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @author      Arnaud GENET <elenwii@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class Lang
{
    private string $id;
    private bool $activated;
    private array $authorizations;

    const ACCES_LANG = 1;

	public function __construct(string $id, array $authorizations = [], bool $activated = false)
	{
        $this->id = $id;
        $this->activated = $activated;
        $this->authorizations = $authorizations;
	}

    public function __unserialize(array $data): void
    {
        $id = $data['id'] ?? $data["\0Lang\0id"] ?? null;
        $authorizations = $data['authorizations'] ?? $data["\0Lang\0authorizations"] ?? [];
        if (!is_string($id) || !is_array($authorizations))
        {
            throw new UnexpectedValueException('Invalid serialized language configuration.');
        }

        $this->id = $id;
        $this->activated = (bool) ($data['activated'] ?? $data["\0Lang\0activated"] ?? false);
        $this->authorizations = $authorizations;
    }

    public function get_id(): string
    {
        return $this->id;
    }

    public function get_identifier(): string
    {
        return TextHelper::substr($this->id, 1, 2);
    }

    public function is_activated(): bool
    {
        return $this->activated;
    }

    public function set_activated(bool $activated)
    {
        $this->activated = $activated;
    }

    public function get_authorizations(): array
    {
        return $this->authorizations;
    }

    public function set_authorizations(array $authorizations): void
    {
        $this->authorizations = $authorizations;
    }

    public function get_configuration(): LangConfiguration
    {
        return LangConfigurationManager::get($this->id);
    }

    public function check_auth(): bool
    {
        if ($this->id == UserAccountsConfig::load()->get_default_lang())
        {
            return true;
        }
        return AppContext::get_current_user()->check_auth($this->authorizations, self::ACCES_LANG);
    }
}
?>
