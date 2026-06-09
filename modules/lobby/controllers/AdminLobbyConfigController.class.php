<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 05 19
 * @since       PHPBoost 6.1 - 2026 03 21
*/

class AdminLobbyConfigController extends DefaultAdminModuleController
{
    /** @var array<string, LobbyModule> */
    private array $modules;

    public function execute(HTTPRequestCustom $request): Response
    {
        $this->lang    = LangLoader::get_all_langs('lobby');
        $this->config  = LobbyConfig::load();
        $this->modules = LobbyModulesList::load();

        $this->build_form();

        $view = new StringTemplate('# INCLUDE MESSAGE_HELPER # # INCLUDE FORM #');

        if ($this->submit_button->has_been_submited() && $this->form->validate())
        {
            $this->save();
            $this->refresh_field_visibility();
            $view->put('MESSAGE_HELPER', MessageHelper::display($this->lang['warning.success.config'], MessageHelper::SUCCESS, 4));
        }

        $view->put('FORM', $this->form->display());

        return new AdminLobbyDisplayResponse($view, $this->lang['lobby.config.module.title']);
    }

    private function active_tab($module_id)
    {
        return $this->modules[$module_id]->is_displayed() ? 'bgc logo-color' : '';
    }

    private function tabs_menu_list(): array
    {
        $tabs_li  = [];
        $config_ids = array_column($this->config->get_modules(), 'module_id');

        $tabs_li[] = new TabsNavElement($this->lang['form.configuration'], self::class . '_configuration', 'fa fa-fw fa-cogs');
        $tabs_li[] = new TabsNavElement($this->lang['lobby.module.carousel'], self::class . '_admin_carousel', 'fa fa-fw fa-image', '', '', $this->active_tab(LobbyConfig::MODULE_CAROUSEL));
        $tabs_li[] = new TabsNavElement($this->lang['lobby.module.lastcoms'], self::class . '_admin_lastcoms', 'far fa-fw fa-comments', '', '', $this->active_tab(LobbyConfig::MODULE_LASTCOMS));

        foreach ($config_ids as $module_id)
        {
            if (in_array($module_id, [LobbyConfig::MODULE_CAROUSEL, LobbyConfig::MODULE_ANCHORS_MENU, LobbyConfig::MODULE_EDITO, LobbyConfig::MODULE_LASTCOMS]))
            {
                continue;
            }

            $phpboost_id = isset($this->modules[$module_id]) ? $this->modules[$module_id]->get_phpboost_module_id() : $module_id;

            if (!ModulesManager::is_module_installed($phpboost_id) || !ModulesManager::is_module_activated($phpboost_id))
            {
                continue;
            }

            $name      = $this->modules[$module_id]->get_module_name();
            $fa_icon   = ModulesManager::get_module($phpboost_id)->get_configuration()->get_fa_icon();
            $hexa_icon = ModulesManager::get_module($phpboost_id)->get_configuration()->get_hexa_icon();

            if (!empty($fa_icon))
                $tabs_li[] = new TabsNavElement($name, self::class . '_admin_' . $module_id, $fa_icon, '', $phpboost_id, $this->active_tab($module_id));
            elseif (!empty($hexa_icon))
                $tabs_li[] = new TabsNavElement($name, self::class . '_admin_' . $module_id, $hexa_icon, '', $phpboost_id, $this->active_tab($module_id));
            else
                $tabs_li[] = new TabsNavElement($name, self::class . '_admin_' . $module_id, '', '', $phpboost_id, $this->active_tab($module_id));
        }

        return $tabs_li;
    }

