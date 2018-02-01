<?php
/*##################################################
 *		                        ContentSharingActionsMenuLink.class.php
 *                            -------------------
 *   begin                : January 30, 2018
 *   copyright            : (C) 2018 Kévin MASSY
 *   email                : kevin.massy@phpboost.com
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
 * @author Kevin MASSY <kevin.massy@phpboost.com>
 */
class ContentSharingActionsMenuLink
{
	private $name;
	private $url;
	private $tpl;

	public function __construct($name, Url $url, Template $tpl = null)
	{
		$this->name = $name;
		$this->url = $url;

		if ($this->tpl instanceof Template)
		{
			$this->tpl = $tpl;
		}
		else
		{
			$this->tpl = new FileTemplate('framework/content/share/ContentSharingActionsMenuLink.tpl');
		}
	}

	public function set_name($name)
	{
		$this->name = $name;
	}
	
	public function get_name()
	{
		return $this->name;
	}

	public function set_url($url)
	{
		if (!($url instanceof Url))
        {
            $url = new Url($url);
        }
        $this->url = $url;
	}
	
	public function get_url()
	{
		return $this->url;
	}

	public function export()
	{
		$this->tpl->put_all(array(
			'NAME'           => $this->get_name(),
			'U_LINK'         => $this->get_url()->rel(),
		));

		return $this->tpl;
	}
}
?>