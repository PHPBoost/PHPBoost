<?php
/*##################################################
 *                               WebModuleMiniMenu.class.php
 *                            -------------------
 *   begin                : August 21, 2014
 *   copyright            : (C) 2014 Julien BRISWALTER
 *   email                : j1.seth@phpboost.com
 *
 *
 ###################################################
 *
 * This program is a free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

 /**
 * @author Julien BRISWALTER <j1.seth@phpboost.com>
 */

class WebModuleMiniMenu extends ModuleMiniMenu
{
	public function get_default_block()
	{
		return self::BLOCK_POSITION__RIGHT;
	}
	
	public function get_menu_id()
	{
		return 'module-mini-web';
	}
	
	public function get_menu_title()
	{
		return LangLoader::get_message('partners', 'common', 'web');
	}
	
	public function is_displayed()
	{
		return WebAuthorizationsService::check_authorizations()->read();
	}
	
	public function get_menu_content()
	{
		//Create file template
		$tpl = new FileTemplate('web/WebModuleMiniMenu.tpl');
		
		//Assign the lang file to the tpl
		$tpl->add_lang(LangLoader::get('common', 'web'));
		
		//Load module cache
		$web_cache = WebCache::load();
		
		$partners_weblinks = $web_cache->get_partners_weblinks();
		
		$tpl->put('C_PARTNERS', !empty($partners_weblinks));
		
		foreach ($partners_weblinks as $partner)
		{
			$partner_picture = new Url($partner['partner_picture']);
			$picture = $partner_picture->rel();
			
			$tpl->assign_block_vars('partners', array(
				'C_HAS_PARTNER_PICTURE' => !empty($picture),
				'NAME' => $partner['name'],
				'U_PARTNER_PICTURE' => $picture,
				'U_VISIT' => WebUrlBuilder::visit($partner['id'])->rel()
			));
		}
		
		return $tpl->render();
	}
}
?>
