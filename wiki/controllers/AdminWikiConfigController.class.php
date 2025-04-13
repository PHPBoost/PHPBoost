<?php
/**
 * @copyright   &copy; 2005-2025 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2025 04 13
 * @since       PHPBoost 6.0 - 2022 11 18
 */

class AdminWikiConfigController extends DefaultAdminModuleController
{

    public function execute(HTTPRequestCustom $request)
    {
        $this->build_form();

        if ($this->submit_button->has_been_submited() && $this->form->validate())
        {
            $this->save();
            $this->form->get_field_by_id('display_summary_to_guests')->set_hidden($this->config->get_display_type() == WikiConfig::TABLE_VIEW);
            $this->form->get_field_by_id('auto_cut_characters_number')->set_hidden($this->config->get_display_type() == WikiConfig::TABLE_VIEW);
            $this->form->get_field_by_id('items_per_row')->set_hidden($this->config->get_display_type() !== WikiConfig::GRID_VIEW);
            $this->form->get_field_by_id('suggested_items_nb')->set_hidden(!$this->config->get_enabled_items_suggestions());
            $this->view->put('MESSAGE_HELPER', MessageHelper::display($this->lang['warning.success.config'], MessageHelper::SUCCESS, 5));
        }

        $this->view->put('CONTENT', $this->form->display());

        return new DefaultAdminDisplayResponse($this->view);
    }

