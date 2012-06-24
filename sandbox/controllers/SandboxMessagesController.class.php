<?php
/*##################################################
 *                       SandboxMessagesController.class.php
 *                            -------------------
 *   begin                : May 05, 2012
 *   copyright            : (C) 2012 Kévin MASSY
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

class SandboxMessagesController extends ModuleController
{
	public function execute(HTTPRequest $request)
	{
		$view = new StringTemplate('# START messages # # INCLUDE messages.VIEW # <br/> # END messages #');
		
		$messages = array(
			MessageHelper::display('Ceci est un message de succès', MessageHelper::SUCCESS),
			MessageHelper::display('Ceci est un message d\'information', MessageHelper::NOTICE),
			MessageHelper::display('Ceci est un message d\'avertissement', MessageHelper::WARNING),
			MessageHelper::display('Ceci est un message d\'erreur', MessageHelper::ERROR)
		);
		
		foreach ($messages as $message)
		{
			$view->assign_block_vars('messages', array('VIEW' => $message));
		}
		
		return new SiteDisplayResponse($view);
	}
}
?>