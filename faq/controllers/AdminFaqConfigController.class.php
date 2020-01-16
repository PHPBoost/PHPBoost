<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2020 01 16
 * @since       PHPBoost 4.0 - 2014 09 02
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class AdminFaqConfigController extends AdminModuleController
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
	 * @var FaqConfig
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
			$tpl->put('MSG', MessageHelper::display(LangLoader::get_message('message.success.config', 'status-messages-common'), MessageHelper::SUCCESS, 5));
		}

		$tpl->put('FORM', $this->form->display());

		return new DefaultAdminDisplayResponse($tpl);
	}

	private function init()
	{
		$this->config = FaqConfig::load();
		$this->lang = LangLoader::get('common', 'faq');
		$this->admin_common_lang = LangLoader::get('admin-common');
	}

	private function build_form()
	{
		$form = new HTMLForm(__CLASS__);

		$fieldset = new FormFieldsetHTMLHeading('configuration', StringVars::replace_vars(LangLoader::get_message('configuration.module.title', 'admin-common'), array('module_name' => self::get_module()->get_configuration()->get_name())));
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldNumberEditor('categories_number_per_page', $this->admin_common_lang['config.categories_number_per_page'], $this->config->get_categories_number_per_page(),
			array('min' => 1, 'max' => 50, 'required' => true),
			array(new FormFieldConstraintIntegerRange(1, 50))
		));

		$fieldset->add_field(new FormFieldNumberEditor('columns_number_per_line', $this->admin_common_lang['config.columns_number_per_line'], $this->config->get_columns_number_per_line(),
			array('min' => 1, 'max' => 4, 'required' => true),
			array(new FormFieldConstraintIntegerRange(1, 4))
		));

		$fieldset->add_field(new FormFieldSimpleSelectChoice('display_type', $this->lang['config.display.type.accordion'], $this->config->get_display_type(),
			array(
				new FormFieldSelectChoiceOption($this->lang['config.display.type.basic'], FaqConfig::DISPLAY_TYPE_BASIC),
				new FormFieldSelectChoiceOption($this->lang['config.display.type.siblings'], FaqConfig::DISPLAY_TYPE_SIBLINGS)
			)
		));

		$fieldset->add_field(new FormFieldCheckbox('display_controls', $this->lang['config.display.controls'], $this->config->are_control_buttons_displayed(),
			array('class' => 'custom-checkbox', 'description' => $this->lang['config.display.controls.explain'] )
		));

		$fieldset->add_field(new FormFieldSimpleSelectChoice('items_default_sort', $this->admin_common_lang['config.items_default_sort'], $this->config->get_items_default_sort_field() . '-' . $this->config->get_items_default_sort_mode(), $this->get_sort_options(),
			array('description' => $this->lang['config.items.default.sort.explain'])
		));

		$fieldset->add_field(new FormFieldRichTextEditor('root_category_description', $this->admin_common_lang['config.root_category_description'], $this->config->get_root_category_description(),
			array('rows' => 8, 'cols' => 47)
		));

		$fieldset_authorizations = new FormFieldsetHTML('authorizations_fieldset', LangLoader::get_message('authorizations', 'common'),
			array('description' => $this->admin_common_lang['config.authorizations.explain'])
		);
		$form->add_fieldset($fieldset_authorizations);

		$auth_settings = new AuthorizationsSettings(RootCategory::get_authorizations_settings());
		$auth_settings->build_from_auth_array($this->config->get_authorizations());
		$fieldset_authorizations->add_field(new FormFieldAuthorizationsSetter('authorizations', $auth_settings));

		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);
		$form->add_button(new FormButtonReset());

		$this->form = $form;
	}

	private function get_sort_options()
	{
		$common_lang = LangLoader::get('common');

		$sort_options = array(
			new FormFieldSelectChoiceOption($common_lang['form.date.creation'] . ' - ' . $common_lang['sort.asc'], FaqQuestion::SORT_DATE . '-' . FaqQuestion::ASC),
			new FormFieldSelectChoiceOption($common_lang['form.date.creation'] . ' - ' . $common_lang['sort.desc'], FaqQuestion::SORT_DATE . '-' . FaqQuestion::DESC),
			new FormFieldSelectChoiceOption($common_lang['sort_by.alphabetic'] . ' - ' . $common_lang['sort.asc'], FaqQuestion::SORT_ALPHABETIC . '-' . FaqQuestion::ASC),
			new FormFieldSelectChoiceOption($common_lang['sort_by.alphabetic'] . ' - ' . $common_lang['sort.desc'], FaqQuestion::SORT_ALPHABETIC . '-' . FaqQuestion::DESC)
		);

		return $sort_options;
	}

	private function save()
	{
		$this->config->set_categories_number_per_page($this->form->get_value('categories_number_per_page'));
		$this->config->set_columns_number_per_line($this->form->get_value('columns_number_per_line'));
		$this->config->set_display_type($this->form->get_value('display_type')->get_raw_value());
		if($this->form->get_value('display_controls'))
			$this->config->display_control_buttons();
		else
			$this->config->hide_control_buttons();

		$items_default_sort = $this->form->get_value('items_default_sort')->get_raw_value();
		$items_default_sort = explode('-', $items_default_sort);
		$this->config->set_items_default_sort_field($items_default_sort[0]);
		$this->config->set_items_default_sort_mode(TextHelper::strtolower($items_default_sort[1]));

		$this->config->set_root_category_description($this->form->get_value('root_category_description'));
		$this->config->set_authorizations($this->form->get_value('authorizations')->build_auth_array());

		FaqConfig::save();
		CategoriesService::get_categories_manager()->regenerate_cache();
		FaqCache::invalidate();
	}
}
?>
