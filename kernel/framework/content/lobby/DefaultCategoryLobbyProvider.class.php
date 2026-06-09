<?php
/**
 * Generic lobby category sub-view provider for any ItemsService-based module.
 *
 * Usage in ExtensionPointProvider::lobby():
 *   return [
 *       new DefaultItemsLobbyProvider('articles'),
 *       new DefaultCategoryLobbyProvider('articles'),
 *   ];
 *
 * The module_id for the category entry will be '{module_id}_category'.
 *
 * @package     Content
 * @subpackage  Lobby
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 05 19
 * @since       PHPBoost 6.1 - 2026 03 21
*/

class DefaultCategoryLobbyProvider extends DefaultModuleLobbyProvider
{
	private string $phpboost_module_id;

	public function __construct(string $phpboost_module_id)
	{
		$this->phpboost_module_id = $phpboost_module_id;
	}

	public function get_module_id(): string
	{
		return $this->phpboost_module_id . '_category';
	}

	public function get_module_name(): string
	{
		return parent::get_module_name() . ' ' . LangLoader::get_message('category.category', 'category-lang');
	}

	public function get_phpboost_module_id(): string
	{
		return $this->phpboost_module_id;
	}

	public function is_category_view(): bool
	{
		return true;
	}

	/**
	 * Default config fields: items limit and characters limit.
	 *
	 * @param  LobbyModule $module
	 * @return AbstractFormField[]
	 */
	public function get_config_fields(LobbyModule $module): array
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
				['class' => 'custom-checkbox', 'hidden' => $hidden]
			),
			new FormFieldNumberEditor(
				$module_id . '_limit',
				$lang['lobby.items.number'],
				$module->get_elements_number_displayed(),
				['min' => 1, 'max' => 50, 'required' => true, 'hidden' => $hidden]
			),
		];
	}

	/**
	 * Visibility for category-view fields.
	 * Call from get_fields_visibility() in category-view subclasses.
	 *
	 * @param  LobbyModule $module
	 * @return array<string, bool>
	 */
	public function get_fields_visibility(LobbyModule $module): array
	{
		$module_id = $this->get_module_id();
		$hidden    = !$module->is_displayed();

		return [
			$module_id . '_cat'                             => $hidden,
			$module_id . '_subcategories_content_displayed' => $hidden,
			$module_id . '_limit'                           => $hidden,
		];
	}

	/**
	 * Category-view save: persist category, subcategories toggle, limit, chars.
	 * Call from save() in category-view subclasses.
	 *
	 * @param HTMLForm    $form
	 * @param LobbyModule $module
	 */
	public function save(HTMLForm $form, LobbyModule $module): void
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
	}

	public function get_view(): FileTemplate
	{
		$phpboost_id   = $this->phpboost_module_id;
		$module_id     = $this->get_module_id();
		$module        = LobbyModulesList::load()[$module_id];
		$mod           = ModulesManager::get_module($phpboost_id);
		$module_config = $mod->get_configuration()->get_configuration_parameters();
		$now           = new Date();

		$view = $this->get_lobby_template('ItemsLobbyProvider.tpl');
		$view->add_lang(array_merge(
			LangLoader::get_all_langs('lobby'),
			LangLoader::get_module_langs($phpboost_id)
		));

		$categories = $module->is_subcategories_content_displayed()
			? CategoriesService::get_authorized_categories(
				$module->get_id_category(),
				$mod->get_configuration()->has_rich_config_parameters()
					? $module_config->get_summary_displayed_to_guests()
					: true,
				$phpboost_id
			)
			: [$module->get_id_category()];

		$sql_condition = 'WHERE id_category IN :categories
			AND (published = ' . Item::PUBLISHED . ' OR (published = ' . Item::DEFERRED_PUBLICATION . '
				AND publishing_start_date < :now
				AND (publishing_end_date > :now OR publishing_end_date = 0)))';

		$items = ItemsService::get_items_manager($phpboost_id)->get_items(
			$sql_condition,
			['categories' => $categories, 'now' => $now->get_timestamp()],
			$module->get_elements_number_displayed(), 0, 'creation_date', Item::DESC
		);

		$category = CategoriesService::get_categories_manager($phpboost_id)
			->get_categories_cache()
			->get_category($module->get_id_category());

		$view->put_all([
			'C_NO_ITEM'          => count($items) === 0,
			'C_CATEGORY'         => true,
			'C_VIEWS_NUMBER'     => $mod->get_configuration()->has_rich_config_parameters() && $module_config->get_views_number_enabled(),
			'C_LIST_VIEW'        => $mod->get_configuration()->has_rich_config_parameters() && $module_config->get_display_type() === DefaultRichModuleConfig::LIST_VIEW,
			'C_GRID_VIEW'        => $mod->get_configuration()->has_rich_config_parameters() && $module_config->get_display_type() === DefaultRichModuleConfig::GRID_VIEW,
			'C_TABLE_VIEW'       => $mod->get_configuration()->has_rich_config_parameters() && $module_config->get_display_type() === DefaultRichModuleConfig::TABLE_VIEW,
			'C_AUTHOR_DISPLAYED' => $mod->get_configuration()->has_rich_config_parameters() && $module_config->get_author_displayed(),
			'MODULE_NAME'        => $phpboost_id,
			'MODULE_POSITION'    => LobbyConfig::load()->get_module_position_by_id($module_id),
			'ITEMS_PER_ROW'      => $mod->get_configuration()->has_rich_config_parameters() ? $module_config->get_items_per_row() : 3,
			'L_MODULE_TITLE'     => $mod->get_configuration()->get_name(),
			'L_CATEGORY_NAME'    => $category->get_name(),
		]);

		foreach ($items as $item)
		{
			$view->assign_block_vars('items', $item->get_template_vars());
		}

		return $view;
	}
}
?>
