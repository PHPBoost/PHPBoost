<?php
/**
 * @copyright   &copy; 2005-2025 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2025 01 14
 * @since       PHPBoost 6.0 - 2022 11 18
 * @contributor Myster <MP : https://www.phpboost.com/user/pm-3023>
 */

class WikiItemFormController extends DefaultModuleController
{
    public function execute(HTTPRequestCustom $request)
    {
        $this->check_authorizations();

        $this->build_form($request);
        $item_content = $this->get_item()->get_item_content();

        if ($this->submit_button->has_been_submited() && $this->form->validate())
        {
            $this->form->get_field_by_id('custom_level')->set_hidden($item_content->get_content_level() != WikiItemContent::CUSTOM_LEVEL);
            $this->save();
            $this->redirect();
        }

        $this->view->put('CONTENT', $this->form->display());

        return $this->generate_response($this->view);
    }

    private function build_form(HTTPRequestCustom $request)
    {
        $item_content = $this->get_item()->get_item_content();

        $form = new HTMLForm(__CLASS__);
        $form->set_layout_title($this->is_new_item ? $this->lang['wiki.add.item'] : ($this->is_duplication ? $this->lang['wiki.duplicate.item'] : $this->lang['wiki.edit.item']));

        $fieldset = new FormFieldsetHTML('wiki', $this->lang['form.parameters']);
        $form->add_fieldset($fieldset);
        if (!$this->is_new_item && !$this->is_duplication)
            $fieldset->set_description($this->lang['wiki.message.draft']);

        $fieldset->add_field(new FormFieldTextEditor('title', $this->lang['form.title'], $this->get_item()->get_title(), 
            ['required' => true]
        ));

        if (CategoriesService::get_categories_manager()->get_categories_cache()->has_categories())
        {
            $search_category_children_options = new SearchCategoryChildrensOptions();
            $search_category_children_options->add_authorizations_bits(Category::CONTRIBUTION_AUTHORIZATIONS);
            $search_category_children_options->add_authorizations_bits(Category::WRITE_AUTHORIZATIONS);
            $fieldset->add_field(CategoriesService::get_categories_manager()->get_select_categories_form_field('id_category', $this->lang['form.category'], $this->get_item()->get_id_category(), $search_category_children_options));
        }

        $fieldset->add_field(new FormFieldRichTextEditor('content', $this->lang['form.description'], $item_content->get_content(),
            ['rows' => 15, 'required' => true]
        ));

        $fieldset->add_field(new FormFieldCheckbox('summary_enabled', $this->lang['form.enable.summary'], $item_content->is_summary_enabled(),
            [
                'description' => StringVars::replace_vars($this->lang['form.summary.clue'], ['number' => WikiConfig::load()->get_auto_cut_characters_number()]),
                'events' => ['click' => '
                    if (HTMLForms.getField("summary_enabled").getValue()) {
                        HTMLForms.getField("summary").enable();
                    } else {
                        HTMLForms.getField("summary").disable();
                    }'
                ]
            ]
        ));

        $fieldset->add_field(new FormFieldRichTextEditor('summary', $this->lang['form.summary'], $item_content->get_summary(),
            ['hidden' => ($request->is_post_method() ? !$request->get_postbool(__CLASS__ . '_summary_enabled', false) : !$item_content->is_summary_enabled())]
        ));

        if ($this->config->is_author_displayed())
        {
            $fieldset->add_field(new FormFieldCheckbox('author_custom_name_enabled', $this->lang['form.enable.author.custom.name'], $item_content->is_author_custom_name_enabled(),
                [
                    'events' => ['click' => '
                        if (HTMLForms.getField("author_custom_name_enabled").getValue()) {
                            HTMLForms.getField("author_custom_name").enable();
                        } else {
                            HTMLForms.getField("author_custom_name").disable();
                        }'
                    ]
                ]
            ));

            $fieldset->add_field(new FormFieldTextEditor('author_custom_name', $this->lang['form.author.custom.name'], $item_content->get_author_custom_name(),
                ['hidden' => ($request->is_post_method() ? !$request->get_postbool(__CLASS__ . '_author_custom_name_enabled', false) : !$item_content->is_author_custom_name_enabled())]
            ));
        }

        $options_fieldset = new FormFieldsetHTML('options', $this->lang['form.options']);
        $form->add_fieldset($options_fieldset);

        $options_fieldset->add_field(new FormFieldSimpleSelectChoice('content_level', $this->lang['wiki.level'], $item_content->get_content_level(),
            [
                new FormFieldSelectChoiceOption('', WikiItemContent::NO_LEVEL),
                new FormFieldSelectChoiceOption($this->lang['wiki.level.wip'], WikiItemContent::WIP_LEVEL),
                new FormFieldSelectChoiceOption($this->lang['wiki.level.sketch'], WikiItemContent::SKETCH_LEVEL),
                new FormFieldSelectChoiceOption($this->lang['wiki.level.redo'], WikiItemContent::REDO_LEVEL),
                new FormFieldSelectChoiceOption($this->lang['wiki.level.claim'], WikiItemContent::CLAIM_LEVEL),
                new FormFieldSelectChoiceOption($this->lang['wiki.level.trust'], WikiItemContent::TRUST_LEVEL),
                new FormFieldSelectChoiceOption($this->lang['wiki.level.custom'], WikiItemContent::CUSTOM_LEVEL),
            ],
            [
                'events' => ['change' => '
                    if (HTMLForms.getField("content_level").getValue() == \'' . WikiItemContent::CUSTOM_LEVEL . '\') {
                        HTMLForms.getField("custom_level").enable();
                    } else {
                        HTMLForms.getField("custom_level").disable();
                    }'
                ]
            ]
        ));

        $options_fieldset->add_field(new FormFieldRichTextEditor('custom_level', $this->lang['wiki.level.custom.content'], $item_content->get_custom_level(),
            ['hidden' => $item_content->get_content_level() != WikiItemContent::CUSTOM_LEVEL]
        ));

        $options_fieldset->add_field(new FormFieldThumbnail('thumbnail', $this->lang['form.thumbnail'], $item_content->get_thumbnail()->relative(), WikiItem::THUMBNAIL_URL));

        $options_fieldset->add_field(KeywordsService::get_keywords_manager()->get_form_field($this->get_item()->get_id(), 'keywords', $this->lang['form.keywords'],
            ['description' => $this->lang['form.keywords.clue']]
        ));

        $options_fieldset->add_field(new FormFieldSelectSources('sources', $this->lang['form.sources'], $item_content->get_sources()));

        if(!$this->is_new_item && !$this->is_duplication)
        {
            $options_fieldset->add_field(new FormFieldMultiLineTextEditor('change_reason', $this->lang['wiki.change.reason'], '',
                ['rows' => 5]
            ));
        }

        if (WikiAuthorizationsService::check_authorizations($this->get_item()->get_id_category())->write())
        {
            $publication_fieldset = new FormFieldsetHTML('publication', $this->lang['form.publication']);
            $form->add_fieldset($publication_fieldset);

            $publication_fieldset->add_field(new FormFieldDateTime('creation_date', $this->lang['form.creation.date'], $this->get_item()->get_creation_date(),
                ['required' => true]
            ));

            if (!$this->get_item()->is_published())
            {
                $publication_fieldset->add_field(new FormFieldCheckbox('update_creation_date', $this->lang['form.update.creation.date'], false,
                    ['hidden' => $this->get_item()->get_publishing_state() != WikiItem::NOT_PUBLISHED]
                ));
            }

            $publication_fieldset->add_field(new FormFieldSimpleSelectChoice('published', $this->lang['form.publication'], $this->is_new_item || $this->is_duplication ? $this->get_item()->get_publishing_state() : WikiItem::NOT_PUBLISHED,
                [
                    new FormFieldSelectChoiceOption($this->lang['form.publication.draft'], WikiItem::NOT_PUBLISHED),
                    new FormFieldSelectChoiceOption($this->lang['form.publication.now'], WikiItem::PUBLISHED),
                    new FormFieldSelectChoiceOption($this->lang['form.publication.deffered'], WikiItem::DEFERRED_PUBLICATION),
                ],
                [
                    'class' => !$this->is_new_item && !$this->is_duplication ? 'bgc warning' : '',
                    'events' => ['change' => '
                        if (HTMLForms.getField("published").getValue() == 2) {
                            jQuery("#' . __CLASS__ . '_publishing_start_date_field").show();
                            HTMLForms.getField("end_date_enabled").enable();
                            if (HTMLForms.getField("end_date_enabled").getValue()) {
                                HTMLForms.getField("publishing_end_date").enable();
                            }
                        } else {
                            jQuery("#' . __CLASS__ . '_publishing_start_date_field").hide();
                            HTMLForms.getField("end_date_enabled").disable();
                            HTMLForms.getField("publishing_end_date").disable();
                        }
                        // change color
                        if (jQuery("#' . __CLASS__ . '_published_field").hasClass("warning")) {
                            if (HTMLForms.getField("published").getValue() !== 0) {
                                jQuery("#' . __CLASS__ . '_published_field, #' . __CLASS__ . '_published").each(function() {
                                    jQuery(this).removeClass("warning").addClass("success");
                                });
                            }
                        } else if (jQuery("#' . __CLASS__ . '_published_field").hasClass("success")) {
                            if (HTMLForms.getField("published").getValue() == 0) {
                                jQuery("#' . __CLASS__ . '_published_field, #' . __CLASS__ . '_published").each(function() {
                                    jQuery(this).removeClass("succes").addClass("warning");
                                });
                            }
                        }'
                    ]
                ]
            ));

            $publication_fieldset->add_field($publishing_start_date = new FormFieldDateTime('publishing_start_date', $this->lang['form.start.date'], ($this->get_item()->get_publishing_start_date() === null ? new Date() : $this->get_item()->get_publishing_start_date()),
                ['hidden' => ($request->is_post_method() ? ($request->get_postint(__CLASS__ . '_publication_state', 0) != WikiItem::DEFERRED_PUBLICATION) : ($this->get_item()->get_publishing_state() != WikiItem::DEFERRED_PUBLICATION))]
            ));

            $publication_fieldset->add_field(new FormFieldCheckbox('end_date_enabled', $this->lang['form.enable.end.date'], $this->get_item()->is_end_date_enabled(),
                [
                    'hidden' => ($request->is_post_method() ? ($request->get_postint(__CLASS__ . '_publication_state', 0) != WikiItem::DEFERRED_PUBLICATION) : ($this->get_item()->get_publishing_state() != WikiItem::DEFERRED_PUBLICATION)),
                    'events' => ['click' => '
                        if (HTMLForms.getField("end_date_enabled").getValue()) {
                            HTMLForms.getField("publishing_end_date").enable();
                        } else {
                            HTMLForms.getField("publishing_end_date").disable();
                        }'
                    ]
                ]
            ));

            $publication_fieldset->add_field($publishing_end_date = new FormFieldDateTime('publishing_end_date', $this->lang['form.end.date'], ($this->get_item()->get_publishing_end_date() === null ? new Date() : $this->get_item()->get_publishing_end_date()),
                ['hidden' => ($request->is_post_method() ? !$request->get_postbool(__CLASS__ . '_end_date_enabled', false) : !$this->get_item()->is_end_date_enabled())]
            ));

            $publishing_end_date->add_form_constraint(new FormConstraintFieldsDifferenceSuperior($publishing_start_date, $publishing_end_date));
        }

        $this->build_contribution_fieldset($form);

        $fieldset->add_field(new FormFieldHidden('referrer', $request->get_url_referrer()));

        $this->submit_button = new FormButtonDefaultSubmit();
        $form->add_button($this->submit_button);
        $form->add_button(new FormButtonReset());

        $this->form = $form;
    }

