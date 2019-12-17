<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Patrick DUBEAU <daaxwizeman@gmail.com>
 * @version     PHPBoost 5.3 - last update: 2019 11 02
 * @since       PHPBoost 4.0 - 2013 02 27
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class AdminArticlesConfigController extends AdminModuleController
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
	 * @var ArticlesConfig
	 */
	private $config;
	private $comments_config;
	private $content_management_config;

	public function execute(HTTPRequestCustom $request)
	{
		$this->init();

		$this->build_form();

		$tpl = new StringTemplate('# INCLUDE MSG # # INCLUDE FORM #');
		$tpl->add_lang($this->lang);

		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->save();
			$tpl->put('MSG', MessageHelper::display(LangLoader::get_message('message.success.config', 'status-messages-common'), MessageHelper::SUCCESS, 4));
		}

		$tpl->put('FORM', $this->form->display());

		return new AdminArticlesDisplayResponse($tpl, $this->lang['articles.module.config.title']);
	}

	private function init()
	{
		$this->lang = LangLoader::get('common', 'articles');
		$this->admin_common_lang = LangLoader::get('admin-common');
		$this->config = ArticlesConfig::load();
		$this->comments_config = CommentsConfig::load();
		$this->content_management_config = ContentManagementConfig::load();
	}

	private function build_form()
	{
		$form = new HTMLForm(__CLASS__);

		$fieldset = new FormFieldsetHTMLHeading('articles_configuration', $this->lang['articles.module.config.title']);
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldNumberEditor('number_articles_per_page', $this->admin_common_lang['config.items_number_per_page'], $this->config->get_number_articles_per_page(),
			array('min' => 1, 'max' => 50, 'required' => true),
			array(new FormFieldConstraintIntegerRange(1, 50))
		));

		$fieldset->add_field(new FormFieldNumberEditor('number_categories_per_page', $this->admin_common_lang['config.categories_number_per_page'], $this->config->get_number_categories_per_page(),
			array('min' => 1, 'max' => 50, 'required' => true),
			array(new FormFieldConstraintIntegerRange(1, 50))
		));

		$fieldset->add_field(new FormFieldNumberEditor('number_cols_display_per_line', $this->admin_common_lang['config.columns_number_per_line'], $this->config->get_number_cols_display_per_line(),
			array('min' => 1, 'max' => 4, 'required' => true, 'description' => $this->admin_common_lang['config.columns_number_per_line.description']),
			array(new FormFieldConstraintIntegerRange(1, 4))
		));

		$fieldset->add_field(new FormFieldSimpleSelectChoice('items_default_sort', $this->admin_common_lang['config.items_default_sort'], $this->config->get_items_default_sort_field() . '-' . $this->config->get_items_default_sort_mode(), $this->get_sort_options()));

		$fieldset->add_field(new FormFieldCheckbox('display_icon_cats', $this->lang['articles.display.categories.icon'], $this->config->are_cats_icon_enabled(),
			array('class' => 'custom-checkbox')
		));

		$fieldset->add_field(new FormFieldSimpleSelectChoice('display_type', $this->admin_common_lang['config.display.type'], $this->config->get_display_type(),
			array(
				new FormFieldSelectChoiceOption($this->admin_common_lang['config.display.type.grid'], ArticlesConfig::DISPLAY_GRID_VIEW),
				new FormFieldSelectChoiceOption($this->admin_common_lang['config.display.type.list'], ArticlesConfig::DISPLAY_LIST_VIEW)
			)
		));

		$fieldset->add_field(new FormFieldNumberEditor('number_character_to_cut', $this->lang['articles.characters.number.to.cut'], $this->config->get_number_character_to_cut(),
			array('min' => 20, 'max' => 1000, 'required' => true),
			array(new FormFieldConstraintIntegerRange(20, 1000))
		));

		$fieldset->add_field(new FormFieldCheckbox('display_descriptions_to_guests', $this->lang['articles.display.decriptions.to.guests'], $this->config->are_descriptions_displayed_to_guests(),
			array('class' => 'custom-checkbox')
		));

		$fieldset->add_field(new FormFieldRichTextEditor('root_category_description', $this->admin_common_lang['config.root_category_description'], $this->config->get_root_category_description(),
			array('rows' => 8, 'cols' => 47)
		));

		$fieldset->add_field(new FormFieldRichTextEditor('default_contents', $this->lang['articles.default.contents'], $this->config->get_default_contents(),
			array('rows' => 8, 'cols' => 47)
		));

		$fieldset_authorizations = new FormFieldsetHTML('authorizations', LangLoader::get_message('authorizations', 'common'),
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
			new FormFieldSelectChoiceOption($common_lang['form.date.creation'] . ' - ' . $common_lang['sort.asc'], Article::SORT_DATE . '-' . Article::ASC),
			new FormFieldSelectChoiceOption($common_lang['form.date.creation'] . ' - ' . $common_lang['sort.desc'], Article::SORT_DATE . '-' . Article::DESC),
			new FormFieldSelectChoiceOption($common_lang['sort_by.alphabetic'] . ' - ' . $common_lang['sort.asc'], Article::SORT_ALPHABETIC . '-' . Article::ASC),
			new FormFieldSelectChoiceOption($common_lang['sort_by.alphabetic'] . ' - ' . $common_lang['sort.desc'], Article::SORT_ALPHABETIC . '-' . Article::DESC),
			new FormFieldSelectChoiceOption($common_lang['sort_by.number_views'] . ' - ' . $common_lang['sort.asc'], Article::SORT_NUMBER_VIEWS . '-' . Article::ASC),
			new FormFieldSelectChoiceOption($common_lang['sort_by.number_views'] . ' - ' . $common_lang['sort.desc'], Article::SORT_NUMBER_VIEWS . '-' . Article::DESC),
			new FormFieldSelectChoiceOption($common_lang['author'] . ' - ' . $common_lang['sort.asc'], Article::SORT_AUTHOR . '-' . Article::ASC),
			new FormFieldSelectChoiceOption($common_lang['author'] . ' - ' . $common_lang['sort.desc'], Article::SORT_AUTHOR . '-' . Article::DESC)
		);

		if ($this->comments_config->module_comments_is_enabled('articles'))
		{
			$sort_options[] = new FormFieldSelectChoiceOption($common_lang['sort_by.number_comments'] . ' - ' . $common_lang['sort.asc'], Article::SORT_NUMBER_COMMENTS . '-' . Article::ASC);
			$sort_options[] = new FormFieldSelectChoiceOption($common_lang['sort_by.number_comments'] . ' - ' . $common_lang['sort.desc'], Article::SORT_NUMBER_COMMENTS . '-' . Article::DESC);
		}

		if ($this->content_management_config->module_notation_is_enabled('articles'))
		{
			$sort_options[] = new FormFieldSelectChoiceOption($common_lang['sort_by.best_note'] . ' - ' . $common_lang['sort.asc'], Article::SORT_NOTATION . '-' . Article::ASC);
			$sort_options[] = new FormFieldSelectChoiceOption($common_lang['sort_by.best_note'] . ' - ' . $common_lang['sort.desc'], Article::SORT_NOTATION . '-' . Article::DESC);
		}

		return $sort_options;
	}

	private function save()
	{
		$this->config->set_number_articles_per_page($this->form->get_value('number_articles_per_page'));
		$this->config->set_number_cols_display_per_line($this->form->get_value('number_cols_display_per_line'));

		$items_default_sort = $this->form->get_value('items_default_sort')->get_raw_value();
		$items_default_sort = explode('-', $items_default_sort);
		$this->config->set_items_default_sort_field($items_default_sort[0]);
		$this->config->set_items_default_sort_mode(TextHelper::strtolower($items_default_sort[1]));

		if ($this->form->get_value('display_icon_cats'))
		{
			$this->config->enable_cats_icon();
		}
		else
		{
			$this->config->disable_cats_icon();
		}

		$this->config->set_number_categories_per_page($this->form->get_value('number_categories_per_page'));
		$this->config->set_number_character_to_cut($this->form->get_value('number_character_to_cut', $this->config->get_number_character_to_cut()));

		if ($this->form->get_value('display_descriptions_to_guests'))
		{
			$this->config->display_descriptions_to_guests();
		}
		else
		{
			$this->config->hide_descriptions_to_guests();
		}

		$this->config->set_display_type($this->form->get_value('display_type')->get_raw_value());
		$this->config->set_root_category_description($this->form->get_value('root_category_description'));
		$this->config->set_default_contents($this->form->get_value('default_contents'));
		$this->config->set_authorizations($this->form->get_value('authorizations')->build_auth_array());

		ArticlesConfig::save();
		CategoriesService::get_categories_manager()->regenerate_cache();
	}
}
?>
