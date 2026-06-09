<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 05 19
 * @since       PHPBoost 3.0 - 2012 03 12
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @author      Arnaud GENET <elenwii@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 */

class UpdateVersionExecuteController extends UpdateController
{
    private FormButtonSubmit $submit;

    private GeneralConfig $general_config;
    private UserAccountsConfig $user_accounts_config;

    private bool $default_module_changed = false;
    private bool $default_theme_changed  = false;
    private bool $default_lang_changed   = false;

    public function execute(HTTPRequestCustom $request)
    {
        parent::load_lang($request);

        $this->init();

        $view = new FileTemplate('update/execute.tpl');
        $this->display_incompatible_elements_messages($view);
        $this->add_navigation($view);
        if ($this->submit->has_been_submited()) {
            $this->handle_form();
        }
        return $this->create_response($view);
    }

    private function init()
    {
        $this->general_config       = GeneralConfig::load();
        $this->user_accounts_config = UserAccountsConfig::load();
    }

    private function display_incompatible_elements_messages(Template $view)
    {
        $incompatible_modules = $this->get_incompatible_modules_list();

        if ($incompatible_modules)
        {
            $message = StringVars::replace_vars(count($incompatible_modules) > 1 ? $this->lang['update.step.execute.incompatible.modules'] : $this->lang['update.step.execute.incompatible.module'], ['modules' => '<b>' . implode('</b>, <b>', $incompatible_modules) . '</b>']);

            if ($this->default_module_changed)
            {
                if (ModulesManager::is_module_installed('news') && ModulesManager::get_module('news')->get_configuration()->get_compatibility() == UpdateServices::NEW_KERNEL_VERSION)
                {
                    $new_default = 'news';
                }
                elseif (ModulesManager::is_module_installed('articles') && ModulesManager::get_module('articles')->get_configuration()->get_compatibility() == UpdateServices::NEW_KERNEL_VERSION)
                {
                    $new_default = 'articles';
                }
                else
                {
                    $new_default = 'forum';
                }

                $message .= StringVars::replace_vars($this->lang['update.step.execute.incompatible.module.default'], ['old_default' => ModulesManager::get_module($this->general_config->get_module_home_page())->get_configuration()->get_name(), 'new_default' => ModulesManager::get_module($new_default)->get_configuration()->get_name()]);
            }

            $view->put('INCOMPATIBLE_MODULES', MessageHelper::display($message, MessageHelper::WARNING));
        }

        $incompatible_themes = $this->get_incompatible_themes_list();

        if ($incompatible_themes)
        {
            $message = StringVars::replace_vars(count($incompatible_themes) > 1 ? $this->lang['update.step.execute.incompatible.themes'] : $this->lang['update.step.execute.incompatible.theme'], ['themes' => '<b>' . implode('</b>, <b>', $incompatible_themes) . '</b>']);

            if ($this->default_theme_changed)
            {
                $message .= StringVars::replace_vars($this->lang['update.step.execute.incompatible.theme.default'], ['old_default' => ThemesManager::get_theme($this->user_accounts_config->get_default_theme())->get_configuration()->get_name(), 'new_default' => 'Base']);
            }

            $view->put('INCOMPATIBLE_THEMES', MessageHelper::display($message, MessageHelper::WARNING));
        }

        $incompatible_langs = $this->get_incompatible_langs_list();

        if ($incompatible_langs)
        {
            $message = StringVars::replace_vars(count($incompatible_langs) > 1 ? $this->lang['update.step.execute.incompatible.langs'] : $this->lang['update.step.execute.incompatible.lang'], ['langs' => '<b>' . implode('</b>, <b>', $incompatible_langs) . '</b>']);

            if ($this->default_lang_changed)
            {
                $message .= StringVars::replace_vars($this->lang['update.step.execute.incompatible.lang.default'], ['old_default' => LangsManager::get_lang($this->user_accounts_config->get_default_lang())->get_configuration()->get_name(), 'new_default' => LangsManager::get_lang(LangLoader::get_locale())->get_configuration()->get_name()]);
            }

            $view->put('INCOMPATIBLE_LANGS', MessageHelper::display($message, MessageHelper::WARNING));
        }
    }

    private function get_incompatible_modules_list()
    {
        $list = [];

        foreach (ModulesManager::get_installed_modules_map() as $module)
        {
            if ($module->get_configuration()->get_compatibility() != UpdateServices::NEW_KERNEL_VERSION)
            {
                $list[] = $module->get_configuration()->get_name();
                if ($this->general_config->get_module_home_page() == $module->get_id())
                {
                    $this->default_module_changed = true;
                }
            }
        }

        return $list;
    }

    private function get_incompatible_themes_list()
    {
        $list = [];

        foreach (ThemesManager::get_installed_themes_map() as $theme)
        {
            if ($theme->get_configuration()->get_compatibility() != UpdateServices::NEW_KERNEL_VERSION)
            {
                $list[] = $theme->get_configuration()->get_name();
                if ($this->user_accounts_config->get_default_theme() == $theme->get_id())
                {
                    $this->default_theme_changed = true;
                }
            }
        }

        return $list;
    }

    private function get_incompatible_langs_list()
    {
        $list = [];

        foreach (LangsManager::get_installed_langs_map() as $lang)
        {
            if ($lang->get_configuration()->get_compatibility() != UpdateServices::NEW_KERNEL_VERSION)
            {
                $list[] = $lang->get_configuration()->get_name();
                if ($this->user_accounts_config->get_default_lang() == $lang->get_id())
                {
                    $this->default_lang_changed = true;
                }
            }
        }

        return $list;
    }

    private function handle_form()
    {
        $service = new UpdateServices();
        $service->execute();
        AppContext::get_response()->redirect(UpdateUrlBuilder::finish());
    }

    /**
     * @param Template $view
     * @return UpdateDisplayResponse
     */
    private function create_response(Template $view)
    {
        $step_title = $this->lang['update.step.execute.title'];
        $response   = new UpdateDisplayResponse(4, $step_title, $view);
        return $response;
    }

    private function add_navigation(Template $view)
    {
        if (UpdateServices::database_config_file_checked())
        {
            if (UpdateServices::check_server())
            {
                $back_url = UpdateUrlBuilder::introduction();
            }
            else
            {
                $back_url = UpdateUrlBuilder::server_configuration();
            }
        }
        else
        {
            $back_url = UpdateUrlBuilder::database();
        }

        $form = new HTMLForm('continueForm', '', false);

        $action_fieldset = new FormFieldsetSubmit('actions');
        $back            = new FormButtonLinkCssImg($this->lang['update.step.previous'], $back_url, 'fa fa-arrow-left');
        $action_fieldset->add_element($back);
        $refresh = new FormButtonLinkCssImg(LangLoader::get_message('form.refresh', 'form-lang'), UpdateUrlBuilder::update()->rel(), 'fa fa-sync');
        $action_fieldset->add_element($refresh);
        $this->submit = new FormButtonSubmitCssImg($this->lang['update.step.next'], 'fa fa-arrow-right', 'finish', 'jQuery(\'#update-in-progress-container\').show();');
        $action_fieldset->add_element($this->submit);
        $form->add_fieldset($action_fieldset);
        $view->put('SERVER_FORM', $form->display());
    }
}