    private function get_duplication_source()
    {
        $url = GeneralConfig::load()->get_site_url() . WikiService::get_item($this->request->get_value('id'))->get_item_url();
        $title = WikiService::get_item($this->request->get_value('id'))->get_title();
        return StringVars::replace_vars($this->lang['common.duplication.source'], ['url' => $url, 'title' => $title]);
    }

    private function build_contribution_fieldset($form)
    {
        if (($this->is_new_item || $this->is_duplication) && $this->is_contributor_member())
        {
            $fieldset = new FormFieldsetHTML('contribution', $this->lang['contribution.contribution']);
            $fieldset->set_description(MessageHelper::display($this->lang['contribution.extended.warning'], MessageHelper::WARNING)->render());
            $form->add_fieldset($fieldset);

            $fieldset->add_field(new FormFieldRichTextEditor('contribution_description', $this->lang['contribution.description'], '',
                ['description' => $this->lang['contribution.description.clue']]
            ));
        }
        elseif ($this->get_item()->is_published() && $this->get_item()->is_authorized_to_edit() && $this->is_contributor_member())
        {
            $fieldset = new FormFieldsetHTML('member_edition', $this->lang['contribution.member.edition']);
            $fieldset->set_description(MessageHelper::display($this->lang['contribution.edition.warning'], MessageHelper::WARNING)->render());
            $form->add_fieldset($fieldset);

            $fieldset->add_field(new FormFieldRichTextEditor('edition_description', $this->lang['contribution.edition.description'], '',
                ['description' => $this->lang['contribution.edition.description.clue']]
            ));
        }
    }