    private function build_form(): void
    {
        $form = new HTMLForm(self::class);

        // Warning for newly compatible modules not yet added
        $feature_modules = ModulesManager::get_activated_feature_modules('lobby');
        $config_ids      = array_column($this->config->get_modules(), 'module_id');
        $new_modules     = array_filter(
            array_map(fn($m) => $m->get_id(), $feature_modules),
            fn($id) => !in_array($id, $config_ids)
        );

        if (!empty($new_modules))
        {
            $names          = array_map(fn($id) => ModulesManager::get_module($id)->get_configuration()->get_name(), $new_modules);
            $fieldset_warn  = new FormFieldMenuFieldset('new_modules_list', $this->lang['lobby.new.modules']);
            $form->add_fieldset($fieldset_warn);
            $fieldset_warn->add_field(new FormFieldFree(
                'new_modules_warning',
                $this->lang['lobby.new.modules'],
                StringVars::replace_vars($this->lang['lobby.new.modules.description'], ['modules_list' => implode(', ', $names)]),
                ['class' => 'bgc warning message-helper full-field']
            ));
        }

        $tabs_start = new FormFieldsetCapsTop('tabs_start');
        $tabs_start->set_css_class('tabs-container tabs-left');
        $form->add_fieldset($tabs_start);

        $tabs_nav = new TabsNavFieldset('tabs_menu', '');
        $form->add_fieldset($tabs_nav);
        $tabs_nav->add_field(new TabsNavList('tabs_menu_module', $this->tabs_menu_list()));

        $caps_wrapper = new FormFieldsetCapsTop('content_start');
        $caps_wrapper->set_css_class('tabs-wrapper');
        $form->add_fieldset($caps_wrapper);

        $this->build_general_fieldset($form);

        $this->build_carousel_fieldset($form);

        $this->build_lastcoms_fieldset($form);

        $providers = LobbyService::get_all_lobby_providers();

        foreach ($providers as $module_id => $lobby_provider)
        {
            $module_id   = $lobby_provider->get_module_id();
            $phpboost_id = $lobby_provider->get_phpboost_module_id();

            if (!isset($this->modules[$module_id]))
            {
                continue;
            }

            if (!ModulesManager::is_module_installed($phpboost_id) || !ModulesManager::is_module_activated($phpboost_id))
            {
                continue;
            }

            $module = $this->modules[$module_id];
            $name = $lobby_provider->get_module_name();

            $fieldset = new TabsContentFieldset('admin_' . $module_id, $name);
            $form->add_fieldset($fieldset);

            $field_ids = array_keys($lobby_provider->get_fields_visibility($module));
            $toggle_js = $this->build_toggle_js($module_id . '_enabled', $field_ids);

            $fieldset->add_field(new FormFieldCheckbox(
                $module_id . '_enabled',
                $this->lang['lobby.display.module'],
                $module->is_displayed(),
                ['class' => 'custom-checkbox', 'events' => ['click' => $toggle_js]]
            ));

            foreach ($lobby_provider->get_config_fields($module) as $field)
            {
                $fieldset->add_field($field);
            }
        }

        $tabs_wrapper_bottom = new FormFieldsetCapsBottom('content_end');
        $form->add_fieldset($tabs_wrapper_bottom);

        $tabs_end = new FormFieldsetCapsBottom('tabs_end');
        $form->add_fieldset($tabs_end);

        $this->submit_button = new FormButtonDefaultSubmit();
        $form->add_button($this->submit_button);

        $this->form = $form;
    }

