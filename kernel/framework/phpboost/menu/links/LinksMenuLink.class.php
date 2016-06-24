<?php
/*##################################################
 *                           LinksMenuLink.class.php
 *                            -------------------
 *   begin                : July 08, 2008
 *   copyright            : (C) 2008 Régis Viarre; Loic Rouchon
 *   email                : crowkait@phpboost.com; loic.rouchon@phpboost.com
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
 * @desc A Simple menu link
 * @package {@package}
 */
class LinksMenuLink extends LinksMenuElement
{
	const LINKS_MENU_LINK__CLASS = 'LinksMenuLink';

	/**
	* @desc Constructor
	* @param string $title Menu title
	* @param string $url Destination url
	* @param string $image Menu's image url relative to the website root or absolute
	* @param int $id The Menu's id in the database
	*/
	public function __construct($title, $url, $image = '')
	{
		parent::__construct($title, $url, $image);
	}

	/**
	 * @desc Display the menu
	 * @param Template $template the template to use
	 * @return string the menu parsed in xHTML
	 */
	public function display($template = false, $mode = LinksMenuElement::LINKS_MENU_ELEMENT__CLASSIC_DISPLAYING)
	{
		// Stop if the user isn't authorised
		if (!$this->check_auth())
		{
			return '';
		}
		
		parent::_assign($template, $mode);
		$template->put_all(array(
			'C_DISPLAY_AUTH' => AppContext::get_current_user()->check_auth($this->get_auth(), Menu::MENU_AUTH_BIT),
			'C_LINK' => true
		));

		return $template->render();
	}
}
?>