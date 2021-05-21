<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 05 21
 * @since       PHPBoost 3.0 - 2011 10 08
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class SearchModuleMiniMenu extends ModuleMiniMenu
{
	public function get_default_block()
	{
		return self::BLOCK_POSITION__HEADER;
	}

	public function admin_display()
	{
		return '';
	}

	public function get_menu_id()
	{
		return 'module-mini-search-form';
	}

	public function get_menu_title()
	{
		return LangLoader::get_message('search.module.title', 'common', 'search');
	}

	public function is_displayed()
	{
		return SearchAuthorizationsService::check_authorizations()->read();
	}

	public function get_menu_content()
	{
		global $LANG;
		load_module_lang('search');
		$lang = LangLoader::get('common','search');
		$form_lang = LangLoader::get('form-lang');

		$search = retrieve(REQUEST, 'q', '');

		$view = new FileTemplate('search/search_mini.tpl');
		$view->add_lang(array_merge($lang, $form_lang));

		MenuService::assign_positions_conditions($view, $this->get_block());
		Menu::assign_common_template_variables($view);

		$view->put_all(Array(
			'SEARCH_TEXT' => !empty($search) ? stripslashes($search) : '',

			'U_FORM_VALID'      => url(TPL_PATH_TO_ROOT . '/search/search.php#results'),
			'U_ADVANCED_SEARCH' => url(TPL_PATH_TO_ROOT . '/search/search.php'),

			'L_SEARCH_LENGTH' => addslashes($lang['search.warning.length']),
			//
			'L_SEARCH_TITLE'    => $LANG['title_search'],
			'L_ADVANCED_SEARCH' => $LANG['advanced_search'],
			'L_SEARCH'          => $LANG['search'],
			'L_YOUR_SEARCH'     => $LANG['your_search'],
		));

		return $view->render();
	}

	public function display()
	{
		if ($this->is_displayed())
		{
			if ($this->get_block() == Menu::BLOCK_POSITION__LEFT || $this->get_block() == Menu::BLOCK_POSITION__RIGHT)
			{
				$template = $this->get_template_to_use();
				MenuService::assign_positions_conditions($template, $this->get_block());
				$this->assign_common_template_variables($template);

				$template->put_all(array(
					'ID' => $this->get_menu_id(),
					'TITLE' => $this->get_menu_title(),
					'CONTENTS' => $this->get_menu_content()
				));

				return $template->render();
			}
			else
			{
				return $this->get_menu_content();
			}
		}
		return '';
	}
}
?>
