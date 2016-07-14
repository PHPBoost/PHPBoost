<?php
/*##################################################
 *                               AdminWebConfigController.class.php
 *                            -------------------
 *   begin                : August 21, 2014
 *   copyright            : (C) 2014 Julien BRISWALTER
 *   email                : j1.seth@phpboost.com
 *
 *
 ###################################################
 *
 * This program is a free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

 /**
 * @author Julien BRISWALTER <j1.seth@phpboost.com>
 */

class AdminWebConfigController extends AdminModuleController
{
	/**
	 * @var HTMLForm
	 */
	private $form;
	/**
	 * @var FormButtonSubmit
	 */
	private $submit_button;
	
	private $lang;
	private $admin_common_lang;
	
	/**
	 * @var WebConfig
	 */
	private $config;
	
	public function execute(HTTPRequestCustom $request)
	{
		$this->init();
		
		$this->build_form();
		
		$tpl = new StringTemplate('# INCLUDE MSG # # INCLUDE FORM #');
		$tpl->add_lang($this->lang);
		
		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->save();
			$this->form->get_field_by_id('display_descriptions_to_guests')->set_hidden($this->config->get_category_display_type() == WebConfig::DISPLAY_ALL_CONTENT);
			$this->form->get_field_by_id('notation_scale')->set_hidden(!$this->config->is_notation_enabled());
			$tpl->put('MSG', MessageHelper::display(LangLoader::get_message('message.success.config', 'status-messages-common'), MessageHelper::SUCCESS, 5));
		}
		
		$tpl->put('FORM', $this->form->display());
		
