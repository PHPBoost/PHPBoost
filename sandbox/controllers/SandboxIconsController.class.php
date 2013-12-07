<?php
/*##################################################
 *                       SandboxIconsController.class.php
 *                            -------------------
 *   begin                : May 05, 2012
 *   copyright            : (C) 2012 Kevin MASSY
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

class SandboxIconsController extends ModuleController
{
	public function execute(HTTPRequestCustom $request)
	{
		$view = new FileTemplate('sandbox/SandboxIconsController.tpl');
		
		$icons = array(
			'icon-arrow-right',
			'icon-arrow-left',
			'icon-arrow-up',
			'icon-arrow-down',
			'icon-arrows',
			'icon-ban',
			'icon-bars',
			'icon-book',
			'icon-calendar',
			'icon-caret-down',
			'icon-caret-left',
			'icon-caret-right',
			'icon-check',
			'icon-clock-o',
			'icon-cog',
			'icon-comment',
			'icon-comments-o',
			'icon-delete',
			'icon-download',
			'icon-edit',
			'icon-edit-sign',
			'icon-envelope',
			'icon-envelope-o',
			'icon-eraser',
			'icon-eye',
			'icon-eye-slash',
			'icon-facebook',
			'icon-female',
			'icon-file',
			'icon-filter',
			'icon-flag',
			'icon-folder',
			'icon-folder-open',
			'icon-forbidden',
			'icon-google-plus',
			'icon-male',
			'icon-minus',
			'icon-minus-square-o',
			'icon-move',
			'icon-offline',
			'icon-online',
			'icon-pencil',
			'icon-plus',
			'icon-plus-square-o',
			'icon-print',
			'icon-refresh',
			'icon-remove',
			'icon-search',
			'icon-skype',
			'icon-sort',
			'icon-sort-alpha-asc',
			'icon-spinner',
			'icon-star',
			'icon-star-half-empty',
			'icon-star-o',
			'icon-syndication',
			'icon-tag',
			'icon-tags',
			'icon-twitter',
			'icon-unban',
			'icon-unlink',
			'icon-user'
		);
		
		foreach ($icons as $icon)
		{
			$view->assign_block_vars('icons', array('CLASS' => $icon));
		}
		return new SiteDisplayResponse($view);
	}
}
?>