    private function build_form()
    {
        $form = new HTMLForm(__CLASS__);

        $fieldset = new FormFieldsetHTML('configuration', StringVars::replace_vars($this->lang['form.module.title'], ['module_name' => self::get_module()->get_configuration()->get_name()]));
        $form->add_fieldset($fieldset);

        $fieldset->add_field(new FormFieldTextEditor('module_name', $this->lang['wiki.name'], $this->config->get_module_name()));

        $fieldset->add_field(new FormFieldCheckbox('display_sticky_summary', $this->lang['wiki.sticky.contents.table'], $this->config->get_sticky_summary(),
            ['class' => 'custom-checkbox']
        ));

        $fieldset->add_field(new FormFieldSimpleSelectChoice('homepage', $this->lang['wiki.homepage'], $this->config->get_homepage(),
            [
                new FormFieldSelectChoiceOption($this->lang['wiki.config.root'], WikiConfig::CATEGORIES, ['data_option_icon' => 'fa fa-th-large']),
                new FormFieldSelectChoiceOption($this->lang['wiki.config.explorer'], WikiConfig::EXPLORER, ['data_option_icon' => 'fa fa-list']),
                new FormFieldSelectChoiceOption($this->lang['wiki.config.overview'], WikiConfig::OVERVIEW, ['data_option_icon' => 'fa fa-grid']),
            ]
        ));

        $fieldset->add_field(new FormFieldSpacer('main_config', ''));

        $fieldset->add_field(new FormFieldNumberEditor('categories_per_page', $this->lang['form.categories.per.page'], $this->config->get_categories_per_page(),
            ['min' => 1, 'max' => 50, 'required' => true],
            [new FormFieldConstraintIntegerRange(1, 50)]
        ));

        $fieldset->add_field(new FormFieldNumberEditor('categories_per_row', $this->lang['form.categories.per.row'], $this->config->get_categories_per_row(),
            ['min' => 1, 'max' => 4, 'required' => true],
            [new FormFieldConstraintIntegerRange(1, 4)]
        ));

        $fieldset->add_field(new FormFieldNumberEditor('items_per_page', $this->lang['form.items.per.page'], $this->config->get_items_per_page(),
            ['min' => 1, 'max' => 50, 'required' => true],
            [new FormFieldConstraintIntegerRange(1, 50)]
        ));

        $fieldset->add_field(new FormFieldCheckbox('display_description', $this->lang['wiki.display.description'], $this->config->get_display_description(),
            ['class' => 'custom-checkbox']
        ));

        $fieldset->add_field(new FormFieldSpacer('display', ''));

        $fieldset->add_field(new FormFieldSimpleSelectChoice('display_type', $this->lang['form.display.type'], $this->config->get_display_type(),
            [
                new FormFieldSelectChoiceOption($this->lang['form.display.type.grid'], WikiConfig::GRID_VIEW, ['data_option_icon' => 'fa fa-th-large']),
                new FormFieldSelectChoiceOption($this->lang['form.display.type.list'], WikiConfig::LIST_VIEW, ['data_option_icon' => 'fa fa-list']),
                new FormFieldSelectChoiceOption($this->lang['form.display.type.table'], WikiConfig::TABLE_VIEW, ['data_option_icon' => 'fa fa-table']),
            ],
            [
                'select_to_list' => true,
                'events' => ['change' => '
                    if (HTMLForms.getField("display_type").getValue() == \'' . WikiConfig::GRID_VIEW . '\') {
                        HTMLForms.getField("items_per_row").enable();
                        HTMLForms.getField("display_summary_to_guests").enable();
                        HTMLForms.getField("auto_cut_characters_number").enable();
                    } else if (HTMLForms.getField("display_type").getValue() == \'' . WikiConfig::LIST_VIEW . '\') {
                        HTMLForms.getField("display_summary_to_guests").enable();
                        HTMLForms.getField("items_per_row").disable();
                    } else {
                        HTMLForms.getField("items_per_row").disable();
                        HTMLForms.getField("display_summary_to_guests").disable();
                        HTMLForms.getField("auto_cut_characters_number").disable();
                    }'
                ]
            ]
        ));

        $fieldset->add_field(new FormFieldNumberEditor('items_per_row', $this->lang['form.items.per.row'], $this->config->get_items_per_row(),
            [
                'min' => 1, 'max' => 4, 'required' => true,
                'hidden' => $this->config->get_display_type() !== WikiConfig::GRID_VIEW
            ],
            [new FormFieldConstraintIntegerRange(1, 4)]
        ));

        $fieldset->add_field(new FormFieldNumberEditor('auto_cut_characters_number', $this->lang['form.characters.number.to.cut'], $this->config->get_auto_cut_characters_number(),
            [
                'min' => 20, 'max' => 1000, 'required' => true,
                'hidden' => $this->config->get_display_type() === WikiConfig::TABLE_VIEW
            ],
            [new FormFieldConstraintIntegerRange(20, 1000)]
        ));

        $fieldset->add_field(new FormFieldCheckbox('display_summary_to_guests', $this->lang['form.display.summary.to.guests'], $this->config->is_summary_displayed_to_guests(),
            [
                'class' => 'custom-checkbox',
                'hidden' => $this->config->get_display_type() === WikiConfig::TABLE_VIEW
            ]
        ));

        $fieldset->add_field(new FormFieldSpacer('links_options', ''));

        $fieldset->add_field(new FormFieldCheckbox('enabled_navigation_links', $this->lang['form.enable.navigation'], $this->config->get_enabled_navigation_links(),
            [
                'class' => 'custom-checkbox',
                'description' => $this->lang['form.enable.navigation.clue']
            ]
        ));

        $fieldset->add_field(new FormFieldCheckbox('enabled_items_suggestions', $this->lang['form.enable.suggestions'], $this->config->get_enabled_items_suggestions(),
            [
                'class' => 'custom-checkbox',
                'events' => ['click' => '
                    if (HTMLForms.getField("enabled_items_suggestions").getValue()) {
                        HTMLForms.getField("suggested_items_nb").enable();
                    } else {
                        HTMLForms.getField("suggested_items_nb").disable();
                    }'
                ]
            ]
        ));

        $fieldset->add_field(new FormFieldNumberEditor('suggested_items_nb', $this->lang['wiki.suggestions.number'], $this->config->get_suggested_items_nb(),
            [
                'min' => 1, 'max' => 10,
                'hidden' => !$this->config->get_enabled_items_suggestions()
            ],
            [new FormFieldConstraintIntegerRange(1, 10)]
        ));

        $fieldset->add_field(new FormFieldRichTextEditor('root_category_description', $this->lang['form.root.category.description'], $this->config->get_root_category_description(),
            ['rows' => 8, 'cols' => 47]
        ));

        $fieldset->add_field(new FormFieldCheckbox('author_displayed', $this->lang['form.display.author'], $this->config->is_author_displayed(),
            ['class' => 'custom-checkbox']
        ));

        $fieldset->add_field(new FormFieldCheckbox('views_number_enabled', $this->lang['form.display.views.number'], $this->config->get_enabled_views_number(),
            ['class' => 'custom-checkbox']
        ));

        $fieldset->add_field(new FormFieldRichTextEditor('default_content', $this->lang['form.item.default.content'], $this->config->get_default_content(),
            ['rows' => 8, 'cols' => 47]
        ));

        $fieldset_menu = new FormFieldsetHTML('menu_title_name', $this->lang['wiki.menu.configuration']);
        $form->add_fieldset($fieldset_menu);

        $fieldset_menu->add_field(new FormFieldTextEditor('menu_title', $this->lang['wiki.menu.title.name'], $this->config->get_menu_name()));

        $fieldset_authorizations = new FormFieldsetHTML('authorizations_fieldset', $this->lang['form.authorizations'],
            ['description' => $this->lang['form.authorizations.clue']]
        );
        $form->add_fieldset($fieldset_authorizations);

        $auth_settings = new AuthorizationsSettings(array_merge(RootCategory::get_authorizations_settings(), [
            new VisitorDisabledActionAuthorization($this->lang['wiki.manage.archives'], WikiAuthorizationsService::MANAGE_ARCHIVES_AUTHORIZATIONS)
        ]));
        $auth_settings->build_from_auth_array($this->config->get_authorizations());
        $fieldset_authorizations->add_field(new FormFieldAuthorizationsSetter('authorizations', $auth_settings));

        $this->submit_button = new FormButtonDefaultSubmit();
        $form->add_button($this->submit_button);
        $form->add_button(new FormButtonReset());

        $this->form = $form;
    }

