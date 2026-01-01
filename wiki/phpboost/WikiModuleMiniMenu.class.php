<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2025 04 13
 * @since       PHPBoost 6.0 - 2023 01 21
*/

class WikiModuleMiniMenu extends ModuleMiniMenu
{
    public function get_default_block()
    {
        return self::BLOCK_POSITION__RIGHT;
    }

    public function admin_display()
    {
        return '';
    }

    public function get_menu_id()
    {
        return 'module-mini-wiki';
    }

    public function get_menu_title()
    {
        return WikiConfig::load()->get_menu_name();
    }

    public function is_displayed()
    {
        return ModulesManager::is_module_installed('wiki') && ModulesManager::is_module_activated('wiki');
    }

    public function get_menu_content()
    {
        $view = new FileTemplate('wiki/WikiModuleMiniMenu.tpl');
        $view->add_lang(LangLoader::get_all_langs('wiki'));
        MenuService::assign_positions_conditions($view, $this->get_block());
        Menu::assign_common_template_variables($view);

        $categories = CategoriesService::get_categories_manager('wiki')->get_categories_cache()->get_categories();
        $authorized_categories = CategoriesService::get_authorized_categories(Category::ROOT_CATEGORY, true, 'wiki');

        $cache = WikiCache::load();
        foreach ($categories as $id => $category)
        {
            if ($id == Category::ROOT_CATEGORY)
            {
                $view->put_all([
                    'C_ROOT_ITEMS' => $category->get_elements_number() > 0,
                ]);

                foreach($cache->get_items() as $item)
                {
                    if ($item['id_category'] == $id)
                    $view->assign_block_vars('root_items', [
                        'TITLE' => $item['title'],
                        'U_ITEM' => WikiUrlBuilder::display($category->get_id(), $category->get_rewrited_name(), $item['id'], $item['rewrited_title'])->rel(),
                    ]);
                }
            }

            if ($id != Category::ROOT_CATEGORY && in_array($id, $authorized_categories))
            {
                $view->assign_block_vars('categories', [
                    'C_ITEMS'		  => $category->get_elements_number() > 0,
                    'C_SEVERAL_ITEMS' => $category->get_elements_number() > 1,

                    'CATEGORY_ID'        => $category->get_id(),
                    'CATEGORY_SUB_ORDER' => $category->get_order(),
                    'CATEGORY_PARENT_ID' => $category->get_id_parent(),
                    'CATEGORY_NAME'      => $category->get_name(),
                    'U_CATEGORY'         => WikiUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name())->rel()
                ]);

                foreach($cache->get_items() as $item)
                {
                    if ($item['id_category'] == $id)
                    $view->assign_block_vars('categories.items', [
                        'TITLE' => $item['title'],

                        'U_ITEM'   => WikiUrlBuilder::display($category->get_id(), $category->get_rewrited_name(), $item['id'], $item['rewrited_title'])->rel(),
                    ]);
                }
            }
        }
        return $view->render();
    }
}
?>
