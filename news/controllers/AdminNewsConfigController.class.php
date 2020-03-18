<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2020 03 18
 * @since       PHPBoost 4.0 - 2013 02 13
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
 * @contributor Mipel <mipel@phpboost.com>
 * @contributor xela <xela@phpboost.com>
*/

class AdminNewsConfigController extends AdminModuleController
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
	 * @var NewsConfig
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
			$this->form->get_field_by_id('characters_number_to_cut')->set_hidden($this->config->get_full_item_display() && $this->config->get_display_type() !== NewsConfig::GRID_VIEW);
			$this->form->get_field_by_id('items_per_row')->set_hidden($this->config->get_display_type() !== NewsConfig::GRID_VIEW);
			$this->form->get_field_by_id('full_item_display')->set_hidden($this->config->get_display_type() !== NewsConfig::LIST_VIEW);
			$tpl->put('MSG', MessageHelper::display(LangLoader::get_message('message.success.config', 'status-messages-common'), MessageHelper::SUCCESS, 5));
		}

		$tpl->put('FORM', $this->form->display());

		return new DefaultAdminDisplayResponse($tpl);
	}

	private function init()
	{
		$this->lang = LangLoader::get('common', 'news');
		$this->admin_common_lang = LangLoader::get('admin-common');
		$this->config = NewsConfig::load();
	}

	private function build_form()
	{
		$form = new HTMLForm(__CLASS__);

		$fieldset = new FormFieldsetHTMLHeading('configuration', StringVars::replace_vars(LangLoader::get_message('configuration.module.title', 'admin-common'), array('module_name' => self::get_module()->get_configuration()->get_name())));
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldNumberEditor('items_per_page', $this->admin_common_lang['config.items_number_per_page'], $this->config->get_items_per_page(),
			array('min' => 1, 'max' => 50, 'required' => true),
			array(new FormFieldConstraintIntegerRange(1, 50))
		));

		$fieldset->add_field(new FormFieldCheckbox('author_displayed', $this->admin_common_lang['config.author.displayed'], $this->config->get_author_displayed(),
			array('class' => 'custom-checkbox')
		));

		$fieldset->add_field(new FormFieldCheckbox('views_number', $this->admin_common_lang['config.views.number.enabled'], $this->config->get_views_number(),
			array('class' => 'custom-checkbox')
		));

		$fieldset->add_field(new FormFieldCheckbox('news_suggestions_enabled', $this->lang['config.news.suggestions.enabled'], $this->config->get_news_suggestions_enabled(),
			array('class' => 'custom-checkbox')
		));

		$fieldset->add_field(new FormFieldCheckbox('summary_display_to_guests', $this->admin_common_lang['config.display.summary.to.guests'], $this->config->is_summary_displayed_to_guests(),
			array('class' => 'custom-checkbox')
		));

		$fieldset->add_field(new FormFieldSimpleSelectChoice('display_type', $this->admin_common_lang['config.display.type'], $this->config->get_display_type(),
			array(
				new FormFieldSelectChoiceOption($this->admin_common_lang['config.display.type.grid'], NewsConfig::GRID_VIEW, array('data_option_icon' => 'fa fa-th-large')),
				new FormFieldSelectChoiceOption($this->admin_common_lang['config.display.type.list'], NewsConfig::LIST_VIEW, array('data_option_icon' => 'fa fa-list')),
			),
			array(
				'select_to_list' => true,
				'events' => array('change' => '
					if (HTMLForms.getField("display_type").getValue() == \'' . NewsConfig::GRID_VIEW . '\') {
						HTMLForms.getField("items_per_row").enable();
						HTMLForms.getField("full_item_display").disable();
					} else {
						HTMLForms.getField("items_per_row").disable();
						HTMLForms.getField("full_item_display").enable();
					}'
				)
			)
		));

		$fieldset->add_field(new FormFieldNumberEditor('items_per_row', $this->admin_common_lang['config.items.per.row'], $this->config->get_items_per_row(),
			array(
				'min' => 1, 'max' => 4, 'required' => true,
				'hidden' => $this->config->get_display_type() !== NewsConfig::GRID_VIEW
			),
			array(new FormFieldConstraintIntegerRange(1, 4))
		));

		$fieldset->add_field(new FormFieldCheckbox('full_item_display', $this->admin_common_lang['config.full.item.display'], $this->config->get_full_item_display(),
			array(
				'class' => 'custom-checkbox',
				'hidden' => $this->config->get_display_type() !== NewsConfig::LIST_VIEW,
				'events' => array('click' => '
					if (HTMLForms.getField("full_item_display").getValue()) {
						HTMLForms.getField("characters_number_to_cut").disable();
					} else {
						HTMLForms.getField("characters_number_to_cut").enable();
					}'
				)
			)
		));

		// $fieldset->add_field(new FormFieldSpacer('full_item', ''));

		$fieldset->add_field(new FormFieldNumberEditor('characters_number_to_cut', $this->admin_common_lang['config.characters.number.to.cut'], $this->config->get_characters_number_to_cut(),
			array(
				'min' => 20, 'max' => 1000, 'required' => true,
				'hidden' => $this->config->get_display_type() == NewsConfig::LIST_VIEW && $this->config->get_full_item_display()
			),
			array(new FormFieldConstraintIntegerRange(20, 1000)
		)));

                $fieldset->add_field(new FormFieldRichTextEditor('default_contents', $this->admin_common_lang['config.item.default.content'], $this->config->get_default_contents(),
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

	private function save()
	{
		$this->config->set_items_per_page($this->form->get_value('items_per_page'));

		if($this->config->get_display_type() == NewsConfig::GRID_VIEW)
			$this->config->set_items_per_row($this->form->get_value('items_per_row'));

		$this->config->set_full_item_display($this->form->get_value('full_item_display'));

		if ($this->config->get_full_item_display())
		{
			if ($this->form->get_value('summary_display_to_guests'))
			{
				$this->config->display_summary_to_guests();
			}
			else
			{
				$this->config->hide_summary_to_guests();
			}
		}

		$this->config->set_characters_number_to_cut($this->form->get_value('characters_number_to_cut', $this->config->get_characters_number_to_cut()));
		$this->config->set_news_suggestions_enabled($this->form->get_value('news_suggestions_enabled'));
		$this->config->set_author_displayed($this->form->get_value('author_displayed'));
		$this->config->set_views_number($this->form->get_value('views_number'));
		$this->config->set_display_type($this->form->get_value('display_type')->get_raw_value());
        $this->config->set_default_contents($this->form->get_value('default_contents'));
		$this->config->set_authorizations($this->form->get_value('authorizations')->build_auth_array());
		NewsConfig::save();
		CategoriesService::get_categories_manager()->regenerate_cache();
	}
}
?>
