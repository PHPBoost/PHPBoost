<?php
/*##################################################
 *                             Theme.class.php
 *                            -------------------
 *   begin                : April 10, 2011
 *   copyright            : (C) 2011 Kevin MASSY
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
 * @package {@package}
 */
class Theme
{
    private $theme_id;
    private $activated;
	private $columns_disabled;
	private $customize_interface;
    private $authorizations;
	const ACCES_THEME = 1;
	
	public function __construct($theme_id, array $authorizations = array(), $activated = false)
	{
        $this->theme_id = $theme_id;
        $this->activated = $activated;
        $this->authorizations = $authorizations;
        $this->customize_interface = new CustomizeInterface();
	}

    public function get_id()
    {
        return $this->theme_id;
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
	
	public function set_columns_disabled(ColumnsDisabled $columns_disabled)
	{
		$this->columns_disabled = $columns_disabled;
	}
	
	public function get_columns_disabled()
	{
		return $this->columns_disabled;
	}
	
	public function set_customize_interface(CustomizeInterface $customize_interface)
	{
		$this->customize_interface = $customize_interface;
	}
	
	public function get_customize_interface()
	{
		return $this->customize_interface;
	}

    public function set_authorizations($authorizations)
    {
        $this->authorizations = $authorizations;
    }

    /**
     * @return ThemeConfiguration
     */
    public function get_configuration()
    {
        return ThemeConfigurationManager::get($this->theme_id);
    }
    
    public function check_auth()
    {
    	if ($this->theme_id == UserAccountsConfig::load()->get_default_theme())
    	{
    		return true;
    	}
    	return AppContext::get_current_user()->check_auth($this->authorizations, self::ACCES_THEME);
    }
}
?>