    private function build_general_fieldset(HTMLForm $form): void
    {
        $fieldset = new TabsContentFieldset('configuration', $this->lang['lobby.config.module.title']);
        $form->add_fieldset($fieldset);

        $fieldset->add_field(new FormFieldTextEditor('module_title', $this->lang['lobby.label.module.title'], $this->config->get_module_title(),
            ['description' => $this->lang['lobby.label.module.title.clue']]
        ));

        $fieldset->add_field(new FormFieldCheckbox('anchors_menu_enabled', $this->lang['lobby.display.anchors'], $this->config->get_anchors_menu(),
            ['class' => 'custom-checkbox', 'description' => $this->lang['lobby.display.anchors.clue']]
        ));

		$fieldset->add_field(new FormFieldSubTitle('columns', $this->lang['lobby.menus.display'], ''));

        $fieldset->add_field(new FormFieldSpacer('columns_desc', $this->lang['lobby.show.menus']));

        $fieldset->add_field(new FormFieldCheckbox('left_columns', $this->lang['lobby.show.menu.left'], !$this->config->get_left_columns(),
            ['class' => 'custom-checkbox']
        ));

        $fieldset->add_field(new FormFieldCheckbox('right_columns', $this->lang['lobby.show.menu.right'], !$this->config->get_right_columns(),
            ['class' => 'custom-checkbox']
        ));

        $fieldset->add_field(new FormFieldCheckbox('top_central', $this->lang['lobby.show.menu.top.central'], !$this->config->get_top_central(),
            ['class' => 'custom-checkbox']
        ));

        $fieldset->add_field(new FormFieldCheckbox('bottom_central', $this->lang['lobby.show.menu.bottom.central'], !$this->config->get_bottom_central(),
            ['class' => 'custom-checkbox']
        ));

        $fieldset->add_field(new FormFieldCheckbox('top_footer', $this->lang['lobby.show.menu.top.footer'], !$this->config->get_top_footer(),
            ['class' => 'custom-checkbox']
        ));

        $fieldset->add_field(new FormFieldSubTitle('admin_edito', $this->lang['lobby.config.edito'], ''));

        $edito_displayed = isset($this->modules[LobbyConfig::MODULE_EDITO]) && $this->modules[LobbyConfig::MODULE_EDITO]->is_displayed();
        $fieldset->add_field(new FormFieldCheckbox('edito_enabled', $this->lang['lobby.display.edito'], $edito_displayed,
            [
                'class'  => 'custom-checkbox',
                'events' => ['click' => $this->build_toggle_js('edito_enabled', ['edito'])],
            ]
        ));

        $fieldset->add_field(new FormFieldRichTextEditor('edito', $this->lang['lobby.edito.content'], $this->config->get_edito(),
            ['hidden' => !$edito_displayed]
        ));
    }

    private function build_carousel_fieldset(HTMLForm $form): void
    {
        $fieldset = new TabsContentFieldset('admin_carousel', $this->lang['lobby.config.carousel']);
        $form->add_fieldset($fieldset);

        $carousel_displayed = isset($this->modules[LobbyConfig::MODULE_CAROUSEL]) && $this->modules[LobbyConfig::MODULE_CAROUSEL]->is_displayed();
        $fieldset->add_field(new FormFieldCheckbox('carousel_enabled', $this->lang['lobby.display.carousel'], $carousel_displayed,
            [
                'class'  => 'custom-checkbox',
                'events' => ['click' => $this->build_toggle_js('carousel_enabled', ['carousel', 'carousel_speed', 'carousel_time', 'carousel_number', 'carousel_auto', 'carousel_hover'])],
            ]
        ));

        $fieldset->add_field(new FormFieldNumberEditor('carousel_speed',  $this->lang['lobby.carousel.speed'],  $this->config->get_carousel_speed(),
            ['min' => 100,  'max' => 2000,  'hidden' => !$carousel_displayed]
        ));

        $fieldset->add_field(new FormFieldNumberEditor('carousel_time',   $this->lang['lobby.carousel.time'],   $this->config->get_carousel_time(),
            ['min' => 1000, 'max' => 30000, 'hidden' => !$carousel_displayed]
        ));

        $fieldset->add_field(new FormFieldNumberEditor('carousel_number', $this->lang['lobby.carousel.number'], $this->config->get_carousel_number(),
            ['min' => 1,    'max' => 10,    'hidden' => !$carousel_displayed]
        ));

        $fieldset->add_field(new FormFieldSimpleSelectChoice('carousel_auto', $this->lang['lobby.carousel.auto'], $this->config->get_carousel_auto(),
            [
                new FormFieldSelectChoiceOption($this->lang['common.yes'], LobbyConfig::CAROUSEL_TRUE),
                new FormFieldSelectChoiceOption($this->lang['common.no'], LobbyConfig::CAROUSEL_FALSE)
            ],
            ['hidden' => !$carousel_displayed]
        ));

        $fieldset->add_field(new FormFieldSimpleSelectChoice('carousel_hover', $this->lang['lobby.carousel.hover'], $this->config->get_carousel_hover(),
            [
                new FormFieldSelectChoiceOption($this->lang['common.yes'], LobbyConfig::CAROUSEL_TRUE),
                new FormFieldSelectChoiceOption($this->lang['common.no'], LobbyConfig::CAROUSEL_FALSE)
            ],
            ['hidden' => !$carousel_displayed]
        ));

        $fieldset->add_field(new LobbyFormFieldSliderConfig('carousel', $this->lang['lobby.carousel.content'], $this->config->get_carousel(),
            ['class' => 'full-field', 'hidden' => !$carousel_displayed]
        ));
    }

