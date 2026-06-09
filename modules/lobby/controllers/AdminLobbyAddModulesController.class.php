<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 05 19
 * @since       PHPBoost 6.1 - 2026 03 21
*/

class AdminLobbyAddModulesController extends DefaultAdminModuleController
{
    public function execute(HTTPRequestCustom $request): Response
    {
        $this->lang   = LangLoader::get_all_langs('lobby');
        $this->config = LobbyConfig::load();

        $this->build_form();

        $this->view->put('CONTENT', $this->form->display());

        return new AdminLobbyDisplayResponse($this->view, $this->lang['lobby.add.modules']);
    }

    private function build_form(): void
    {
        $form     = new HTMLForm(self::class);
        $fieldset = new FormFieldsetHTML('has_new_module', $this->lang['lobby.add.modules']);
        $form->add_fieldset($fieldset);

        $feature_modules = ModulesManager::get_activated_feature_modules('lobby');
        $config_ids      = array_column($this->config->get_modules(), 'module_id');

        $complete_new   = [];
        $incomplete_new = [];

        foreach ($feature_modules as $module)
        {
            $module_id = $module->get_id();

            if (in_array($module_id, $config_ids))
            {
                continue;
            }

            $missing = $this->get_missing_files($module_id);

            if (empty($missing))
            {
                $complete_new[] = $module_id;
            }
            else
            {
                $incomplete_new[$module_id] = $missing;
            }
        }

        if (!empty($complete_new))
        {
            $names = array_map(
                fn($id) => ModulesManager::get_module($id)->get_configuration()->get_name(),
                $complete_new
            );

            $fieldset->add_field(new FormFieldFree('new_modules', $this->lang['lobby.new.modules'], StringVars::replace_vars($this->lang['lobby.add.modules.warning'], ['modules_list' => implode(', ', $names)]),
                ['class' => 'full-field']
            ));

            $this->submit_button = new FormButtonDefaultSubmit();
            $form->add_button($this->submit_button);

            if ($this->submit_button->has_been_submited())
            {
                $this->add_complete_modules($complete_new);
            }
        }

        foreach ($incomplete_new as $module_id => $missing_files)
        {
            $module_name = ModulesManager::get_module($module_id)->get_configuration()->get_name();

            $fieldset->add_field(new FormFieldFree('incomplete_' . $module_id, $module_name,
                StringVars::replace_vars($this->lang['lobby.incomplete.module.warning'], [
                    'module_name'   => $module_name,
                    'missing_files' => implode(', ', $missing_files),
                ]),
                ['class' => 'full-field warning bgc message-helper']
            ));
        }

        if (empty($complete_new) && empty($incomplete_new))
        {
            $empty_fieldset = new FormFieldsetHTML('no_new_module', '');
            $form->add_fieldset($empty_fieldset);

            $empty_fieldset->add_field(new FormFieldHTML('no_modules', $this->lang['lobby.no.new.module'],
                ['class' => 'full-field success bgc message-helper']
            ));

            $empty_fieldset->add_field(new FormFieldHTML('back_config', $this->lang['lobby.back.to.configuration'],
                ['class' => 'full-field']
            ));

            $this->submit_button = '';
        }

        $this->form = $form;
    }

    /**
     * A module is complete for lobby when:
     *   - it declares the 'lobby' feature in config.ini
     *   - ModuleNameLobbyProvider.class.php exists in phpboost/ if declared in ModuleNameExtensionPointProvider
     *
     * @return string[]
     */
    private function get_missing_files(string $module_id): array
    {
        $missing = [];
        $ucfirst = TextHelper::ucfirst($module_id);
        $base    = $this->get_module_path($module_id);

        $provider_class = $ucfirst . 'ExtensionPointProvider';
        $file = new File(ModulesManager::get_module_path($module_id) . '/phpboost/' . $provider_class . '.class.php');
        if ($file->exists())
            $provider = new $provider_class();
        else
            $provider = new ItemsModuleExtensionPointProvider();

        $lobbyProviders = $provider->lobby();

        foreach ($lobbyProviders as $lobbyProvider)
        {
            if (get_class($lobbyProvider) === $ucfirst . 'LobbyProvider')
            {
                if (!file_exists($base . '/phpboost/' . $ucfirst . 'LobbyProvider.class.php'))
                {
                    $missing[] = $ucfirst . 'LobbyProvider.class.php';
                }
            }
        }

        return $missing;
    }

    private function get_module_path(string $module_id): string
    {
        $path = PATH_TO_ROOT . '/modules/' . $module_id;
        if (is_dir($path))
        {
            return $path;
        }
        return PATH_TO_ROOT . '/' . $module_id;
    }

    /**
     * Loads and registers all lobby entries for the given phpboost module ids.
     * Uses require_once + class_exists directly — no classlist dependency.
     */
    private function add_complete_modules(array $module_ids): void
    {
        $compatible_modules = ModulesManager::get_activated_feature_modules('lobby');

		if (empty($compatible_modules))
		{
			return;
		}

		$modules_list = LobbyConfig::load()->get_modules();
        $existing_module_ids = array_column($modules_list, 'module_id');

		foreach ($compatible_modules as $module)
		{
            $phpboost_id  = $module->get_id();
            if (in_array($phpboost_id, $existing_module_ids)) {
                continue;
            }
            $provider_class = TextHelper::ucfirst($phpboost_id) . 'ExtensionPointProvider';
            $file = new File(ModulesManager::get_module_path($phpboost_id) . '/phpboost/' . $provider_class . '.class.php');

            if ($file->exists())
                $provider = new $provider_class();
            else
                $provider = new ItemsModuleExtensionPointProvider($phpboost_id);

            $lobbyProviders = $provider->lobby();

			foreach ($lobbyProviders as $provider)
			{
				if (!($provider instanceof LobbyProvider))
				{
					continue;
				}

				$module = new LobbyModule();
				$module->set_module_id($provider->get_module_id());
				$module->set_module_name($provider->get_module_name());
				$module->set_phpboost_module_id($provider->get_phpboost_module_id());
				$module->set_has_category($provider->is_category_view());
				$module->hide();

				$modules_list[] = $module->get_properties();
			}
		}

		LobbyModulesList::save($modules_list);
		LobbyConfig::save();

        ClassLoader::generate_classlist(true);

        AppContext::get_response()->redirect(LobbyUrlBuilder::add_modules());
    }
}
?>
