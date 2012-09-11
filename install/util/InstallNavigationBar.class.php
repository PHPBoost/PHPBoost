<?php
/*##################################################
 *                          InstallNavigationBar.class.php
 *                            -------------------
 *   begin                : September 15, 2010
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
 * @author Loic Rouchon <loic.rouchon@phpboost.com>
 * @desc
 */
class InstallNavigationBar implements FormButton
{
    private $previous_step_url;

    public function set_previous_step_url($url)
    {
        $this->previous_step_url = $url;
    }
    
    public function display()
    {
    	$tpl = new FileTemplate('install/InstallNavigationBar.tpl');
    	$tpl->put_all(array(
            'HAS_PREVIOUS_STEP' => !empty($this->previous_step_url),
            'PREVIOUS_STEP_URL' => $this->previous_step_url
        ));
    	return $tpl;
    }
}
?>