    private function is_contributor_member()
    {
        return (!WikiAuthorizationsService::check_authorizations()->write() && WikiAuthorizationsService::check_authorizations()->contribution());
    }

    private function get_item()
    {
        if ($this->item === null)
        {
            $id = AppContext::get_request()->get_getint('id', 0);
            if (!empty($id))
            {
                try {
                    $this->item = WikiService::get_item($id);
                } catch (RowNotFoundException $e) {
                    $error_controller = PHPBoostErrors::unexisting_page();
                    DispatchManager::redirect($error_controller);
                }
            }
            else
            {
                $this->is_new_item = true;
                $this->item = new WikiItem();
                $this->item->init_default_properties(AppContext::get_request()->get_getint('id_category', Category::ROOT_CATEGORY));

                $item_content = new WikiItemContent();
                $item_content->init_default_properties($id);
                $this->item->set_item_content($item_content);
            }
        }
        return $this->item;
    }

    private function check_authorizations()
    {
        $item = $this->get_item();

        if ($item->get_id() === null)
        {
            if (
                ($this->is_new_item && !$item->is_authorized_to_add())
                || ($this->is_duplication && !$item->is_authorized_to_duplicate())
                || (!$this->is_duplication && !$item->is_authorized_to_edit())
            )
            {
                $error_controller = PHPBoostErrors::user_not_authorized();
                DispatchManager::redirect($error_controller);
            }
        }

        if (AppContext::get_current_user()->is_readonly())
        {
            $controller = PHPBoostErrors::user_in_read_only();
            DispatchManager::redirect($controller);
        }
    }

