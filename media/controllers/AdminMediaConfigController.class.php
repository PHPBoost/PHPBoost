<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 03 31
 * @since       PHPBoost 4.1 - 2015 02 03
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
 * @contributor xela <xela@phpboost.com>
*/

class AdminMediaConfigController extends AdminModuleController
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
	private $config_lang;

	/**
	 * @var MediaConfig
	 */
	private $config;

	public function execute(HTTPRequestCustom $request)
	{
		$this->init();

		$this->build_form();

		$view = new StringTemplate('# INCLUDE MSG # # INCLUDE FORM #');
		$view->add_lang($this->lang);

		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->save();
			$this->form->get_field_by_id('items_per_row')->set_hidden($this->config->get_display_type() !== MediaConfig::GRID_VIEW);
			$view->put('MSG', MessageHelper::display(LangLoader::get_message('message.success.config', 'status-messages-common'), MessageHelper::SUCCESS, 5));
		}

		$view->put('FORM', $this->form->display());

		return new DefaultAdminDisplayResponse($view);
	}

	private function init()
	{
		$this->config = MediaConfig::load();
		$this->lang = LangLoader::get('common', 'media');
		$this->config_lang = LangLoader::get('admin-common');
	}

	private function build_form()
	{
		$form = new HTMLForm(__CLASS__);

		$fieldset = new FormFieldsetHTML('configuration', StringVars::replace_vars(LangLoader::get_message('configuration.module.title', 'admin-common'), array('module_name' => self::get_module()->get_configuration()->get_name())));
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldNumberEditor('categories_per_page', $this->config_lang['config.categories.per.page'], $this->config->get_categories_per_page(),
			array('min' => 1, 'max' => 50, 'required' => true),
			array(new FormFieldConstraintIntegerRange(1, 50))
		));

		$fieldset->add_field(new FormFieldNumberEditor('categories_per_row', $this->config_lang['config.categories.per.row'], $this->config->get_categories_per_row(),
			array('min' => 1, 'max' => 4, 'required' => true),
			array(new FormFieldConstraintIntegerRange(1, 4))
		));

		$fieldset->add_field(new FormFieldSimpleSelectChoice('root_category_content_type', $this->lang['config.root.category.media.content.type'], $this->config->get_root_category_content_type(),
			array(
				new FormFieldSelectChoiceOption($this->lang['media.content.type.music.and.video'], MediaConfig::CONTENT_TYPE_MUSIC_AND_VIDEO),
				new FormFieldSelectChoiceOption($this->lang['media.content.type.music'], MediaConfig::CONTENT_TYPE_MUSIC),
				new FormFieldSelectChoiceOption($this->lang['media.content.type.video'], MediaConfig::CONTENT_TYPE_VIDEO)
			)
		));

		$fieldset->add_field(new FormFieldRichTextEditor('root_category_description', $this->config_lang['config.root_category_description'], $this->config->get_root_category_description(),
			array('rows' => 8, 'cols' => 47)
		));

		$fieldset->add_field(new FormFieldNumberEditor('items_per_page', $this->config_lang['config.items_per_page'], $this->config->get_items_per_page(),
			array('min' => 1, 'max' => 50, 'required' => true),
			array(new FormFieldConstraintIntegerRange(1, 50))
		));

		$fieldset->add_field(new FormFieldNumberEditor('max_video_width', $this->lang['config.max.video.width'], $this->config->get_max_video_width(),
			array('min' => 50, 'max' => 2000, 'required' => true),
			array(new FormFieldConstraintIntegerRange(50, 2000))
		));

		$fieldset->add_field(new FormFieldNumberEditor('max_video_height', $this->lang['config.max.video.height'], $this->config->get_max_video_height(),
			array('min' => 50, 'max' => 2000, 'required' => true),
			array(new FormFieldConstraintIntegerRange(50, 2000))
		));

		$fieldset->add_field(new FormFieldCheckbox('author_displayed', $this->config_lang['config.author.displayed'], $this->config->is_author_displayed(),
			array('class' => 'custom-checkbox')
		));

		$fieldset->add_field(new FormFieldSimpleSelectChoice('display_type', $this->config_lang['config.display.type'], $this->config->get_display_type(),
			array(
				new FormFieldSelectChoiceOption($this->config_lang['config.display.type.grid'], MediaConfig::GRID_VIEW, array('data_option_icon' => 'fa fa-th-large')),
				new FormFieldSelectChoiceOption($this->config_lang['config.display.type.list'], MediaConfig::LIST_VIEW, array('data_option_icon' => 'fa fa-list')),
			),
			array(
				'select_to_list' => true,
				'events' => array('change' => '
					if (HTMLForms.getField("display_type").getValue() == \'' . MediaConfig::GRID_VIEW . '\') {
						HTMLForms.getField("items_per_row").enable();
					} else {
						HTMLForms.getField("items_per_row").disable();
					}'
				)
			)
		));

		$fieldset->add_field(new FormFieldNumberEditor('items_per_row', $this->config_lang['config.items.per.row'], $this->config->get_items_per_row(),
			array(
				'min' => 1, 'max' => 4, 'required' => true,
				'hidden' => $this->config->get_display_type() !== MediaConfig::GRID_VIEW
			),
			array(new FormFieldConstraintIntegerRange(1, 4))
		));

		$fieldset_constant = new FormFieldsetHTML('constant_host', $this->lang['config.constant.host']);
		$form->add_fieldset($fieldset_constant);

		$fieldset_constant->add_field(new FormFieldTextEditor('peertube_constant', $this->lang['config.constant.host.peertube'], $this->config->get_peertube_constant(),
			array('description' => $this->lang['config.constant.host.peertube.desc'])
		));

		$fieldset_authorizations = new FormFieldsetHTML('authorizations_fieldset', LangLoader::get_message('authorizations', 'common'),
			array('description' => $this->config_lang['config.authorizations.explain'])
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
		$this->config->set_categories_per_page($this->form->get_value('categories_per_page'));
		$this->config->set_categories_per_row($this->form->get_value('categories_per_row'));
		$this->config->set_items_per_page($this->form->get_value('items_per_page'));
		$this->config->set_items_per_row($this->form->get_value('items_per_row'));
		$this->config->set_display_type($this->form->get_value('display_type')->get_raw_value());

		if ($this->form->get_value('author_displayed'))
			$this->config->display_author();
		else
			$this->config->hide_author();

		$this->config->set_max_video_width($this->form->get_value('max_video_width'));
		$this->config->set_max_video_height($this->form->get_value('max_video_height'));
		$this->config->set_root_category_description($this->form->get_value('root_category_description'));
		$this->config->set_root_category_content_type($this->form->get_value('root_category_content_type')->get_raw_value());
		$this->config->set_authorizations($this->form->get_value('authorizations')->build_auth_array());

		$this->config->set_peertube_constant($this->form->get_value('peertube_constant'));

		MediaConfig::save();
		CategoriesService::get_categories_manager()->regenerate_cache();
	}
}
?>
