<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2023 01 13
 * @since       PHPBoost 4.0 - 2014 08 24
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class DownloadCategoriesFormController extends DefaultCategoriesFormController
{
	protected function build_form(HTTPRequestCustom $request)
	{
		self::$lang = LangLoader::get_all_langs('download');
		$form = new HTMLForm(__CLASS__);
		$form->set_layout_title($this->get_title());

		$fieldset = new FormFieldsetHTML('category', self::$lang['form.parameters']);
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldTextEditor('name', self::$lang['form.name'], $this->get_category()->get_name(), array('required' => true)));

		$fieldset->add_field(new FormFieldCheckbox('personalize_rewrited_name', self::$lang['form.rewrited.title.personalize'], $this->get_category()->rewrited_name_is_personalized(),
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

		$fieldset->add_field(new FormFieldTextEditor('rewrited_name', self::$lang['form.rewrited.title'], $this->get_category()->get_rewrited_name(),
			array(
				'description' => self::$lang['form.rewrited.title.clue'],
				'hidden' => !$this->get_category()->rewrited_name_is_personalized()
			),
			array(new FormFieldConstraintRegex('`^[a-z0-9\-]+$`iu'))
		));

		if ($this->get_category()->is_allowed_to_have_childs()) {
			$search_category_children_options = new SearchCategoryChildrensOptions();

			if ($this->get_category()->get_id())
				$search_category_children_options->add_category_in_excluded_categories($this->get_category()->get_id());

			$fieldset->add_field(self::$categories_manager->get_select_categories_form_field('id_parent', self::$lang['category.location'], $this->get_category()->get_id_parent(), $search_category_children_options));
		}

		$fieldset->add_field(new FormFieldThumbnail('thumbnail', self::$lang['form.thumbnail'], $this->get_category()->get_thumbnail()->relative(), DownloadCategory::THUMBNAIL_URL,
			array()
		));

		$fieldset->add_field(new FormFieldRichTextEditor('description', self::$lang['form.description'], $this->get_category()->get_description(),
			array()
		));

		$fieldset_authorizations = new FormFieldsetHTML('authorizations_fieldset', self::$lang['form.authorizations']);
		$form->add_fieldset($fieldset_authorizations);

		$fieldset_authorizations->add_field(new FormFieldCheckbox('special_authorizations', self::$lang['form.authorizations'], $this->get_category()->has_special_authorizations(),
			array(
				'description' => self::$lang['category.form.authorizations.clue'],
				'events' => array(
					'click' => '
					if (HTMLForms.getField("special_authorizations").getValue()) {
						jQuery("#' . __CLASS__ . '_authorizations").show();
					} else {
						jQuery("#' . __CLASS__ . '_authorizations").hide();
					}'
				)
			)
		));

		// Hide categories manager authorizations but keep its bit in auth
		$fieldset_authorizations->add_field(new FormFieldFree('hide_authorizations', '', '
			<script>
				<!--
					jQuery(document).ready(function() {
						jQuery("#' . __CLASS__ . '_authorizations > div").eq(4).hide();
					});
				-->
			</script>'
		));

		$auth_settings = new AuthorizationsSettings(array_merge(RootCategory::get_authorizations_settings(), array(
			new ActionAuthorization(self::$lang['download.config.download.link'], DownloadAuthorizationsService::DISPLAY_DOWNLOAD_LINK_AUTHORIZATIONS)
		)));
		$auth_settings->build_from_auth_array($this->get_category()->get_authorizations());
		$fieldset_authorizations->add_field(new FormFieldAuthorizationsSetter('authorizations', $auth_settings, array('hidden' => !$this->get_category()->has_special_authorizations())));

		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);
		$form->add_button(new FormButtonReset());

		$this->form = $form;
	}

	protected function set_properties()
	{
		parent::set_properties();
		$this->get_category()->set_additional_property('description', $this->form->get_value('description'));
		$this->get_category()->set_additional_property('thumbnail', $this->form->get_value('thumbnail'));

		if ($this->form->get_value('special_authorizations'))
		{
			$this->get_category()->set_special_authorizations(true);
			$autorizations = $this->form->get_value('authorizations')->build_auth_array();
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
		if (!DownloadAuthorizationsService::check_authorizations()->manage())
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
	}
}
?>
