<?php
/*##################################################
 *                      MemberConfirmRegisterationController.class.php
 *                            -------------------
 *   begin                : September 18, 2010 2009
 *   copyright            : (C) 2010 Kévin MASSY
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

class MemberConfirmRegisterationController extends AbstractController
{
	public function execute(HTTPRequest $request)
	{
		$view = new StringTemplate('# INCLUDE form #');
		$key = $request->get_getint('key', 0);
		
		if(AppContext::get_user()->check_level(MEMBER_LEVEL))
		{
			// TODO redirect error already registered
		}
		else
		{
			$form = $this->check_activation($key);
		}
		
		
		$view->put('form', $form->display());
		return new SiteDisplayResponse($view);
	}
	
	private function check_activation($key)
	{
		$check_mbr = ConfirmHelper::check_activation_pass_exist($key);
		if ($check_mbr && !empty($key))
		{
			ConfirmHelper::update_aprobation($key);
			
			// TODO redirect activation success
		}
		else
		{
			//TODO redirect error key isn't exist
		}
	}	
}

?>