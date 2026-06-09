<?php
/**
 * @package     Content
 * @subpackage  Lobby
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 05 19
 * @since       PHPBoost 6.1 - 2026 03 21
*/

abstract class DefaultModuleLobbyProvider implements LobbyProvider
{
	/**
	 * Default: module_id equals phpboost_module_id.
	 * Override in category-view providers.
	 */
	public function get_phpboost_module_id(): string
	{
		return $this->get_module_id();
	}

	/**
	 * Default: module name in desc.ini.
	 * Override in category-view or specific-view providers.
	 */
	public function get_module_name(): string
	{
		return ModulesManager::get_module($this->get_phpboost_module_id())->get_configuration()->get_name();
	}

	/**
	 * Default: the module uses CategoriesService.
	 * Override to false for modules without categories (forum, guestbook, flux...).
	 */
	public function has_categories(): bool
	{
		return true;
	}

	/**
	 * Default: this is not a category sub-view.
	 * Override to true in NomModuleCategoryLobbyProvider classes.
	 */
	public function is_category_view(): bool
	{
		return false;
	}

	/**
	 * Default config fields: items limit and characters limit.
	 *
	 * @param  LobbyModule $module
	 * @return AbstractFormField[]
	 */
	public function get_config_fields(LobbyModule $module): array
	{
		$module_id = $this->get_module_id();
		$lang      = LangLoader::get_all_langs('lobby');
		$hidden    = !$module->is_displayed();

		return [
			new FormFieldNumberEditor(
				$module_id . '_limit',
				$lang['lobby.items.number'],
				$module->get_elements_number_displayed(),
				['min' => 1, 'max' => 50, 'required' => true, 'hidden' => $hidden]
			),
			new FormFieldNumberEditor(
				$module_id . '_char',
				$lang['lobby.chars.number'],
				$module->get_characters_number_displayed(),
				['min' => 0, 'max' => 1000, 'required' => true, 'hidden' => $hidden]
			),
		];
	}

	/**
	 * Category-view config fields: category selector, subcategories toggle, limit, chars.
	 * Call from get_config_fields() in category-view subclasses.
	 *
	 * @param  LobbyModule $module
	 * @return AbstractFormField[]
	 */
	protected function get_category_config_fields(LobbyModule $module): array
	{
		$module_id   = $this->get_module_id();
		$phpboost_id = $this->get_phpboost_module_id();
		$lang        = LangLoader::get_all_langs('lobby');
		$hidden      = !$module->is_displayed();

		$search_options = new SearchCategoryChildrensOptions();
		$search_options->add_authorizations_bits(Category::WRITE_AUTHORIZATIONS);

		return [
			CategoriesService::get_categories_manager($phpboost_id)->get_select_categories_form_field(
				$module_id . '_cat',
				$lang['lobby.category'],
				$module->get_id_category(),
				$search_options,
				['hidden' => $hidden, 'required' => true]
			),
			new FormFieldCheckbox(
				$module_id . '_subcategories_content_displayed',
				$lang['lobby.subcategories.content.displayed'],
				$module->is_subcategories_content_displayed(),
				['hidden' => $hidden]
			),
			new FormFieldNumberEditor(
				$module_id . '_limit',
				$lang['lobby.items.number'],
				$module->get_elements_number_displayed(),
				['min' => 1, 'max' => 50, 'required' => true, 'hidden' => $hidden]
			),
			new FormFieldNumberEditor(
				$module_id . '_char',
				$lang['lobby.chars.number'],
				$module->get_characters_number_displayed(),
				['min' => 0, 'max' => 1000, 'required' => true, 'hidden' => $hidden]
			),
		];
	}

	/**
	 * Default visibility: limit and char hidden when the module is not displayed.
	 *
	 * @param  LobbyModule $module
	 * @return array<string, bool>
	 */
	public function get_fields_visibility(LobbyModule $module): array
	{
		$module_id = $this->get_module_id();
		$hidden    = !$module->is_displayed();

		return [
			$module_id . '_limit' => $hidden,
			$module_id . '_char'  => $hidden,
		];
	}

	/**
	 * Visibility for category-view fields.
	 * Call from get_fields_visibility() in category-view subclasses.
	 *
	 * @param  LobbyModule $module
	 * @return array<string, bool>
	 */
	protected function get_category_fields_visibility(LobbyModule $module): array
	{
		$module_id = $this->get_module_id();
		$hidden    = !$module->is_displayed();

		return [
			$module_id . '_cat'                             => $hidden,
			$module_id . '_subcategories_content_displayed' => $hidden,
			$module_id . '_limit'                           => $hidden,
			$module_id . '_char'                            => $hidden,
		];
	}

	/**
	 * Default save: persist items limit and characters limit.
	 *
	 * @param HTMLForm    $form
	 * @param LobbyModule $module
	 */
	public function save(HTMLForm $form, LobbyModule $module): void
	{
		$module_id = $this->get_module_id();
		$module->set_elements_number_displayed(
			(int) $form->get_value($module_id . '_limit')
		);
		$module->set_characters_number_displayed(
			(int) $form->get_value($module_id . '_char')
		);
	}

	/**
	 * Category-view save: persist category, subcategories toggle, limit, chars.
	 * Call from save() in category-view subclasses.
	 *
	 * @param HTMLForm    $form
	 * @param LobbyModule $module
	 */
	protected function save_category_fields(HTMLForm $form, LobbyModule $module): void
	{
		$module_id = $this->get_module_id();

		// FormFieldCategoriesSelect returns the raw category id via get_raw_value()
		$id_category = (int) $form->get_value($module_id . '_cat')->get_raw_value();
		if ($id_category)
		{
			$module->set_id_category($id_category);
		}

		if ($form->get_value($module_id . '_subcategories_content_displayed'))
		{
			$module->display_subcategories_content();
		}
		else
		{
			$module->hide_subcategories_content();
		}

		$module->set_elements_number_displayed(
			(int) $form->get_value($module_id . '_limit')
		);
		$module->set_characters_number_displayed(
			(int) $form->get_value($module_id . '_char')
		);
	}

	/**
	 * Resolves the FileTemplate for this provider.
	 * Looks first in the active user theme, then in the module's own templates folder.
	 *
	 * @param  string $tpl_filename  e.g. 'DownloadLobbyProvider.tpl'
	 * @return FileTemplate
	 */
	protected function get_lobby_template(string $tpl_filename): FileTemplate
	{
		$phpboost_id = $this->get_phpboost_module_id();

		$module_path = ModulesManager::get_module_path($phpboost_id);
		if (file_exists($module_path . '/templates/' . $tpl_filename))
			return new FileTemplate($phpboost_id . '/' . $tpl_filename);
		elseif (file_exists(PATH_TO_ROOT . '/lobby/templates/pagecontent/' . $tpl_filename))
			return new FileTemplate('/lobby/templates/pagecontent/' . $tpl_filename);
		else
            return new FileTemplate('/lobby/templates/pagecontent/ItemsLobbyProvider.tpl');
	}
}
?>
