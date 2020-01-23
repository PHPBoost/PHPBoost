<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2020 01 23
 * @since       PHPBoost 4.0 - 2014 08 24
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class DownloadModuleMiniMenu extends ModuleMiniMenu
{
	public function get_default_block()
	{
		return self::BLOCK_POSITION__RIGHT;
	}

	public function get_menu_id()
	{
		return 'module-mini-download';
	}

	public function get_menu_title()
	{
		return DownloadConfig::load()->is_sort_type_date() ? LangLoader::get_message('last.download.files', 'common', 'download') : LangLoader::get_message('most.downloaded.files', 'common', 'download');
	}

	public function is_displayed()
	{
		return DownloadAuthorizationsService::check_authorizations()->read();
	}

	public function get_menu_content()
	{
		//Create file template
		$tpl = new FileTemplate('download/DownloadModuleMiniMenu.tpl');

		//Assign the lang file to the tpl
		$tpl->add_lang(LangLoader::get('common', 'download'));

		//Assign common menu variables to the tpl
		MenuService::assign_positions_conditions($tpl, $this->get_block());

		//Load module config
		$config = DownloadConfig::load();

		//Load module cache
		$download_cache = DownloadCache::load();

		//Load categories cache
		$categories_cache = CategoriesService::get_categories_manager('download')->get_categories_cache();

		$downloadfiles = $download_cache->get_downloadfiles();

		$tpl->put_all(array(
			'C_FILES' => !empty($downloadfiles),
			'C_SORT_BY_DATE' => $config->is_sort_type_date(),
			'C_SORT_BY_NOTATION' => $config->is_sort_type_notation(),
			'C_SORT_BY_DOWNLOADS_NUMBER' => $config->is_sort_type_downloads_number(),
			'C_SORT_BY_VIEWS_NUMBERS' => $config->is_sort_type_views_numbers()
		));

		$displayed_position = 1;
		foreach ($downloadfiles as $file)
		{
			$downloadfile = new DownloadFile();
			$downloadfile->set_properties($file);

			$tpl->assign_block_vars('downloadfiles', array_merge($downloadfile->get_array_tpl_vars(), array(
				'DISPLAYED_POSITION' => $displayed_position
			)));

			$displayed_position++;
		}

		return $tpl->render();
	}
}
?>
