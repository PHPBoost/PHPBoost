<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2020 02 17
 * @since       PHPBoost 4.1 - 2015 05 15
 * @contributor mipel <mipel@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class ForumCategoriesFormController extends DefaultCategoriesFormController
{
	protected function build_form(HTTPRequestCustom $request)
	{
		$form = new HTMLForm(__CLASS__);

		$fieldset = new FormFieldsetHTML('category', $this->get_title());
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldSimpleSelectChoice('type', LangLoader::get_message('type', 'main'), $this->get_category()->get_type(),
			array(
				new FormFieldSelectChoiceOption(LangLoader::get_message('category', 'categories-common'), ForumCategory::TYPE_CATEGORY),
				new FormFieldSelectChoiceOption(LangLoader::get_message('forum.module.title', 'common', 'forum'), ForumCategory::TYPE_FORUM),
				new FormFieldSelectChoiceOption(LangLoader::get_message('link', 'admin'), ForumCategory::TYPE_URL)
			), array(
				'events' => array('change' => '
				if (HTMLForms.getField("type").getValue() == ' . ForumCategory::TYPE_CATEGORY . ') {
					HTMLForms.getField("id_parent").disable();
					HTMLForms.getField("description").disable();
					HTMLForms.getField("status").disable();
					HTMLForms.getField("url").disable();
					if (HTMLForms.getField("special_authorizations").getValue()) {
						jQuery("#' . __CLASS__ . '_authorizations > div").eq(1).hide();
						jQuery("#' . __CLASS__ . '_authorizations > div").eq(2).hide();
					}
				} else if (HTMLForms.getField("type").getValue() == ' . ForumCategory::TYPE_FORUM . ') {
					HTMLForms.getField("id_parent").enable();
					HTMLForms.getField("description").enable();
					HTMLForms.getField("status").enable();
					HTMLForms.getField("url").disable();
					if (HTMLForms.getField("special_authorizations").getValue()) {
						jQuery("#' . __CLASS__ . '_authorizations > div").eq(1).show();
						jQuery("#' . __CLASS__ . '_authorizations > div").eq(2).show();
					}
				} else {
					HTMLForms.getField("id_parent").enable();
					HTMLForms.getField("description").enable();
					HTMLForms.getField("status").disable();
					HTMLForms.getField("url").enable();
					if (HTMLForms.getField("special_authorizations").getValue()) {
						jQuery("#' . __CLASS__ . '_authorizations > div").eq(1).hide();
						jQuery("#' . __CLASS__ . '_authorizations > div").eq(2).hide();
					}
				}')
			)
		));

		$fieldset->add_field(new FormFieldTextEditor('name', self::$common_lang['form.name'], $this->get_category()->get_name(), array('required' => true)));

		$fieldset->add_field(new FormFieldCheckbox('personalize_rewrited_name', self::$common_lang['form.rewrited_name.personalize'], $this->get_category()->rewrited_name_is_personalized(),
			array(
				'events' => array('click' => '
					if (HTMLForms.getField("personalize_rewrited_name").getValue()) {
						HTMLForms.getField("rewrited_name").enable();
					} else {
						HTMLForms.getField("rewrited_name").disable();
					}'
				)
			)
		));

		$fieldset->add_field(new FormFieldTextEditor('rewrited_name', self::$common_lang['form.rewrited_name'], $this->get_category()->get_rewrited_name(),
			array(
				'description' => self::$common_lang['form.rewrited_name.description'],
				'hidden' => !$this->get_category()->rewrited_name_is_personalized()
			),
			array(new FormFieldConstraintRegex('`^[a-z0-9\-]+$`iu'))
		));

		$fieldset->add_field(new FormFieldRichTextEditor('description', self::$common_lang['form.description'], $this->get_category()->get_description(),
			array('hidden' => $this->get_category()->get_type() == ForumCategory::TYPE_CATEGORY)
		));

		$search_category_children_options = new SearchCategoryChildrensOptions();
		$search_category_children_options->add_category_in_excluded_categories(Category::ROOT_CATEGORY);

		if ($this->get_category()->get_id())
			$search_category_children_options->add_category_in_excluded_categories($this->get_category()->get_id());

		$fieldset->add_field(self::get_categories_manager()->get_select_categories_form_field('id_parent', self::$common_lang['form.category'], $this->get_category()->get_id_parent(), $search_category_children_options, array('required' => true, 'hidden' => $this->get_category()->get_type() == ForumCategory::TYPE_CATEGORY)));

		$fieldset->add_field(new FormFieldCheckbox('status', LangLoader::get_message('category.status.locked', 'common', 'forum'), $this->get_category()->get_status(),
			array('hidden' => $this->get_category()->get_type() != ForumCategory::TYPE_FORUM)
		));

		$fieldset->add_field(new FormFieldUrlEditor('url', LangLoader::get_message('form.url', 'common'), $this->get_category()->get_url(),
			array('required' => true, 'hidden' => $this->get_category()->get_type() != ForumCategory::TYPE_URL)
		));

		$fieldset_authorizations = new FormFieldsetHTML('authorizations_fieldset', self::$common_lang['authorizations']);
		$form->add_fieldset($fieldset_authorizations);

		$root_auth = self::get_categories_manager()->get_categories_cache()->get_category(Category::ROOT_CATEGORY)->get_authorizations();

		$fieldset_authorizations->add_field(new FormFieldCheckbox('special_authorizations', self::$common_lang['authorizations'], !$this->get_category()->auth_is_equals($root_auth),
		array('description' => self::$lang['category.form.authorizations.description'], 'events' => array('click' => '
		if (HTMLForms.getField("special_authorizations").getValue()) {
			jQuery("#' . __CLASS__ . '_authorizations").show();
			if (HTMLForms.getField("type").getValue() == ' . ForumCategory::TYPE_CATEGORY . ' || HTMLForms.getField("type").getValue() == ' . ForumCategory::TYPE_URL . ') {
				jQuery("#' . __CLASS__ . '_authorizations > div").eq(1).hide();
				jQuery("#' . __CLASS__ . '_authorizations > div").eq(2).hide();
			}
		} else {
			jQuery("#' . __CLASS__ . '_authorizations").hide();
		}')
		)));

		// Autorisations cachées à l'édition Si le type est catégorie ou url
		$fieldset_authorizations->add_field(new FormFieldFree('hide_authorizations', '', '
		<script>
		<!--
		jQuery(document).ready(function() {
			if (HTMLForms.getField("special_authorizations").getValue() && (HTMLForms.getField("type").getValue() == ' . ForumCategory::TYPE_CATEGORY . ' || HTMLForms.getField("type").getValue() == ' . ForumCategory::TYPE_URL . ')) {
				jQuery("#' . __CLASS__ . '_authorizations > div").eq(1).hide();
				jQuery("#' . __CLASS__ . '_authorizations > div").eq(2).hide();
			}
			});
		-->
		</script>'));

		$auth_settings = new AuthorizationsSettings($this->get_authorizations_settings());
		$auth_setter = new FormFieldAuthorizationsSetter('authorizations', $auth_settings, array('hidden' => $this->get_category()->auth_is_equals($root_auth)));
		$auth_settings->build_from_auth_array($this->get_category()->get_authorizations());
		$fieldset_authorizations->add_field($auth_setter);

		$fieldset->add_field(new FormFieldHidden('referrer', $request->get_url_referrer()));
		$fieldset->add_field(new FormFieldHidden('last_topic_id', $this->is_new_category ? 0 : $this->get_category()->get_last_topic_id()));
		
		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);
		$form->add_button(new FormButtonReset());

		$this->form = $form;
	}

	protected function set_properties()
	{
		parent::set_properties();
		$this->get_category()->set_type($this->form->get_value('type')->get_raw_value());
		$this->get_category()->set_additional_property('description', $this->form->get_value('description'));

		if ($this->get_category()->get_type() == ForumCategory::TYPE_URL)
			$this->get_category()->set_additional_property('url', $this->form->get_value('url'));
		else
			$this->get_category()->set_additional_property('url', '');

		if ($this->get_category()->get_type() == ForumCategory::TYPE_FORUM)
			$status = $this->form->get_value('status');
		else
			$status = ForumCategory::STATUS_UNLOCKED;

		$this->get_category()->set_additional_property('status', $status);
		$this->get_category()->set_additional_property('last_topic_id', (int)$this->form->get_value('last_topic_id'));

		if ($this->form->get_value('special_authorizations'))
		{
			$this->get_category()->set_special_authorizations(true);
			$autorizations = $this->form->get_value('authorizations')->build_auth_array();
			if ($this->get_category()->get_type() != ForumCategory::TYPE_FORUM)
			{
				foreach ($autorizations as $id => $auth)
				{
					$new_auth = ($autorizations[$id] > Category::MODERATION_AUTHORIZATIONS) ? ($autorizations[$id] - Category::MODERATION_AUTHORIZATIONS) : $autorizations[$id];
					$new_auth = ($new_auth > Category::WRITE_AUTHORIZATIONS) ? ($new_auth - Category::WRITE_AUTHORIZATIONS) : $new_auth;

					if ($new_auth == 1)
						$autorizations[$id] = $new_auth;
					else
						unset($autorizations[$id]);
				}
			}
		}
		else
		{
			$this->get_category()->set_special_authorizations(false);
			$autorizations = array();
		}

		$this->get_category()->set_authorizations($autorizations);
	}

	protected function check_authorizations()
	{
		if (!ForumAuthorizationsService::check_authorizations()->manage_categories())
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
	}
}
?>
