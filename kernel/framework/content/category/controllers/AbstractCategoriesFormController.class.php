<?php
/*##################################################
 *                             AbstractCategoriesFormController.class.php
 *                            -------------------
 *   begin                : February 06, 2013
 *   copyright            : (C) 2013 Kévin MASSY
 *   email                : kevin.massy@phpboost.com
 *
 *
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

/**
 * @package {@package}
 * @author Kévin MASSY
 * @desc
 */
abstract class AbstractCategoriesFormController extends AdminModuleController
{
	/**
	 * @var HTMLForm
	 */
	protected $form;
	/**
	 * @var FormButtonSubmit
	 */
	private $submit_button;
	
	protected $lang;
	
	/**
	 * @var Category
	 */
	private $category;
	
	public function execute(HTTPRequestCustom $request)
	{
		$this->init();
		$this->build_form();
		
		$tpl = new StringTemplate('# INCLUDE FORM #');
		$tpl->add_lang($this->lang);
		
		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->set_properties();
			$this->save();
			AppContext::get_response()->redirect($this->get_categories_management_url());
		}
		
		$tpl->put('FORM', $this->form->display());
		
		return $this->generate_response($tpl);
	}
	
	private function init()
	{
		$this->lang = LangLoader::get('categories-common');
	}
	
	private function build_form()
	{
		$form = new HTMLForm(__CLASS__);
		
		$fieldset = new FormFieldsetHTML('category', $this->lang['category']);
		$form->add_fieldset($fieldset);
		
		$fieldset->add_field(new FormFieldTextEditor('name', $this->lang['category.form.name'], $this->get_category()->get_name(), array('required' => true)));
		
		$fieldset->add_field(new FormFieldCheckbox('personalize_rewrited_name', $this->lang['category.form.rewrited_name.personalize'], $this->get_category()->rewrited_name_is_personalized(), array(
		'events' => array('click' => '
		if (HTMLForms.getField("personalize_rewrited_name").getValue()) {
			HTMLForms.getField("rewrited_name").enable();
		} else { 
			HTMLForms.getField("rewrited_name").disable();
		}'
		))));
		
		$fieldset->add_field(new FormFieldTextEditor('rewrited_name', $this->lang['category.form.rewrited_name'], $this->get_category()->get_rewrited_name(), array(
			'description' => $this->lang['category.form.rewrited_name.description'], 
			'hidden' => !$this->get_category()->rewrited_name_is_personalized()
		), array(new FormFieldConstraintRegex('`^[a-z0-9\-]+$`i'))));
		
		$search_category_children_options = new SearchCategoryChildrensOptions();
		
		if ($this->get_category()->get_id())
			$search_category_children_options->add_category_in_excluded_categories($this->get_category()->get_id());
			
		$fieldset->add_field($this->get_categories_manager()->get_select_categories_form_field('id_parent', $this->lang['category.form.parent'], $this->get_category()->get_id_parent(), $search_category_children_options));
		
		$this->build_fieldset_options($form);
		
		$fieldset_authorizations = new FormFieldsetHTML('authorizations_fieldset', $this->lang['category.form.authorizations']);
		$form->add_fieldset($fieldset_authorizations);
		
		$root_auth = $this->get_categories_manager()->get_categories_cache()->get_category(Category::ROOT_CATEGORY)->get_auth();
		
		$fieldset_authorizations->add_field(new FormFieldCheckbox('special_authorizations', $this->lang['category.form.authorizations'], (!$this->get_category()->auth_is_empty() && !$this->get_category()->auth_is_equals($root_auth)), 
		array('description' => $this->lang['category.form.authorizations.description'], 'events' => array('click' => '
		if (HTMLForms.getField("special_authorizations").getValue()) {
			$("'.__CLASS__.'_authorizations").appear();
		} else { 
			$("'.__CLASS__.'_authorizations").fade();
		}')
		)));
		
		$auth_settings = $this->get_authorizations_settings();
		$auth_setter = new FormFieldAuthorizationsSetter('authorizations', $auth_settings, array('hidden' => ($this->get_category()->auth_is_empty() || $this->get_category()->auth_is_equals($root_auth))));
		$auth_settings->build_from_auth_array($this->get_category()->get_authorizations());
		$fieldset_authorizations->add_field($auth_setter);
		
		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);
		$form->add_button(new FormButtonReset());
		
		$this->form = $form;
	}
	
	protected function set_properties()
	{
		$this->get_category()->set_name($this->form->get_value('name'));
		$rewrited_name = $this->form->get_value('personalize_rewrited_name') ? $this->form->get_value('rewrited_name') : Url::encode_rewrite($this->get_category()->get_name());
		$this->get_category()->set_rewrited_name($rewrited_name);
		$this->get_category()->set_id_parent($this->form->get_value('id_parent')->get_raw_value());
		$authorizations = $this->form->get_value('special_authorizations') ? $this->form->get_value('authorizations')->build_auth_array() : array();
		$this->get_category()->set_authorizations($authorizations);
	}
	
	private function build_fieldset_options(HTMLForm $form)
	{
		$fieldset = new FormFieldsetHTML('options_fieldset', $this->lang['category.form.options']);
		$this->get_options_fields($fieldset);
		if ($fieldset->get_fields())
		{
			$form->add_fieldset($fieldset);
		}
	}
	
	protected function get_options_fields(FormFieldset $fieldset)
	{
		
	}
	
	/**
	 * Update or add category
	 */
	private function save()
	{
		$category = $this->get_category();
		if ($category->get_id())
		{
			$this->get_categories_manager()->update($category);
		}
		else
		{
			$this->get_categories_manager()->add($category);
		}
	}
	
	/**
	 * @return Category
	 */
	protected function get_category()
	{
		if ($this->category === null)
		{
			$id_category = $this->get_id_category();
			if (!empty($id_category))
			{
				$this->category = $this->get_categories_manager()->get_categories_cache()->get_category($id_category);
			}
			else
			{
				$category_class = $this->get_categories_manager()->get_categories_cache()->get_category_class();
				$this->category =  new $category_class();
			}
		}
		return $this->category;
	}
	
	/**
	 * @return AuthorizationsSettings
	 */
	public function get_authorizations_settings()
	{
		return new AuthorizationsSettings(array(
			new ActionAuthorization($this->lang['category.form.authorizations.read'], Category::READ_AUTHORIZATIONS),
			new ActionAuthorization($this->lang['category.form.authorizations.write'], Category::WRITE_AUTHORIZATIONS),
			new ActionAuthorization($this->lang['category.form.authorizations.contribution'], Category::CONTRIBUTION_AUTHORIZATIONS),
			new ActionAuthorization($this->lang['category.form.authorizations.moderation'], Category::MODERATION_AUTHORIZATIONS),
		));
	}
	
	/**
	 * @return string id category for edit it
	 */
	protected function get_id_category()
	{
		return;
	}
	
	/**
	 * @param View $view
	 * @return Response
	 */
	abstract protected function generate_response(View $view);
	
	/**
	 * @return CategoriesManager
	 */
	abstract protected function get_categories_manager();
	
	/**
	 * @return Url
	 */
	abstract protected function get_categories_management_url();
}
?>