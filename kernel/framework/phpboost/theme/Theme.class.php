<?php
/*##################################################
 *                             Theme.class.php
 *                            -------------------
 *   begin                : April 10, 2011
 *   copyright            : (C) 2011 Kévin MASSY
 *   email                : soldier.weasel@gmail.com
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
 * @author Kévin MASSY <soldier.weasel@gmail.com>
 * @package {@package}
 */
class Theme
{
    private $theme_id;
    private $activated;
	private $columns_disabled;
    private $authorizations;
	public static const ACCES_THEME = 1;
	
	public function __construct($theme_id, $activated = false, array $authorizations = array())
	{
        $this->theme_id = $theme_id;
        $this->activated = $activated;
        $this->authorizations = $authorizations;
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
    	return AppContext::get_user()->check_auth($this->authorizations, self::ACCES_THEME);
    }
}
?>
