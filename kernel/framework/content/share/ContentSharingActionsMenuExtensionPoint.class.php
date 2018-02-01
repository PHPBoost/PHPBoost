<?php
/*##################################################
 *		                        ContentSharingActionsMenuExtensionPoint.class.php
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
class ContentSharingActionsMenuExtensionPoint implements ExtensionPoint
{
	const EXTENSION_POINT = 'content_sharing_actions_menu';

	private $content_sharing_actions_menu

	public function __construct(ContentSharingActionsMenu $content_sharing_actions_menu)
	{
		$this->content_sharing_actions_menu = $content_sharing_actions_menu;
	}

	public function get_content_sharing_actions_menu()
	{
		return $this->content_sharing_actions_menu;
	}
}
?>