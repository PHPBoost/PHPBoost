<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 10 24
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
		return DownloadConfig::load()->is_sort_type_date() ? LangLoader::get_message('download.last.items', 'common', 'download') : LangLoader::get_message('download.most.downloaded', 'common', 'download');
	}

	public function get_formated_title()
	{
		return LangLoader::get_message('download.module.title', 'common', 'download');
	}

	public function is_displayed()
	{
		return DownloadAuthorizationsService::check_authorizations()->read();
	}

	public function get_menu_content()
	{
		// Create file template
		$view = new FileTemplate('download/DownloadModuleMiniMenu.tpl');

		// Assign the lang file to the tpl
		$view->add_lang(LangLoader::get_all_langs('download'));

		// Assign common menu variables to the tpl
		MenuService::assign_positions_conditions($view, $this->get_block());

		// Load module config
		$config = DownloadConfig::load();

		// Load module cache
		$download_cache = DownloadCache::load();

		// Load categories cache
		$categories_cache = CategoriesService::get_categories_manager('download')->get_categories_cache();

		$items = $download_cache->get_items();

		$view->put_all(array(
			'C_ITEMS'                    => !empty($items),
			'C_SORT_BY_DATE'             => $config->is_sort_type_date(),
			'C_SORT_BY_NOTATION'         => $config->is_sort_type_notation(),
			'C_SORT_BY_DOWNLOADS_NUMBER' => $config->is_sort_type_downloads_number(),
			'C_SORT_BY_VIEWS_NUMBERS'    => $config->is_sort_type_views_numbers()
		));

		$displayed_position = 1;
		foreach ($items as $file)
		{
			$item = new DownloadItem();
			$item->set_properties($file);

			$view->assign_block_vars('items', array_merge($item->get_template_vars(), array(
				'DISPLAYED_POSITION' => $displayed_position
			)));

			$displayed_position++;
		}

		return $view->render();
	}
}
?>
