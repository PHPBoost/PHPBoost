<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2020 02 28
 * @since       PHPBoost 3.0 - 2009 12 20
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor mipel <mipel@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class SandboxFormController extends ModuleController
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
		$sub_tpl = $this->get_sub_tpl();

		$this->view->put_all(array(
			'form' => $form->display(),
			'SANDBOX_SUB_MENU' => self::get_sub_tpl()
		));

		return $this->generate_response();
	}

	private function init()
	{
		$this->common_lang = LangLoader::get('common', 'sandbox');
		$this->lang = LangLoader::get('form', 'sandbox');
		$this->view = new FileTemplate('sandbox/SandboxFormController.tpl');
		$this->view->add_lang($this->common_lang);
		$this->view->add_lang($this->lang);
	}

	private static function get_sub_tpl()
	{
		$sub_lang = LangLoader::get('submenu', 'sandbox');
		$sub_tpl = new FileTemplate('sandbox/SandboxSubMenu.tpl');
		$sub_tpl->add_lang($sub_lang);

		if (ModulesManager::is_module_installed('GoogleMaps') && ModulesManager::is_module_activated('GoogleMaps') && GoogleMapsConfig::load()->get_api_key())
			$c_gmap = true;
		else
			$c_gmap = false;

		$sub_tpl->put_all(array(
			'C_GMAP' => $c_gmap,
			'C_SANDBOX_FORM' => true
		));
		return $sub_tpl;
	}

	private function build_form()
	{
		$security_config = SecurityConfig::load();
		$form = new HTMLForm('Sandbox_Form');
		$form->set_css_class('tabs-container fieldset-content');

		// TEXT FIELDS
		$text_fields = new FormFieldsetHTML('text_field', $this->lang['form.title.inputs']);
			$form->add_fieldset($text_fields);

			// Text
			$text_fields->add_field(new FormFieldTextEditor('text', $this->lang['form.input.text'], $this->lang['form.input.text.lorem'],
				array('maxlength' => 25, 'description' => $this->lang['form.input.text.desc'], 'class' => 'css-class'),
				array(new FormFieldConstraintRegex('`^[a-z0-9_ ]+$`iu'))
			));

			// Url
			$text_fields->add_field(new FormFieldUrlEditor('siteweb', $this->lang['form.input.url'], $this->lang['form.input.url.placeholder'],
				array('description' => $this->lang['form.input.url.desc'], 'class' => 'css-class')
			));

			// Email
			$text_fields->add_field(new FormFieldMailEditor('email', $this->lang['form.input.email'], $this->lang['form.input.email.placeholder'],
				array('description' => $this->lang['form.input.email.desc'], 'class' => 'css-class')
			));
			$text_fields->add_field(new FormFieldMailEditor('multiple_email', $this->lang['form.input.email.multiple'], $this->lang['form.input.email.multiple.placeholder'],
				array('description' => $this->lang['form.input.email.multiple.desc'], 'multiple' => true, 'class' => 'css-class')
			));

			// Phone
			$text_fields->add_field(new FormFieldTelEditor('tel', $this->lang['form.input.phone'], $this->lang['form.input.phone.placeholder'],
				array('description' => $this->lang['form.input.phone.desc'], 'class' => 'css-class')
			));

			// Disabled
			$text_fields->add_field(new FormFieldTextEditor('text_disabled', $this->lang['form.input.text.disabled'], '',
				array('maxlength' => 25, 'description' => $this->lang['form.input.text.disabled.desc'], 'disabled' => true, 'class' => 'css-class')
			));

			// Readonly
			$text_fields->add_field(new FormFieldTextEditor('text_readonly', $this->lang['form.input.text.readonly'], '',
				array('maxlength' => 25, 'description' => $this->lang['form.input.text.disabled.desc'], 'readonly' => true, 'class' => 'css-class')
			));

			// Required
			$text_fields->add_field(new FormFieldTextEditor('required', $this->lang['form.input.text.required'], $this->lang['form.input.text.lorem'],
				array('maxlength' => 25, 'description' => $this->lang['form.input.text.required.filled'], 'required' => true, 'class' => 'css-class')
			));
			$text_fields->add_field(new FormFieldTextEditor('required_empty', $this->lang['form.input.text.required'], '',
				array('maxlength' => 25, 'description' => $this->lang['form.input.text.required.empty'], 'required' => true, 'class' => 'css-class')
			));

			// Number
			$text_fields->add_field(new FormFieldNumberEditor('number', $this->lang['form.input.number'], $this->lang['form.input.number.placeholder'],
				array('min' => 10, 'max' => 100, 'description' => $this->lang['form.input.number.desc'], 'class' => 'css-class'),
				array(new FormFieldConstraintIntegerRange(10, 100))
			));
			$text_fields->add_field(new FormFieldDecimalNumberEditor('decimal', $this->lang['form.input.number.decimal'], $this->lang['form.input.number.decimal.placeholder'],
				array('min' => 0, 'step' => 0.1, 'description' => $this->lang['form.input.number.decimal.desc'], 'class' => 'css-class')
			));

			// Password
			$text_fields->add_field($password = new FormFieldPasswordEditor('password', $this->lang['form.input.password'], $this->lang['form.input.password.placeholder'],
				array('description' => $security_config->get_internal_password_min_length() . $this->lang['form.input.password.desc'], 'class' => 'css-class'),
				array(new FormFieldConstraintLengthMin($security_config->get_internal_password_min_length()))
			));
			$text_fields->add_field($password_bis = new FormFieldPasswordEditor('password_bis', $this->lang['form.input.password.confirm'], $this->lang['form.input.password.placeholder'],
				array('description' => $security_config->get_internal_password_min_length() . $this->lang['form.input.password.desc'], 'class' => 'css-class'),
				array(new FormFieldConstraintLengthMin($security_config->get_internal_password_min_length()))
			));

		// TEXTAREA
		$textarea = new FormFieldsetHTML('textarea', $this->lang['form.title.textarea']);
			$form->add_fieldset($textarea);

			// Short multi line text
			$textarea->add_field(new FormFieldShortMultiLineTextEditor('short_multi_line_text', $this->lang['form.input.multiline.medium'], $this->lang['form.input.multiline.lorem'],
				array('rows' => 3, 'required' => true, 'class' => 'css-class')
			));

			// Multi line text
			$textarea->add_field(new FormFieldMultiLineTextEditor('multi_line_text', $this->lang['form.input.multiline'], $this->lang['form.input.multiline.lorem'],
				array('rows' => 6, 'cols' => 47, 'description' => $this->lang['form.input.multiline.desc'], 'required' => true, 'class' => 'css-class')
			));

			// Rich text
			$textarea->add_field(new FormFieldRichTextEditor('rich_text', $this->lang['form.input.rich.text'], $this->lang['form.input.rich.text.placeholder'],
				array('required' => true, 'class' => 'css-class')
			));

		// RADIO / CHECKBOX
		$choices = new FormFieldsetHTML('choices', $this->lang['form.title.choices']);
			$form->add_fieldset($choices);

			// Checkboxes
			$choices->add_field(new FormFieldCheckbox('checkbox', $this->lang['form.input.checkbox'], FormFieldCheckbox::CHECKED,
				array('class' => 'css-class')
			));
			$choices->add_field(new FormFieldMultipleCheckbox('multiple_check_box', $this->lang['form.input.multiple.checkbox'],
				array('1'),
				array(
					new FormFieldMultipleCheckboxOption('1', $this->lang['form.input.choice'].'1'),
					new FormFieldMultipleCheckboxOption('2', $this->lang['form.input.choice'].'2')
				),
				array('required' => true, 'class' => 'css-class')
			));

			// Radios
			$default_option = new FormFieldRadioChoiceOption($this->lang['form.input.choice'].'1', '1');
			$choices->add_field(new FormFieldRadioChoice('inline_radio', $this->lang['form.input.radio'] . ' inline', '',
				array(
					$default_option,
					new FormFieldRadioChoiceOption($this->lang['form.input.choice'].'2', '2')
				),
				array('required' => true, 'class' => 'css-class inline-radio')
			));
			$default_option = new FormFieldRadioChoiceOption($this->lang['form.input.choice'].'1', '1');
			$choices->add_field(new FormFieldRadioChoice('radio', $this->lang['form.input.radio'], '',
				array(
					$default_option,
					new FormFieldRadioChoiceOption($this->lang['form.input.choice'].'2', '2')
				),
				array('required' => true, 'class' => 'css-class')
			));

		// SELECTORS
		$select = new FormFieldsetHTML('selects', $this->lang['form.title.select']);
			$form->add_fieldset($select);

			// SELECT
			$select->add_field(new FormFieldSimpleSelectChoice('select', $this->lang['form.input.select'], '',
				array(
					new FormFieldSelectChoiceOption(' ', '0'),
					new FormFieldSelectChoiceOption($this->lang['form.input.choice'].'1', '1'),
					new FormFieldSelectChoiceOption($this->lang['form.input.choice'].'2', '2'),
					new FormFieldSelectChoiceOption($this->lang['form.input.choice'].'3', '3'),
					new FormFieldSelectChoiceGroupOption($this->lang['form.input.choice.group'].'1',
						array(
							new FormFieldSelectChoiceOption($this->lang['form.input.choice'].'4', '4'),
							new FormFieldSelectChoiceOption($this->lang['form.input.choice'].'5', '5'),
						)
					),
					new FormFieldSelectChoiceGroupOption($this->lang['form.input.choice.group'].'2',
						array(
							new FormFieldSelectChoiceOption($this->lang['form.input.choice'].'6', '6'),
							new FormFieldSelectChoiceOption($this->lang['form.input.choice'].'7', '7'),
						)
					)
				),
				array('required' => true, 'class' => 'css-class')
			));
			$select->add_field(new FormFieldMultipleSelectChoice('multiple_select', $this->lang['form.input.multiple.select'],
				array('1', '2'),
				array(
					new FormFieldSelectChoiceOption($this->lang['form.input.choice'].'1', '1'),
					new FormFieldSelectChoiceOption($this->lang['form.input.choice'].'2', '2'),
					new FormFieldSelectChoiceOption($this->lang['form.input.choice'].'3', '3')
				),
				array('required' => true, 'class' => 'css-class')
			));

			// Fake select
			$select->add_field(new FormFieldSimpleSelectChoice('fake_select', $this->lang['form.input.fake.select'],
				array('1'),
				array(
					new FormFieldSelectChoiceOption(' ', '0'),
					new FormFieldSelectChoiceOption($this->lang['form.input.choice'].'1', '1', array('selected' => true, 'data_option_icon' => 'far fa-id-card')),
					new FormFieldSelectChoiceOption($this->lang['form.input.choice'].'2', '2', array('data_option_icon' => 'far fa-id-card')),
					new FormFieldSelectChoiceOption($this->lang['form.input.choice'].'3', '3', array('data_option_icon' => 'far fa-id-card')),
				),
				array('class' => 'css-class', 'select_to_list' => true)
			));

			// Autocomplete
			$select->add_field(new FormFieldTimezone('timezone', $this->lang['form.input.timezone'], 'UTC+0',
				array('class' => 'css-class')
			));

			$select->add_field(new FormFieldAjaxSearchUserAutoComplete('user_completition', $this->lang['form.input.user.completion'], '',
				array('class' => 'css-class')
			));

		// BUTTONS
		$buttons = new FormFieldsetHTML('buttons', $this->lang['form.title.buttons']);
			$form->add_fieldset($buttons);
			$buttons->add_field(new FormFieldSpacer('all_buttons_explain', $this->lang['form.all.buttons']));

			$buttons->add_element(new FormButtonButton('.reset', '', 'reset-button', 'reset-button'));
			$buttons->add_element(new FormButtonButton('.button', '', 'simple-button', ''));
			$buttons->add_element(new FormButtonButton('.alt-button', '', 'alt-button', 'alt-button'));
			$buttons->add_element(new FormButtonButton('.submit', '', 'submit-button', 'submit'));
			$buttons->add_element(new FormButtonButton('.alt-submit', '', 'alt-submit-button', 'alt-submit'));

			$buttons->add_field(new FormFieldSpacer('button_sizes', ''));

			$buttons->add_element(new FormButtonButton('.smallest', '', 'smallest-button', 'smallest'));
			$buttons->add_element(new FormButtonButton('.smaller', '', 'smaller-button', 'smaller'));
			$buttons->add_element(new FormButtonButton('.small', '', 'small-button', 'small'));
			$buttons->add_element(new FormButtonButton('.button', '', 'medium-button', ''));
			$buttons->add_element(new FormButtonButton('.big', '', 'big-button', 'big'));
			$buttons->add_element(new FormButtonButton('.bigger', '', 'bigger-button', ''));
			$buttons->add_element(new FormButtonButton('.biggest', '', 'biggest-button', 'biggest'));

			$buttons->add_field(new FormFieldSpacer('button_notice', ''));
			$buttons->add_element(new FormButtonButton('.notice', '', 'notice-button', 'notice'));
			$buttons->add_element(new FormButtonButton('.notice.bgc', '', 'bgc-notice-button', 'bgc notice'));
			$buttons->add_element(new FormButtonButton('.notice.bgc-full', '', 'bgc-full-notice-button', 'bgc-full notice'));

			$buttons->add_field(new FormFieldSpacer('button_question', ''));
			$buttons->add_element(new FormButtonButton('.question', '', 'question-button', 'question'));
			$buttons->add_element(new FormButtonButton('.question.bgc', '', 'bgc-question-button', 'bgc question'));
			$buttons->add_element(new FormButtonButton('.question.bgc-full', '', 'bgc-question-button', 'bgc-full question'));

			$buttons->add_field(new FormFieldSpacer('button_success', ''));
			$buttons->add_element(new FormButtonButton('.success', '', 'success-button', 'success'));
			$buttons->add_element(new FormButtonButton('.success.bgc', '', 'bgc-success-button', 'bgc success'));
			$buttons->add_element(new FormButtonButton('.success.bgc-full', '', 'bgc-full-success-button', 'bgc-full success'));

			$buttons->add_field(new FormFieldSpacer('button_warning', ''));
			$buttons->add_element(new FormButtonButton('.warning', '', 'warning-button', 'warning'));
			$buttons->add_element(new FormButtonButton('.warning.bgc', '', 'bgc-warning-button', 'bgc warning'));
			$buttons->add_element(new FormButtonButton('.warning.bgc-full', '', 'bgc-full-warning-button', 'bgc-full warning'));

			$buttons->add_field(new FormFieldSpacer('button_errors', ''));
			$buttons->add_element(new FormButtonButton('.error', '', 'error-button', 'error'));
			$buttons->add_element(new FormButtonButton('.error.bgc', '', 'bgc-error-button', 'bgc error'));
			$buttons->add_element(new FormButtonButton('.error.bgc-full', '', 'bgc-full-error-button', 'bgc-full error'));

			$buttons->add_field(new FormFieldSpacer('button_visitor', ''));
			$buttons->add_element(new FormButtonButton('.visitor', '', 'visitor-button', 'visitor'));
			$buttons->add_element(new FormButtonButton('.visitor.bgc', '', 'bgc-visitor-button', 'bgc visitor'));
			$buttons->add_element(new FormButtonButton('.visitor.bgc-full', '', 'bgc-full-visitor-button', 'bgc-full visitor'));

			$buttons->add_field(new FormFieldSpacer('button_custom_author', ''));
			$buttons->add_element(new FormButtonButton('.custom-author', '', 'custom-author-button', 'custom-author'));
			$buttons->add_element(new FormButtonButton('.custom-author.bgc', '', 'bgc-custom-author-button', 'bgc custom-author'));
			$buttons->add_element(new FormButtonButton('.custom-author.bgc-full', '', 'bgc-full-custom-author-button', 'bgc-full custom-author'));

			$buttons->add_field(new FormFieldSpacer('button_member', ''));
			$buttons->add_element(new FormButtonButton('.member', '', 'member-button', 'member'));
			$buttons->add_element(new FormButtonButton('.member.bgc', '', 'bgc-member-button', 'bgc member'));
			$buttons->add_element(new FormButtonButton('.member.bgc-full', '', 'bgc-full-member-button', 'bgc-full member'));

			$buttons->add_field(new FormFieldSpacer('button_moderator', ''));
			$buttons->add_element(new FormButtonButton('.moderator', '', 'moderator-button', 'moderator'));
			$buttons->add_element(new FormButtonButton('.moderator.bgc', '', 'bgc-moderator-button', 'bgc moderator'));
			$buttons->add_element(new FormButtonButton('.moderator.bgc-full', '', 'bgc-full-moderator-button', 'bgc-full moderator'));

			$buttons->add_field(new FormFieldSpacer('button_administrator', ''));
			$buttons->add_element(new FormButtonButton('.administrator', '', 'administrator-button', 'administrator'));
			$buttons->add_element(new FormButtonButton('.administrator.bgc', '', 'bgc-administrator-button', 'bgc administrator'));
			$buttons->add_element(new FormButtonButton('.administrator.bgc-full', '', 'bgc-full-administrator-button', 'bgc-full administrator'));

		// MISCELLANEOUS
		$miscellaneous = new FormFieldsetHTML('miscellaneous', $this->lang['form.title.miscellaneous']);
			$form->add_fieldset($miscellaneous);

			// CAPTCHA
			$miscellaneous->add_field(new FormFieldCaptcha('Captcha'));

			// HIDDEN
			$miscellaneous->add_field(new FormFieldHidden('hidden', $this->lang['form.input.hidden']));

			// Description
			$miscellaneous->set_description($this->lang['form.desc']);

			// Separator
			$miscellaneous->add_field(new FormFieldSpacer('spacer', '<span class="smaller">' . $this->lang['form.spacer'] . '</span>', array('class' => 'css-class')));

			// Free field
			$miscellaneous->add_field(new FormFieldFree('free', $this->lang['form.free.html'], $this->lang['form.input.text.lorem'],
				array('class' => 'css-class')
			));

			// Range
			$miscellaneous->add_field($password = new FormFieldRangeEditor('range', $this->lang['form.input.length'], $this->lang['form.input.length.placeholder'],
				array('min' => 1, 'max' => 10, 'description' => $this->lang['form.input.length.desc'], 'class' => 'css-class')
			));

			// Date
			$miscellaneous->add_field(new FormFieldDate('date', $this->lang['form.date'], null,
				array('required' => true, 'class' => 'css-class')
			));

			// Date time
			$miscellaneous->add_field(new FormFieldDateTime('date_time', $this->lang['form.date.hm'], null,
				array('required' => true, 'class' => 'css-class')
			));

			// Color picker
			$miscellaneous->add_field(new FormFieldColorPicker('color', $this->lang['form.color'], '#366393',
				array('class' => 'css-class')
			));

			// Search
			$miscellaneous->add_field(new FormFieldSearch('search', $this->lang['form.search'], '',
				array('class' => 'css-class')
			));

			// File picker
			$miscellaneous->add_field(new FormFieldFilePicker('file', $this->lang['form.file.picker'],
				array('class' => 'css-class')
			));

			// Multiple file picker
			$miscellaneous->add_field(new FormFieldFilePicker('multiple_files', $this->lang['form.multiple.file.picker'],
				array('class' => 'css-class', 'multiple' => true)
			));

			// Thumbnail
			$miscellaneous->add_field(new FormFieldThumbnail('thumbnail', $this->lang['form.thumbnail.picker'], '', '/sandbox/templates/images/paysage.png',
				array('class' => 'css-class')
			));

			// Upload file
			$miscellaneous->add_field(new FormFieldUploadFile('upload_file', $this->lang['form.file.upload'], '',
				array('required' => true, 'class' => 'css-class')
			));

		// LINK LIST
		$link_list = new FormFieldsetHTML('links_list', $this->lang['form.links.menu']);
			$form->add_fieldset($link_list);

			// List actionLinks
			$link_list->add_field(new FormFieldSubTitle('simple_list', $this->lang['form.links.list'], ''));

			$link_list->add_field(new FormFieldActionLinkList('actionlink_list',
				array(
					new FormFieldActionLinkElement($this->lang['form.link.icon'], '#', 'far fa-edit'),
					new FormFieldActionLinkElement($this->lang['form.link.img'], '#', '', '/sandbox/sandbox_mini.png'),
					new FormFieldActionLinkElement($this->lang['form.link'].' 3', '#', ''),
					new FormFieldActionLinkElement($this->lang['form.link'].' 4', '#', '')
				),
				array('class' => 'css-class')
			));

			// Tabs menu
			$tabs_menu = new FormFieldMenuFieldset('tabs_menu', '');
			$form->add_fieldset($tabs_menu);

			$tabs_menu->add_field(new FormFieldSubTitle('tabs', $this->lang['form.tabs.menu'], ''));

			$tabs_menu->add_field(new FormFieldMultitabsLinkList('tabs_menu_list',
				array(
					new FormFieldMultitabsLinkElement($this->lang['form.link.icon'], 'tabs', 'Sandbox_Form_tabs_01', 'fa-cog'),
					new FormFieldMultitabsLinkElement($this->lang['form.link.img'], 'tabs', 'Sandbox_Form_tabs_02', '', '/sandbox/sandbox_mini.png'),
					new FormFieldMultitabsLinkElement($this->lang['form.link'].' 3', 'tabs', 'Sandbox_Form_tabs_03'),
					new FormFieldMultitabsLinkElement($this->lang['form.link'].' 4', 'tabs', 'Sandbox_Form_tabs_04')
				)
			));

			$tabs_01 = new FormFieldsetMultitabsHTML('tabs_01', $this->lang['form.panel'].' 1',
				array('css_class' => 'tabs tabs-animation first-tab')
			);
			$form->add_fieldset($tabs_01);

			$tabs_01->set_description($this->common_lang['lorem.short.content']);

			$tabs_02 = new FormFieldsetMultitabsHTML('tabs_02', $this->lang['form.panel'].' 2',
				array('css_class' => 'tabs tabs-animation')
			);
			$form->add_fieldset($tabs_02);

			$tabs_02->set_description($this->common_lang['lorem.medium.content']);

			$tabs_03 = new FormFieldsetMultitabsHTML('tabs_03', $this->lang['form.panel'].' 3',
				array('css_class' => 'tabs tabs-animation')
			);
			$form->add_fieldset($tabs_03);

			$tabs_03->set_description($this->common_lang['lorem.large.content']);

			$tabs_04 = new FormFieldsetMultitabsHTML('tabs_04', $this->lang['form.panel'].' 4',
				array('css_class' => 'tabs tabs-animation')
			);
			$form->add_fieldset($tabs_04);

			$tabs_04->set_description($this->common_lang['lorem.short.content']);

		// GOOGLE MAPS
		if (ModulesManager::is_module_installed('GoogleMaps') && ModulesManager::is_module_activated('GoogleMaps') && GoogleMapsConfig::load()->get_api_key())
		{
			$fieldset_maps = new FormFieldsetHTML('fieldset_maps', $this->lang['form.googlemap']);
			$form->add_fieldset($fieldset_maps);

			// Simple address
			$fieldset_maps->add_field(new GoogleMapsFormFieldSimpleAddress('simple_address', $this->lang['form.googlemap.simple_address'], '', array('class' => 'css-class')));

			// Map address
			$fieldset_maps->add_field(new GoogleMapsFormFieldMapAddress('map_address', $this->lang['form.googlemap.map_address'], '', array('class' => 'css-class', 'include_api' => false)));

			// Simple marker
			$fieldset_maps->add_field(new GoogleMapsFormFieldSimpleMarker('simple_marker', $this->lang['form.googlemap.simple_marker'], '', array('class' => 'css-class', 'include_api' => false)));

			// Multiple markers
			$fieldset_maps->add_field(new GoogleMapsFormFieldMultipleMarkers('multiple_markers', $this->lang['form.googlemap.multiple_markers'], '', array('class' => 'css-class', 'include_api' => false)));
		}

		// AUTH
		$authorizations = new FormFieldsetHTML('authorizations', $this->lang['form.authorization']);
			$auth_settings = new AuthorizationsSettings(array(new ActionAuthorization($this->lang['form.authorization.1'], 1, $this->lang['form.authorization.1.desc']), new ActionAuthorization($this->lang['form.authorization.2'], 2)));
			$auth_settings->build_from_auth_array(array('r1' => 3, 'r0' => 2, 'm1' => 1, '1' => 2));
			$auth_setter = new FormFieldAuthorizationsSetter('auth', $auth_settings);
			$authorizations->add_field($auth_setter);
			$form->add_fieldset($authorizations);

		// VERTICAL FIELDSET
		$vertical_fieldset = new FormFieldsetVertical('vertical_fieldset');
			$vertical_fieldset->set_description($this->lang['form.vertical.desc']);
			$form->add_fieldset($vertical_fieldset);
			$vertical_fieldset->add_field(new FormFieldTextEditor('alone', $this->lang['form.input.text'], $this->lang['form.input.text.lorem'], array('class' => 'css-class')));
			$vertical_fieldset->add_field(new FormFieldCheckbox('cbhor', $this->lang['form.input.checkbox'], FormFieldCheckbox::UNCHECKED, array('class' => 'css-class')));

		// HORIZONTAL FIELDSET
		$horizontal_fieldset = new FormFieldsetHorizontal('horizontal_fieldset');
			$horizontal_fieldset->set_description($this->lang['form.horizontal.desc']);
			$form->add_fieldset($horizontal_fieldset);
			$horizontal_fieldset->add_field(new FormFieldTextEditor('texthor', $this->lang['form.input.text'], $this->lang['form.input.text.lorem'], array('required' => true, 'class' => 'css-class')));
			$horizontal_fieldset->add_field(new FormFieldCheckbox('cbvert', $this->lang['form.input.checkbox'], FormFieldCheckbox::CHECKED, array('class' => 'css-class')));

		// SUBMIT BUTTONS
		$buttons_fieldset = new FormFieldsetSubmit('button_submit');
			$buttons_fieldset->add_element(new FormButtonReset());
			$this->preview_button = new FormButtonSubmit($this->lang['form.preview'], 'previewl', 'alert("Hello world preview")');
			$buttons_fieldset->add_element($this->preview_button);
			$this->submit_button = new FormButtonDefaultSubmit();
			$buttons_fieldset->add_element($this->submit_button);
			$buttons_fieldset->add_element(new FormButtonButton($this->lang['form.button'], 'alert("Hello world");', '', 'button'));
			$form->add_fieldset($buttons_fieldset);

		// FORM CONSTRAINTS
		$form->add_constraint(new FormConstraintFieldsEquality($password, $password_bis));

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
		$graphical_environment->set_page_title($this->common_lang['title.form'], $this->common_lang['sandbox.module.title']);

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->common_lang['sandbox.module.title'], SandboxUrlBuilder::home()->rel());
		$breadcrumb->add($this->common_lang['title.form'], SandboxUrlBuilder::form()->rel());

		return $response;
	}
}
?>