    private function save()
    {
        $this->config->set_module_name($this->form->get_value('module_name'));
        $this->config->set_sticky_summary($this->form->get_value('display_sticky_summary'));
        $this->config->set_homepage($this->form->get_value('homepage')->get_raw_value());

        $this->config->set_items_per_page($this->form->get_value('items_per_page'));

        if($this->form->get_value('display_type') == WikiConfig::GRID_VIEW)
            $this->config->set_items_number_per_row($this->form->get_value('items_per_row'));

        $this->config->set_categories_per_page($this->form->get_value('categories_per_page'));
        $this->config->set_categories_per_row($this->form->get_value('categories_per_row'));
        $this->config->set_display_description($this->form->get_value('display_description'));

        $this->config->set_display_type($this->form->get_value('display_type')->get_raw_value());

        if ($this->config->get_display_type() != WikiConfig::TABLE_VIEW)
        {
            if ($this->form->get_value('display_summary_to_guests'))
                $this->config->display_summary_to_guests();
            else
                $this->config->hide_summary_to_guests();
        }

        if ($this->form->get_value('author_displayed'))
            $this->config->display_author();
        else
            $this->config->hide_author();

        $this->config->set_auto_cut_characters_number($this->form->get_value('auto_cut_characters_number', $this->config->get_auto_cut_characters_number()));
        $this->config->set_enabled_views_number($this->form->get_value('views_number_enabled'));
        $this->config->set_root_category_description($this->form->get_value('root_category_description'));

        $this->config->set_default_content($this->form->get_value('default_content'));
        $this->config->set_authorizations($this->form->get_value('authorizations')->build_auth_array());

        $this->config->set_enabled_items_suggestions($this->form->get_value('enabled_items_suggestions'));
        if($this->form->get_value('enabled_items_suggestions'))
            $this->config->set_suggested_items_nb($this->form->get_value('suggested_items_nb'));

        $this->config->set_enabled_navigation_links($this->form->get_value('enabled_navigation_links'));

        $this->config->set_menu_name($this->form->get_value('menu_title'));

        WikiConfig::save();
        CategoriesService::get_categories_manager()->regenerate_cache();
        WikiCache::invalidate();

        HooksService::execute_hook_action('edit_config', self::$module_id, ['title' => StringVars::replace_vars($this->lang['form.module.title'], ['module_name' => self::get_module_configuration()->get_name()]), 'url' => ModulesUrlBuilder::configuration()->rel()]);
    }
}
?>
