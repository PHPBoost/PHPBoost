<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 02 05
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
		global $LANG;
		load_module_lang('search');
		return $LANG['title_search'];
	}

	public function is_displayed()
	{
		return SearchAuthorizationsService::check_authorizations()->read();
	}

	public function get_menu_content()
	{
		global $LANG;
		load_module_lang('search');

		$search = retrieve(REQUEST, 'q', '');

		$tpl = new FileTemplate('search/search_mini.tpl');
		MenuService::assign_positions_conditions($tpl, $this->get_block());
		Menu::assign_common_template_variables($tpl);

		$tpl->put_all(Array(
			'TEXT_SEARCHED'                => !empty($search) ? stripslashes($search) : '',
			'WARNING_LENGTH_STRING_SEARCH' => addslashes($LANG['warning_length_string_searched']),
			'L_SEARCH_TITLE'               => $LANG['title_search'],
			'L_SEARCH'                     => $LANG['search'],
			'L_YOUR_SEARCH'                => $LANG['your_search'],
			'U_FORM_VALID'                 => url(TPL_PATH_TO_ROOT . '/search/search.php#results'),
			'L_ADVANCED_SEARCH'            => $LANG['advanced_search'],
			'U_ADVANCED_SEARCH'            => url(TPL_PATH_TO_ROOT . '/search/search.php')
		));

		return $tpl->render();
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
