<?php
/**
 * This class represents a lang
 * @package     PHPBoost
 * @subpackage  Langs
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2016 10 28
 * @since       PHPBoost 3.0 - 2012 01 19
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

class Lang
{
    private $id;
    private $activated;
    private $authorizations;

    const ACCES_LANG = 1;

	public function __construct($id, array $authorizations = array(), $activated = false)
	{
        $this->id = $id;
        $this->activated = $activated;
        $this->authorizations = $authorizations;
	}

    public function get_id()
    {
        return $this->id;
    }

    public function get_identifier()
    {
        return TextHelper::substr($this->id, 1, 2);
    }

    public function is_activated()
    {
        return $this->activated;
    }

    public function get_authorizations()
    {
        return $this->authorizations;
    }

    public function set_activated($activated)
    {
        $this->activated = $activated;
    }

    public function set_authorizations($authorizations)
    {
        $this->authorizations = $authorizations;
    }

    /**
     * @return LangConfiguration
     */
    public function get_configuration()
    {
        return LangConfigurationManager::get($this->id);
    }

    public function check_auth()
    {
        if ($this->id == UserAccountsConfig::load()->get_default_lang())
        {
            return true;
        }
        return AppContext::get_current_user()->check_auth($this->authorizations, self::ACCES_LANG);
    }
}
?>
