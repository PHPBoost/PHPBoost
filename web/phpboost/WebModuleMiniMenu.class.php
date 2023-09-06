<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2023 09 06
 * @since       PHPBoost 4.1 - 2014 08 21
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
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
		return LangLoader::get_message('web.partners', 'common', 'web');
	}

	public function get_formated_title()
	{
		return LangLoader::get_message('web.module.title', 'common', 'web');
	}

	public function is_displayed()
	{
		return CategoriesAuthorizationsService::check_authorizations(Category::ROOT_CATEGORY, 'web')->read();
	}

	public function get_menu_content()
	{
		//Create file template
		$view = new FileTemplate('web/WebModuleMiniMenu.tpl');

		//Assign the lang file to the template
		$view->add_lang(LangLoader::get_all_langs('web'));

		//Assign common menu variables to the template
		MenuService::assign_positions_conditions($view, $this->get_block());

		//Load module cache
		$web_cache = WebCache::load();

		$partners_items = $web_cache->get_partners_weblinks();

		$view->put('C_PARTNERS', !empty($partners_items));

		foreach ($partners_items as $partner)
		{
			$partner_thumbnail = new Url($partner['partner_thumbnail']);
			$thumbnail = $partner_thumbnail->rel();
            $category_rewrited_name = $partner['category_rewrited_name'] ?? 'root';

			$view->assign_block_vars('items', array(
				'C_HAS_PARTNER_THUMBNAIL' => !empty($thumbnail),
				'NAME'                    => $partner['title'],
				'U_PARTNER_THUMBNAIL'     => $thumbnail,
				'U_VISIT'                 => WebUrlBuilder::display($partner['id_category'], $category_rewrited_name, $partner['id'], $partner['rewrited_title'])->rel()
			));
		}

		return $view->render();
	}
}
?>