    private function build_lastcoms_fieldset(HTMLForm $form): void
    {
        $fieldset = new TabsContentFieldset('admin_lastcoms', $this->lang['lobby.config.lastcoms']);
        $form->add_fieldset($fieldset);

        $lc_displayed = $this->modules[LobbyConfig::MODULE_LASTCOMS]->is_displayed();
        $fieldset->add_field(new FormFieldCheckbox('lastcoms_enabled', $this->lang['lobby.display.lastcoms'], $lc_displayed,
            [
                'class' => 'custom-checkbox',
                'events' => ['click' => $this->build_toggle_js('lastcoms_enabled', ['lastcoms_limit', 'lastcoms_char'])]
            ]
        ));

        $fieldset->add_field(new FormFieldNumberEditor('lastcoms_limit', $this->lang['lobby.items.number'], $this->modules[LobbyConfig::MODULE_LASTCOMS]->get_elements_number_displayed(),
            ['min' => 1, 'max' => 100, 'hidden' => !$lc_displayed]
        ));

        $fieldset->add_field(new FormFieldNumberEditor('lastcoms_char',  $this->lang['lobby.chars.number'], $this->modules[LobbyConfig::MODULE_LASTCOMS]->get_characters_number_displayed(),
            ['min' => 1, 'max' => 512, 'hidden' => !$lc_displayed]
        ));
    }

    private function save(): void
    {
        $this->config->set_module_title($this->form->get_value('module_title'));
        $this->config->set_left_columns(!$this->form->get_value('left_columns'));
        $this->config->set_right_columns(!$this->form->get_value('right_columns'));
        $this->config->set_top_central(!$this->form->get_value('top_central'));
        $this->config->set_bottom_central(!$this->form->get_value('bottom_central'));
        $this->config->set_top_footer(!$this->form->get_value('top_footer'));

        // Anchors menu
        $this->config->set_anchors_menu($this->form->get_value('anchors_menu_enabled'));
        if (isset($this->modules[LobbyConfig::MODULE_ANCHORS_MENU]))
        {
            $this->form->get_value('anchors_menu_enabled')
                ? $this->modules[LobbyConfig::MODULE_ANCHORS_MENU]->display()
                : $this->modules[LobbyConfig::MODULE_ANCHORS_MENU]->hide();
        }

        // Edito
        if (isset($this->modules[LobbyConfig::MODULE_EDITO]))
        {
            if ($this->form->get_value('edito_enabled'))
            {
                $this->modules[LobbyConfig::MODULE_EDITO]->display();
                $this->config->set_edito($this->form->get_value('edito'));
            }
            else
            {
                $this->modules[LobbyConfig::MODULE_EDITO]->hide();
            }
        }

        // Carousel
        if (isset($this->modules[LobbyConfig::MODULE_CAROUSEL]))
        {
            if ($this->form->get_value('carousel_enabled'))
            {
                $this->modules[LobbyConfig::MODULE_CAROUSEL]->display();
                $this->config->set_carousel($this->form->get_value('carousel'));
                $this->config->set_carousel_speed($this->form->get_value('carousel_speed'));
                $this->config->set_carousel_time($this->form->get_value('carousel_time'));
                $this->config->set_carousel_number($this->form->get_value('carousel_number'));
                $this->config->set_carousel_auto($this->form->get_value('carousel_auto')->get_raw_value());
                $this->config->set_carousel_hover($this->form->get_value('carousel_hover')->get_raw_value());
            }
            else
            {
                $this->modules[LobbyConfig::MODULE_CAROUSEL]->hide();
            }
        }

        // Lastcoms (kernel-level, no LobbyProvider)
        if (isset($this->modules[LobbyConfig::MODULE_LASTCOMS]))
        {
            if ($this->form->get_value('lastcoms_enabled'))
            {
                $this->modules[LobbyConfig::MODULE_LASTCOMS]->display();
                $this->modules[LobbyConfig::MODULE_LASTCOMS]->set_elements_number_displayed($this->form->get_value('lastcoms_limit'));
                $this->modules[LobbyConfig::MODULE_LASTCOMS]->set_characters_number_displayed($this->form->get_value('lastcoms_char'));
            }
            else
            {
                $this->modules[LobbyConfig::MODULE_LASTCOMS]->hide();
            }
        }

        // All provider-driven modules
        $providers = LobbyService::get_all_lobby_providers();

        foreach ($providers as $module_id => $lobby_provider)
        {
            $module_id = $lobby_provider->get_module_id();

            if (!isset($this->modules[$module_id]))
            {
                continue;
            }

            $module = $this->modules[$module_id];

            if ($this->form->get_value($module_id . '_enabled'))
            {
                $module->display();
                $lobby_provider->save($this->form, $module);
            }
            else
            {
                $module->hide();
            }
        }

        LobbyModulesList::save($this->modules);
        LobbyConfig::save();

        HooksService::execute_hook_action('edit_config', 'lobby', [
            'title' => StringVars::replace_vars($this->lang['form.module.title'], ['module_name' => ModulesManager::get_module('lobby')->get_configuration()->get_name()]),
            'url'   => LobbyUrlBuilder::configuration()->rel(),
        ]);
    }

