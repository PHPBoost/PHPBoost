<?php
class SandboxCategoriesFormController extends AbstractRichCategoriesFormController
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
}
?>