    private function save()
    {
        $item = $this->get_item();
        $item_content = $item->get_item_content();

        if ($item->get_i_order() === null) {
            $items_number_in_category = WikiService::count('WHERE id_category = :id_category', ['id_category' => $this->get_item()->get_id_category()]);
            $item->set_i_order($items_number_in_category + 1);
        }

        $item->set_title($this->form->get_value('title'));
        $item->set_rewrited_title(Url::encode_rewrite($item->get_title()));

        if (CategoriesService::get_categories_manager()->get_categories_cache()->has_categories())
            $item->set_id_category($this->form->get_value('id_category')->get_raw_value());

        if ($this->is_duplication)
            $item_content->set_content($this->form->get_value('content') . $this->get_duplication_source());
        else
            $item_content->set_content($this->form->get_value('content'));
        $item_content->set_summary(($this->form->get_value('summary_enabled') ? $this->form->get_value('summary') : ''));
        $item_content->set_thumbnail($this->form->get_value('thumbnail'));

        $item_content->set_content_level($this->form->get_value('content_level')->get_raw_value());
        if ($this->form->get_value('content_level')->get_raw_value() == WikiItemContent::CUSTOM_LEVEL)
            $item_content->set_custom_level($this->form->get_value('custom_level'));

        if ($this->config->is_author_displayed())
            $item_content->set_author_custom_name(($this->form->get_value('author_custom_name') && ($this->form->get_value('author_custom_name') !== $item->get_item_content()->get_author_user()->get_display_name()) ? $this->form->get_value('author_custom_name') : ''));

        $item_content->set_sources($this->form->get_value('sources'));

        if (!WikiAuthorizationsService::check_authorizations($this->get_item()->get_id_category())->write())
        {
            $item->clean_publishing_start_and_end_date();

            if (WikiAuthorizationsService::check_authorizations($item->get_id_category())->contribution() && !WikiAuthorizationsService::check_authorizations($item->get_id_category())->write())
                $item->set_publishing_state(WikiItem::NOT_PUBLISHED);
        }
        else
        {
            if ($this->form->get_value('update_creation_date'))
                $item->set_creation_date(new Date());
            else
                $item->set_creation_date($this->form->get_value('creation_date'));

            $item->set_publishing_state($this->form->get_value('published')->get_raw_value());
            if ($item->get_publishing_state() == WikiItem::DEFERRED_PUBLICATION)
            {
                $deferred_operations = $this->config->get_deferred_operations();

                $old_publishing_start_date = $item->get_publishing_start_date();
                $publishing_start_date = $this->form->get_value('publishing_start_date');
                $item->set_publishing_start_date($publishing_start_date);

                if ($old_publishing_start_date !== null && $old_publishing_start_date->get_timestamp() != $publishing_start_date->get_timestamp() && in_array($old_publishing_start_date->get_timestamp(), $deferred_operations))
                {
                    $key = array_search($old_publishing_start_date->get_timestamp(), $deferred_operations);
                    unset($deferred_operations[$key]);
                }

                if (!in_array($publishing_start_date->get_timestamp(), $deferred_operations))
                    $deferred_operations[] = $publishing_start_date->get_timestamp();

                if ($this->form->get_value('end_date_enabled'))
                {
                    $old_publishing_end_date = $item->get_publishing_end_date();
                    $publishing_end_date = $this->form->get_value('publishing_end_date');
                    $item->set_publishing_end_date($publishing_end_date);

                    if ($old_publishing_end_date !== null && $old_publishing_end_date->get_timestamp() != $publishing_end_date->get_timestamp() && in_array($old_publishing_end_date->get_timestamp(), $deferred_operations))
                    {
                        $key = array_search($old_publishing_end_date->get_timestamp(), $deferred_operations);
                        unset($deferred_operations[$key]);
                    }

                    if (!in_array($publishing_end_date->get_timestamp(), $deferred_operations))
                        $deferred_operations[] = $publishing_end_date->get_timestamp();
                }
                else
                    $item->clean_publishing_end_date();

                $this->config->set_deferred_operations($deferred_operations);
                WikiConfig::save();
            }
            else
                $item->clean_publishing_start_and_end_date();
        }

        if ($this->is_new_item)
        {
            $items_number_in_category = WikiService::count('WHERE id_category = :id_category', ['id_category' => $item->get_id_category()]);
            $item->set_i_order($items_number_in_category + 1);

            $last_content_id = WikiService::get_last_content_id();
            foreach ($last_content_id as $content_id) {
                $item_content->set_content_id($content_id + 1);
            }
            $item_content->set_active_content('1');

            $id = WikiService::add($item);
            $item->set_id($id);

            $item_content->set_item_id($id);
            $content_id = WikiService::add_content($item_content);
            $item->set_item_content($item_content);

            if (!$this->is_contributor_member())
                HooksService::execute_hook_action('add', self::$module_id, array_merge($item_content->get_properties(), $item->get_properties(), ['item_url' => $item->get_item_url()]));
        }
        elseif ($this->is_duplication)
        {
            $item_content->set_content_id(0);
            $item_content->set_author_user(AppContext::get_current_user());
            $items_number_in_category = WikiService::count('WHERE id_category = :id_category', ['id_category' => $item->get_id_category()]);
            $item->set_i_order($items_number_in_category + 1);

            $last_content_id = WikiService::get_last_content_id();
            foreach ($last_content_id as $content_id) {
                $item_content->set_content_id($content_id + 1);
            }
            $item_content->set_active_content('1');

            $item->set_id(0);
            $item->set_views_number(0);
            $item->set_creation_date($this->is_contributor_member() ? new Date() : $this->form->get_value('creation_date'));
            $id = WikiService::add($item);
            $item->set_id($id);

            $item_content->set_item_id($id);
            $content_id = WikiService::add_content($item_content);
            $item->set_item_content($item_content);

            if (!$this->is_contributor_member())
                HooksService::execute_hook_action('add', self::$module_id, array_merge($item_content->get_properties(), $item->get_properties(), ['item_url' => $item->get_item_url()]));
        }
        else
        {
            $item_content->set_update_date(new Date());
            if ($item->is_published()) 
            {
                $item_content->set_author_user(AppContext::get_current_user());
                $item_content->set_change_reason($this->form->get_value('change_reason'));
                $last_content_id = WikiService::get_last_content_id();
                foreach ($last_content_id as $content_id) {
                    $item_content->set_content_id($content_id + 1);
                }
                PersistenceContext::get_querier()->update(PREFIX . "wiki_contents", ['active_content' => 0], 'WHERE item_id = :item_id', ['item_id' => $this->get_item()->get_id()]);

                $item_content->set_active_content('1');
                $content_id = WikiService::add_content($item_content);
            }
            else
            {
                $item_content->set_active_content('1');
                $content_id = WikiService::update_content($item_content);
            }

            $item_content->set_content_id($content_id);
            $item->set_item_content($item_content);
            WikiService::update($item);

            if (!$this->is_contributor_member())
                HooksService::execute_hook_action('edit', self::$module_id, array_merge($item_content->get_properties(), $item->get_properties(), ['item_url' => $item->get_item_url()]));
        }

        $this->contribution_actions($item);

        KeywordsService::get_keywords_manager()->put_relations($item->get_id(), $this->form->get_value('keywords'));

        WikiService::clear_cache();
    }

