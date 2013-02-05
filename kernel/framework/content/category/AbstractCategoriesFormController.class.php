<?php
abstract class AbstractCategoriesFormController //extends AdminModuleController
extends AbstractController
{
	/**
	 * @var HTMLForm
	 */
	protected $form;
	/**
	 * @var FormButtonSubmit
	 */
	private $submit_button;
	
	private $lang;
	
	/**
	 * @var Category
	 */
	private $category;
	
	public function execute(HTTPRequestCustom $request)
	{
		$this->init();
		$this->build_form();
		
		$tpl = new StringTemplate('# INCLUDE FORM #');
		//$tpl->add_lang($this->lang);
		
		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->set_properties();
			$this->save();
		}
		
		$tpl->put('FORM', $this->form->display());
		
		return $this->generate_response($tpl);
	}
	
	private function init()
	{
		//$this->lang = LangLoader::get('categories');
	}
	
	private function build_form()
	{
		$form = new HTMLForm(__CLASS__);
		
		$fieldset = new FormFieldsetHTML('category', 'Categorie');
		$form->add_fieldset($fieldset);
		
		$fieldset->add_field(new FormFieldTextEditor('name', 'Nom', $this->get_category()->get_name(), array('required' => true)));
		
		$search_category_children_options = new SearchCategoryChildrensOptions();
		// TODO Ne pas afficher la categorie actuelle en édition
		$fieldset->add_field($this->get_categories_manager()->get_select_categories_form_field('id_parent', 'Parent', $this->get_category()->get_id_parent(), $search_category_children_options));
		
		$fieldset->add_field(new FormFieldCheckbox('visible', 'Visible', $this->get_category()->is_visible()));
		
		$this->build_fieldset_options($form);
		
		$fieldset_authorizations = new FormFieldsetHTML('authorizations_fieldset', 'Autorisations');
		$form->add_fieldset($fieldset_authorizations);
		
		$fieldset_authorizations->add_field(new FormFieldCheckbox('special_authorizations', 'Autorisations spéciales', !$this->get_category()->auth_is_empty(), 
		array('events' => array('click' => '
		if (HTMLForms.getField("special_authorizations").getValue()) {
			$("'.__CLASS__.'_authorizations").appear();
		} else { 
			$("'.__CLASS__.'_authorizations").fade();
		}')
		)));
		
		$auth_settings = $this->get_authorizations_settings();
		$auth_setter = new FormFieldAuthorizationsSetter('authorizations', $auth_settings, array('hidden' => $this->get_category()->auth_is_empty()));
		$auth_settings->build_from_auth_array($this->get_category()->get_auth());
		$fieldset_authorizations->add_field($auth_setter);
		
		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);
		$form->add_button(new FormButtonReset());
		
		$this->form = $form;
	}
	
	protected function set_properties()
	{
		$this->get_category()->set_name($this->form->get_value('name'));
		$this->get_category()->set_id_parent($this->form->get_value('id_parent')->get_raw_value());
		$this->get_category()->set_visible($this->form->get_value('visible'));
		$authorizations = $this->form->get_value('special_authorizations') ? $this->form->get_value('authorizations')->build_auth_array() : array();
		$this->get_category()->set_auth($authorizations);
	}
	
	private function build_fieldset_options(HTMLForm $form)
	{
		$fieldset = new FormFieldsetHTML('options_fieldset', 'Options');
		$this->get_options_fields($fieldset);
		if ($fieldset->get_fields())
		{
			$form->add_fieldset($fieldset);
		}
	}
	
	protected function get_options_fields(FormFieldset $fieldset)
	{
		
	}
	
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
	
	protected function get_category()
	{
		if ($this->category === null)
		{
			$id_category = $this->get_id_category();
			if ($id_category)
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
	
	public function get_authorizations_settings()
	{
		return new AuthorizationsSettings(array(
			new ActionAuthorization('Lecture', 1),
			new ActionAuthorization('Ecriture', 2),
			new ActionAuthorization('Contribution', 4),
			new ActionAuthorization('Modération', 8),
		));
	}
	
	protected function get_id_category()
	{
		return;
	}
	
	abstract protected function generate_response(View $view);
	
	abstract protected function get_categories_manager();
}
?>