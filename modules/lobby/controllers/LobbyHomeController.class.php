<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 05 19
 * @since       PHPBoost 6.1 - 2026 03 21
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
*/

class LobbyHomeController extends DefaultModuleController
{
	/** @var array<string, LobbyModule> */
	private array $modules;

	public function __construct(string $module_id = '')
	{
		self::$module_id = 'lobby';
		$this->request = AppContext::get_request();
		$this->config  = LobbyConfig::load();
		$this->lang    = LangLoader::get_all_langs('lobby');
		$this->view    = new FileTemplate('lobby/LobbyHomeController.tpl');
		$this->view->add_lang($this->lang);
	}

	public function execute(HTTPRequestCustom $request): Response
	{
		$this->modules = LobbyModulesList::load();

		$columns = ThemesManager::get_theme(AppContext::get_current_user()->get_theme())->get_columns_disabled();
		$columns->set_disable_left_columns($this->config->get_left_columns());
		$columns->set_disable_right_columns($this->config->get_right_columns());
		$columns->set_disable_top_central($this->config->get_top_central());
		$columns->set_disable_bottom_central($this->config->get_bottom_central());
		$columns->set_disable_top_footer($this->config->get_top_footer());

		$this->build_view();

		return $this->generate_response();
	}

	private function build_view(): void
	{
		$this->view->put_all([
			'MODULE_TITLE'              => $this->config->get_module_title(),
			'C_EDITO_ENABLED'           => isset($this->modules[LobbyConfig::MODULE_EDITO]) && $this->modules[LobbyConfig::MODULE_EDITO]->is_displayed(),
			'EDITO'                     => FormatingHelper::second_parse($this->config->get_edito()),
			'EDITO_POSITION'            => $this->config->get_module_position_by_id(LobbyConfig::MODULE_EDITO),
			'C_HAS_ALL_VERTICAL_MENUS'  => !$this->config->get_left_columns() && !$this->config->get_right_columns(),
			'C_HAS_SOME_VERTICAL_MENUS' => !$this->config->get_left_columns() || !$this->config->get_right_columns(),
		]);

		// Built-in: anchors menu
		if (isset($this->modules[LobbyConfig::MODULE_ANCHORS_MENU]) && $this->modules[LobbyConfig::MODULE_ANCHORS_MENU]->is_displayed())
		{
			$this->view->put('ANCHORS_MENU', LobbyAnchorsMenu::get_anchors_menu_view());
		}

		// Built-in: carousel
		if (isset($this->modules[LobbyConfig::MODULE_CAROUSEL]) && $this->modules[LobbyConfig::MODULE_CAROUSEL]->is_displayed())
		{
			$this->view->put('CAROUSEL', LobbyCarousel::get_carousel_view());
		}

		// Built-in: lastcoms (kernel-level comments, no LobbyProvider)
		if (isset($this->modules[LobbyConfig::MODULE_LASTCOMS]) && $this->modules[LobbyConfig::MODULE_LASTCOMS]->is_displayed())
		{
			$this->view->put('LASTCOMS', LobbyLastcoms::get_lastcoms_view());
		}

		// Dynamic modules via LobbyProvider — rendered in config order
		$providers = LobbyService::get_all_lobby_providers();

		foreach ($providers as $module_id => $lobby_provider)
		{
			$module_id   = $lobby_provider->get_module_id();
			$phpboost_id = $lobby_provider->get_phpboost_module_id();

			if (!isset($this->modules[$module_id]) || !$this->modules[$module_id]->is_displayed())
			{
				continue;
			}

			if (!ModulesManager::is_module_installed($phpboost_id) || !ModulesManager::is_module_activated($phpboost_id))
			{
				continue;
			}

			// Authorization check
			if ($lobby_provider->has_categories())
			{
				$id_cat = $this->modules[$module_id]->has_category()
					? $this->modules[$module_id]->get_id_category()
					: Category::ROOT_CATEGORY;

				if (!CategoriesAuthorizationsService::check_authorizations($id_cat, $phpboost_id)->read())
				{
					continue;
				}
			}

			$this->view->assign_block_vars(
				'modules',
				[],
				['MODULE_CONTENT' => $lobby_provider->get_view()]
			);
		}
	}

	private function generate_response(): Response
	{
		$response              = new SiteDisplayResponse($this->view);
		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($this->config->get_module_title());
		$graphical_environment->get_seo_meta_data()->set_description(GeneralConfig::load()->get_site_description());
		$graphical_environment->get_seo_meta_data()->set_canonical_url(LobbyUrlBuilder::home());
		$graphical_environment->get_seo_meta_data()->set_picture_url(new Url(
			PATH_TO_ROOT . '/templates/' . AppContext::get_current_user()->get_theme() . '/images/default_item.webp'
		));

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->config->get_module_title(), LobbyUrlBuilder::home());

		return $response;
	}

	public static function get_view(): FileTemplate
	{
		$object = new self('lobby');
		$object->modules = LobbyModulesList::load();
		$object->build_view();
		return $object->view;
	}
}
?>