    private function contribution_actions(WikiItem $item)
    {
        $item_content = $this->get_item()->get_item_content();
        if ($this->is_contributor_member())
        {
            $contribution = new Contribution();
            $contribution->set_id_in_module($item->get_id());
            if ($this->is_new_item || $this->is_duplication)
                $contribution->set_description(stripslashes($this->form->get_value('contribution_description')));
            else
                $contribution->set_description(stripslashes($this->form->get_value('edition_description')));

            $contribution->set_entitled($this->get_item()->get_title());
            $contribution->set_fixing_url(WikiUrlBuilder::edit($item->get_id())->relative());
            $contribution->set_poster_id(AppContext::get_current_user()->get_id());
            $contribution->set_module('wiki');
            $contribution->set_auth(
                Authorizations::capture_and_shift_bit_auth(
                    CategoriesService::get_categories_manager()->get_heritated_authorizations($item->get_id_category(), Category::MODERATION_AUTHORIZATIONS, Authorizations::AUTH_CHILD_PRIORITY),
                    Category::MODERATION_AUTHORIZATIONS, Contribution::CONTRIBUTION_AUTH_BIT
                )
            );
            ContributionService::save_contribution($contribution);
            HooksService::execute_hook_action($this->is_new_item || $this->is_duplication ? 'add_contribution' : 'edit_contribution', self::$module_id, array_merge($contribution->get_properties(), $item->get_item_content()->get_properties(), $item->get_properties(), ['item_url' => $item->get_item_url()]));
        }
        else
        {
            $corresponding_contributions = ContributionService::find_by_criteria('wiki', $item->get_id());
            if (count($corresponding_contributions) > 0)
            {
                foreach ($corresponding_contributions as $contribution)
                {
                    $contribution->set_status(Event::EVENT_STATUS_PROCESSED);
                    ContributionService::save_contribution($contribution);
                }
                HooksService::execute_hook_action('process_contribution', self::$module_id, array_merge($contribution->get_properties(),$item->get_item_content()->get_properties(), $item->get_properties(), ['item_url' => $item->get_item_url()]));
            }
        }
    }