		return new AdminWebDisplayResponse($tpl, $this->lang['module_config_title']);
	}
	
	private function init()
	{
		$this->config = WebConfig::load();
		$this->lang = LangLoader::get('common', 'web');
		$this->admin_common_lang = LangLoader::get('admin-common');
	}
	
	private function build_form()
	{
		$form = new HTMLForm(__CLASS__);
		
		$fieldset = new FormFieldsetHTML('config', $this->admin_common_lang['configuration']);
		$form->add_fieldset($fieldset);
		
		$fieldset->add_field(new FormFieldNumberEditor('items_number_per_page', $this->admin_common_lang['config.items_number_per_page'], $this->config->get_items_number_per_page(), 
			array('min' => 1, 'max' => 50, 'required' => true),
			array(new FormFieldConstraintIntegerRange(1, 50))
		));
		
		$fieldset->add_field(new FormFieldNumberEditor('categories_number_per_page', $this->admin_common_lang['config.categories_number_per_page'], $this->config->get_categories_number_per_page(),
			array('min' => 1, 'max' => 50, 'required' => true),
			array(new FormFieldConstraintIntegerRange(1, 50))
		));
		
		$fieldset->add_field(new FormFieldNumberEditor('columns_number_per_line', $this->admin_common_lang['config.columns_number_per_line'], $this->config->get_columns_number_per_line(),
			array('min' => 1, 'max' => 4, 'required' => true),
			array(new FormFieldConstraintIntegerRange(1, 4))
		));
		
		$fieldset->add_field(new FormFieldSimpleSelectChoice('category_display_type', $this->lang['config.category_display_type'], $this->config->get_category_display_type(),
			array(
				new FormFieldSelectChoiceOption($this->lang['config.category_display_type.display_summary'], WebConfig::DISPLAY_SUMMARY),
				new FormFieldSelectChoiceOption($this->lang['config.category_display_type.display_all_content'], WebConfig::DISPLAY_ALL_CONTENT),
				new FormFieldSelectChoiceOption($this->lang['config.category_display_type.display_table'], WebConfig::DISPLAY_TABLE)
			),
			array('events' => array('click' => '
				if (HTMLForms.getField("category_display_type").getValue() != \'' . WebConfig::DISPLAY_ALL_CONTENT . '\') {
					HTMLForms.getField("display_descriptions_to_guests").enable();
				} else {
					HTMLForms.getField("display_descriptions_to_guests").disable();
				}'
			))
		));
		
		$fieldset->add_field(new FormFieldCheckbox('display_descriptions_to_guests', $this->lang['config.display_descriptions_to_guests'], $this->config->are_descriptions_displayed_to_guests(),
			array('hidden' => $this->config->get_category_display_type() == WebConfig::DISPLAY_ALL_CONTENT)
		));
		
		$fieldset->add_field(new FormFieldCheckbox('comments_enabled', $this->admin_common_lang['config.comments_enabled'], $this->config->are_comments_enabled()));
		
		$fieldset->add_field(new FormFieldCheckbox('notation_enabled', $this->admin_common_lang['config.notation_enabled'], $this->config->is_notation_enabled(), array(
			'events' => array('click' => '
				if (HTMLForms.getField("notation_enabled").getValue()) {
					HTMLForms.getField("notation_scale").enable();
				} else {
					HTMLForms.getField("notation_scale").disable();
				}'
			)
		)));
		
		$fieldset->add_field(new FormFieldNumberEditor('notation_scale', $this->admin_common_lang['config.notation_scale'], $this->config->get_notation_scale(), 
			array('min' => 3, 'max' => 20, 'required' => true, 'hidden' => !$this->config->is_notation_enabled()),
			array(new FormFieldConstraintIntegerRange(3, 20))
		));
		
		$fieldset->add_field(new FormFieldRichTextEditor('root_category_description', $this->admin_common_lang['config.root_category_description'], $this->config->get_root_category_description(), 
			array('rows' => 8, 'cols' => 47)
		));
		
		$fieldset = new FormFieldsetHTML('menu', $this->lang['config.partners_menu']);
		$form->add_fieldset($fieldset);
		
		$fieldset->add_field(new FormFieldSimpleSelectChoice('sort_type', $this->lang['config.sort_type'], $this->config->get_sort_type(),
			array(
				new FormFieldSelectChoiceOption(LangLoader::get_message('sort_by.alphabetic', 'common'), WebLink::SORT_ALPHABETIC),
				new FormFieldSelectChoiceOption(LangLoader::get_message('form.date.creation', 'common'), WebLink::SORT_DATE),
				new FormFieldSelectChoiceOption(LangLoader::get_message('sort_by.best_note', 'common'), WebLink::SORT_NOTATION),
				new FormFieldSelectChoiceOption($this->lang['config.sort_type.visits'], WebLink::SORT_NUMBER_VISITS),
				new FormFieldSelectChoiceOption(LangLoader::get_message('sort_by.number_comments', 'common'), WebLink::SORT_NUMBER_COMMENTS)
			),
			array('description' => $this->lang['config.sort_type.explain'])
		));
		
		$fieldset->add_field(new FormFieldSimpleSelectChoice('sort_mode', $this->lang['config.sort_mode'], $this->config->get_sort_mode(),
			array(
				new FormFieldSelectChoiceOption(LangLoader::get_message('sort.asc', 'common'), WebLink::ASC),
				new FormFieldSelectChoiceOption(LangLoader::get_message('sort.desc', 'common'), WebLink::DESC)
			)
		));
		
		$fieldset->add_field(new FormFieldNumberEditor('partners_number_in_menu', $this->lang['config.partners_number_in_menu'], $this->config->get_partners_number_in_menu(), 
			array('min' => 1, 'max' => 50, 'required' => true),
			array(new FormFieldConstraintIntegerRange(1, 50))
		));
		
		$common_lang = LangLoader::get('common');
		$fieldset_authorizations = new FormFieldsetHTML('authorizations_fieldset', $common_lang['authorizations'],
			array('description' => $this->admin_common_lang['config.authorizations.explain'])
		);
		$form->add_fieldset($fieldset_authorizations);
		
		$auth_settings = new AuthorizationsSettings(array(
			new ActionAuthorization($common_lang['authorizations.read'], Category::READ_AUTHORIZATIONS),
			new ActionAuthorization($common_lang['authorizations.write'], Category::WRITE_AUTHORIZATIONS),
			new ActionAuthorization($common_lang['authorizations.contribution'], Category::CONTRIBUTION_AUTHORIZATIONS),
			new ActionAuthorization($common_lang['authorizations.moderation'], Category::MODERATION_AUTHORIZATIONS),
			new ActionAuthorization($common_lang['authorizations.categories_management'], Category::CATEGORIES_MANAGEMENT_AUTHORIZATIONS)
		));
		$auth_setter = new FormFieldAuthorizationsSetter('authorizations', $auth_settings);
		$auth_settings->build_from_auth_array($this->config->get_authorizations());
		$fieldset_authorizations->add_field($auth_setter);
		
		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);
		$form->add_button(new FormButtonReset());
		
		$this->form = $form;
	}
	
	private function save()
	{
		$this->config->set_items_number_per_page($this->form->get_value('items_number_per_page'));
		$this->config->set_categories_number_per_page($this->form->get_value('categories_number_per_page'));
		$this->config->set_columns_number_per_line($this->form->get_value('columns_number_per_line'));
		$this->config->set_category_display_type($this->form->get_value('category_display_type')->get_raw_value());
		
		if ($this->config->get_category_display_type() != WebConfig::DISPLAY_ALL_CONTENT)
		{
			if ($this->form->get_value('display_descriptions_to_guests'))
			{
				$this->config->display_descriptions_to_guests();
			}
			else
			{
				$this->config->hide_descriptions_to_guests();
			}
		}
		
		if ($this->form->get_value('comments_enabled'))
			$this->config->enable_comments();
		else
			$this->config->disable_comments();
		
		if ($this->form->get_value('notation_enabled'))
		{
			$this->config->enable_notation();
			$this->config->set_notation_scale($this->form->get_value('notation_scale'));
			if ($this->form->get_value('notation_scale') != $this->config->get_notation_scale())
				NotationService::update_notation_scale('web', $this->config->get_notation_scale(), $this->form->get_value('notation_scale'));
		}
		else
			$this->config->disable_notation();
		
		$this->config->set_root_category_description($this->form->get_value('root_category_description'));
		$this->config->set_sort_type($this->form->get_value('sort_type')->get_raw_value());
		$this->config->set_sort_mode($this->form->get_value('sort_mode')->get_raw_value());
		$this->config->set_partners_number_in_menu($this->form->get_value('partners_number_in_menu'));
		$this->config->set_authorizations($this->form->get_value('authorizations')->build_auth_array());
		
		WebConfig::save();
		WebService::get_categories_manager()->regenerate_cache();
		WebCache::invalidate();
	}
}
?>
