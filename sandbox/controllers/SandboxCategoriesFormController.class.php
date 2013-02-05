<?php
class SandboxCategoriesFormController extends AbstractCategoriesFormController
{
	protected function generate_response(View $view)
	{
		return new SiteDisplayResponse($view);
	}
	
	protected function get_categories_manager()
	{
		return new CategoriesManager(ArticlesCategoriesCache::load());
	}
	
	protected function get_id_category()
	{
		return 3;
	}
	
	protected function get_options_fields(FormFieldset $fieldset)
	{
		$fieldset->add_field(new FormFieldRichTextEditor('description', 'description', $this->get_category()->get_description(), array('required' => true)));
	}
	
	protected function set_properties()
	{
		parent::set_properties();
		$this->get_category()->set_description($this->form->get_value('description'));
	}
}
?>