    private function redirect()
    {
        $item = $this->get_item();
        $item_content = $this->get_item()->get_item_content();
        $category = $item->get_category();

        if ($this->is_contributor_member())
        {
            DispatchManager::redirect(new UserContributionSuccessController());
        }
        elseif ($item->is_published())
        {
            if ($this->is_new_item || $this->is_duplication)
                AppContext::get_response()->redirect(WikiUrlBuilder::display($category->get_id(), $category->get_rewrited_name(), $item->get_id(), $item->get_rewrited_title()), StringVars::replace_vars($this->lang['wiki.message.success.add'], ['title' => $item->get_title()]));
            else
                AppContext::get_response()->redirect(($this->form->get_value('referrer') ? $this->form->get_value('referrer') : WikiUrlBuilder::display($category->get_id(), $category->get_rewrited_name(), $item->get_id(), $item->get_rewrited_title())), StringVars::replace_vars($this->lang['wiki.message.success.edit'], ['title' => $item->get_title()]));
        }
        else
        {
            if ($this->is_new_item || $this->is_duplication)
                AppContext::get_response()->redirect(WikiUrlBuilder::display_pending(), StringVars::replace_vars($this->lang['wiki.message.success.add'], ['title' => $item->get_title()]));
            else
                AppContext::get_response()->redirect(($this->form->get_value('referrer') ? $this->form->get_value('referrer') : WikiUrlBuilder::display_pending()), StringVars::replace_vars($this->lang['wiki.message.success.edit'], ['title' => $item->get_title()]));
        }
    }

