<?php
class SandboxCategoriesFormController //extends AbstractRichCategoriesFormController
extends AbstractCategoriesManageController 
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
		return 5;
	}
	
	protected function get_categories_management_url()
	{
		return Url();
	}
	
	protected function get_edit_category_url($id)
	{
		return Url();
	}
	
	protected function get_delete_category_url($id)
	{
		return Url();
	}
}
?>