    private function refresh_field_visibility(): void
    {
        $edito_displayed    = isset($this->modules[LobbyConfig::MODULE_EDITO]) && $this->modules[LobbyConfig::MODULE_EDITO]->is_displayed();
        $carousel_displayed = isset($this->modules[LobbyConfig::MODULE_CAROUSEL]) && $this->modules[LobbyConfig::MODULE_CAROUSEL]->is_displayed();
        $lc_displayed       = isset($this->modules[LobbyConfig::MODULE_LASTCOMS]) && $this->modules[LobbyConfig::MODULE_LASTCOMS]->is_displayed();

        $this->form->get_field_by_id('edito')->set_hidden(!$edito_displayed);

        foreach (['carousel', 'carousel_speed', 'carousel_time', 'carousel_number', 'carousel_auto', 'carousel_hover'] as $field_id)
        {
            $this->form->get_field_by_id($field_id)->set_hidden(!$carousel_displayed);
        }

        $this->form->get_field_by_id('lastcoms_limit')->set_hidden(!$lc_displayed);
        $this->form->get_field_by_id('lastcoms_char')->set_hidden(!$lc_displayed);

        $providers = LobbyService::get_all_lobby_providers();

        foreach ($providers as $module_id => $lobby_provider)
        {
            $module_id      = $lobby_provider->get_module_id();

            if (!isset($this->modules[$module_id]))
            {
                continue;
            }

            foreach ($lobby_provider->get_fields_visibility($this->modules[$module_id]) as $field_id => $hidden)
            {
                if ($this->form->has_field($field_id))
                {
                    $this->form->get_field_by_id($field_id)->set_hidden($hidden);
                }
            }
        }
    }

    /**
     * Builds the JS snippet toggling a list of fields when a checkbox changes.
     *
     * @param  string   $checkbox_id
     * @param  string[] $field_ids
     * @return string
     */
    private function build_toggle_js(string $checkbox_id, array $field_ids): string
    {
        $enables  = implode(' ', array_map(fn($id) => 'HTMLForms.getField("' . $id . '").enable();', $field_ids));
        $disables = implode(' ', array_map(fn($id) => 'HTMLForms.getField("' . $id . '").disable();', $field_ids));

        return '
            if (HTMLForms.getField("' . $checkbox_id . '").getValue()) {
                ' . $enables . '
            } else {
                ' . $disables . '
            }';
    }
}
?>
