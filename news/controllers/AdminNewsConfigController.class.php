<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 11 04
 * @since       PHPBoost 4.0 - 2013 02 13
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
 * @contributor Mipel <mipel@phpboost.com>
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
			$this->form->get_field_by_id('display_descriptions_to_guests')->set_hidden(!$this->config->get_display_condensed_enabled());
			$this->form->get_field_by_id('number_character_to_cut')->set_hidden(!$this->config->get_display_condensed_enabled());
			$tpl->put('MSG', MessageHelper::display(LangLoader::get_message('message.success.config', 'status-messages-common'), MessageHelper::SUCCESS, 5));
		}

		$tpl->put('FORM', $this->form->display());

		return new AdminNewsDisplayResponse($tpl, $this->lang['module_config_title']);
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

		$fieldset = new FormFieldsetHTMLHeading('config', $this->admin_common_lang['configuration']);
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldNumberEditor('number_news_per_page', $this->admin_common_lang['config.items_number_per_page'], $this->config->get_number_news_per_page(),
			array('min' => 1, 'max' => 50, 'required' => true, 'class' => 'third-field'),
			array(new FormFieldConstraintIntegerRange(1, 50))
		));

		$fieldset->add_field(new FormFieldNumberEditor('number_columns_display_news', $this->admin_common_lang['config.columns_number_per_line'], $this->config->get_number_columns_display_news(),
			array('min' => 1, 'max' => 4, 'required' => true, 'class' => 'third-field'),
			array(new FormFieldConstraintIntegerRange(1, 4))
		));

		$fieldset->add_field(new FormFieldSimpleSelectChoice('display_type', $this->admin_common_lang['config.display.type'], $this->config->get_display_type(),
			array(
				new FormFieldSelectChoiceOption($this->admin_common_lang['config.display.type.grid'], NewsConfig::DISPLAY_GRID_VIEW),
				new FormFieldSelectChoiceOption($this->admin_common_lang['config.display.type.list'], NewsConfig::DISPLAY_LIST_VIEW),
			),
			array('class' => 'third-field')
		));

		$fieldset->add_field(new FormFieldCheckbox('author_displayed', $this->admin_common_lang['config.author_displayed'], $this->config->get_author_displayed(),
			array('class' => 'third-field custom-checkbox')
		));

		$fieldset->add_field(new FormFieldCheckbox('nb_view_enabled', $this->lang['admin.config.news_number_view_enabled'], $this->config->get_nb_view_enabled(),
			array('class' => 'third-field custom-checkbox')
		));

		$fieldset->add_field(new FormFieldCheckbox('news_suggestions_enabled', $this->lang['admin.config.news_suggestions_enabled'], $this->config->get_news_suggestions_enabled(),
			array('class' => 'third-field custom-checkbox')
		));

		$fieldset->add_field(new FormFieldCheckbox('display_condensed', $this->lang['admin.config.display_condensed'], $this->config->get_display_condensed_enabled(),
			array(
				'class' => 'third-field custom-checkbox',
				'events' => array('click' => '
					if (HTMLForms.getField("display_condensed").getValue()) {
						HTMLForms.getField("display_descriptions_to_guests").enable();
						HTMLForms.getField("number_character_to_cut").enable();
					} else {
						HTMLForms.getField("display_descriptions_to_guests").disable();
						HTMLForms.getField("number_character_to_cut").disable();
					}'
				)
			)
		));

		$fieldset->add_field(new FormFieldCheckbox('display_descriptions_to_guests', $this->lang['admin.config.display_descriptions_to_guests'], $this->config->are_descriptions_displayed_to_guests(),
			array('hidden' => !$this->config->get_display_condensed_enabled(), 'class' => 'third-field custom-checkbox')
		));

		$fieldset->add_field(new FormFieldNumberEditor('number_character_to_cut', $this->lang['admin.config.number_character_to_cut'], $this->config->get_number_character_to_cut(),
			array('min' => 20, 'max' => 1000, 'required' => true, 'hidden' => !$this->config->get_display_condensed_enabled(), 'class' => 'third-field'),
			array(new FormFieldConstraintIntegerRange(20, 1000)
		)));

                $fieldset->add_field(new FormFieldRichTextEditor('default_contents', $this->lang['news.default.contents'], $this->config->get_default_contents(),
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
		$this->config->set_number_news_per_page($this->form->get_value('number_news_per_page'));
		$this->config->set_number_columns_display_news($this->form->get_value('number_columns_display_news'));
		$this->config->set_display_condensed_enabled($this->form->get_value('display_condensed'));

		if ($this->config->get_display_condensed_enabled())
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

		$this->config->set_number_character_to_cut($this->form->get_value('number_character_to_cut', $this->config->get_number_character_to_cut()));
		$this->config->set_news_suggestions_enabled($this->form->get_value('news_suggestions_enabled'));
		$this->config->set_author_displayed($this->form->get_value('author_displayed'));
		$this->config->set_nb_view_enabled($this->form->get_value('nb_view_enabled'));
		$this->config->set_display_type($this->form->get_value('display_type')->get_raw_value());
                $this->config->set_default_contents($this->form->get_value('default_contents'));
		$this->config->set_authorizations($this->form->get_value('authorizations')->build_auth_array());
		NewsConfig::save();
		CategoriesService::get_categories_manager()->regenerate_cache();
	}
}
?>
