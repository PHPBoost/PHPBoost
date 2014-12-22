<?php
/*##################################################
 *                             Lang.class.php
 *                            -------------------
 *   begin                : January 19, 2012
 *   copyright            : (C) 2012 Kevin MASSY
 *   email                : kevin.massy@phpboost.com
 *
 *
 *###################################################
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
 *###################################################
 */

 /**
 * @author Kevin MASSY <kevin.massy@phpboost.com>
 * @desc This class represente a lang
 * @package {@package}
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
    	return substr($this->id, 1, 2);
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