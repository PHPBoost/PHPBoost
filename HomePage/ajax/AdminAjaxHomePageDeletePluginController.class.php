<?php
/*##################################################
 *                           AdminAjaxHomePageDeletePluginController.class.php
 *                            -------------------
 *   begin                : March 18, 2012
 *   copyright            : (C) 2012 Kevin MASSY
 *   email                : soldier.weasel@gmail.com
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

class AdminAjaxHomePageDeletePluginController extends AdminModuleController
{
	public function execute(HTTPRequest $request)
	{
		$lang = LangLoader::get('common', 'HomePage');
		$plugin_id = $request->get_postint('id', 0);
		try {
			HomePagePluginsService::delete('WHERE id=:id', array('id' => $plugin_id));
			$object = array(
				'success' => true,
				'message' => $comments_lang['success']
			);
		} catch (Exception $e) {
			$object = array(
				'success' => false,
				'message' => $comments_lang['error']
			);
		}
		
		return new JSONResponse($object);
	}
}
?>