    private function generate_response(View $view)
    {
        $item = $this->get_item();
        $item_content = $this->get_item()->get_item_content();

        $location_name = $this->is_duplication ? 'wiki-duplicate-' : 'wiki-edit-';
        $location_id = $item->get_id() ? $location_name . $item->get_id() : '';

        $response = new SiteDisplayResponse($view, $location_id);
        $graphical_environment = $response->get_graphical_environment();

        $breadcrumb = $graphical_environment->get_breadcrumb();
        $breadcrumb->add($this->config->get_module_name(), WikiUrlBuilder::home());

        if ($item->get_id() === null)
        {
            $breadcrumb->add($this->lang['wiki.add.item'], WikiUrlBuilder::add($item->get_id_category()));
            $graphical_environment->set_page_title($this->lang['wiki.add.item'], $this->config->get_module_name());
            $graphical_environment->get_seo_meta_data()->set_description($this->lang['wiki.add.item']);
            $graphical_environment->get_seo_meta_data()->set_canonical_url(WikiUrlBuilder::add($item->get_id_category()));
        }
        else
        {
            $page_title = $this->is_duplication ? $this->lang['wiki.duplicate.item'] : $this->lang['wiki.edit.item'];
            $page_url = $this->is_duplication ? WikiUrlBuilder::duplicate($item->get_id()) : WikiUrlBuilder::edit($item->get_id());
            if (!AppContext::get_session()->location_id_already_exists($location_id))
                $graphical_environment->set_location_id($location_id);

            $graphical_environment->set_page_title($page_title . ': ' . $item->get_title(), $this->config->get_module_name());
            $graphical_environment->get_seo_meta_data()->set_description($page_title);
            $graphical_environment->get_seo_meta_data()->set_canonical_url($page_url);

            $categories = array_reverse(CategoriesService::get_categories_manager()->get_parents($item->get_id_category(), true));
            foreach ($categories as $id => $category)
            {
                if ($category->get_id() != Category::ROOT_CATEGORY)
                    $breadcrumb->add($category->get_name(), WikiUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name()));
            }
            $category = $item->get_category();
            $breadcrumb->add($item->get_title(), WikiUrlBuilder::display($category->get_id(), $category->get_rewrited_name(), $item->get_id(), $item->get_rewrited_title()));
            $breadcrumb->add($page_title, $page_url);
        }

        return $response;
    }
}
?>
