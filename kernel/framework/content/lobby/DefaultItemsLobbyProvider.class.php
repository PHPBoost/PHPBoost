<?php
/**
 * Generic lobby provider for any ItemsService-based module (articles, news, recipe, etc.).
 *
 * Usage in ExtensionPointProvider::lobby():
 *   return [new DefaultItemsLobbyProvider('articles')];
 *
 * @package     Content
 * @subpackage  Lobby
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 05 19
 * @since       PHPBoost 6.1 - 2026 03 21
*/

class DefaultItemsLobbyProvider extends DefaultModuleLobbyProvider
{
	private string $module_id;

	public function __construct(string $module_id)
	{
		$this->module_id = $module_id;
	}

	public function get_module_id(): string
	{
		return $this->module_id;
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
	}

	public function get_view(): FileTemplate
	{
		$module_id     = $this->module_id;
		$module        = LobbyModulesList::load()[$module_id];
		$mod           = ModulesManager::get_module($module_id);
		$module_config = $mod->get_configuration()->get_configuration_parameters();
		$now           = new Date();

		$view = $this->get_lobby_template('ItemsLobbyProvider.tpl');
		$view->add_lang(array_merge(
			LangLoader::get_all_langs('lobby'),
			LangLoader::get_module_langs($module_id)
		));

		$categories = CategoriesService::get_authorized_categories(
			Category::ROOT_CATEGORY,
			$mod->get_configuration()->has_rich_config_parameters()
				? $module_config->get_summary_displayed_to_guests()
				: true,
			$module_id
		);

		$sql_condition = '
            WHERE id_category IN :categories
			AND (
                published = ' . Item::PUBLISHED . ' OR (
                    published = ' . Item::DEFERRED_PUBLICATION . '
                    AND publishing_start_date < :timestamp_now
                    AND (publishing_end_date > :timestamp_now OR publishing_end_date = 0)
                )
            )
        ';

		$items = ItemsService::get_items_manager($module_id)->get_items(
			$sql_condition,
			['categories' => $categories, 'timestamp_now' => $now->get_timestamp()],
			$module->get_elements_number_displayed(), 0, 'creation_date', Item::DESC
		);

		$view->put_all([
			'C_NO_ITEM'          => count($items) === 0,
			'C_VIEWS_NUMBER'     => $mod->get_configuration()->has_rich_config_parameters() && $module_config->get_views_number_enabled(),
			'C_LIST_VIEW'        => $mod->get_configuration()->has_rich_config_parameters() && $module_config->get_display_type() === DefaultRichModuleConfig::LIST_VIEW,
			'C_GRID_VIEW'        => $mod->get_configuration()->has_rich_config_parameters() && $module_config->get_display_type() === DefaultRichModuleConfig::GRID_VIEW,
			'C_TABLE_VIEW'       => $mod->get_configuration()->has_rich_config_parameters() && $module_config->get_display_type() === DefaultRichModuleConfig::TABLE_VIEW,
			'C_AUTHOR_DISPLAYED' => $mod->get_configuration()->has_rich_config_parameters() && $module_config->get_author_displayed(),
			'MODULE_NAME'        => $module_id,
			'MODULE_POSITION'    => LobbyConfig::load()->get_module_position_by_id($module_id),
			'ITEMS_PER_ROW'      => $mod->get_configuration()->has_rich_config_parameters() ? $module_config->get_items_per_row() : 3,
			'L_MODULE_TITLE'     => $mod->get_configuration()->get_name(),
		]);

		foreach ($items as $item)
		{
			$view->assign_block_vars('items', $item->get_template_vars());
		}

		return $view;
	}
}
?>
