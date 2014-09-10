<?php
/*##################################################
 *                               DownloadModuleMiniMenu.class.php
 *                            -------------------
 *   begin                : August 24, 2014
 *   copyright            : (C) 2014 Julien BRISWALTER
 *   email                : julienseth78@phpboost.com
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
 * @author Julien BRISWALTER <julienseth78@phpboost.com>
 */

class DownloadModuleMiniMenu extends ModuleMiniMenu
{
	public function get_default_block()
	{
		return self::BLOCK_POSITION__RIGHT;
	}
	
	public function display($tpl = false)
	{
		if (DownloadAuthorizationsService::check_authorizations()->read())
		{
			//Load module lang
			$lang = LangLoader::get('common', 'download');
			
			//Create file template
			$tpl = new FileTemplate('download/DownloadModuleMiniMenu.tpl');
			
			//Assign the lang file to the tpl
			$tpl->add_lang($lang);
			
			//Assign menu default position
			MenuService::assign_positions_conditions($tpl, $this->get_block());
			
			//Load module config
			$config = DownloadConfig::load();
			
			//Load module cache
			$download_cache = DownloadCache::load();
			
			//Load categories cache
			$categories_cache = DownloadService::get_categories_manager()->get_categories_cache();
			
			$downloadfiles = $download_cache->get_downloadfiles();
			
			$tpl->put_all(array(
				'C_FILES' => !empty($downloadfiles),
				'C_NOTATION_ENABLED' => $config->is_notation_enabled(),
				'C_SORT_BY_DATE' => $config->is_sort_type_date(),
				'C_SORT_BY_NUMBER_DOWNLOADS' => $config->is_sort_type_number_downloads()
			));
			
			foreach ($downloadfiles as $file)
			{
				$downloadfile = new DownloadFile();
				$downloadfile->set_properties($file);
				
				$tpl->assign_block_vars('downloadfiles', $downloadfile->get_array_tpl_vars());
			}
			
			return $tpl->render();
		}
		return '';
	}
}
?>
