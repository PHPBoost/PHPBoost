<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2020 03 13
 * @since       PHPBoost 3.0 - 2009 12 20
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor mipel <mipel@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class SandboxBuilderController extends ModuleController
{
	private $view;
	private $lang;
	private $sub_lang;
	private $common_lang;

	/**
	 * @var FormButtonSubmit
	 */
	private $preview_button;
	/**
	 * @var FormButtonDefaultSubmit
	 */
	private $submit_button;

	public function execute(HTTPRequestCustom $request)
	{
		$this->check_authorizations();

		$this->init();

		$form = $this->build_form();

		if (ModulesManager::is_module_installed('GoogleMaps') && ModulesManager::is_module_activated('GoogleMaps') && GoogleMapsConfig::load()->get_api_key())
			$c_gmap = true;
		else
			$c_gmap = false;

		$this->view->put_all(array('C_GMAP' => $c_gmap));

		if ($this->submit_button->has_been_submited() || $this->preview_button->has_been_submited())
		{
			if ($form->validate())
			{
				$this->view->put_all(array(
					'C_RESULT' => true,
					'TEXT' => $form->get_value('text'),
					'MAIL' => $form->get_value('mail'),
					'WEB' => $form->get_value('siteweb'),
					'AGE' => $form->get_value('age'),
					'MULTI_LINE_TEXT' => $form->get_value('multi_line_text'),
					'RICH_TEXT' => $form->get_value('rich_text'),
					'RADIO' => $form->get_value('radio')->get_label(),
					'CHECKBOX' => var_export($form->get_value('checkbox'), true),
					'SELECT' => $form->get_value('select')->get_label(),
					'HIDDEN' => $form->get_value('hidden'),
					'DATE' => $form->get_value('date')->format(Date::FORMAT_DAY_MONTH_YEAR),
					'DATE_TIME' => $form->get_value('date_time')->format(Date::FORMAT_DAY_MONTH_YEAR_HOUR_MINUTE),
					'H_T_TEXT_FIELD' => $form->get_value('alone'),
					'C_PREVIEW' => $this->preview_button->has_been_submited()
				));

				$file = $form->get_value('file');
				if ( $file !== null)
				{
					$this->view->put_all(array('FILE' => $file->get_name() . ' - ' . $file->get_size() . 'b - ' . $file->get_mime_type()));
				}
			}
		}

		$this->view->put_all(array(
			'form' => $form->display(),
			'SANDBOX_SUBMENU' => self::get_submenu()
		));

		return $this->generate_response();
	}

	private function init()
	{
		$this->common_lang = LangLoader::get('common', 'sandbox');
		$this->lang = LangLoader::get('builder', 'sandbox');
		$this->view = new FileTemplate('sandbox/SandboxBuilderController.tpl');
		$this->view->add_lang($this->common_lang);
		$this->view->add_lang($this->lang);
	}

	private function get_submenu()
	{
		$submenu_lang = LangLoader::get('submenu', 'sandbox');
		$submenu_tpl = new FileTemplate('sandbox/SandboxSubMenu.tpl');
		$submenu_tpl->add_lang($submenu_lang);

		if (ModulesManager::is_module_installed('GoogleMaps') && ModulesManager::is_module_activated('GoogleMaps') && GoogleMapsConfig::load()->get_api_key())
			$c_gmap = true;
		else
			$c_gmap = false;

		$submenu_tpl->put_all(array(
			'C_GMAP' => $c_gmap,
			'C_SANDBOX_FORM' => true
		));
		return $submenu_tpl;
	}

	private function build_form()
	{
		$security_config = SecurityConfig::load();
		$form = new HTMLForm('Sandbox_Builder');
		$form->set_css_class('accordion-container siblings modal-container tabs-container wizard-container fieldset-content');

		// TEXT FIELDS
		$text_fields = new FormFieldsetHTML('text_field', $this->lang['builder.title.inputs']);
			$form->add_fieldset($text_fields);

			// Text
			$text_fields->add_field(new FormFieldTextEditor('text', $this->lang['builder.input.text'], $this->lang['builder.input.text.lorem'],
				array('maxlength' => 25, 'description' => $this->lang['builder.input.text.desc'], 'class' => 'css-class'),
				array(new FormFieldConstraintRegex('`^[a-z0-9_ ]+$`iu'))
			));

			// Url
			$text_fields->add_field(new FormFieldUrlEditor('siteweb', $this->lang['builder.input.url'], $this->lang['builder.input.url.placeholder'],
				array('description' => $this->lang['builder.input.url.desc'], 'class' => 'css-class')
			));

			// Email
			$text_fields->add_field(new FormFieldMailEditor('email', $this->lang['builder.input.email'], $this->lang['builder.input.email.placeholder'],
				array('description' => $this->lang['builder.input.email.desc'], 'class' => 'css-class')
			));
			$text_fields->add_field(new FormFieldMailEditor('multiple_email', $this->lang['builder.input.email.multiple'], $this->lang['builder.input.email.multiple.placeholder'],
				array('description' => $this->lang['builder.input.email.multiple.desc'], 'multiple' => true, 'class' => 'css-class')
			));

			// Phone
			$text_fields->add_field(new FormFieldTelEditor('tel', $this->lang['builder.input.phone'], $this->lang['builder.input.phone.placeholder'],
				array('description' => $this->lang['builder.input.phone.desc'], 'class' => 'css-class')
			));

			// Disabled
			$text_fields->add_field(new FormFieldTextEditor('text_disabled', $this->lang['builder.input.text.disabled'], '',
				array('maxlength' => 25, 'description' => $this->lang['builder.input.text.disabled.desc'], 'disabled' => true, 'class' => 'css-class')
			));

			// Readonly
			$text_fields->add_field(new FormFieldTextEditor('text_readonly', $this->lang['builder.input.text.readonly'], '',
				array('maxlength' => 25, 'description' => $this->lang['builder.input.text.disabled.desc'], 'readonly' => true, 'class' => 'css-class')
			));

			// Required
			$text_fields->add_field(new FormFieldTextEditor('required', $this->lang['builder.input.text.required'], $this->lang['builder.input.text.lorem'],
				array('maxlength' => 25, 'description' => $this->lang['builder.input.text.required.filled'], 'required' => true, 'class' => 'css-class')
			));
			$text_fields->add_field(new FormFieldTextEditor('required_empty', $this->lang['builder.input.text.required'], '',
				array('maxlength' => 25, 'description' => $this->lang['builder.input.text.required.empty'], 'required' => true, 'class' => 'css-class')
			));

			// Number
			$text_fields->add_field(new FormFieldNumberEditor('number', $this->lang['builder.input.number'], $this->lang['builder.input.number.placeholder'],
				array('min' => 10, 'max' => 100, 'description' => $this->lang['builder.input.number.desc'], 'class' => 'css-class'),
				array(new FormFieldConstraintIntegerRange(10, 100))
			));
			$text_fields->add_field(new FormFieldDecimalNumberEditor('decimal', $this->lang['builder.input.number.decimal'], $this->lang['builder.input.number.decimal.placeholder'],
				array('min' => 0, 'step' => 0.1, 'description' => $this->lang['builder.input.number.decimal.desc'], 'class' => 'css-class')
			));

			// Password
			// $text_fields->add_field($password = new FormFieldPasswordEditor('password', $this->lang['builder.input.password'], $this->lang['builder.input.password.placeholder'],
			// 	array('description' => $security_config->get_internal_password_min_length() . $this->lang['builder.input.password.desc'], 'class' => 'css-class'),
			// 	array(new FormFieldConstraintLengthMin($security_config->get_internal_password_min_length()))
			// ));
			// $text_fields->add_field($password_bis = new FormFieldPasswordEditor('password_bis', $this->lang['builder.input.password.confirm'], $this->lang['builder.input.password.placeholder'],
			// 	array('description' => $security_config->get_internal_password_min_length() . $this->lang['builder.input.password.desc'], 'class' => 'css-class'),
			// 	array(new FormFieldConstraintLengthMin($security_config->get_internal_password_min_length()))
			// ));

		// TEXTAREA
		$textarea = new FormFieldsetHTML('textarea', $this->lang['builder.title.textarea']);
			$form->add_fieldset($textarea);

			// Short multi line text
			$textarea->add_field(new FormFieldShortMultiLineTextEditor('short_multi_line_text', $this->lang['builder.input.multiline.medium'], $this->lang['builder.input.multiline.lorem'],
				array('rows' => 3, 'required' => true, 'class' => 'css-class')
			));

			// Multi line text
			$textarea->add_field(new FormFieldMultiLineTextEditor('multi_line_text', $this->lang['builder.input.multiline'], $this->lang['builder.input.multiline.lorem'],
				array('rows' => 6, 'cols' => 47, 'description' => $this->lang['builder.input.multiline.desc'], 'required' => true, 'class' => 'css-class')
			));

			// Rich text
			$textarea->add_field(new FormFieldRichTextEditor('rich_text', $this->lang['builder.input.rich.text'], $this->lang['builder.input.rich.text.placeholder'],
				array('required' => true, 'class' => 'css-class')
			));

		// RADIO / CHECKBOX
		$choices = new FormFieldsetHTML('choices', $this->lang['builder.title.choices']);
			$form->add_fieldset($choices);

			// Checkboxes
			$choices->add_field(new FormFieldCheckbox('checkbox', $this->lang['builder.input.checkbox'], FormFieldCheckbox::CHECKED,
				array('class' => 'css-class')
			));
			$choices->add_field(new FormFieldMultipleCheckbox('multiple_check_box', $this->lang['builder.input.multiple.checkbox'],
				array('1'),
				array(
					new FormFieldMultipleCheckboxOption('1', $this->lang['builder.input.choice'].'1'),
					new FormFieldMultipleCheckboxOption('2', $this->lang['builder.input.choice'].'2')
				),
				array('required' => true, 'class' => 'css-class')
			));

			// Radios
			$default_option = new FormFieldRadioChoiceOption($this->lang['builder.input.choice'].'1', '1');
			$choices->add_field(new FormFieldRadioChoice('inline_radio', $this->lang['builder.input.radio'] . ' inline', '',
				array(
					$default_option,
					new FormFieldRadioChoiceOption($this->lang['builder.input.choice'].'2', '2')
				),
				array('required' => true, 'class' => 'css-class inline-radio')
			));
			$default_option = new FormFieldRadioChoiceOption($this->lang['builder.input.choice'].'1', '1');
			$choices->add_field(new FormFieldRadioChoice('radio', $this->lang['builder.input.radio'], '',
				array(
					$default_option,
					new FormFieldRadioChoiceOption($this->lang['builder.input.choice'].'2', '2')
				),
				array('required' => true, 'class' => 'css-class')
			));

		// SELECTORS
		$select = new FormFieldsetHTML('selects', $this->lang['builder.title.select']);
			$form->add_fieldset($select);

			// SELECT
			$select->add_field(new FormFieldSimpleSelectChoice('select', $this->lang['builder.input.select'], '',
				array(
					new FormFieldSelectChoiceOption(' ', '0'),
					new FormFieldSelectChoiceOption($this->lang['builder.input.choice'].'1', '1'),
					new FormFieldSelectChoiceOption($this->lang['builder.input.choice'].'2', '2'),
					new FormFieldSelectChoiceOption($this->lang['builder.input.choice'].'3', '3'),
					new FormFieldSelectChoiceGroupOption($this->lang['builder.input.choice.group'].'1',
						array(
							new FormFieldSelectChoiceOption($this->lang['builder.input.choice'].'4', '4'),
							new FormFieldSelectChoiceOption($this->lang['builder.input.choice'].'5', '5'),
						)
					),
					new FormFieldSelectChoiceGroupOption($this->lang['builder.input.choice.group'].'2',
						array(
							new FormFieldSelectChoiceOption($this->lang['builder.input.choice'].'6', '6'),
							new FormFieldSelectChoiceOption($this->lang['builder.input.choice'].'7', '7'),
						)
					)
				),
				array('required' => true, 'class' => 'css-class')
			));
			$select->add_field(new FormFieldMultipleSelectChoice('multiple_select', $this->lang['builder.input.multiple.select'],
				array('1', '2'),
				array(
					new FormFieldSelectChoiceOption($this->lang['builder.input.choice'].'1', '1'),
					new FormFieldSelectChoiceOption($this->lang['builder.input.choice'].'2', '2'),
					new FormFieldSelectChoiceOption($this->lang['builder.input.choice'].'3', '3')
				),
				array('required' => true, 'class' => 'css-class')
			));

			// Fake select
			$select->add_field(new FormFieldSimpleSelectChoice('fake_select', $this->lang['builder.input.fake.select'],
				array('1'),
				array(
					new FormFieldSelectChoiceOption(' ', '0'),
					new FormFieldSelectChoiceOption($this->lang['builder.input.choice'].'1', '1', array('selected' => true, 'data_option_icon' => 'far fa-id-card')),
					new FormFieldSelectChoiceOption($this->lang['builder.input.choice'].'2', '2', array('data_option_icon' => 'far fa-id-card')),
					new FormFieldSelectChoiceOption($this->lang['builder.input.choice'].'3', '3', array('data_option_icon' => 'far fa-id-card')),
				),
				array('class' => 'css-class', 'select_to_list' => true)
			));

			// Autocomplete
			$select->add_field(new FormFieldTimezone('timezone', $this->lang['builder.input.timezone'], 'UTC+0',
				array('class' => 'css-class')
			));

			$select->add_field(new FormFieldAjaxSearchUserAutoComplete('user_completition', $this->lang['builder.input.user.completion'], '',
				array('class' => 'css-class')
			));

		// MISCELLANEOUS
		$miscellaneous = new FormFieldsetHTML('miscellaneous', $this->lang['builder.title.miscellaneous']);
			$form->add_fieldset($miscellaneous);

			// CAPTCHA
			$miscellaneous->add_field(new FormFieldCaptcha('Captcha'));

			// HIDDEN
			$miscellaneous->add_field(new FormFieldHidden('hidden', $this->lang['builder.input.hidden']));

			// Description
			$miscellaneous->set_description($this->lang['builder.desc']);

			// Separator
			$miscellaneous->add_field(new FormFieldSpacer('spacer', '<span class="smaller">' . $this->lang['builder.spacer'] . '</span>', array('class' => 'css-class')));

			// Free field
			$miscellaneous->add_field(new FormFieldFree('free', $this->lang['builder.free.html'], $this->lang['builder.input.text.lorem'],
				array('class' => 'css-class')
			));

			// Range
			$miscellaneous->add_field($password = new FormFieldRangeEditor('range', $this->lang['builder.input.length'], $this->lang['builder.input.length.placeholder'],
				array('min' => 1, 'max' => 10, 'description' => $this->lang['builder.input.length.desc'], 'class' => 'css-class')
			));

			// Date
			$miscellaneous->add_field(new FormFieldDate('date', $this->lang['builder.date'], null,
				array('required' => true, 'class' => 'css-class')
			));

			// Date time
			$miscellaneous->add_field(new FormFieldDateTime('date_time', $this->lang['builder.date.hm'], null,
				array('required' => true, 'class' => 'css-class')
			));

			// Color picker
			$miscellaneous->add_field(new FormFieldColorPicker('color', $this->lang['builder.color'], '#366393',
				array('class' => 'css-class')
			));

			// Search
			$miscellaneous->add_field(new FormFieldSearch('search', $this->lang['builder.search'], '',
				array('class' => 'css-class')
			));

			// File picker
			$miscellaneous->add_field(new FormFieldFilePicker('file', $this->lang['builder.file.picker'],
				array('class' => 'css-class')
			));

			// Multiple file picker
			$miscellaneous->add_field(new FormFieldFilePicker('multiple_files', $this->lang['builder.multiple.file.picker'],
				array('class' => 'css-class', 'multiple' => true)
			));

			// Thumbnail
			$miscellaneous->add_field(new FormFieldThumbnail('thumbnail', $this->lang['builder.thumbnail.picker'], '', '/sandbox/templates/images/paysage.png',
				array('class' => 'css-class')
			));

			// Upload file
			$miscellaneous->add_field(new FormFieldUploadFile('upload_file', $this->lang['builder.file.upload'], '',
				array('required' => true, 'class' => 'css-class')
			));

		// LINK LIST
		$link_list = new FormFieldsetHTML('links_list', $this->lang['builder.links.menu']);
			$form->add_fieldset($link_list);

			// List actionsLinks
			$link_list->add_field(new FormFieldSubTitle('simple_list', $this->lang['builder.links.list'], ''));

			$link_list->add_field(new FormFieldActionLinkList('actionlink_list',
				array(
					new FormFieldActionLinkElement($this->lang['builder.link.icon'], '#', 'far fa-edit'),
					new FormFieldActionLinkElement($this->lang['builder.link.img'], '#', '', '/sandbox/sandbox_mini.png'),
					new FormFieldActionLinkElement($this->lang['builder.link'].' 3', '#', ''),
					new FormFieldActionLinkElement($this->lang['builder.link'].' 4', '#', '')
				),
				array('class' => 'css-class')
			));

			// Accordion menu
			// $fieldset_accordion_controls = new FormFieldsetAccordionControls('accordion_controls', '');
			// $accordion_form->add_fieldset($fieldset_accordion_controls);
			//
			// $fieldset_tab_menu = new FormFieldMenuFieldset('tabmenulistID', '');
			// $accordion_form->add_fieldset($fieldset_tab_menu);
			//
			// $fieldset_tab_menu->add_field(new FormFieldMultitabsLinkList('tabitemlistID',
			//     array(
			//         //new FormFieldMultitabsLinkElement(ItemTitle, 'accordion', 'HTMLFormID_targetID', 'fa-icon', 'picture_url', 'active_module'),
			//         new FormFieldMultitabsLinkElement($this->lang['Pannel 01 tabitem'], 'accordion', 'HTMLFormID_targetID-01'),
			//         new FormFieldMultitabsLinkElement($this->lang['multitabs.accordion.title.link'], 'accordion', 'HTMLFormID_targetID-02'),
			//         new FormFieldMultitabsLinkElement($this->lang['multitabs.accordion.title.link'] . ' 03', 'accordion', 'HTMLFormID_targetID-03'),
			//     )
			// ));
			//
			// $fieldset_tab_one = new FormFieldsetMultitabsHTML('targetID-01', $this->lang['multitabs.panel.title'] . ' 01', array('css_class' => 'accordion accordion-animation'));
			// $accordion_form->add_fieldset($fieldset_tab_one);
			//
			// $fieldset_tab_one->add_field(new FormFieldSubTitle('subtitleID', $this->lang['multitabs.form.subtitle'],''));
			//
			// $fieldset_tab_two = new FormFieldsetMultitabsHTML('targetID-02', $this->lang['multitabs.panel.title'] . ' 02', array('css_class' => 'accordion accordion-animation'));
			// $accordion_form->add_fieldset($fieldset_tab_two);
			//
			// $fieldset_tab_three = new FormFieldsetMultitabsHTML('targetID-03', $this->lang['multitabs.panel.title'] . ' 03', array('css_class' => 'accordion accordion-animation'));
			// $accordion_form->add_fieldset($fieldset_tab_three);


			// Modal menu
			$modal_menu = new FormFieldMenuFieldset('modal_menu', '');
				$form->add_fieldset($modal_menu);
				$modal_menu->set_css_class('modal-nav');

				$modal_menu->add_field(new FormFieldSubTitle('modal', $this->lang['builder.modal.menu'], ''));

				$modal_menu->add_field(new FormFieldMultitabsLinkList('modal_menu_list',
					array(
						new FormFieldMultitabsLinkElement($this->lang['builder.link.icon'], 'modal', 'Sandbox_Builder_modal_01', 'fa-cog'),
						new FormFieldMultitabsLinkElement($this->lang['builder.link.img'], 'modal', 'Sandbox_Builder_modal_02', '', '/sandbox/sandbox_mini.png'),
						new FormFieldMultitabsLinkElement($this->lang['builder.link'].' 3', 'modal', 'Sandbox_Builder_modal_03'),
						new FormFieldMultitabsLinkElement($this->lang['builder.link'].' 4', 'modal', 'Sandbox_Builder_modal_04', '', '', '','button d-inline-block')
					)
				));

				$modal_01 = new FormFieldsetMultitabsHTML('modal_01', $this->lang['builder.panel'].' 1',
					array('css_class' => 'modal modal-animation first-tab', 'modal' => true)
				);
				$form->add_fieldset($modal_01);

				$modal_01->set_description($this->common_lang['lorem.short.content']);

				$modal_02 = new FormFieldsetMultitabsHTML('modal_02', $this->lang['builder.panel'].' 2',
					array('css_class' => 'modal modal-animation', 'modal' => true)
				);
				$form->add_fieldset($modal_02);

				$modal_02->set_description($this->common_lang['lorem.medium.content']);

				$modal_03 = new FormFieldsetMultitabsHTML('modal_03', $this->lang['builder.panel'].' 3',
					array('css_class' => 'modal modal-animation', 'modal' => true)
				);
				$form->add_fieldset($modal_03);

				$modal_03->set_description($this->common_lang['lorem.large.content']);

				$modal_04 = new FormFieldsetMultitabsHTML('modal_04', $this->lang['builder.panel'].' 4',
					array('css_class' => 'modal modal-animation', 'modal' => true)
				);
				$form->add_fieldset($modal_04);

				$modal_04->set_description($this->common_lang['lorem.short.content']);

			// Tabs menu
			$tabs_menu = new FormFieldMenuFieldset('tabs_menu', '');
				$form->add_fieldset($tabs_menu);
				$tabs_menu->set_css_class('tabs-nav');

				$tabs_menu->add_field(new FormFieldSubTitle('tabs', $this->lang['builder.tabs.menu'], ''));

				$tabs_menu->add_field(new FormFieldMultitabsLinkList('tabs_menu_list',
					array(
						new FormFieldMultitabsLinkElement($this->lang['builder.link.icon'], 'tabs', 'Sandbox_Builder_tabs_01', 'fa-cog'),
						new FormFieldMultitabsLinkElement($this->lang['builder.link.img'], 'tabs', 'Sandbox_Builder_tabs_02', '', '/sandbox/sandbox_mini.png'),
						new FormFieldMultitabsLinkElement($this->lang['builder.link'].' 3', 'tabs', 'Sandbox_Builder_tabs_03'),
						new FormFieldMultitabsLinkElement($this->lang['builder.link'].' 4', 'tabs', 'Sandbox_Builder_tabs_04', '', '', '', 'button d-inline-block')
					)
				));

				$tabs_01 = new FormFieldsetMultitabsHTML('tabs_01', $this->lang['builder.panel'].' 1',
					array('css_class' => 'tabs tabs-animation first-tab')
				);
				$form->add_fieldset($tabs_01);

				$tabs_01->set_description($this->common_lang['lorem.short.content']);

				$tabs_02 = new FormFieldsetMultitabsHTML('tabs_02', $this->lang['builder.panel'].' 2',
					array('css_class' => 'tabs tabs-animation')
				);
				$form->add_fieldset($tabs_02);

				$tabs_02->set_description($this->common_lang['lorem.medium.content']);

				$tabs_03 = new FormFieldsetMultitabsHTML('tabs_03', $this->lang['builder.panel'].' 3',
					array('css_class' => 'tabs tabs-animation')
				);
				$form->add_fieldset($tabs_03);

				$tabs_03->set_description($this->common_lang['lorem.large.content']);

				$tabs_04 = new FormFieldsetMultitabsHTML('tabs_04', $this->lang['builder.panel'].' 4',
					array('css_class' => 'tabs tabs-animation')
				);
				$form->add_fieldset($tabs_04);

				$tabs_04->set_description($this->common_lang['lorem.short.content']);

		// GOOGLE MAPS
		if (ModulesManager::is_module_installed('GoogleMaps') && ModulesManager::is_module_activated('GoogleMaps') && GoogleMapsConfig::load()->get_api_key())
		{
			$fieldset_maps = new FormFieldsetHTML('fieldset_maps', $this->lang['builder.googlemap']);
			$form->add_fieldset($fieldset_maps);

			// Simple address
			$fieldset_maps->add_field(new GoogleMapsFormFieldSimpleAddress('simple_address', $this->lang['builder.googlemap.simple_address'], '', array('class' => 'css-class')));

			// Map address
			$fieldset_maps->add_field(new GoogleMapsFormFieldMapAddress('map_address', $this->lang['builder.googlemap.map_address'], '', array('class' => 'css-class', 'include_api' => false)));

			// Simple marker
			$fieldset_maps->add_field(new GoogleMapsFormFieldSimpleMarker('simple_marker', $this->lang['builder.googlemap.simple_marker'], '', array('class' => 'css-class', 'include_api' => false)));

			// Multiple markers
			$fieldset_maps->add_field(new GoogleMapsFormFieldMultipleMarkers('multiple_markers', $this->lang['builder.googlemap.multiple_markers'], '', array('class' => 'css-class', 'include_api' => false)));
		}

		// AUTH
		$authorizations = new FormFieldsetHTML('authorizations', $this->lang['builder.authorization']);
			$auth_settings = new AuthorizationsSettings(array(new ActionAuthorization($this->lang['builder.authorization.1'], 1, $this->lang['builder.authorization.1.desc']), new ActionAuthorization($this->lang['builder.authorization.2'], 2)));
			$auth_settings->build_from_auth_array(array('r1' => 3, 'r0' => 2, 'm1' => 1, '1' => 2));
			$auth_setter = new FormFieldAuthorizationsSetter('auth', $auth_settings);
			$authorizations->add_field($auth_setter);
			$form->add_fieldset($authorizations);

		// VERTICAL FIELDSET
		$vertical_fieldset = new FormFieldsetVertical('vertical_fieldset');
			$vertical_fieldset->set_description($this->lang['builder.vertical.desc']);
			$form->add_fieldset($vertical_fieldset);
			$vertical_fieldset->add_field(new FormFieldTextEditor('alone', $this->lang['builder.input.text'], $this->lang['builder.input.text.lorem'], array('class' => 'css-class')));
			$vertical_fieldset->add_field(new FormFieldCheckbox('cbhor', $this->lang['builder.input.checkbox'], FormFieldCheckbox::UNCHECKED, array('class' => 'css-class')));

		// HORIZONTAL FIELDSET
		$horizontal_fieldset = new FormFieldsetHorizontal('horizontal_fieldset');
			$horizontal_fieldset->set_description($this->lang['builder.horizontal.desc']);
			$form->add_fieldset($horizontal_fieldset);
			$horizontal_fieldset->add_field(new FormFieldTextEditor('texthor', $this->lang['builder.input.text'], $this->lang['builder.input.text.lorem'], array('required' => true, 'class' => 'css-class')));
			$horizontal_fieldset->add_field(new FormFieldCheckbox('cbvert', $this->lang['builder.input.checkbox'], FormFieldCheckbox::CHECKED, array('class' => 'css-class')));

		// BUTTONS
		$buttons = new FormFieldsetHTML('buttons', $this->lang['builder.title.buttons']);
			$form->add_fieldset($buttons);
			$buttons->add_field(new FormFieldSpacer('all_buttons_explain', $this->lang['builder.all.buttons']));

			$buttons->add_field(new FormFieldSpacer('button_sizes', $this->lang['builder.button.sizes']));
			$buttons->add_element(new FormButtonButton('.smallest', '', 'smallest-button', 'smallest'));
			$buttons->add_element(new FormButtonButton('.biggest', '', 'biggest-button', 'biggest'));

			$buttons->add_field(new FormFieldSpacer('button_warning', $this->lang['builder.button.colors']));
			$buttons->add_element(new FormButtonButton('.warning', '', 'warning-button', 'warning'));
			$buttons->add_element(new FormButtonButton('.warning.bgc', '', 'bgc-warning-button', 'bgc warning'));
			$buttons->add_element(new FormButtonButton('.warning.bgc-full', '', 'bgc-full-warning-button', 'bgc-full warning'));

		// SUBMIT BUTTONS
		$buttons_fieldset = new FormFieldsetSubmit('button_submit');
			$buttons_fieldset->add_element(new FormButtonReset());
			$this->preview_button = new FormButtonSubmit($this->lang['builder.preview'], 'previewl', 'alert("Hello world preview")');
			$buttons_fieldset->add_element($this->preview_button);
			$this->submit_button = new FormButtonDefaultSubmit();
			$buttons_fieldset->add_element($this->submit_button);
			$buttons_fieldset->add_element(new FormButtonButton($this->lang['builder.button'], 'alert("Hello world");', '', 'button'));
			$form->add_fieldset($buttons_fieldset);

		// FORM CONSTRAINTS
		// $form->add_constraint(new FormConstraintFieldsEquality($password, $password_bis));

		return $form;
	}

	private function check_authorizations()
	{
		if (!SandboxAuthorizationsService::check_authorizations()->read())
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
	}

	private function generate_response()
	{
		$response = new SiteDisplayResponse($this->view);
		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($this->common_lang['title.builder'], $this->common_lang['sandbox.module.title']);

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->common_lang['sandbox.module.title'], SandboxUrlBuilder::home()->rel());
		$breadcrumb->add($this->common_lang['title.builder'], SandboxUrlBuilder::builder()->rel());

		return $response;
	}
}
?>
